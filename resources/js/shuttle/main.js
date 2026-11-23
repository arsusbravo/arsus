import * as THREE from 'three';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';
import { EffectComposer } from 'three/examples/jsm/postprocessing/EffectComposer.js';
import { RenderPass } from 'three/examples/jsm/postprocessing/RenderPass.js';
import { UnrealBloomPass } from 'three/examples/jsm/postprocessing/UnrealBloomPass.js';
import { OutputPass } from 'three/examples/jsm/postprocessing/OutputPass.js';
import { RoundedBoxGeometry } from 'three/examples/jsm/geometries/RoundedBoxGeometry.js';
import * as TX from './textures.js';
import { createAudio } from './audio.js';
import { initUISound } from './ui-sound.js';
import { initNav } from './nav.js';
import { routes, t } from './config.js';
import { hideBootLoader } from './boot.js';

const sfx = createAudio();

// ---------- Palette (from the herd app) ----------
const ORANGE = 0xfe742b;
const ORANGE_LIGHT = 0xff8c4d;
const NAVY_DARK = 0x050812;

const canvas = document.querySelector('#scene');

// ---------- Renderer / scene / camera ----------
// MSAA is redundant once we're already supersampling via devicePixelRatio >= 2, so skip it there
// to save GPU bandwidth on high-DPI screens (phones especially) without losing visible sharpness.
const renderer = new THREE.WebGLRenderer({ canvas, antialias: window.devicePixelRatio < 2 });
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.1;
// live view of space for the bridge window (filled by a second camera, see "Window view")
const portalRT = new THREE.WebGLRenderTarget(1024, 582, { type: THREE.HalfFloatType });

const scene = new THREE.Scene();
scene.background = new THREE.Color(NAVY_DARK);
const pmrem = new THREE.PMREMGenerator(renderer);
scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
scene.environmentIntensity = 0.6;

const camera = new THREE.PerspectiveCamera(60, window.innerWidth / window.innerHeight, 0.2, 1000);
// portrait screens (phones) get a wider vertical view so rooms aren't cropped to one door
function fitFov() { camera.fov = camera.aspect < 1 ? THREE.MathUtils.clamp(60 / Math.sqrt(camera.aspect), 60, 85) : 60; }
fitFov();
camera.updateProjectionMatrix();

// ---------- Materials ----------
// Procedural PBR textures (see textures.js): plating, worn metal, wall panels, diamond plate, doors, quilting
const skinTex = TX.hullTextures();
const metalTex = TX.metalTextures();
const wallTex = TX.wallTextures();
const floorTex = TX.floorTextures();
const doorTex = TX.doorTextures();
const fabricTex = TX.quiltTextures('#5d657e', 'rgba(254,160,110,0.55)');
const leatherTex = TX.quiltTextures('#3a3f55', 'rgba(254,116,43,0.7)');
const pbr = (t, rx, ry = rx) => ({ map: TX.repeat(t.map, rx, ry), normalMap: TX.repeat(t.normalMap, rx, ry), roughnessMap: TX.repeat(t.roughnessMap, rx, ry) });

// props: worn metal with beveled edges on every face
const hullMat = new THREE.MeshStandardMaterial({ color: 0x727b9e, ...pbr(metalTex, 1), metalness: 0.75, roughness: 1 });
const hullDarkMat = new THREE.MeshStandardMaterial({ color: 0x343c6a, ...pbr(metalTex, 1), metalness: 0.8, roughness: 1 });
// exterior skin: hull plating (cylinder UVs: around x along), plus lit windows
const skinMat = new THREE.MeshStandardMaterial({
  color: 0xb4b9c8, ...pbr(skinTex, 4, 6), metalness: 0.65, roughness: 1, flatShading: true,
  emissiveMap: TX.repeat(TX.hullWindows(), 4, 6), emissive: 0xffffff, emissiveIntensity: 1.4,
});
const skinCapMat = new THREE.MeshStandardMaterial({ color: 0x8a8fa0, ...pbr(skinTex, 1.4), metalness: 0.7, roughness: 1 });
const frontPlateMat = new THREE.MeshStandardMaterial({ color: 0x9a9fb2, ...pbr(skinTex, 1 / 5), metalness: 0.65, roughness: 1 });
// interior
const wallMat = new THREE.MeshStandardMaterial({ ...pbr(wallTex, 1), metalness: 0.55, roughness: 1, side: THREE.BackSide });
const bulkheadMat = new THREE.MeshStandardMaterial({ color: 0xc8cde6, ...pbr(wallTex, 1 / 2.2), metalness: 0.6, roughness: 1 });
const floorMat = new THREE.MeshStandardMaterial({ ...pbr(floorTex, 4.8 / 1.2, 32 / 1.2), metalness: 0.85, roughness: 1 });
const doorMat = new THREE.MeshStandardMaterial({ ...pbr(doorTex, 1), metalness: 0.7, roughness: 1 });
const fabricMat = new THREE.MeshStandardMaterial({ ...pbr(fabricTex, 1, 2), roughness: 1, metalness: 0 });
const leatherMat = new THREE.MeshStandardMaterial({ ...pbr(leatherTex, 1.5), roughness: 0.75, metalness: 0.05 });
const glassMat = new THREE.MeshPhysicalMaterial({ color: 0x9fb4e0, metalness: 0, roughness: 0.08, transparent: true, opacity: 0.14, clearcoat: 1, clearcoatRoughness: 0.05, envMapIntensity: 0.5, depthWrite: false });
const glowMat = new THREE.MeshStandardMaterial({ color: ORANGE, emissive: ORANGE, emissiveIntensity: 2, toneMapped: false });
const whiteGlowMat = new THREE.MeshStandardMaterial({ color: 0xffffff, emissive: 0xcfe0ff, emissiveIntensity: 2, toneMapped: false });
const wingMat = new THREE.MeshStandardMaterial({ color: 0xa9aebe, ...pbr(skinTex, 1 / 6), metalness: 0.65, roughness: 1, side: THREE.DoubleSide });

const ship = new THREE.Group();
scene.add(ship);
const animated = []; // functions (t) => void

// Camera shake: triggered when an asteroid bounces off the hull (see the flybys collision below)
let shakeMag = 0;
function triggerShake(mag) { shakeMag = Math.max(shakeMag, mag); }

// Set inside the cockpit room block below (which is otherwise block-scoped); used by the
// cockpit minigame's hit-testing, which lives much further down the file.
let cockpitWindowMesh = null;
let cockpitHudMesh = null; // the decorative baked-in reticle on the glass; hidden during the
                            // minigame so it doesn't double up with the real interactive crosshair

// Deterministic pseudo-random so the ship looks the same on every load
let seed = 7;
const rand = () => ((seed = (seed * 16807) % 2147483647) / 2147483647);

// ---------- Layout ----------
const HULL_R = 4;
const Z_FRONT = 10;
const Z_REAR = -24;
const HULL_LEN = Z_FRONT - Z_REAR;
const HULL_MID = (Z_FRONT + Z_REAR) / 2;

const ROOM_W = 4.8;
const ROOM_H = 4.4;
const FLOOR = -ROOM_H / 2;
const CEIL = ROOM_H / 2;
const DOOR_W = 2.4;
const DOOR_H = 3.2;

// Doors sit at the entrance of each room; each is a link to its page.
const ROOMS = [
  { key: 'sleeping', room: t('rooms.crew', 'Crew quarters').toUpperCase(), label: t('signs.crew', 'Meet the crew').toUpperCase(), href: 'about', door: 10, zc: 6 },
  { key: 'machine', room: t('rooms.engine', 'Engine room').toUpperCase(), label: t('signs.engine', 'Capabilities').toUpperCase(), href: 'services', door: 2, zc: -2 },
  { key: 'computer', room: t('rooms.missions', 'Mission control').toUpperCase(), label: t('signs.missions', 'Mission log').toUpperCase(), href: 'cases', door: -6, zc: -10 },
  { key: 'cockpit', room: t('rooms.comms', 'Command bridge').toUpperCase(), label: t('signs.comms', 'Open comms').toUpperCase(), href: 'contact', door: -14, zc: -18 },
];

// ---------- Exterior hull ----------
const hull = new THREE.Mesh(new THREE.CylinderGeometry(HULL_R, HULL_R * 1.1, HULL_LEN, 8, 1, true), skinMat);
hull.rotation.x = Math.PI / 2;
hull.position.z = HULL_MID;
ship.add(hull);

const rear = new THREE.Mesh(new THREE.CircleGeometry(HULL_R * 1.1, 8), skinCapMat);
rear.rotation.y = Math.PI;
rear.position.z = Z_REAR;
ship.add(rear);

const entryFrame = new THREE.Mesh(new THREE.TorusGeometry(HULL_R + 0.35, 0.3, 6, 8), hullDarkMat);
entryFrame.position.z = Z_FRONT - 0.2;
ship.add(entryFrame);

// Swept wings
const wingShape = new THREE.Shape();
wingShape.moveTo(0, -3);
wingShape.lineTo(11, 7);
wingShape.lineTo(11, 10);
wingShape.lineTo(0, 9);
wingShape.closePath();
const wingGeo = new THREE.ExtrudeGeometry(wingShape, { depth: 0.35, bevelEnabled: true, bevelSize: 0.05, bevelThickness: 0.05, bevelSegments: 1 });
for (const side of [1, -1]) {
  const wing = new THREE.Mesh(wingGeo, wingMat);
  wing.rotation.x = -Math.PI / 2;
  wing.position.set(side * (HULL_R - 0.4), -0.5, -2);
  wing.scale.x = side;
  ship.add(wing);

  const tip = new THREE.Mesh(new THREE.SphereGeometry(0.24, 12, 12), side > 0 ? glowMat : whiteGlowMat);
  tip.position.set(side * (HULL_R + 10.6), -0.3, -10.5);
  ship.add(tip);

  const edge = new THREE.Mesh(new THREE.BoxGeometry(0.08, 0.08, 14.9), glowMat);
  edge.position.set(side * 9.1, -0.3, -4);
  edge.rotation.y = side * 2.31;
  ship.add(edge);

  const strip = new THREE.Mesh(new THREE.BoxGeometry(0.06, 0.1, HULL_LEN - 6), glowMat);
  strip.position.set(side * (HULL_R + 0.02), 0, HULL_MID - 1);
  ship.add(strip);
}

// Structural bands around the hull (break up the tube)
for (const z of [Z_FRONT - 1.2, 4.5, -3.5, -11.5, Z_REAR + 1.5]) {
  const k = (Z_FRONT - z) / HULL_LEN; // hull widens towards the rear
  const band = new THREE.Mesh(new THREE.CylinderGeometry(HULL_R * (1 + 0.1 * k) + 0.14, HULL_R * (1 + 0.1 * k) + 0.14, 0.7, 8, 1, true), hullDarkMat);
  band.rotation.x = Math.PI / 2;
  band.position.z = z;
  ship.add(band);
}

// Engine nacelles on the wings
for (const side of [1, -1]) {
  const nac = new THREE.Group();
  nac.position.set(side * 10.2, -0.2, -8.2);
  const body = new THREE.Mesh(new THREE.CylinderGeometry(0.55, 0.75, 6, 12), skinCapMat);
  body.rotation.x = Math.PI / 2;
  const intake = new THREE.Mesh(new THREE.TorusGeometry(0.55, 0.1, 8, 20), hullDarkMat);
  intake.position.z = 3;
  const nozzle = new THREE.Mesh(new THREE.CylinderGeometry(0.62, 0.5, 0.6, 12, 1, true), hullDarkMat);
  nozzle.rotation.x = Math.PI / 2;
  nozzle.position.z = -3.2;
  const flame = new THREE.Mesh(new THREE.CircleGeometry(0.5, 16), glowMat);
  flame.rotation.y = Math.PI;
  flame.position.z = -3.4;
  for (const z of [-1.5, 0.5, 2]) {
    const ring = new THREE.Mesh(new THREE.TorusGeometry(0.68, 0.05, 6, 20), hullMat);
    ring.position.z = z;
    nac.add(ring);
  }
  nac.add(body, intake, nozzle, flame);
  ship.add(nac);
}

// Antenna mast and sensor dish on the spine
{
  const mast = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.08, 2.2, 6), hullMat);
  mast.position.set(0.9, HULL_R + 1.0, -18);
  const dish = new THREE.Mesh(new THREE.SphereGeometry(0.9, 16, 8, 0, Math.PI * 2, 0, Math.PI / 3), hullMat);
  dish.material = hullMat;
  dish.rotation.x = -Math.PI / 2.4;
  dish.position.set(-1.2, HULL_R + 0.55, -19);
  const tipLight = new THREE.Mesh(new THREE.SphereGeometry(0.07, 8, 8), new THREE.MeshBasicMaterial({ color: 0xff3b30, toneMapped: false }));
  tipLight.position.set(0.9, HULL_R + 2.15, -18);
  ship.add(mast, dish, tipLight);
}

// Engines
const enginePulse = [];
[[0, 0, 1.2], [-2.1, 1.5, 0.7], [2.1, 1.5, 0.7], [-2.1, -1.5, 0.7], [2.1, -1.5, 0.7]].forEach(([x, y, r]) => {
  const nozzle = new THREE.Mesh(new THREE.CylinderGeometry(r, r * 1.25, 1.6, 10, 1, true), hullDarkMat);
  nozzle.rotation.x = Math.PI / 2;
  nozzle.position.set(x, y, Z_REAR - 0.6);
  ship.add(nozzle);
  const glow = new THREE.Mesh(new THREE.CircleGeometry(r * 1.05, 16), glowMat);
  glow.rotation.y = Math.PI;
  glow.position.set(x, y, Z_REAR - 0.35);
  ship.add(glow);
  const plume = new THREE.Mesh(
    new THREE.ConeGeometry(r * 0.95, r * 7, 12, 1, true),
    new THREE.MeshBasicMaterial({ color: ORANGE_LIGHT, transparent: true, opacity: 0.35, blending: THREE.AdditiveBlending, depthWrite: false, side: THREE.DoubleSide })
  );
  plume.rotation.x = -Math.PI / 2;
  plume.position.set(x, y, Z_REAR - 0.5 - r * 3.5);
  ship.add(plume);
  enginePulse.push(plume);
});

// Bridge dome + hull greebles
const dome = new THREE.Mesh(
  new THREE.SphereGeometry(1.4, 12, 8, 0, Math.PI * 2, 0, Math.PI / 2),
  new THREE.MeshPhysicalMaterial({ color: 0x0b1024, metalness: 0.2, roughness: 0.05, clearcoat: 1, clearcoatRoughness: 0.03, emissive: 0x2a3a6a, emissiveIntensity: 0.35, envMapIntensity: 2 })
);
dome.scale.set(1.2, 0.8, 2.4);
dome.position.set(0, HULL_R * 0.93, -14);
ship.add(dome);

for (let i = 0; i < 140; i++) {
  const phi = (rand() * 0.8 + 0.1) * Math.PI;
  const z = Z_REAR + 2 + rand() * (HULL_LEN - 6);
  const w = 0.2 + rand() * 0.8, h = 0.1 + rand() * 0.3, l = 0.3 + rand() * 1.4;
  const g = new THREE.Mesh(new THREE.BoxGeometry(w, h, l), rand() > 0.96 ? glowMat : rand() > 0.5 ? hullMat : hullDarkMat);
  const rr = HULL_R * 0.93 + h / 2;
  g.position.set(Math.cos(phi) * rr, Math.sin(phi) * rr, z);
  g.rotation.z = phi - Math.PI / 2;
  ship.add(g);
}

// ---------- Door logo (</> drawn as text) ----------
const LOGO_W = 2.0;
const LOGO_H = 1.0;
const logoCanvas = document.createElement('canvas');
logoCanvas.width = 1024; logoCanvas.height = 512;
const logoTextures = [];
function drawLogo() {
  const g = logoCanvas.getContext('2d');
  g.clearRect(0, 0, 1024, 512);
  g.font = '800 280px Orbitron, ui-monospace, Menlo, monospace';
  g.textAlign = 'center'; g.textBaseline = 'middle';
  g.shadowColor = 'rgba(254,116,43,0.9)'; g.shadowBlur = 30;
  g.fillStyle = '#fe742b';
  g.fillText('</>', 512, 270);
  g.shadowBlur = 0;
  g.fillText('</>', 512, 270);
  logoTextures.forEach((t) => (t.needsUpdate = true));
}
function logoHalf(offsetX) {
  const t = new THREE.CanvasTexture(logoCanvas);
  t.colorSpace = THREE.SRGBColorSpace;
  t.anisotropy = 8;
  t.repeat.set(0.5, 1);
  t.offset.set(offsetX, 0);
  logoTextures.push(t);
  return t;
}
drawLogo();
if (document.fonts) document.fonts.load('800 280px Orbitron').then(drawLogo).catch(() => {});

// ---------- Interior shell ----------
const INT_FRONT = Z_FRONT;
const INT_REAR = -22;
const shell = new THREE.Mesh(TX.boxUV(new THREE.BoxGeometry(ROOM_W, ROOM_H, INT_FRONT - INT_REAR), 2.2), wallMat);
shell.position.z = (INT_FRONT + INT_REAR) / 2;
ship.add(shell);

const floor = new THREE.Mesh(new THREE.PlaneGeometry(ROOM_W, INT_FRONT - INT_REAR), floorMat);
floor.rotation.x = -Math.PI / 2;
floor.position.set(0, FLOOR + 0.01, shell.position.z);
ship.add(floor);

// Light strips along floor and ceiling
for (const x of [-1.5, 1.5]) {
  const s = new THREE.Mesh(new THREE.BoxGeometry(0.08, 0.04, INT_FRONT - INT_REAR), glowMat);
  s.position.set(x, FLOOR + 0.03, shell.position.z);
  ship.add(s);
}
const ceilStrip = new THREE.Mesh(new THREE.BoxGeometry(0.4, 0.04, INT_FRONT - INT_REAR), whiteGlowMat);
ceilStrip.position.set(0, CEIL - 0.03, shell.position.z);
ship.add(ceilStrip);

// ---------- Doors (bulkhead + glowing frame + link button) ----------
// Door signs: an illuminated display panel in a metal housing (deck number, room name, action)
const signs = [];
function drawSign(sign) {
  const { g, room, idx } = sign;
  const W = 1024, H = 256;
  const bg = g.createLinearGradient(0, 0, 0, H);
  bg.addColorStop(0, '#10163a'); bg.addColorStop(1, '#05070f');
  g.fillStyle = bg; g.fillRect(0, 0, W, H);
  // faint display grid
  g.strokeStyle = 'rgba(254,116,43,0.07)'; g.lineWidth = 1;
  for (let x = 0; x < W; x += 32) { g.beginPath(); g.moveTo(x + 0.5, 0); g.lineTo(x + 0.5, H); g.stroke(); }
  for (let y = 0; y < H; y += 32) { g.beginPath(); g.moveTo(0, y + 0.5); g.lineTo(W, y + 0.5); g.stroke(); }
  // deck number block
  g.fillStyle = 'rgba(254,116,43,0.13)'; g.fillRect(0, 0, 210, H);
  g.fillStyle = '#fe742b'; g.fillRect(210, 30, 4, H - 60);
  g.textAlign = 'center'; g.textBaseline = 'middle';
  g.letterSpacing = '4px';
  g.font = '600 26px ui-monospace, Menlo, monospace';
  g.fillStyle = 'rgba(255,190,150,0.85)';
  g.fillText(t('rooms.deck', 'DECK'), 105, 62);
  g.font = '800 100px Orbitron, system-ui, sans-serif';
  g.shadowColor = 'rgba(254,116,43,0.9)'; g.shadowBlur = 18;
  g.fillStyle = '#fe742b';
  g.fillText(String(idx + 1).padStart(2, '0'), 105, 150);
  g.shadowBlur = 0;
  // room name + action
  g.textAlign = 'left';
  g.letterSpacing = '6px';
  g.font = '600 30px ui-monospace, Menlo, monospace';
  g.fillStyle = 'rgba(255,190,150,0.85)';
  let rs = 30;
  do { g.font = '600 ' + rs + 'px ui-monospace, Menlo, monospace'; rs -= 1; } while (g.measureText(room.room).width > 560 && rs > 16);
  g.fillText(room.room, 252, 72);
  let size = 92;
  g.letterSpacing = '3px';
  do { g.font = '700 ' + size + 'px Orbitron, system-ui, sans-serif'; size -= 2; } while (g.measureText(room.label).width > 600 && size > 30);
  g.shadowColor = 'rgba(254,116,43,0.85)'; g.shadowBlur = 20;
  g.fillStyle = '#fff1e6';
  g.fillText(room.label, 252, 164);
  g.shadowBlur = 0;
  g.letterSpacing = '0px';
  // chevrons pointing through the door
  for (let k = 0; k < 3; k++) {
    g.strokeStyle = 'rgba(254,116,43,' + (0.35 + k * 0.3) + ')';
    g.lineWidth = 10; g.lineCap = 'round'; g.lineJoin = 'round';
    const x = 890 + k * 34;
    g.beginPath(); g.moveTo(x, 108); g.lineTo(x + 22, 140); g.lineTo(x, 172); g.stroke();
  }
  // status LED, inner border, scanlines
  g.fillStyle = '#35e07a'; g.shadowColor = '#35e07a'; g.shadowBlur = 12;
  g.beginPath(); g.arc(W - 40, 40, 9, 0, Math.PI * 2); g.fill();
  g.shadowBlur = 0;
  g.strokeStyle = 'rgba(254,116,43,0.45)'; g.lineWidth = 3; g.strokeRect(8, 8, W - 16, H - 16);
  g.fillStyle = 'rgba(0,0,0,0.18)';
  for (let y = 0; y < H; y += 4) g.fillRect(0, y, W, 2);
  sign.tex.needsUpdate = true;
}
function makeSign(room, idx) {
  const c = document.createElement('canvas');
  c.width = 1024; c.height = 256;
  const tex = new THREE.CanvasTexture(c);
  tex.colorSpace = THREE.SRGBColorSpace;
  tex.anisotropy = 8;
  const sign = { g: c.getContext('2d'), tex, room, idx };
  drawSign(sign);
  signs.push(sign);
  return tex;
}
if (document.fonts) document.fonts.load('700 92px Orbitron').then(() => signs.forEach(drawSign)).catch(() => {});

function rectShape(w, h, cy = 0) {
  const s = new THREE.Shape();
  s.moveTo(-w / 2, cy - h / 2); s.lineTo(w / 2, cy - h / 2); s.lineTo(w / 2, cy + h / 2); s.lineTo(-w / 2, cy + h / 2); s.closePath();
  return s;
}
function octShape(r) {
  const s = new THREE.Shape();
  for (let k = 0; k < 8; k++) {
    const a = (k * Math.PI) / 4;
    k ? s.lineTo(Math.cos(a) * r, Math.sin(a) * r) : s.moveTo(Math.cos(a) * r, Math.sin(a) * r);
  }
  s.closePath();
  return s;
}

const buttons = []; // clickable door labels
const doors = []; // sliding door panels
const doorHoleY = FLOOR + DOOR_H / 2;

ROOMS.forEach((room, i) => {
  const outer = i === 0 ? octShape(HULL_R) : rectShape(ROOM_W, ROOM_H);
  const hole = rectShape(DOOR_W, DOOR_H, doorHoleY);
  outer.holes.push(new THREE.Path(hole.getPoints()));
  const plate = new THREE.Mesh(new THREE.ExtrudeGeometry(outer, { depth: 0.3, bevelEnabled: false }), i === 0 ? frontPlateMat : bulkheadMat);
  plate.position.z = room.door - 0.3;
  ship.add(plate);

  // glowing frame around the opening
  const t = 0.07;
  const frameParts = [
    [DOOR_W + 2 * t, t, 0, doorHoleY + DOOR_H / 2 + t / 2],
    [t, DOOR_H, -(DOOR_W / 2 + t / 2), doorHoleY],
    [t, DOOR_H, DOOR_W / 2 + t / 2, doorHoleY],
  ];
  frameParts.forEach(([w, h, x, y]) => {
    const f = new THREE.Mesh(new THREE.BoxGeometry(w, h, 0.06), glowMat);
    f.position.set(x, y, room.door + 0.03);
    ship.add(f);
  });

  // sliding door (two halves) that opens when the camera gets close
  const panelMat = doorMat;
  const halves = [-1, 1].map((side) => {
    const half = new THREE.Group();
    const panel = new THREE.Mesh(new THREE.BoxGeometry(DOOR_W / 2, DOOR_H, 0.1), panelMat);
    const stripe = new THREE.Mesh(new THREE.BoxGeometry(DOOR_W / 2 - 0.3, 0.05, 0.02), glowMat);
    stripe.position.set(0, 0, 0.06);
    stripe.position.y = -1.1;
    const edge = new THREE.Mesh(new THREE.BoxGeometry(0.05, DOOR_H, 0.02), glowMat);
    edge.position.set(-side * (DOOR_W / 4 - 0.03), 0, 0.06);
    // each half shows one half of the logo, so it splits when the door opens
    const tex = logoHalf(side < 0 ? 0 : 0.5);
    const logo = new THREE.Mesh(
      new THREE.PlaneGeometry(LOGO_W / 2, LOGO_H),
      new THREE.MeshBasicMaterial({ map: tex, transparent: true, toneMapped: false, color: 0xdddddd, polygonOffset: true, polygonOffsetFactor: -4, polygonOffsetUnits: -4 })
    );
    logo.position.set(-side * (DOOR_W / 4 - LOGO_W / 4), 0.3, 0.09);
    half.add(panel, stripe, edge, logo);
    half.position.set(side * DOOR_W / 4, doorHoleY, room.door - 0.15);
    ship.add(half);
    return half;
  });
  doors.push({ z: room.door, open: 0, halves });

  // link sign above the door: metal housing + illuminated display face (the face is the click target)
  const signGroup = new THREE.Group();
  signGroup.position.set(0, doorHoleY + DOOR_H / 2 + 0.55, room.door);
  const housing = new THREE.Mesh(new RoundedBoxGeometry(2.62, 0.74, 0.12, 4, 0.05), hullDarkMat);
  housing.position.z = 0.06;
  const btn = new THREE.Mesh(
    new THREE.PlaneGeometry(2.44, 0.6),
    new THREE.MeshBasicMaterial({ map: makeSign(room, i), color: 0xc8c8c8, toneMapped: false })
  );
  btn.position.z = 0.125;
  for (const x of [-1.22, 1.22]) {
    const bolt = new THREE.Mesh(new THREE.CylinderGeometry(0.025, 0.025, 0.03, 8), hullMat);
    bolt.rotation.x = Math.PI / 2;
    bolt.position.set(x, 0.3, 0.125);
    signGroup.add(bolt);
  }
  signGroup.add(housing, btn);
  btn.userData = { href: room.href, group: signGroup };
  ship.add(signGroup);
  buttons.push(btn);
});

// Room lights
ROOMS.forEach((room) => {
  const l = new THREE.PointLight(room.key === 'cockpit' ? 0x9cc2ff : ORANGE, room.key === 'cockpit' ? 10 : 14, 12, 2);
  l.position.set(0, CEIL - 0.8, room.zc);
  ship.add(l);
  const l2 = new THREE.PointLight(ORANGE_LIGHT, 8, 8, 2);
  l2.position.set(0, FLOOR + 0.8, room.zc - 2);
  ship.add(l2);
});

// ---------- Room 1: sleeping quarters ----------
{
  const zc = 6;
  const shellMat = glassMat;
  const innerMat = fabricMat;
  for (const side of [-1, 1]) {
    for (const dz of [-1.7, 1.7]) {
      const x = side * 1.75, z = zc + dz;
      const base = new THREE.Mesh(new RoundedBoxGeometry(1.3, 0.5, 2.6, 3, 0.08), hullDarkMat);
      base.position.set(x, FLOOR + 0.25, z);
      const mattress = new THREE.Mesh(new THREE.CapsuleGeometry(0.4, 1.3, 4, 10), innerMat);
      mattress.rotation.x = Math.PI / 2;
      mattress.position.set(x, FLOOR + 0.7, z);
      const canopy = new THREE.Mesh(new THREE.CapsuleGeometry(0.52, 1.6, 6, 14, undefined), shellMat);
      canopy.rotation.x = Math.PI / 2;
      canopy.position.set(x, FLOOR + 0.9, z);
      canopy.scale.set(1, 1, 0.7);
      const rim = new THREE.Mesh(new THREE.BoxGeometry(0.06, 0.06, 2.6), glowMat);
      rim.position.set(x - side * 0.6, FLOOR + 0.52, z);
      ship.add(base, mattress, canopy, rim);
    }
    // porthole
    const port = new THREE.Mesh(new THREE.TorusGeometry(0.4, 0.06, 8, 24), glowMat);
    port.rotation.y = Math.PI / 2;
    port.position.set(side * (ROOM_W / 2 - 0.03), 0.7, zc);
    const glass = new THREE.Mesh(new THREE.CircleGeometry(0.38, 24), new THREE.MeshBasicMaterial({ color: 0x0a1030 }));
    glass.rotation.y = -side * Math.PI / 2;
    glass.position.set(side * (ROOM_W / 2 - 0.04), 0.7, zc);
    if (side === 1) ship.add(port, glass);
  }
  // Wall monitor with the crew portrait (left wall, hangs on brackets)
  const SCR_W = 1.09, SCR_H = 1.5, MON_Y = 0.5;
  const photo = new Image();
  photo.src = '/images/crew.jpg';
  const mc = document.createElement('canvas');
  mc.width = 420; mc.height = 580;
  const mg = mc.getContext('2d');
  const mtex = new THREE.CanvasTexture(mc);
  mtex.colorSpace = THREE.SRGBColorSpace;
  mtex.anisotropy = 8;
  function drawMonitor(t) {
    const w = mc.width, h = mc.height;
    mg.fillStyle = '#050a1c'; mg.fillRect(0, 0, w, h);
    if (photo.complete && photo.naturalWidth) {
      const k = Math.max(w / photo.naturalWidth, h / photo.naturalHeight);
      const iw = photo.naturalWidth * k, ih = photo.naturalHeight * k;
      mg.filter = 'brightness(1.4) contrast(1.1)';
      mg.drawImage(photo, (w - iw) / 2, (h - ih) / 2, iw, ih);
      mg.filter = 'none';
    }
    mg.fillStyle = 'rgba(254,116,43,0.10)'; mg.fillRect(0, 0, w, h);
    mg.fillStyle = 'rgba(0,0,0,0.28)';
    for (let y = 0; y < h; y += 4) mg.fillRect(0, y, w, 2);
    const by = ((t * 70) % (h + 120)) - 60;
    const gr = mg.createLinearGradient(0, by - 40, 0, by + 40);
    gr.addColorStop(0, 'rgba(255,140,77,0)'); gr.addColorStop(0.5, 'rgba(255,140,77,0.2)'); gr.addColorStop(1, 'rgba(255,140,77,0)');
    mg.fillStyle = gr; mg.fillRect(0, by - 40, w, 80);
    mg.font = '600 15px ui-monospace, Menlo, monospace';
    mg.textBaseline = 'top';
    mg.fillStyle = '#fe742b';
    mg.fillText('CREW FILE // 001', 14, 12);
    mg.fillText((Math.floor(t * 2) % 2 === 0 ? '\u25CF' : ' ') + ' ARSUS  ONLINE', 14, h - 28);
    const v = mg.createRadialGradient(w / 2, h / 2, h * 0.35, w / 2, h / 2, h * 0.75);
    v.addColorStop(0, 'rgba(0,0,0,0)'); v.addColorStop(1, 'rgba(0,0,0,0.5)');
    mg.fillStyle = v; mg.fillRect(0, 0, w, h);
    mtex.needsUpdate = true;
  }
  drawMonitor(0);
  photo.onload = () => drawMonitor(0);
  let lastMon = 0;
  animated.push((t) => { if (t - lastMon < 0.1) return; lastMon = t; drawMonitor(t); });

  const monX = -ROOM_W / 2;
  const monFrame = new THREE.Mesh(new THREE.BoxGeometry(0.2, SCR_H + 0.25, SCR_W + 0.25), hullDarkMat);
  monFrame.position.set(monX + 0.1 + 0.06, MON_Y - 0.05, zc);
  const monChin = new THREE.Mesh(new THREE.BoxGeometry(0.22, 0.04, SCR_W * 0.6), glowMat);
  monChin.position.set(monX + 0.2, MON_Y - SCR_H / 2 - 0.14, zc);
  const monScreen = new THREE.Mesh(new THREE.PlaneGeometry(SCR_W, SCR_H), new THREE.MeshBasicMaterial({ map: mtex, toneMapped: false }));
  monScreen.rotation.y = Math.PI / 2;
  monScreen.position.set(monX + 0.27, MON_Y, zc);
  ship.add(monFrame, monChin, monScreen);
  for (const dz of [-0.4, 0.4]) {
    const bracket = new THREE.Mesh(new THREE.BoxGeometry(0.16, 0.12, 0.12), hullMat);
    bracket.position.set(monX + 0.08, MON_Y + SCR_H / 2 + 0.2, zc + dz);
    ship.add(bracket);
  }
  const monLight = new THREE.PointLight(0xffd7b8, 5, 5, 2);
  monLight.position.set(monX + 1.0, MON_Y, zc);
  ship.add(monLight);
}

// ---------- Room 2: machine room ----------
{
  const zc = -2;
  const pistonMat = new THREE.MeshStandardMaterial({ color: 0xc9cede, ...pbr(metalTex, 1, 3), metalness: 1, roughness: 0.55 });
  const rods = [];
  for (const side of [-1, 1]) {
    for (const dz of [-2.2, 2.2]) {
      const x = side * 1.8, z = zc + dz;
      const cyl = new THREE.Mesh(new THREE.CylinderGeometry(0.4, 0.4, 2.2, 12), hullDarkMat);
      cyl.position.set(x, FLOOR + 1.1, z);
      const ring = new THREE.Mesh(new THREE.TorusGeometry(0.42, 0.04, 6, 16), glowMat);
      ring.rotation.x = Math.PI / 2;
      ring.position.set(x, FLOOR + 1.6, z);
      const rod = new THREE.Mesh(new THREE.CylinderGeometry(0.16, 0.16, 1.6, 10), pistonMat);
      rod.position.set(x, FLOOR + 2.4, z);
      rod.userData = { y0: FLOOR + 2.4, phase: rand() * 6 };
      rods.push(rod);
      ship.add(cyl, ring, rod);
    }
  }
  animated.push((t) => rods.forEach((r) => (r.position.y = r.userData.y0 + Math.sin(t * 2 + r.userData.phase) * 0.35)));

  // Gears on the walls
  const gs = new THREE.Shape();
  const teeth = 12;
  for (let i = 0; i < teeth * 4; i++) {
    const a = (i / (teeth * 4)) * Math.PI * 2;
    const r = i % 4 < 2 ? 1 : 0.82;
    i ? gs.lineTo(Math.cos(a) * r, Math.sin(a) * r) : gs.moveTo(Math.cos(a) * r, Math.sin(a) * r);
  }
  gs.closePath();
  const gearGeo = new THREE.ExtrudeGeometry(gs, { depth: 0.2, bevelEnabled: false });
  const gearMat = new THREE.MeshStandardMaterial({ color: 0x7d8398, ...pbr(metalTex, 0.9), metalness: 0.85, roughness: 1 });
  const gears = [];
  for (const side of [-1, 1]) {
    for (const [dz, dir] of [[-1.1, 1], [1.1, -1]]) {
      const holder = new THREE.Group();
      holder.position.set(side * (ROOM_W / 2 - 0.25), 0.9, zc + dz);
      holder.rotation.y = Math.PI / 2;
      const gear = new THREE.Mesh(gearGeo, gearMat);
      const hub = new THREE.Mesh(new THREE.TorusGeometry(0.45, 0.06, 6, 20), glowMat);
      hub.position.z = 0.22;
      gear.add(hub);
      gear.userData.dir = dir;
      holder.add(gear);
      gears.push(gear);
      ship.add(holder);
    }
  }
  animated.push((t) => gears.forEach((g) => (g.rotation.z = t * 0.5 * g.userData.dir)));

  // Ceiling pipes
  for (const x of [-1.3, 1.3]) {
    const pipe = new THREE.Mesh(new THREE.CylinderGeometry(0.16, 0.16, 8, 10), hullMat);
    pipe.rotation.x = Math.PI / 2;
    pipe.position.set(x, CEIL - 0.35, zc);
    ship.add(pipe);
    for (let dz = -3; dz <= 3; dz += 2) {
      const band = new THREE.Mesh(new THREE.TorusGeometry(0.18, 0.03, 6, 12), glowMat);
      band.position.set(x, CEIL - 0.35, zc + dz);
      ship.add(band);
    }
  }
}

// ---------- Room 3: computer room ----------
{
  const zc = -10;
  const ledGeo = new THREE.BoxGeometry(0.05, 0.05, 0.02);
  const ledMat = new THREE.MeshBasicMaterial({ toneMapped: false });
  const leds = [];
  const dummy = new THREE.Object3D();
  const placements = [];
  for (const side of [-1, 1]) {
    for (const dz of [-2.6, 0, 2.6]) {
      const rack = new THREE.Mesh(new RoundedBoxGeometry(0.9, 2.2, 2.2, 2, 0.04), hullDarkMat);
      rack.position.set(side * (ROOM_W / 2 - 0.45), FLOOR + 1.1, zc + dz);
      ship.add(rack);
      const faceX = side * (ROOM_W / 2 - 0.45 - 0.46);
      for (let row = 0; row < 8; row++) {
        const unit = new THREE.Mesh(new THREE.BoxGeometry(0.04, 0.16, 1.9), hullMat);
        unit.position.set(faceX + side * 0.01, FLOOR + 0.4 + row * 0.24, zc + dz);
        ship.add(unit);
        for (let col = 0; col < 8; col++) placements.push([faceX, FLOOR + 0.4 + row * 0.24, zc + dz - 0.8 + col * 0.23]);
      }
    }
  }
  const ledMesh = new THREE.InstancedMesh(ledGeo, ledMat, placements.length);
  ledMesh.rotation.y = 0;
  const palette = [new THREE.Color(ORANGE).multiplyScalar(2.5), new THREE.Color(0xffffff).multiplyScalar(1.8), new THREE.Color(0x1a1f3a), new THREE.Color(0x9cc2ff).multiplyScalar(2)];
  placements.forEach((p, i) => {
    dummy.position.set(p[0], p[1], p[2]);
    dummy.rotation.y = Math.PI / 2;
    dummy.updateMatrix();
    ledMesh.setMatrixAt(i, dummy.matrix);
    ledMesh.setColorAt(i, palette[Math.floor(rand() * palette.length)]);
  });
  ship.add(ledMesh);
  // Wall monitors above the racks, each showing a case thumbnail
  const accents = ['#fe742b', '#ff8c4d', '#9cc2ff', '#ffffff'];
  function screenTexture(n) {
    const c = document.createElement('canvas');
    c.width = 640; c.height = 400;
    const g = c.getContext('2d');
    const t = new THREE.CanvasTexture(c);
    t.colorSpace = THREE.SRGBColorSpace;
    t.anisotropy = 8;
    const photo = new Image();
    photo.src = '/images/cases/thumb-' + n + '.jpg';
    function draw() {
      g.fillStyle = '#0a0e27'; g.fillRect(0, 0, 640, 400);
      if (photo.complete && photo.naturalWidth) {
        const k = Math.max(640 / photo.naturalWidth, 344 / photo.naturalHeight);
        const iw = photo.naturalWidth * k, ih = photo.naturalHeight * k;
        g.save(); g.beginPath(); g.rect(0, 56, 640, 344); g.clip();
        g.drawImage(photo, (640 - iw) / 2, 56 + (344 - ih) / 2, iw, ih);
        g.restore();
        g.fillStyle = 'rgba(0,0,0,0.25)';
        for (let y = 56; y < 400; y += 4) g.fillRect(0, y, 640, 2);
      }
      g.fillStyle = '#1a1f3a'; g.fillRect(0, 0, 640, 56);
      g.fillStyle = '#fe742b'; g.font = '700 28px system-ui, sans-serif'; g.textBaseline = 'middle';
      g.fillText('MISSION ' + String(n).padStart(2, '0'), 24, 29);
      for (let i = 0; i < 3; i++) { g.fillStyle = accents[i]; g.beginPath(); g.arc(590 - i * 30, 28, 8, 0, Math.PI * 2); g.fill(); }
      t.needsUpdate = true;
    }
    draw();
    photo.onload = draw;
    return t;
  }
  let n = 1;
  for (const side of [-1, 1]) {
    for (const dz of [-2.6, 0, 2.6]) {
      const x = side * (ROOM_W / 2 - 0.12);
      const frame = new THREE.Mesh(new THREE.BoxGeometry(0.08, 1.2, 1.9), hullDarkMat);
      frame.position.set(x, 0.95, zc + dz);
      const screen = new THREE.Mesh(new THREE.PlaneGeometry(1.75, 1.1), new THREE.MeshBasicMaterial({ map: screenTexture(n++), color: 0xd0d0d0, toneMapped: false }));
      screen.position.set(x - side * 0.05, 0.95, zc + dz);
      screen.rotation.y = -side * Math.PI / 2;
      const arm = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.3, 0.3), hullMat);
      arm.position.set(x + side * 0.05, 0.3, zc + dz);
      ship.add(frame, screen, arm);
    }
  }

  let last = 0;
  animated.push((t) => {
    if (t - last < 0.12) return;
    last = t;
    for (let k = 0; k < 14; k++) ledMesh.setColorAt(Math.floor(Math.random() * placements.length), palette[Math.floor(Math.random() * palette.length)]);
    ledMesh.instanceColor.needsUpdate = true;
  });

}

// ---------- Room 4: cockpit ----------
{
  const zc = -18;
  const TAU = Math.PI * 2;
  const redMat = new THREE.MeshBasicMaterial({ color: 0xff3b30, toneMapped: false });
  const greenMat = new THREE.MeshBasicMaterial({ color: 0x35e07a, toneMapped: false });
  const blueGlowMat = new THREE.MeshBasicMaterial({ color: 0x9cc2ff, toneMapped: false });
  const orangeBasic = new THREE.MeshBasicMaterial({ color: ORANGE, toneMapped: false });
  const whiteBasic = new THREE.MeshBasicMaterial({ color: 0xffffff, toneMapped: false });
  const btnMats = [orangeBasic, whiteBasic, blueGlowMat, greenMat, redMat];

  // --- live (animated) canvas screens ---
  const live = [];
  function liveScreen(w, h, draw, fps = 12) {
    const c = document.createElement('canvas');
    c.width = w; c.height = h;
    const ctx = c.getContext('2d');
    const tex = new THREE.CanvasTexture(c);
    tex.colorSpace = THREE.SRGBColorSpace;
    tex.anisotropy = 8;
    draw(ctx, w, h, 0);
    live.push({ tex, ctx, w, h, draw, step: 1 / fps, last: 0 });
    return new THREE.MeshBasicMaterial({ map: tex, color: 0xd8d8d8, toneMapped: false });
  }
  animated.push((t) => live.forEach((s) => {
    if (t - s.last < s.step) return;
    s.last = t;
    s.draw(s.ctx, s.w, s.h, t);
    s.tex.needsUpdate = true;
  }));

  const blips = Array.from({ length: 8 }, () => ({ ang: rand() * TAU, r: 0.2 + rand() * 0.75 }));
  function drawRadar(g, w, h, t) {
    g.fillStyle = '#050a1c'; g.fillRect(0, 0, w, h);
    const cx = w / 2, cy = h / 2, R = Math.min(w, h) / 2 - 14;
    g.strokeStyle = 'rgba(254,116,43,0.65)'; g.lineWidth = 2;
    for (let i = 1; i <= 4; i++) { g.beginPath(); g.arc(cx, cy, (R * i) / 4, 0, TAU); g.stroke(); }
    g.beginPath(); g.moveTo(cx - R, cy); g.lineTo(cx + R, cy); g.moveTo(cx, cy - R); g.lineTo(cx, cy + R); g.stroke();
    const a = t * 1.6;
    for (let i = 0; i < 30; i++) {
      g.fillStyle = 'rgba(255,140,77,' + 0.4 * (1 - i / 30) + ')';
      g.beginPath(); g.moveTo(cx, cy); g.arc(cx, cy, R, a - (i + 1) * 0.04, a - i * 0.04); g.closePath(); g.fill();
    }
    blips.forEach((b) => {
      const d = (((a - b.ang) % TAU) + TAU) % TAU;
      const v = Math.max(0, 1 - d / 3.5);
      g.fillStyle = 'rgba(255,255,255,' + v + ')';
      g.beginPath(); g.arc(cx + Math.cos(b.ang) * b.r * R, cy + Math.sin(b.ang) * b.r * R, 6, 0, TAU); g.fill();
    });
    g.fillStyle = '#ffffff'; g.font = '600 16px system-ui, sans-serif'; g.fillText('RADAR  RNG 400K', 14, 24);
  }
  function drawScope(g, w, h, t) {
    g.fillStyle = '#050a1c'; g.fillRect(0, 0, w, h);
    g.strokeStyle = 'rgba(156,194,255,0.18)'; g.lineWidth = 1;
    for (let x = 0; x < w; x += 32) { g.beginPath(); g.moveTo(x, 0); g.lineTo(x, h); g.stroke(); }
    for (let y = 0; y < h; y += 32) { g.beginPath(); g.moveTo(0, y); g.lineTo(w, y); g.stroke(); }
    [['#ff8c4d', 0.18, 3, 0], ['#9cc2ff', 0.1, 5, 1.7]].forEach(([col, amp, sp, off]) => {
      g.strokeStyle = col; g.lineWidth = 3; g.beginPath();
      for (let x = 0; x <= w; x += 4) {
        const y = h / 2 + Math.sin(x * 0.03 + t * sp + off) * h * amp + Math.sin(x * 0.11 + t * sp * 1.7) * h * amp * 0.45;
        x ? g.lineTo(x, y) : g.moveTo(x, y);
      }
      g.stroke();
    });
    g.fillStyle = '#ffffff'; g.font = '600 16px system-ui, sans-serif'; g.fillText('SIGNAL ANALYSIS', 14, 24);
  }
  function drawTelemetry(g, w, h, t) {
    g.fillStyle = '#050a1c'; g.fillRect(0, 0, w, h);
    g.font = '600 16px system-ui, sans-serif'; g.textBaseline = 'middle';
    ['FUEL', 'SHIELD', 'REACTOR', 'HULL', 'O2', 'THRUST'].forEach((name, i) => {
      const y = 44 + i * ((h - 60) / 6);
      const v = 0.55 + 0.4 * Math.sin(t * 0.7 + i * 1.3);
      g.fillStyle = '#ffffff'; g.fillText(name, 14, y);
      g.fillStyle = '#1a1f3a'; g.fillRect(100, y - 8, w - 160, 16);
      g.fillStyle = v > 0.35 ? '#fe742b' : '#ff3b30'; g.fillRect(100, y - 8, (w - 160) * v, 16);
      g.fillStyle = '#9ca3af'; g.fillText(Math.round(v * 100) + '%', w - 50, y);
    });
    g.fillStyle = '#fe742b'; g.fillText('SYSTEMS', 14, 20);
  }
  const mapStars = Array.from({ length: 26 }, () => [rand(), rand()]);
  function drawMap(g, w, h, t) {
    g.fillStyle = '#050a1c'; g.fillRect(0, 0, w, h);
    g.strokeStyle = 'rgba(156,194,255,0.35)'; g.lineWidth = 1.5; g.beginPath();
    mapStars.forEach(([x, y], i) => (i ? g.lineTo(x * w, y * h) : g.moveTo(x * w, y * h)));
    g.stroke();
    mapStars.forEach(([x, y]) => { g.fillStyle = '#ffffff'; g.fillRect(x * w - 2, y * h - 2, 4, 4); });
    const [mx, my] = mapStars[9];
    g.strokeStyle = '#fe742b'; g.lineWidth = 3;
    g.beginPath(); g.arc(mx * w, my * h, 10 + (Math.sin(t * 4) + 1) * 5, 0, TAU); g.stroke();
    g.fillStyle = '#ffffff'; g.font = '600 16px system-ui, sans-serif'; g.fillText('NAV CHART  SECTOR 7', 14, 24);
  }

  // --- viewport window: live view of space (see "Window view") ---
  const WIN_W = 4.4, WIN_H = 2.5;
  const win = new THREE.Mesh(new THREE.PlaneGeometry(WIN_W, WIN_H), new THREE.MeshBasicMaterial({ map: portalRT.texture, toneMapped: false }));
  win.position.set(0, 0.5, INT_REAR + 0.05);
  cockpitWindowMesh = win; // reachable outside this block for the cockpit minigame's hit-testing
  const sheen = new THREE.Mesh(new THREE.PlaneGeometry(WIN_W, WIN_H), new THREE.MeshBasicMaterial({ map: TX.glassSheen(), transparent: true, blending: THREE.AdditiveBlending, depthWrite: false, toneMapped: false }));
  sheen.position.set(0, 0.5, INT_REAR + 0.07);
  const hud = new THREE.Mesh(new THREE.PlaneGeometry(WIN_W, WIN_H), new THREE.MeshBasicMaterial({ map: TX.viewportHud(), transparent: true, blending: THREE.AdditiveBlending, depthWrite: false, toneMapped: false }));
  hud.position.set(0, 0.5, INT_REAR + 0.08);
  cockpitHudMesh = hud; // reachable outside this block: hidden during the minigame (see below)
  ship.add(win, sheen, hud);

  // Thick beveled cockpit bezel around the glass (no center mullion: that read as a house window)
  [[WIN_W + 0.55, 0.42, 0, WIN_H / 2 + 0.71], [WIN_W + 0.55, 0.42, 0, -WIN_H / 2 + 0.29],
   [0.42, WIN_H + 0.3, -(WIN_W / 2 + 0.71), 0.5], [0.42, WIN_H + 0.3, WIN_W / 2 + 0.71, 0.5]].forEach(([w, h, x, y]) => {
    const f = new THREE.Mesh(new RoundedBoxGeometry(w, h, 0.35, 2, 0.08), hullDarkMat);
    f.position.set(x, y, INT_REAR + 0.15);
    ship.add(f);
  });
  // glowing seam right at the edge of the glass
  [[WIN_W + 0.08, 0.05, 0, WIN_H / 2 + 0.52], [WIN_W + 0.08, 0.05, 0, -WIN_H / 2 + 0.48],
   [0.05, WIN_H + 0.08, -(WIN_W / 2 + 0.02), 0.5], [0.05, WIN_H + 0.08, WIN_W / 2 + 0.02, 0.5]].forEach(([w, h, x, y]) => {
    const f = new THREE.Mesh(new THREE.BoxGeometry(w, h, 0.03), glowMat);
    f.position.set(x, y, INT_REAR + 0.09);
    ship.add(f);
  });
  // diagonal corner gussets: structural braces instead of a flat center divider
  [[-1, 1], [1, 1], [-1, -1], [1, -1]].forEach(([sx, sy]) => {
    const gusset = new THREE.Mesh(new THREE.BoxGeometry(0.85, 0.16, 0.3), hullMat);
    gusset.position.set(sx * (WIN_W / 2 - 0.15), 0.5 + sy * (WIN_H / 2 - 0.15), INT_REAR + 0.14);
    gusset.rotation.z = sx * sy * Math.PI / 4;
    ship.add(gusset);
  });
  // rivets along the bezel
  const rivetGeo = new THREE.CylinderGeometry(0.035, 0.035, 0.1, 8);
  [-1.8, -0.9, 0.9, 1.8].forEach((x) => {
    [WIN_H / 2 + 0.71, -WIN_H / 2 + 0.29].forEach((y) => {
      const r = new THREE.Mesh(rivetGeo, hullMat);
      r.rotation.x = Math.PI / 2;
      r.position.set(x, y, INT_REAR + 0.33);
      ship.add(r);
    });
  });

  // --- console blocks with tilted live screens and button rows ---
  const btnGeo = new THREE.BoxGeometry(0.09, 0.04, 0.09);
  function consoleBlock(x, z, w, rotY, screenMatl, sw, sh) {
    const grp = new THREE.Group();
    grp.position.set(x, 0, z);
    grp.rotation.y = rotY;
    const body = new THREE.Mesh(new RoundedBoxGeometry(w, 0.95, 1.0, 3, 0.06), hullDarkMat);
    body.position.y = FLOOR + 0.475;
    const trim = new THREE.Mesh(new THREE.BoxGeometry(w, 0.04, 0.04), glowMat);
    trim.position.set(0, FLOOR + 0.96, 0.5);
    const panel = new THREE.Group();
    panel.position.set(0, FLOOR + 0.95 + sh * 0.42, -0.1);
    panel.rotation.x = -0.9;
    const bezel = new THREE.Mesh(new THREE.BoxGeometry(sw + 0.1, sh + 0.1, 0.05), hullMat);
    const scr = new THREE.Mesh(new THREE.PlaneGeometry(sw, sh), screenMatl);
    scr.position.z = 0.03;
    panel.add(bezel, scr);
    grp.add(body, trim, panel);
    const n = Math.floor((w - 0.2) / 0.16);
    for (let i = 0; i < n; i++) {
      const b = new THREE.Mesh(btnGeo, btnMats[Math.floor(rand() * btnMats.length)]);
      b.position.set(-((n - 1) * 0.16) / 2 + i * 0.16, FLOOR + 0.98, 0.3 + (i % 2) * 0.1);
      grp.add(b);
    }
    ship.add(grp);
    return grp;
  }
  const CZ = INT_REAR + 1.4;
  consoleBlock(0, CZ, 2.0, 0, liveScreen(512, 400, drawRadar, 15), 1.6, 1.25);
  const wl = consoleBlock(-1.55, CZ + 0.25, 1.3, 0.5, liveScreen(384, 300, drawScope), 1.05, 0.8);
  const wr = consoleBlock(1.55, CZ + 0.25, 1.3, -0.5, liveScreen(384, 300, drawTelemetry), 1.05, 0.8);
  // status lamps on the wing consoles
  [[wl, redMat], [wr, greenMat]].forEach(([grp, m]) => {
    const lamp = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.1, 0.1), m);
    lamp.position.set(0, FLOOR + 1.0, 0.45);
    grp.add(lamp);
  });

  // --- pilot chairs ---
  for (const x of [-1, 1]) {
    const seat = new THREE.Mesh(new RoundedBoxGeometry(0.8, 0.25, 0.8, 4, 0.1), leatherMat);
    seat.position.set(x * 1.0, FLOOR + 0.6, zc - 0.2);
    const back = new THREE.Mesh(new RoundedBoxGeometry(0.8, 1.2, 0.22, 4, 0.1), leatherMat);
    back.position.set(x * 1.0, FLOOR + 1.25, zc + 0.2);
    back.rotation.x = 0.12;
    const head = new THREE.Mesh(new RoundedBoxGeometry(0.5, 0.3, 0.16, 4, 0.07), leatherMat);
    head.position.set(x * 1.0, FLOOR + 2.0, zc + 0.27);
    const post = new THREE.Mesh(new THREE.CylinderGeometry(0.1, 0.18, 0.55, 8), hullDarkMat);
    post.position.set(x * 1.0, FLOOR + 0.28, zc - 0.2);
    const trim = new THREE.Mesh(new THREE.BoxGeometry(0.04, 0.9, 0.04), glowMat);
    trim.position.set(x * 1.0 + 0.38, FLOOR + 1.25, zc + 0.12);
    ship.add(seat, back, head, post, trim);
    for (const side of [-1, 1]) {
      const arm = new THREE.Mesh(new THREE.BoxGeometry(0.1, 0.1, 0.6), hullDarkMat);
      arm.position.set(x * 1.0 + side * 0.45, FLOOR + 0.85, zc - 0.2);
      ship.add(arm);
    }
  }

  // --- steering yokes ---
  const yokes = [];
  for (const x of [-1, 1]) {
    const col = new THREE.Mesh(new THREE.CylinderGeometry(0.05, 0.08, 1.0, 8), hullMat);
    col.position.set(x * 0.95, FLOOR + 0.5, INT_REAR + 2.4);
    const wheel = new THREE.Group();
    wheel.position.set(x * 0.95, FLOOR + 1.15, INT_REAR + 2.4);
    wheel.rotation.x = -0.35;
    const ring = new THREE.Mesh(new THREE.TorusGeometry(0.24, 0.03, 8, 28), hullDarkMat);
    const hub = new THREE.Mesh(new THREE.CylinderGeometry(0.07, 0.07, 0.08, 10), glowMat);
    hub.rotation.x = Math.PI / 2;
    const spokeA = new THREE.Mesh(new THREE.BoxGeometry(0.48, 0.035, 0.035), hullDarkMat);
    const spokeB = new THREE.Mesh(new THREE.BoxGeometry(0.035, 0.24, 0.035), hullDarkMat);
    spokeB.position.y = -0.12;
    wheel.add(ring, hub, spokeA, spokeB);
    for (const s of [-1, 1]) {
      const grip = new THREE.Mesh(new THREE.BoxGeometry(0.07, 0.14, 0.09), glowMat);
      grip.position.set(s * 0.24, 0, 0);
      wheel.add(grip);
    }
    // Clickable hit-target for the cockpit minigame. A dedicated (non-shared) material is
    // required: the generic hover loop below mutates b.material.color, which would otherwise
    // visibly tint every other mesh sharing hullDarkMat/glowMat.
    const hit = new THREE.Mesh(
      new THREE.SphereGeometry(0.32, 10, 10),
      new THREE.MeshBasicMaterial({ color: 0xffffff, transparent: true, opacity: 0, depthWrite: false })
    );
    hit.userData = { isGame: true, group: wheel }; // group: reuses the existing hover scale-pulse
    wheel.add(hit);
    buttons.push(hit);
    ship.add(col, wheel);
    yokes.push([wheel, x]);
  }
  animated.push((t) => yokes.forEach(([w, x]) => (w.rotation.z = Math.sin(t * 0.7 + x) * 0.18)));

  // --- central throttle pedestal ---
  const ped = new THREE.Mesh(new THREE.BoxGeometry(0.5, 0.6, 1.0), hullDarkMat);
  ped.position.set(0, FLOOR + 0.3, zc - 0.7);
  ship.add(ped);
  const levers = [];
  for (const dx of [-0.1, 0.1]) {
    const piv = new THREE.Group();
    piv.position.set(dx, FLOOR + 0.62, zc - 0.7);
    const stem = new THREE.Mesh(new THREE.BoxGeometry(0.04, 0.4, 0.04), hullMat);
    stem.position.y = 0.2;
    const knob = new THREE.Mesh(new THREE.BoxGeometry(0.08, 0.12, 0.08), glowMat);
    knob.position.y = 0.43;
    piv.add(stem, knob);
    ship.add(piv);
    levers.push([piv, dx]);
  }
  animated.push((t) => levers.forEach(([p, dx]) => (p.rotation.x = Math.sin(t * 0.5 + dx * 10) * 0.3 - 0.1)));

  // --- overhead switch panel ---
  const over = new THREE.Group();
  over.position.set(0, CEIL - 0.25, INT_REAR + 1.6);
  over.rotation.x = 0.35;
  over.add(new THREE.Mesh(new THREE.BoxGeometry(2.6, 0.12, 1.3), hullDarkMat));
  const COLS = 13, ROWS = 4;
  const toggles = new THREE.InstancedMesh(new THREE.BoxGeometry(0.08, 0.06, 0.08), new THREE.MeshBasicMaterial({ toneMapped: false }), COLS * ROWS);
  const dm = new THREE.Object3D();
  const swPalette = [new THREE.Color(ORANGE), new THREE.Color(0xffffff), new THREE.Color(0x9cc2ff), new THREE.Color(0x35e07a), new THREE.Color(0x1a1f3a)];
  for (let r = 0; r < ROWS; r++) for (let q = 0; q < COLS; q++) {
    dm.position.set(-1.2 + q * 0.2, -0.08, -0.45 + r * 0.28);
    dm.updateMatrix();
    toggles.setMatrixAt(r * COLS + q, dm.matrix);
    toggles.setColorAt(r * COLS + q, swPalette[Math.floor(rand() * swPalette.length)]);
  }
  over.add(toggles);
  ship.add(over);
  let lastSw = 0;
  animated.push((t) => {
    if (t - lastSw < 0.35) return;
    lastSw = t;
    for (let k = 0; k < 4; k++) toggles.setColorAt(Math.floor(Math.random() * COLS * ROWS), swPalette[Math.floor(Math.random() * swPalette.length)]);
    toggles.instanceColor.needsUpdate = true;
  });

  // --- side wall screens ---
  for (const side of [-1, 1]) {
    const frame = new THREE.Mesh(new THREE.BoxGeometry(0.06, 1.3, 2.0), hullDarkMat);
    frame.position.set(side * (ROOM_W / 2 - 0.03), 0.3, zc + 1.2);
    const scr = new THREE.Mesh(new THREE.PlaneGeometry(1.85, 1.15), liveScreen(480, 300, side < 0 ? drawMap : drawTelemetry, side < 0 ? 6 : 12));
    scr.position.set(side * (ROOM_W / 2 - 0.07), 0.3, zc + 1.2);
    scr.rotation.y = -side * Math.PI / 2;
    ship.add(frame, scr);
  }

  // extra light on the dashboard
  const dash = new THREE.PointLight(0x9cc2ff, 6, 6, 2);
  dash.position.set(0, FLOOR + 1.6, INT_REAR + 2.2);
  ship.add(dash);
}

// ---------- Outer space: sun, Earth, asteroids, meteors ----------
// The sun sits upper-left of the opening view; Earth fills the bottom-right corner.
const SUN_DIR = new THREE.Vector3(-0.78, 0.33, -0.5).normalize();
const sun = new THREE.DirectionalLight(0xfff1dc, 2.6);
sun.position.copy(SUN_DIR).multiplyScalar(100);
scene.add(sun);
// soft blue bounce from Earth below-right, and a dim fill so the shadow side isn't pitch black
const earthBounce = new THREE.DirectionalLight(0x6f9cff, 0.55);
earthBounce.position.set(60, -50, -40);
scene.add(earthBounce);
const spaceAmbient = new THREE.AmbientLight(0x1c2244, 0.35);
scene.add(spaceAmbient);
// soft cool fill from the viewer's side so the hull plating reads when the sun is behind it
const fill = new THREE.DirectionalLight(0xbfd0ff, 0.9);
fill.position.set(25, 12, 60);
scene.add(fill);

// Sun disc + glare (bloom does the rest)
const sunGlow = new THREE.Sprite(new THREE.SpriteMaterial({ map: TX.glowTexture('rgba(255,250,240,1)', 'rgba(255,190,120,0.3)'), blending: THREE.AdditiveBlending, depthWrite: false, toneMapped: false }));
sunGlow.position.copy(SUN_DIR).multiplyScalar(420);
sunGlow.scale.setScalar(70);
scene.add(sunGlow);

// Earth: day/night shader (city lights on the dark side), clouds, atmosphere rim
const texLoader = new THREE.TextureLoader();
const loadTex = (f, color = true) => {
  const t = texLoader.load('/textures/earth/' + f);
  if (color) t.colorSpace = THREE.SRGBColorSpace;
  t.anisotropy = 8;
  return t;
};
// Earth placement: computed from the opening camera so it sits in the bottom-right of the first view,
// far away and not too close to the screen edge (big spheres near the edge get stretched by perspective).
const HERO_CAM = new THREE.Vector3(15, 6, 36), HERO_LOOK = new THREE.Vector3(0, 0, -4);
const vDir = HERO_LOOK.clone().sub(HERO_CAM).normalize();
const vRight = new THREE.Vector3().crossVectors(vDir, new THREE.Vector3(0, 1, 0)).normalize();
const vUp = new THREE.Vector3().crossVectors(vRight, vDir).normalize();
const EARTH_R = 120;
const earth = new THREE.Group();
earth.position.copy(HERO_CAM).addScaledVector(vDir.clone().addScaledVector(vRight, 0.42).addScaledVector(vUp, -0.34).normalize(), 430);
earth.rotation.z = 0.41; // axial tilt
scene.add(earth);
const earthMat = new THREE.ShaderMaterial({
  uniforms: {
    dayMap: { value: loadTex('earth_atmos_2048.jpg') },
    nightMap: { value: loadTex('earth_lights_2048.png') },
    specMap: { value: loadTex('earth_specular_2048.jpg', false) },
    cloudMap: { value: loadTex('earth_clouds_1024.png') },
    cloudShift: { value: 0 },
    sunDir: { value: SUN_DIR },
  },
  vertexShader: `
    varying vec2 vUv; varying vec3 vN; varying vec3 vView;
    void main() {
      vUv = uv;
      vN = normalize(mat3(modelMatrix) * normal);
      vec4 wp = modelMatrix * vec4(position, 1.0);
      vView = normalize(cameraPosition - wp.xyz);
      gl_Position = projectionMatrix * viewMatrix * wp;
    }`,
  fragmentShader: `
    uniform sampler2D dayMap, nightMap, specMap, cloudMap; uniform vec3 sunDir; uniform float cloudShift;
    varying vec2 vUv; varying vec3 vN; varying vec3 vView;
    void main() {
      vec3 n = normalize(vN), v = normalize(vView);
      float d = dot(n, sunDir);
      float light = max(d, 0.0);
      float day = smoothstep(-0.2, 0.3, d);
      float cloud = texture2D(cloudMap, vUv + vec2(cloudShift, 0.0)).a;
      vec3 ground = texture2D(dayMap, vUv).rgb * (0.015 + 1.05 * light);
      vec3 cloudC = vec3(0.97) * (0.02 + 1.0 * light);
      vec3 col = mix(ground, cloudC, cloud * 0.9);
      // sun glint on open water only
      float ocean = texture2D(specMap, vUv).r * (1.0 - cloud);
      col += vec3(1.0, 0.95, 0.85) * ocean * pow(max(dot(reflect(-sunDir, n), v), 0.0), 40.0) * 0.35 * day;
      // city lights on the night side, dimmed by clouds
      col += texture2D(nightMap, vUv).rgb * vec3(1.0, 0.78, 0.5) * 1.1 * (1.0 - day) * (1.0 - cloud * 0.8);
      // atmospheric haze towards the limb on the lit side
      float limb = pow(1.0 - max(dot(n, v), 0.0), 2.5);
      col += vec3(0.32, 0.55, 1.0) * limb * smoothstep(-0.25, 0.6, d) * 0.7;
      gl_FragColor = vec4(col, 1.0);
      #include <tonemapping_fragment>
      #include <colorspace_fragment>
    }`,
});
// 64x48 (not 128x96): a plain smooth sphere doesn't need 4x the triangles to look round at the
// distance it's viewed from — the shader detail (clouds/night lights/limb glow) is unaffected.
const earthMesh = new THREE.Mesh(new THREE.SphereGeometry(EARTH_R, 64, 48), earthMat);
// thin glowing atmosphere shell, bright on the day side, fading into the night side
const atmosphere = new THREE.Mesh(
  new THREE.SphereGeometry(EARTH_R * 1.025, 64, 48),
  new THREE.ShaderMaterial({
    uniforms: { sunDir: { value: SUN_DIR } },
    vertexShader: `
      varying vec3 vN; varying vec3 vView;
      void main() {
        vN = normalize(mat3(modelMatrix) * normal);
        vec4 wp = modelMatrix * vec4(position, 1.0);
        vView = normalize(cameraPosition - wp.xyz);
        gl_Position = projectionMatrix * viewMatrix * wp;
      }`,
    fragmentShader: `
      uniform vec3 sunDir; varying vec3 vN; varying vec3 vView;
      void main() {
        float rim = pow(1.0 - abs(dot(normalize(vN), normalize(vView))), 6.0);
        float lit = smoothstep(-0.3, 0.6, dot(normalize(vN), sunDir));
        gl_FragColor = vec4(vec3(0.35, 0.6, 1.0) * rim * (0.08 + 1.4 * lit), 1.0);
      }`,
    side: THREE.BackSide, blending: THREE.AdditiveBlending, transparent: true, depthWrite: false,
  })
);
earth.add(earthMesh, atmosphere);

// Asteroids: displaced, cratered rocks
const rockTex = TX.rockTextures();
const rockMat = new THREE.MeshStandardMaterial({ map: rockTex.map, normalMap: rockTex.normalMap, normalScale: new THREE.Vector2(1.4, 1.4), roughness: 0.95, metalness: 0.05 });
function rockGeometry(seedN) {
  // detail 3 (not 4): the normal map carries the crater/lump detail at pixel scale anyway, so the
  // base mesh doesn't need 4x the triangles — same look, much cheaper across ~50 asteroid meshes.
  const geo = new THREE.IcosahedronGeometry(1, 3);
  const p = geo.attributes.position, v = new THREE.Vector3();
  const lumps = Array.from({ length: 6 }, (_, k) => new THREE.Vector3(Math.sin(seedN * 3.1 + k * 1.7), Math.cos(seedN * 1.3 + k * 2.3), Math.sin(seedN * 2.2 + k)).normalize());
  for (let i = 0; i < p.count; i++) {
    v.fromBufferAttribute(p, i).normalize();
    let r = 1 + 0.08 * Math.sin(v.x * 7 + seedN) * Math.sin(v.y * 6 + seedN * 2) + 0.05 * Math.sin(v.z * 13 + seedN * 3);
    lumps.forEach((l, k) => { r += (k % 2 ? -0.18 : 0.22) * Math.pow(Math.max(0, v.dot(l)), 3); });
    v.multiplyScalar(r);
    v.y *= 0.8;
    p.setXYZ(i, v.x, v.y, v.z);
  }
  geo.computeVertexNormals();
  return geo;
}
const rockGeos = [1, 2, 3, 4, 5].map(rockGeometry);

// a distant drifting field
const field = [];
for (let i = 0; i < 28; i++) {
  const m = new THREE.Mesh(rockGeos[i % rockGeos.length], rockMat);
  const a = Math.random() * Math.PI * 2;
  m.position.set(-150 + Math.random() * 120, -30 + Math.random() * 70, -90 - Math.random() * 110);
  m.scale.setScalar(0.6 + Math.pow(Math.random(), 2) * 4);
  m.rotation.set(a, a * 0.7, a * 1.3);
  m.userData.spin = new THREE.Vector3(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).multiplyScalar(0.3);
  scene.add(m);
  field.push(m);
}

// occasional asteroid that tumbles past the ship
const flybys = [0, 1].map((k) => {
  const m = new THREE.Mesh(rockGeos[(k + 2) % rockGeos.length], rockMat);
  m.visible = false;
  m.userData = { vel: new THREE.Vector3(), spin: new THREE.Vector3(), next: 4 + k * 9, hit: false };
  scene.add(m);
  return m;
});

// meteors: short, thin streaks of light flashing across the far background now and then
const METEORS = 3;
const meteorPos = new Float32Array(METEORS * 6);
const meteorCol = new Float32Array(METEORS * 6);
const meteorGeo = new THREE.BufferGeometry();
meteorGeo.setAttribute('position', new THREE.BufferAttribute(meteorPos, 3));
meteorGeo.setAttribute('color', new THREE.BufferAttribute(meteorCol, 3));
const meteorLines = new THREE.LineSegments(meteorGeo, new THREE.LineBasicMaterial({ vertexColors: true, blending: THREE.AdditiveBlending, transparent: true, depthWrite: false, toneMapped: false }));
meteorLines.frustumCulled = false;
scene.add(meteorLines);
const meteors = Array.from({ length: METEORS }, (_, k) => ({ head: new THREE.Vector3(), dir: new THREE.Vector3(), speed: 0, len: 0, age: 0, life: 0, next: 2 + k * 2.5 }));

const tmpV = new THREE.Vector3();
animated.push((t, dt) => {
  earth.rotation.y = 2.2 + t * 0.006;
  earthMat.uniforms.cloudShift.value = t * 0.0015;
  field.forEach((m) => { m.rotation.x += m.userData.spin.x * dt; m.rotation.y += m.userData.spin.y * dt; });

  flybys.forEach((m) => {
    const u = m.userData;
    if (!m.visible) {
      if (t > u.next) { // spawn on the left, drift to the right in front of/behind the ship
        const z = -25 - Math.random() * 45;
        m.position.set(-90, -8 + Math.random() * 24, z);
        u.vel.set(5 + Math.random() * 4, (Math.random() - 0.5) * 1.5, (Math.random() - 0.5) * 2);
        u.spin.set(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).multiplyScalar(1.2);
        m.scale.setScalar(1.2 + Math.random() * 2.2);
        m.visible = true;
      }
      return;
    }
    m.position.addScaledVector(u.vel, dt);
    m.rotation.x += u.spin.x * dt; m.rotation.y += u.spin.y * dt; m.rotation.z += u.spin.z * dt;
    // deflect off the hull instead of tumbling straight through it
    if (m.position.z > Z_REAR - 3 && m.position.z < Z_FRONT + 3) {
      const r = Math.hypot(m.position.x, m.position.y);
      const safeR = HULL_R * 1.2 + m.scale.x;
      if (r < safeR) {
        if (!u.hit) {
          u.hit = true;
          triggerShake(0.05 + m.scale.x * 0.045);
          sfx.bump(Math.min(1, 0.35 + m.scale.x * 0.18));
        }
        const nx = r > 0.001 ? m.position.x / r : 1, ny = r > 0.001 ? m.position.y / r : 0;
        const vDotN = u.vel.x * nx + u.vel.y * ny;
        if (vDotN < 0) { u.vel.x -= 2 * vDotN * nx; u.vel.y -= 2 * vDotN * ny; }
        m.position.x = nx * safeR; m.position.y = ny * safeR;
      } else {
        u.hit = false;
      }
    }
    if (m.position.x > 90) { m.visible = false; u.next = t + 6 + Math.random() * 14; }
  });

  meteors.forEach((m, k) => {
    if (m.life === 0) {
      if (t > m.next) {
        m.head.set(-220 + Math.random() * 440, 30 + Math.random() * 140, -180 - Math.random() * 200);
        m.dir.set((Math.random() < 0.5 ? -1 : 1) * (0.6 + Math.random() * 0.4), -0.5 - Math.random() * 0.5, (Math.random() - 0.5) * 0.3).normalize();
        m.speed = 160 + Math.random() * 120;
        m.len = 14 + Math.random() * 22;
        m.age = 0;
        m.life = 0.5 + Math.random() * 0.6;
      }
    } else {
      m.age += dt;
      m.head.addScaledVector(m.dir, m.speed * dt);
      if (m.age >= m.life) { m.life = 0; m.next = t + 2 + Math.random() * 6; }
    }
    const fade = m.life ? Math.sin(Math.PI * Math.min(1, m.age / m.life)) : 0;
    const tail = tmpV.copy(m.head).addScaledVector(m.dir, -m.len);
    meteorPos.set([m.head.x, m.head.y, m.head.z, tail.x, tail.y, tail.z], k * 6);
    meteorCol.set([fade * 1.6, fade * 1.5, fade * 1.3, 0, 0, 0], k * 6);
  });
  meteorGeo.attributes.position.needsUpdate = true;
  meteorGeo.attributes.color.needsUpdate = true;
});

// ---------- Easter egg: a UFO that occasionally cruises past ----------
const ufo = new THREE.Group();
const ufoHullMat = new THREE.MeshStandardMaterial({ color: 0x9aa0b4, metalness: 0.85, roughness: 0.25 });
const ufoDomeMat = new THREE.MeshPhysicalMaterial({ color: 0x8fffe0, metalness: 0, roughness: 0.08, transparent: true, opacity: 0.55, clearcoat: 1, clearcoatRoughness: 0.05, envMapIntensity: 0.6 });
const ufoBody = new THREE.Mesh(new THREE.SphereGeometry(1, 24, 16), ufoHullMat);
ufoBody.scale.set(1.8, 0.4, 1.8);
const ufoRim = new THREE.Mesh(new THREE.TorusGeometry(1.75, 0.18, 10, 28), ufoHullMat);
ufoRim.rotation.x = Math.PI / 2;
const ufoDome = new THREE.Mesh(new THREE.SphereGeometry(0.75, 20, 14, 0, Math.PI * 2, 0, Math.PI / 2), ufoDomeMat);
ufoDome.position.y = 0.22;
const UFO_LIGHTS = 8;
const ufoLights = Array.from({ length: UFO_LIGHTS }, (_, i) => {
  const l = new THREE.Mesh(new THREE.SphereGeometry(0.11, 8, 8), new THREE.MeshStandardMaterial({ color: ORANGE, emissive: ORANGE, emissiveIntensity: 2, toneMapped: false }));
  const a = (i / UFO_LIGHTS) * Math.PI * 2;
  l.position.set(Math.cos(a) * 1.75, -0.12, Math.sin(a) * 1.75);
  return l;
});
ufo.add(ufoBody, ufoRim, ufoDome, ...ufoLights);
ufo.visible = false;
ufo.traverse((o) => o.layers.enable(1)); // also visible through the bridge window
ufo.userData = { vel: new THREE.Vector3(), next: 6 + Math.random() * 6, bobSeed: 0, seen: false };
scene.add(ufo);

animated.push((t, dt) => {
  const u = ufo.userData;
  if (!ufo.visible) {
    if (t > u.next) {
      const dir = Math.random() < 0.5 ? 1 : -1;
      // guaranteed on the first pass so it's easy to spot; a rare surprise after that
      if (!u.seen || Math.random() < 0.6) {
        // kept well behind the asteroid flybys (which sit at z -25 to -70) so it reads as distant and passes behind them
        ufo.position.set(-dir * 95, 6 + Math.random() * 18, -90 - Math.random() * 50);
        u.vel.set(dir * (9 + Math.random() * 5), 0, 0);
        u.bobSeed = Math.random() * 100;
        ufo.visible = true;
        u.seen = true;
      }
      u.next = t + 45 + Math.random() * 70;
    }
    return;
  }
  ufo.position.x += u.vel.x * dt;
  ufo.position.y += Math.sin((t + u.bobSeed) * 1.6) * dt * 1.8;
  ufo.rotation.y += dt * 0.6;
  ufoLights.forEach((l, i) => { l.material.emissiveIntensity = 1.6 + Math.sin(t * 6 + i) * 1.2; });
  if (Math.abs(ufo.position.x) > 100) ufo.visible = false;
});

// ---------- Starfield ----------
// beyond Earth and the sun so no star is drawn in front of them
const STARS = 4000;
const starPos = new Float32Array(STARS * 3);
const starCol = new Float32Array(STARS * 3);
const sc = new THREE.Color();
for (let i = 0; i < STARS; i++) {
  const r = 720 + Math.random() * 120;
  const th = Math.random() * Math.PI * 2;
  const ph = Math.acos(2 * Math.random() - 1);
  starPos[i * 3] = r * Math.sin(ph) * Math.cos(th);
  starPos[i * 3 + 1] = r * Math.sin(ph) * Math.sin(th);
  starPos[i * 3 + 2] = r * Math.cos(ph);
  sc.setHSL(Math.random() < 0.7 ? 0.6 : 0.08, 0.35, 0.65 + Math.random() * 0.35);
  starCol.set([sc.r, sc.g, sc.b], i * 3);
}
const starGeo = new THREE.BufferGeometry();
starGeo.setAttribute('position', new THREE.BufferAttribute(starPos, 3));
starGeo.setAttribute('color', new THREE.BufferAttribute(starCol, 3));
const stars = new THREE.Points(starGeo, new THREE.PointsMaterial({ size: 2.4, vertexColors: true, transparent: true, opacity: 0.9 }));
scene.add(stars);

// ---------- Window view (bridge) ----------
// A second camera behind the ship renders space into the bridge window. It only sees layer 1,
// so the ship itself never blocks the view. Some traffic is window-only (layer 1 only).
const PORTAL_POS = new THREE.Vector3(0, 0.5, -40);
const WINDOW_CENTER = new THREE.Vector3(0, 0.5, INT_REAR + 0.05);
const portalCam = new THREE.PerspectiveCamera(30, 4.4 / 2.5, 1, 1200);
portalCam.position.copy(PORTAL_POS);
portalCam.layers.set(1);
for (const o of [sun, earthBounce, fill, spaceAmbient, sunGlow, earthMesh, atmosphere, meteorLines, stars, ...field, ...flybys]) o.layers.enable(1);

const winRocks = [0, 1, 2].map((k) => {
  const m = new THREE.Mesh(rockGeos[(k + 1) % rockGeos.length], rockMat);
  m.layers.set(1);
  m.visible = false;
  m.userData = { vel: new THREE.Vector3(), spin: new THREE.Vector3(), next: 0.5 + k * 2.2, lim: 0 };
  scene.add(m);
  return m;
});
const WIN_METEORS = 4;
const wmPos = new Float32Array(WIN_METEORS * 6), wmCol = new Float32Array(WIN_METEORS * 6);
const wmGeo = new THREE.BufferGeometry();
wmGeo.setAttribute('position', new THREE.BufferAttribute(wmPos, 3));
wmGeo.setAttribute('color', new THREE.BufferAttribute(wmCol, 3));
const wmLines = new THREE.LineSegments(wmGeo, new THREE.LineBasicMaterial({ vertexColors: true, blending: THREE.AdditiveBlending, transparent: true, depthWrite: false, toneMapped: false }));
wmLines.frustumCulled = false;
wmLines.layers.set(1);
scene.add(wmLines);
const winMeteors = Array.from({ length: WIN_METEORS }, (_, k) => ({ head: new THREE.Vector3(), dir: new THREE.Vector3(), speed: 0, len: 0, age: 0, life: 0, next: 0.4 + k * 0.9 }));

animated.push((t, dt) => {
  winRocks.forEach((m) => {
    const u = m.userData;
    if (!m.visible) {
      if (t > u.next) {
        const depth = 45 + Math.random() * 90;
        const dir = Math.random() < 0.5 ? 1 : -1;
        u.lim = depth * 0.55 + 8;
        m.position.set(PORTAL_POS.x - dir * u.lim, PORTAL_POS.y - 12 + Math.random() * 22, PORTAL_POS.z - depth);
        u.vel.set(dir * (7 + Math.random() * 9), (Math.random() - 0.5) * 2, (Math.random() - 0.5) * 3);
        u.spin.set(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).multiplyScalar(1.4);
        m.scale.setScalar(1.2 + Math.random() * 3.2);
        m.visible = true;
      }
      return;
    }
    m.position.addScaledVector(u.vel, dt);
    m.rotation.x += u.spin.x * dt; m.rotation.y += u.spin.y * dt; m.rotation.z += u.spin.z * dt;
    if (Math.abs(m.position.x - PORTAL_POS.x) > u.lim + 2) { m.visible = false; u.next = t + 1.5 + Math.random() * 4.5; }
  });
  winMeteors.forEach((m, k) => {
    if (m.life === 0) {
      if (t > m.next) {
        m.head.set(-160 + Math.random() * 320, -10 + Math.random() * 110, PORTAL_POS.z - 160 - Math.random() * 220);
        m.dir.set((Math.random() < 0.5 ? -1 : 1) * (0.5 + Math.random() * 0.5), -0.4 - Math.random() * 0.6, (Math.random() - 0.5) * 0.2).normalize();
        m.speed = 150 + Math.random() * 130;
        m.len = 12 + Math.random() * 24;
        m.age = 0;
        m.life = 0.4 + Math.random() * 0.6;
      }
    } else {
      m.age += dt;
      m.head.addScaledVector(m.dir, m.speed * dt);
      if (m.age >= m.life) { m.life = 0; m.next = t + 0.6 + Math.random() * 2.4; }
    }
    const fade = m.life ? Math.sin(Math.PI * Math.min(1, m.age / m.life)) : 0;
    const tail = tmpV.copy(m.head).addScaledVector(m.dir, -m.len);
    wmPos.set([m.head.x, m.head.y, m.head.z, tail.x, tail.y, tail.z], k * 6);
    wmCol.set([fade * 1.7, fade * 1.6, fade * 1.4, 0, 0, 0], k * 6);
  });
  wmGeo.attributes.position.needsUpdate = true;
  wmGeo.attributes.color.needsUpdate = true;
});

const portalDir = new THREE.Vector3();
// Shared by renderWindowView() and the minigame's hit-testing (handleGameShot), so the view the
// player sees and the view the shot is tested against are always the exact same aim.
function aimPortalCam() {
  // look out along the line from the viewer's eye through the window (parallax), tilted slightly down towards Earth
  portalDir.copy(WINDOW_CENTER).sub(camera.position).normalize();
  portalDir.y -= 0.12;
  if (GAME.active) {
    // while manning the guns, the view pans with the mouse like aiming a turret
    portalDir.x += mouse.x * 0.22;
    portalDir.y += -mouse.y * 0.16;
  }
  const dist = Math.max(1.2, camera.position.z - WINDOW_CENTER.z);
  portalCam.fov = THREE.MathUtils.clamp(THREE.MathUtils.radToDeg(2 * Math.atan(1.25 / dist)), 24, 75);
  portalCam.updateProjectionMatrix();
  portalCam.lookAt(tmpV.copy(PORTAL_POS).add(portalDir));
}
function renderWindowView() {
  aimPortalCam();
  renderer.setRenderTarget(portalRT);
  renderer.render(scene, portalCam);
  renderer.setRenderTarget(null);
}

// ---------- Cockpit minigame: asteroid attack ----------
// A scored shooting-gallery round seen through the bridge window. Reuses the rock geometry/
// material, the hull-bounce feedback (triggerShake + sfx.bump) for "missed" asteroids, and the
// window's layer-1-only visibility trick (see "Window view" above) so these rocks never appear
// anywhere except inside the portal-camera render target.

const GAME_POOL_SIZE = 8;
const GAME_SPREAD_X = 20, GAME_SPREAD_Y = 12; // lateral spawn spread around PORTAL_POS
const GAME_SPAWN_DEPTH_MIN = 80, GAME_SPAWN_DEPTH_RANGE = 70; // spawn 80-150 units behind PORTAL_POS
const GAME_SPEED_MIN = 20, GAME_SPEED_RANGE = 12; // 20-32 u/s -> ~2.5-7.5s to arrive
const GAME_MISS_RADIUS = 8; // distance to PORTAL_POS at which an un-shot rock is "missed"
const ALERT_DURATION = 1.4; // seconds the "ASTEROID ALERT" banner shows before spawns start
const ROUND_DURATION = 50; // seconds of active shooting per round
const SPAWN_INTERVAL = 1.8; // seconds between spawns
const COMPLETE_HOLD = 2.5; // seconds the "round complete" banner stays before auto-exit
const LASER_LIFE = 0.12; // seconds the tracer line stays visible
const EXPLOSION_LIFE = 0.35; // seconds the explosion sprite stays visible

const GAME = { active: false, phase: 'idle', score: 0, timeLeft: 0, spawnTimer: 0, phaseTimer: 0 };

const gameAsteroids = Array.from({ length: GAME_POOL_SIZE }, (_, k) => {
  const m = new THREE.Mesh(rockGeos[k % rockGeos.length], rockMat);
  m.layers.set(1); // window-only, exactly like winRocks
  m.visible = false;
  m.userData = { vel: new THREE.Vector3(), spin: new THREE.Vector3(), alive: false };
  scene.add(m);
  return m;
});

function spawnGameAsteroid(m) {
  const depth = GAME_SPAWN_DEPTH_MIN + Math.random() * GAME_SPAWN_DEPTH_RANGE;
  m.position.set(
    PORTAL_POS.x + (Math.random() * 2 - 1) * GAME_SPREAD_X,
    PORTAL_POS.y + (Math.random() * 2 - 1) * GAME_SPREAD_Y,
    PORTAL_POS.z - depth
  );
  const speed = GAME_SPEED_MIN + Math.random() * GAME_SPEED_RANGE;
  m.userData.vel.copy(tmpV.copy(PORTAL_POS).sub(m.position).normalize()).multiplyScalar(speed);
  m.userData.spin.set(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).multiplyScalar(1.1);
  m.scale.setScalar(0.8 + Math.random() * 1.4); // smaller than winRocks: fill the frame as they close in
  m.rotation.set(Math.random() * 6.28, Math.random() * 6.28, Math.random() * 6.28);
  m.userData.alive = true;
  m.userData.bounced = false;
  m.visible = true;
}

function updateOneAsteroid(m, dt) {
  const u = m.userData;
  if (!u.alive) return;
  m.position.addScaledVector(u.vel, dt);
  m.rotation.x += u.spin.x * dt; m.rotation.y += u.spin.y * dt; m.rotation.z += u.spin.z * dt;
  const distToPortal = m.position.distanceTo(PORTAL_POS);
  if (!u.bounced && distToPortal < GAME_MISS_RADIUS) {
    // Missed: bounce it off instead of vanishing, reusing the exact same feedback as a real
    // hull bounce (bump/shake), for continuity with the ambient flybys system, even though this
    // mesh never actually touches the hull geometry itself.
    u.bounced = true;
    triggerShake(0.04 + m.scale.x * 0.03);
    sfx.bump(Math.min(1, 0.3 + m.scale.x * 0.15));
    GAME.score = Math.max(0, GAME.score - 1); // it hit the shuttle: a point off instead of on
    updateHudScore();
    const normal = tmpV.copy(m.position).sub(PORTAL_POS);
    if (normal.lengthSq() < 0.0001) normal.set(0, 1, 0); else normal.normalize();
    const vDotN = u.vel.dot(normal);
    if (vDotN < 0) u.vel.addScaledVector(normal, -2 * vDotN);
    u.vel.multiplyScalar(0.6); // glances off, doesn't keep its full approach speed
  }
  if (u.bounced && distToPortal > GAME_MISS_RADIUS * 6) {
    // far enough away now: recycle quietly, no more feedback
    u.alive = false;
    m.visible = false;
  }
}

// Reusable across the dedicated minigame pool AND the pre-existing ambient asteroids (flybys,
// winRocks) that happen to be visible through the window at the same time — "any asteroid you
// can see, you can shoot," per feedback, with the UFO deliberately left alone (see handleGameShot).
function destroyAsteroid(mesh) {
  if (!mesh.visible) return;
  mesh.visible = false;
  spawnExplosion(mesh.position, mesh.scale.x);
  spawnFragments(mesh.position, mesh.scale.x);
  sfx.explosion(Math.min(1, 0.5 + mesh.scale.x * 0.2));
  GAME.score += 1;
  updateHudScore();

  const u = mesh.userData;
  if (gameAsteroids.includes(mesh)) {
    u.alive = false;
  } else if (flybys.includes(mesh)) {
    u.hit = false;
    u.next = clock.getElapsedTime() + 6 + Math.random() * 14; // same cadence as its natural despawn
  } else if (winRocks.includes(mesh)) {
    u.next = clock.getElapsedTime() + 1.5 + Math.random() * 4.5; // same cadence as its natural despawn
  }
}

// --- laser tracer: short additive line, reusing the meteorLines/wmLines pattern ---
const laserGeo = new THREE.BufferGeometry();
laserGeo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(6), 3));
const laserMat = new THREE.LineBasicMaterial({ color: 0x9cffcf, transparent: true, blending: THREE.AdditiveBlending, depthWrite: false, toneMapped: false });
const laserLine = new THREE.LineSegments(laserGeo, laserMat);
laserLine.layers.set(1); // window-only
laserLine.visible = false;
laserLine.frustumCulled = false;
scene.add(laserLine);
let laserT0 = -1;

function spawnLaserTracer(from, to) {
  const p = laserGeo.attributes.position.array;
  p[0] = from.x; p[1] = from.y; p[2] = from.z;
  p[3] = to.x; p[4] = to.y; p[5] = to.z;
  laserGeo.attributes.position.needsUpdate = true;
  laserLine.visible = true;
  laserMat.opacity = 1;
  laserT0 = clock.getElapsedTime();
}

// --- interior beam: a visible bolt fired from the shuttle itself, from a gun mount on the
// console out to the exact spot hit on the window glass, on the DEFAULT layer so the main camera
// (sitting right there in the cockpit) sees it leave the ship — the window-only tracer above then
// carries the same shot visually onward into the space beyond the glass. ---
const GUN_EMITTER = new THREE.Vector3(0, 0.75, INT_REAR + 2.3); // just above the console, between the yokes
const beamGeo = new THREE.BufferGeometry();
beamGeo.setAttribute('position', new THREE.BufferAttribute(new Float32Array(6), 3));
const beamMat = new THREE.LineBasicMaterial({ color: 0x9cffcf, transparent: true, blending: THREE.AdditiveBlending, depthWrite: false, toneMapped: false, linewidth: 2 });
const interiorBeam = new THREE.LineSegments(beamGeo, beamMat);
interiorBeam.visible = false;
interiorBeam.frustumCulled = false;
scene.add(interiorBeam); // default layer 0: visible to the main camera
let beamT0 = -1;

const muzzleFlash = new THREE.Sprite(new THREE.SpriteMaterial({ map: TX.glowTexture('rgba(220,255,245,1)', 'rgba(156,255,207,0.5)'), blending: THREE.AdditiveBlending, depthWrite: false, toneMapped: false, transparent: true, opacity: 0 }));
muzzleFlash.position.copy(GUN_EMITTER);
muzzleFlash.scale.setScalar(0.4);
muzzleFlash.visible = false;
scene.add(muzzleFlash); // default layer 0: visible to the main camera

function spawnInteriorBeam(to) {
  const p = beamGeo.attributes.position.array;
  p[0] = GUN_EMITTER.x; p[1] = GUN_EMITTER.y; p[2] = GUN_EMITTER.z;
  p[3] = to.x; p[4] = to.y; p[5] = to.z;
  beamGeo.attributes.position.needsUpdate = true;
  interiorBeam.visible = true;
  beamMat.opacity = 1;
  beamT0 = clock.getElapsedTime();
  muzzleFlash.visible = true;
  muzzleFlash.material.opacity = 1;
}

// --- explosion flash: pooled additive sprites, scale-up-then-fade ---
const EXPLOSION_POOL = 4;
const explosionTex = TX.glowTexture('rgba(255,255,255,1)', 'rgba(255,140,60,0.5)');
const explosions = Array.from({ length: EXPLOSION_POOL }, () => {
  const spr = new THREE.Sprite(new THREE.SpriteMaterial({ map: explosionTex, blending: THREE.AdditiveBlending, depthWrite: false, toneMapped: false, transparent: true, opacity: 0 }));
  spr.layers.set(1); // window-only
  spr.visible = false;
  scene.add(spr);
  return spr;
});
let explosionCursor = 0;

function spawnExplosion(position, scale) {
  const spr = explosions[explosionCursor];
  explosionCursor = (explosionCursor + 1) % EXPLOSION_POOL;
  spr.position.copy(position);
  spr.userData.t0 = clock.getElapsedTime();
  spr.userData.maxScale = scale * 3.2;
  spr.scale.setScalar(scale * 0.6);
  spr.material.opacity = 1;
  spr.visible = true;
}

// --- debris: a destroyed asteroid breaks into a few smaller tumbling fragments, reusing the
// same rock geometry/material (just scaled down), instead of just a glow flash ---
const FRAGMENT_POOL = 18; // up to 6 simultaneous bursts of 3
const FRAGMENTS_PER_HIT = 3;
const fragments = Array.from({ length: FRAGMENT_POOL }, (_, k) => {
  const m = new THREE.Mesh(rockGeos[k % rockGeos.length], rockMat);
  m.layers.set(1); // window-only
  m.visible = false;
  m.userData = { vel: new THREE.Vector3(), spin: new THREE.Vector3(), life: 0, maxLife: 0, baseScale: 0 };
  scene.add(m);
  return m;
});
let fragmentCursor = 0;

function spawnFragments(position, scale) {
  for (let i = 0; i < FRAGMENTS_PER_HIT; i++) {
    const f = fragments[fragmentCursor];
    fragmentCursor = (fragmentCursor + 1) % FRAGMENT_POOL;
    const u = f.userData;
    f.position.copy(position);
    u.baseScale = scale * (0.22 + Math.random() * 0.22);
    f.scale.setScalar(u.baseScale);
    f.rotation.set(Math.random() * 6.28, Math.random() * 6.28, Math.random() * 6.28);
    const dir = tmpV.set(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).normalize();
    u.vel.copy(dir).multiplyScalar(4 + Math.random() * 7);
    u.spin.set(Math.random() - 0.5, Math.random() - 0.5, Math.random() - 0.5).multiplyScalar(3.5);
    u.life = 0;
    u.maxLife = 0.7 + Math.random() * 0.5;
    f.visible = true;
  }
}

// --- emergency lighting: flickers red during the alert, settles to a tense pulse while shooting ---
const alertLight = new THREE.PointLight(0xff3b30, 0, 14, 1.6);
alertLight.position.set(0, CEIL - 0.3, INT_REAR + 1.8);
ship.add(alertLight);
const alertLight2 = new THREE.PointLight(0xff3b30, 0, 14, 1.6);
alertLight2.position.set(0, FLOOR + 0.4, INT_REAR + 2.6);
ship.add(alertLight2); // low, near the yokes, so the strobe catches the pilot's hands/console too

// --- two-stage raycast hit-testing: a single raycast from the main camera cannot work here,
// since portalCam's orientation/FOV are recomputed every frame from the main camera's position
// (see renderWindowView() above) and bear no fixed geometric relationship to the main camera's
// own projection. Instead: raycast the window plane to get a UV, convert that UV to portalCam's
// NDC space, then raycast again from portalCam. ---
const windowRaycaster = new THREE.Raycaster();
const portalRaycaster = new THREE.Raycaster();
portalRaycaster.layers.set(1); // CRITICAL: a fresh Raycaster only tests layer 0 by default; the
                                // game asteroids/laser/explosions are layer-1-only, so without
                                // this every shot would silently find nothing.

function handleGameShot(clientX, clientY) {
  const ndc = new THREE.Vector2((clientX / window.innerWidth) * 2 - 1, -(clientY / window.innerHeight) * 2 + 1);
  windowRaycaster.setFromCamera(ndc, camera);
  const winHit = windowRaycaster.intersectObject(cockpitWindowMesh)[0];
  if (!winHit || !winHit.uv) return; // the click missed the window entirely: no shot fired

  // Recompute portalCam's current orientation/FOV/pan exactly as renderWindowView() does, so the
  // hit-test matches what the player is actually looking at right now (cheap, deterministic
  // from camera.position + mouse, since the camera is essentially static while mode === 'hold').
  aimPortalCam();
  portalCam.updateMatrixWorld(true); // lookAt only sets rotation; raycasting needs matrixWorld current

  const px = winHit.uv.x * 2 - 1, py = winHit.uv.y * 2 - 1; // RT textures aren't Y-flipped: no extra flip needed
  portalRaycaster.setFromCamera(new THREE.Vector2(px, py), portalCam);
  const shotDir = portalRaycaster.ray.direction.clone();
  // Shootable: the dedicated minigame rocks plus any ambient asteroid also visible through the
  // window right now (flybys, winRocks) — everything "seen" except the UFO, left alone on purpose.
  const shootable = gameAsteroids.concat(flybys, winRocks);
  const hit = portalRaycaster.intersectObjects(shootable, false)[0];

  sfx.laser();
  spawnInteriorBeam(winHit.point); // the visible bolt leaving the shuttle, seen from inside the cockpit
  spawnLaserTracer(portalCam.position, hit ? hit.point : tmpV.copy(portalCam.position).addScaledVector(shotDir, 120));
  if (hit) destroyAsteroid(hit.object);
}

// --- HUD DOM wiring (elements rendered hidden in index.blade.php) ---
const hudRoot = document.getElementById('cockpit-game');
const hudAlert = document.getElementById('game-alert');
const hudStats = document.getElementById('game-stats');
const hudComplete = document.getElementById('game-complete');
const hudScoreEl = document.getElementById('game-score');
const hudTimeEl = document.getElementById('game-time');
const hudCompleteScoreEl = document.getElementById('game-complete-score');
const hudExitBtn = document.getElementById('game-exit');
const hintEl = document.getElementById('cockpit-hint');
const crosshairEl = document.getElementById('game-crosshair');
const emergencyFlashEl = document.getElementById('emergency-flash');

function updateHudScore() { if (hudScoreEl) hudScoreEl.textContent = String(GAME.score); }
function updateHudTimer() { if (hudTimeEl) hudTimeEl.textContent = String(Math.max(0, Math.ceil(GAME.timeLeft))); }
function hudSetAlert() { if (!hudRoot) return; hudRoot.hidden = false; hudAlert.hidden = false; hudStats.hidden = true; hudComplete.hidden = true; if (crosshairEl) crosshairEl.hidden = true; }
function hudSetPlaying() { if (!hudRoot) return; hudAlert.hidden = true; hudStats.hidden = false; updateHudScore(); updateHudTimer(); if (crosshairEl) crosshairEl.hidden = false; }
function hudSetComplete() { if (!hudRoot) return; hudStats.hidden = true; hudComplete.hidden = false; if (hudCompleteScoreEl) hudCompleteScoreEl.textContent = String(GAME.score); if (crosshairEl) crosshairEl.hidden = true; }
function hudHideAll() { if (!hudRoot) return; hudRoot.hidden = true; hudAlert.hidden = true; hudStats.hidden = true; hudComplete.hidden = true; if (crosshairEl) crosshairEl.hidden = true; document.body.style.cursor = ''; }

if (hudExitBtn) hudExitBtn.addEventListener('click', (e) => { e.stopPropagation(); exitCockpitGame(); });

// --- round state machine ---
function startAsteroidAttack() {
  GAME.active = true;
  GAME.score = 0;
  GAME.phase = 'alert';
  GAME.phaseTimer = ALERT_DURATION;
  gameAsteroids.forEach((m) => { m.visible = false; m.userData.alive = false; });
  updateHudScore();
  hudSetAlert();
  if (cockpitHudMesh) cockpitHudMesh.visible = false; // avoid doubling up with the real crosshair
  // Hide the scrolling page content (whichever room section happens to be in view behind the
  // canvas) so it doesn't overlap the HUD/crosshair, especially cramped on mobile. visibility
  // (not display) so document.documentElement.scrollHeight is unaffected — exitCockpitGame()
  // relies on the scroll position being intact to fly back to the right spot.
  document.documentElement.classList.add('game-active');
}

function stopAsteroidAttack() {
  GAME.active = false;
  GAME.phase = 'idle';
  gameAsteroids.forEach((m) => { m.visible = false; m.userData.alive = false; });
  laserLine.visible = false; laserT0 = -1;
  interiorBeam.visible = false; muzzleFlash.visible = false; beamT0 = -1;
  explosions.forEach((s) => (s.visible = false));
  fragments.forEach((f) => (f.visible = false));
  alertLight.intensity = alertLight2.intensity = 0;
  if (emergencyFlashEl) emergencyFlashEl.classList.remove('on');
  hudHideAll();
  if (cockpitHudMesh) cockpitHudMesh.visible = true;
  document.documentElement.classList.remove('game-active');
}

function updateCockpitGame(t, dt) {
  if (laserT0 >= 0) {
    const k = (t - laserT0) / LASER_LIFE;
    if (k >= 1) { laserLine.visible = false; laserT0 = -1; } else { laserMat.opacity = 1 - k; }
  }
  if (beamT0 >= 0) {
    const k = (t - beamT0) / LASER_LIFE;
    if (k >= 1) { interiorBeam.visible = false; muzzleFlash.visible = false; beamT0 = -1; } else {
      beamMat.opacity = 1 - k;
      muzzleFlash.material.opacity = (1 - k) * 0.8;
      muzzleFlash.scale.setScalar(0.4 + k * 0.5);
    }
  }
  explosions.forEach((spr) => {
    if (!spr.visible) return;
    const k = (t - spr.userData.t0) / EXPLOSION_LIFE;
    if (k >= 1) { spr.visible = false; return; }
    const ease = 1 - (1 - k) * (1 - k);
    spr.scale.setScalar(THREE.MathUtils.lerp(spr.userData.maxScale * 0.3, spr.userData.maxScale, ease));
    spr.material.opacity = 1 - k;
  });
  fragments.forEach((f) => {
    const u = f.userData;
    if (!f.visible) return;
    u.life += dt;
    if (u.life >= u.maxLife) { f.visible = false; return; }
    f.position.addScaledVector(u.vel, dt);
    u.vel.multiplyScalar(0.98);
    f.rotation.x += u.spin.x * dt; f.rotation.y += u.spin.y * dt; f.rotation.z += u.spin.z * dt;
    f.scale.setScalar(u.baseScale * (1 - (u.life / u.maxLife) * 0.7));
  });

  // Emergency lighting: a true on/off strobe (not a smooth pulse) during the alert, settling to
  // an irregular tense flicker while shooting, off otherwise. Synced with a full-screen red
  // overlay (see index.blade.php/.emergency-flash) for drama beyond just the 3D light.
  if (GAME.phase === 'alert') {
    const strobeOn = Math.sin(t * 22) > -0.35;
    alertLight.intensity = alertLight2.intensity = strobeOn ? 14 + Math.random() * 4 : 0;
    if (emergencyFlashEl) emergencyFlashEl.classList.toggle('on', strobeOn);
  } else if (GAME.phase === 'playing') {
    const flickerOn = Math.sin(t * 6.5) > -0.55 && Math.random() > 0.04;
    alertLight.intensity = alertLight2.intensity = flickerOn ? 2 + Math.random() * 1.5 : 0.1;
    if (emergencyFlashEl) emergencyFlashEl.classList.remove('on');
  } else {
    alertLight.intensity = alertLight2.intensity = 0;
    if (emergencyFlashEl) emergencyFlashEl.classList.remove('on');
  }

  if (GAME.phase === 'alert') {
    GAME.phaseTimer -= dt;
    if (GAME.phaseTimer <= 0) { GAME.phase = 'playing'; GAME.timeLeft = ROUND_DURATION; GAME.spawnTimer = 0; hudSetPlaying(); }
    return;
  }
  if (GAME.phase === 'playing') {
    GAME.timeLeft -= dt;
    updateHudTimer();
    GAME.spawnTimer -= dt;
    if (GAME.spawnTimer <= 0) {
      GAME.spawnTimer = SPAWN_INTERVAL;
      const free = gameAsteroids.find((m) => !m.userData.alive);
      if (free) spawnGameAsteroid(free);
    }
    gameAsteroids.forEach((m) => updateOneAsteroid(m, dt));
    if (GAME.timeLeft <= 0) { GAME.phase = 'complete'; GAME.phaseTimer = COMPLETE_HOLD; hudSetComplete(); }
    return;
  }
  if (GAME.phase === 'complete') {
    GAME.phaseTimer -= dt;
    gameAsteroids.forEach((m) => updateOneAsteroid(m, dt)); // let any still-visible rocks finish their pass or recycle
    if (GAME.phaseTimer <= 0) exitCockpitGame();
  }
}
animated.push(updateCockpitGame); // no-ops while GAME.phase === 'idle'

// ---------- Scroll-driven camera path ----------
// One keyframe per page section: hero, then one inside each room.
// Six scroll stops now (one per section, including the extra "door-approach" section in
// index.blade.php): hero, outside door 01, inside crew quarters, engine room, mission control,
// bridge — evenly spaced at 0, 0.2, 0.4, 0.6, 0.8, 1.0, matching six 100vh sections.
const keys = [
  { s: 0.0, pos: [15, 6, 36], look: [0, 0, -4] },
  { s: 0.07, pos: [8, 3, 28], look: [0, 0, -2] },
  { s: 0.14, pos: [1.5, 1, 18], look: [0, 0.5, 6] },
  // outside door 01 (z=10) with enough clearance (>5 units) that it stays shut — doors only
  // start sliding open once the camera gets within 5 units (see the door-animation code below).
  // Facing the "Meet the crew" sign above it, which is clickable from here.
  { s: 0.2, pos: [0, 0.5, 16], look: [0, 1.0, 10] },
  // through the (now open) door and inside crew quarters
  { s: 0.4, pos: [0, 0.2, 7.5], look: [0, -0.3, -1] },
  { s: 0.6, pos: [0, 0.2, -0.5], look: [0, -0.3, -8] },
  { s: 0.8, pos: [0, 0.2, -8.5], look: [0, -0.3, -16] },
  { s: 1.0, pos: [0, 0.2, -16.5], look: [0, -0.2, -23] },
];
const camPath = new THREE.CatmullRomCurve3(keys.map((k) => new THREE.Vector3(...k.pos)), false, 'catmullrom', 0.3);
const lookPath = new THREE.CatmullRomCurve3(keys.map((k) => new THREE.Vector3(...k.look)), false, 'catmullrom', 0.3);
const ease = (x) => x * x * (3 - 2 * x);
function curveParam(s) {
  for (let i = 0; i < keys.length - 1; i++) {
    if (s <= keys[i + 1].s) {
      const local = (s - keys[i].s) / (keys[i + 1].s - keys[i].s);
      return (i + ease(local)) / (keys.length - 1);
    }
  }
  return 1;
}

// ---------- Post-processing ----------
const composer = new EffectComposer(renderer);
composer.addPass(new RenderPass(scene, camera));
// Bloom is an inherently blurred effect, so running it at half resolution costs ~1/4 the GPU time
// with no visible softening — runs every frame, so this is the single biggest win available here.
const bloom = new UnrealBloomPass(new THREE.Vector2(window.innerWidth / 2, window.innerHeight / 2), 0.35, 0.5, 0.95);
composer.addPass(bloom);
composer.addPass(new OutputPass());

// ---------- Input (parallax + clickable door buttons) ----------
const mouse = { x: 0, y: 0 };
const raycaster = new THREE.Raycaster();
let hovered = null;

function pick(clientX, clientY) {
  const ndc = new THREE.Vector2((clientX / window.innerWidth) * 2 - 1, -(clientY / window.innerHeight) * 2 + 1);
  raycaster.setFromCamera(ndc, camera);
  const hit = raycaster.intersectObjects(buttons, false)[0];
  return hit && hit.distance < 18 && camera.position.z > hit.object.getWorldPosition(new THREE.Vector3()).z ? hit.object : null;
}

window.addEventListener('pointermove', (e) => {
  mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
  mouse.y = (e.clientY / window.innerHeight) * 2 - 1;

  if (GAME.phase === 'playing') {
    // the crosshair replaces the native cursor while manning the guns
    document.body.style.cursor = 'none';
    if (crosshairEl) { crosshairEl.style.left = e.clientX + 'px'; crosshairEl.style.top = e.clientY + 'px'; }
    return;
  }

  const prev = hovered;
  hovered = pick(e.clientX, e.clientY);
  if (hovered && hovered !== prev) sfx.hover();
  document.body.style.cursor = hovered ? 'pointer' : '';
  // Hover-only hint on the steering yoke (not a permanent label like the door signs elsewhere).
  if (hintEl) {
    const show = mode === 'scroll' && !!hovered && hovered.userData.isGame;
    hintEl.classList.toggle('show', show);
    if (show) { hintEl.style.left = e.clientX + 'px'; hintEl.style.top = (e.clientY - 46) + 'px'; }
  }
});
window.addEventListener('click', (e) => {
  if (e.target.closest('a, button')) return; // let normal links (and the HUD exit button) work
  if (GAME.phase === 'playing') { handleGameShot(e.clientX, e.clientY); return; }
  const b = pick(e.clientX, e.clientY);
  if (!b) return;
  sfx.click();
  if (b.userData.isGame) { openCockpitGame(); return; } // before the TARGETS check: no href to match
  if (TARGETS[b.userData.href]) openRoom(b.userData.href);
  else window.location.href = routes[b.userData.href] || b.userData.href;
});

window.addEventListener('resize', () => {
  camera.aspect = window.innerWidth / window.innerHeight;
  fitFov();
  camera.updateProjectionMatrix();
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(window.innerWidth, window.innerHeight);
  composer.setSize(window.innerWidth, window.innerHeight);
  bloom.resolution.set(window.innerWidth / 2, window.innerHeight / 2);
});

// ---------- Loop ----------
const clock = new THREE.Clock();
let lastT = 0;
let progress = 0;
const tmpPos = new THREE.Vector3();
const tmpLook = new THREE.Vector3();
const lookSmooth = new THREE.Vector3(0, 0, -4);

// ---------- Zoom into a room, then open its page ----------
// Each page has a spot in the ship that the camera flies to before the page opens.
const TARGETS = {
  about: { pos: new THREE.Vector3(-0.7, 0.5, 6), look: new THREE.Vector3(-2.2, 0.5, 6) }, // wall monitor, crew quarters
  contact: { pos: new THREE.Vector3(0, -0.15, -19.4), look: new THREE.Vector3(0, -0.7, -20.7) }, // main console screen, command bridge
  cases: { pos: new THREE.Vector3(0.5, 0.8, -10), look: new THREE.Vector3(2.3, 0.95, -10) }, // wall monitor, computer room
  services: { pos: new THREE.Vector3(-0.2, 0.6, -2.4), look: new THREE.Vector3(2.2, 0.5, -2.2) }, // gears and pistons, engine room
  // sitting behind a yoke looking out the bridge window (distinct from `contact`, which looks
  // down at the console screen instead)
  cockpitGame: { pos: new THREE.Vector3(0, 0.9, -19.0), look: new THREE.Vector3(0, 0.55, -24) },
};
let mode = 'scroll'; // scroll | flying | hold
let fly = null;
let hold = null;
const par = { x: 0, y: 0 };

function startFly(posPts, lookPts, dur, done) {
  fly = {
    t0: clock.getElapsedTime(), dur, done,
    pos: new THREE.CatmullRomCurve3(posPts, false, 'catmullrom', 0.5),
    look: new THREE.CatmullRomCurve3(lookPts, false, 'catmullrom', 0.5),
  };
}

function openRoom(key) {
  const target = TARGETS[key];
  if (mode !== 'scroll' || !target) return;
  mode = 'flying';
  const start = camera.position.clone();
  const pts = [start];
  if (start.z > 11 && target.pos.z < 11) pts.push(new THREE.Vector3(0, 0.3, 12.5));
  if (start.z > 8.5 && target.pos.z < 8) pts.push(new THREE.Vector3(0, 0.2, 8));
  if (start.z > 4 && target.pos.z < 1) pts.push(new THREE.Vector3(0, 0.2, 3.5));
  if (start.z < 5 && target.pos.z > 3) pts.push(new THREE.Vector3(0, 0.2, 7));
  pts.push(target.pos.clone());
  const looks = [lookSmooth.clone(), new THREE.Vector3(0, 0.3, target.pos.z - 3), target.look.clone()];
  const dist = start.distanceTo(target.pos);
  const flyDur = Math.min(5.5, 1.6 + dist * 0.06);
  sfx.whoosh(flyDur);
  startFly(pts, looks, flyDur, () => {
    mode = 'hold'; // stay on the target, then the page takes over
    hold = target;
    setTimeout(() => { window.location.href = routes[key]; }, 300);
  });
}

// Same intermediate-doorway logic as openRoom(), duplicated rather than extracted so openRoom()
// itself is never touched (avoids any regression risk to existing room navigation).
function buildDoorwayWaypoints(start, targetPos) {
  const pts = [start.clone()];
  if (start.z > 11 && targetPos.z < 11) pts.push(new THREE.Vector3(0, 0.3, 12.5));
  if (start.z > 8.5 && targetPos.z < 8) pts.push(new THREE.Vector3(0, 0.2, 8));
  if (start.z > 4 && targetPos.z < 1) pts.push(new THREE.Vector3(0, 0.2, 3.5));
  if (start.z < 5 && targetPos.z > 3) pts.push(new THREE.Vector3(0, 0.2, 7));
  pts.push(targetPos.clone());
  return pts;
}

function openCockpitGame() {
  if (mode !== 'scroll') return;
  mode = 'flying';
  const start = camera.position.clone();
  const target = TARGETS.cockpitGame;
  const pts = buildDoorwayWaypoints(start, target.pos);
  const looks = [lookSmooth.clone(), new THREE.Vector3(0, 0.3, target.pos.z - 3), target.look.clone()];
  const dist = start.distanceTo(target.pos);
  const flyDur = Math.min(5.5, 1.6 + dist * 0.06);
  sfx.whoosh(flyDur);
  startFly(pts, looks, flyDur, () => {
    mode = 'hold';
    hold = target;
    startAsteroidAttack();
  });
}

function exitCockpitGame() {
  if (!GAME.active && GAME.phase === 'idle') return; // guard against double-fire (manual exit + auto-exit)
  stopAsteroidAttack();
  mode = 'flying';
  const start = camera.position.clone();
  const endPos = new THREE.Vector3(), endLook = new THREE.Vector3();
  const u = curveParam(progress);
  camPath.getPoint(u, endPos);
  lookPath.getPoint(u, endLook);
  startFly([start, endPos], [lookSmooth.clone(), endLook], 1.8, () => { mode = 'scroll'; });
}

document.addEventListener('click', (e) => {
  if (e.metaKey || e.ctrlKey || e.shiftKey) return;
  const z = e.target.closest('[data-zoom]');
  if (z) { e.preventDefault(); openRoom(z.dataset.zoom); }
});
// Coming back with the browser's back button: reset the camera state
window.addEventListener('pageshow', (e) => { if (e.persisted) { mode = 'scroll'; fly = null; hold = null; } });

let booted = false;
renderer.setAnimationLoop(() => {
  const t = clock.getElapsedTime();
  const scrollMax = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
  const target = Math.min(1, Math.max(0, window.scrollY / scrollMax));
  progress += (target - progress) * 0.06;

  document.body.classList.toggle('in-rooms', progress > 0.1);
  const u = curveParam(progress);
  camPath.getPoint(u, tmpPos);
  lookPath.getPoint(u, tmpLook);

  // Mouse parallax: wide outside, tight once we approach the entrance
  const near = THREE.MathUtils.clamp((tmpPos.z - 8) / 8, 0, 1);
  const amp = mode === 'scroll' ? THREE.MathUtils.lerp(0.2, 1.0, near) : 0;
  par.x += (mouse.x * amp - par.x) * 0.08;
  par.y += (-mouse.y * amp * 0.6 - par.y) * 0.08;

  if (fly) {
    const k = Math.min(1, (t - fly.t0) / fly.dur);
    const e = ease(k);
    fly.pos.getPoint(e, camera.position);
    fly.look.getPoint(e, lookSmooth);
    camera.lookAt(lookSmooth);
    if (k >= 1) { const done = fly.done; fly = null; if (done) done(); }
  } else if (mode === 'hold') {
    camera.position.set(hold.pos.x + Math.sin(t * 0.5) * 0.01, hold.pos.y + Math.sin(t * 0.7) * 0.008, hold.pos.z);
    lookSmooth.copy(hold.look);
    camera.lookAt(lookSmooth);
  } else {
    camera.position.set(tmpPos.x + par.x, tmpPos.y + par.y, tmpPos.z);
    lookSmooth.lerp(tmpLook, 0.2);
    camera.lookAt(lookSmooth);
  }

  // Impact shake: a brief rumble when an asteroid bounces off the hull
  if (shakeMag > 0.001) {
    camera.position.x += (Math.random() - 0.5) * shakeMag;
    camera.position.y += (Math.random() - 0.5) * shakeMag;
    camera.position.z += (Math.random() - 0.5) * shakeMag * 0.4;
    shakeMag *= 0.86;
  } else {
    shakeMag = 0;
  }

  // Idle motion (only visible from outside)
  ship.position.y = Math.sin(t * 0.6) * 0.12 * near;
  ship.rotation.z = Math.sin(t * 0.4) * 0.02 * near;
  stars.rotation.y = t * 0.005;

  // Pulses and room animations
  glowMat.emissiveIntensity = 2 + Math.sin(t * 2) * 0.2;
  const pulse = 1 + Math.sin(t * 3) * 0.08;
  enginePulse.forEach((m) => m.scale.setScalar(pulse));
  const dt = Math.min(0.05, t - (lastT || t));
  lastT = t;
  animated.forEach((fn) => fn(t, dt));
  sfx.update(t, dt, camera.position.z, camera.position.z < Z_FRONT);

  // Doors slide open as the camera approaches
  doors.forEach((d) => {
    const dist = camera.position.z - d.z;
    const goal = 1 - THREE.MathUtils.smoothstep(dist, 2.5, 5);
    d.open += (goal - d.open) * 0.12;
    const wantOpen = goal > 0.5;
    if (wantOpen !== !!d.wantOpen) {
      d.wantOpen = wantOpen;
      if (d.ready) sfx.door(wantOpen, Math.max(0, 1 - Math.abs(dist) / 12)); // skip the sound on the very first frame
    }
    d.ready = true;
    d.halves[0].position.x = -DOOR_W / 4 - d.open * (DOOR_W / 2);
    d.halves[1].position.x = DOOR_W / 4 + d.open * (DOOR_W / 2);
  });

  // Button hover feedback
  buttons.forEach((b) => {
    const on = b === hovered;
    const grp = b.userData.group;
    const s = on ? 1.05 : 1;
    grp.scale.x += (s - grp.scale.x) * 0.2;
    grp.scale.y += (s - grp.scale.y) * 0.2;
    const c = on ? 1 : 0.78;
    b.material.color.r += (c - b.material.color.r) * 0.2;
    b.material.color.g = b.material.color.b = b.material.color.r;
  });

  if (camera.position.z < -11) renderWindowView(); // only when the bridge window can be seen
  composer.render();
  if (!booted) { booted = true; hideBootLoader(); }
});

// Landing with a hash or restored scroll (e.g. back from a room page): start in place, no fly-in from the hero
function snapToScroll() {
  const target = location.hash.length > 1 ? document.querySelector(location.hash) : null;
  if (target) window.scrollTo({ top: target.getBoundingClientRect().top + window.scrollY, behavior: 'instant' });
  const max = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
  progress = Math.min(1, Math.max(0, window.scrollY / max));
  const u = curveParam(progress);
  camPath.getPoint(u, camera.position);
  lookPath.getPoint(u, lookSmooth);
}
snapToScroll();
window.addEventListener('load', snapToScroll);

// Coming back from a room page: start on the room's spot, then fly back to where the scroll position is
let arrive = null;
try { arrive = sessionStorage.getItem('arrive'); sessionStorage.removeItem('arrive'); } catch (e) { /* storage blocked: no fly-back */ }
if (arrive && TARGETS[arrive]) {
  const from = TARGETS[arrive];
  mode = 'hold';
  hold = from;
  camera.position.copy(from.pos);
  lookSmooth.copy(from.look);
  setTimeout(() => document.documentElement.classList.remove('arriving'), 350);
  setTimeout(() => {
    const endPos = new THREE.Vector3(), endLook = new THREE.Vector3();
    const u = curveParam(progress);
    camPath.getPoint(u, endPos);
    lookPath.getPoint(u, endLook);
    mode = 'flying';
    startFly([from.pos.clone(), endPos], [from.look.clone(), endLook], 1.8, () => { mode = 'scroll'; });
  }, 600);
} else {
  document.documentElement.classList.remove('arriving');
}

// ---------- Sound toggle, button beeps and page-open beep ----------
initUISound(sfx);

initNav();
