import { initUISound } from './ui-sound.js';
import { initNav } from './nav.js';
import { routes } from './config.js';
import { hideBootLoader } from './boot.js';
import * as THREE from 'three';
import { RoomEnvironment } from 'three/examples/jsm/environments/RoomEnvironment.js';
import { EffectComposer } from 'three/examples/jsm/postprocessing/EffectComposer.js';
import { RenderPass } from 'three/examples/jsm/postprocessing/RenderPass.js';
import { UnrealBloomPass } from 'three/examples/jsm/postprocessing/UnrealBloomPass.js';
import { OutputPass } from 'three/examples/jsm/postprocessing/OutputPass.js';
import {
  siPhp, siLaravel, siMysql, siPostgresql, siVuedotjs, siReact, siPython, siWordpress,
  siJavascript, siTailwindcss, siNodedotjs, siAndroid, siApple, siDocker, siGit, siClaude
} from 'simple-icons';

// ---------- Tech stack rail (fuel cells) ----------
const TECH = [
  ['PHP', siPhp], ['Laravel', siLaravel], ['MySQL', siMysql], ['PostgreSQL', siPostgresql],
  ['Vue.js', siVuedotjs], ['React', siReact], ['Claude', siClaude], ['Python', siPython], ['WordPress', siWordpress],
  ['JavaScript', siJavascript], ['Tailwind', siTailwindcss], ['Node.js', siNodedotjs],
  ['Android', siAndroid], ['iOS', siApple], ['Docker', siDocker], ['Git', siGit]
];

function brandColor(hex) {
  const n = parseInt(hex, 16);
  const lum = (0.299 * ((n >> 16) & 255) + 0.587 * ((n >> 8) & 255) + 0.114 * (n & 255)) / 255;
  return lum < 0.25 ? '#ffffff' : '#' + hex; // near-black brands would vanish on the dark background
}

const rail = document.querySelector('#rail');
const cells = TECH.map(([name, icon]) =>
  '<li class="cell" style="--c:' + brandColor(icon.hex) + '" title="' + name + '">' +
  '<svg viewBox="0 0 24 24" role="img" aria-label="' + name + '"><path d="' + icon.path + '"/></svg>' +
  '<span>' + name + '</span></li>').join('');
rail.innerHTML = cells + cells.replace(/<li /g, '<li aria-hidden="true" '); // doubled for a seamless loop

// ---------- Reveal on scroll ----------
const io = new IntersectionObserver((entries) => entries.forEach((e) => {
  if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
}), { threshold: 0.2 });
document.querySelectorAll('.module, .mode, .svc-head, .svc-cta').forEach((el) => io.observe(el));

// ---------- Engine scene ----------
const ORANGE = 0xfe742b;
const ORANGE_LIGHT = 0xff8c4d;
const canvas = document.querySelector('#engine');

const renderer = new THREE.WebGLRenderer({ canvas, antialias: true });
renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
renderer.setSize(window.innerWidth, window.innerHeight);
renderer.toneMapping = THREE.ACESFilmicToneMapping;
renderer.toneMappingExposure = 1.1;

const scene = new THREE.Scene();
scene.background = new THREE.Color(0x050812);
scene.fog = new THREE.FogExp2(0x050812, 0.028);
const pmrem = new THREE.PMREMGenerator(renderer);
scene.environment = pmrem.fromScene(new RoomEnvironment(), 0.04).texture;
scene.environmentIntensity = 0.4;

const camera = new THREE.PerspectiveCamera(42, window.innerWidth / window.innerHeight, 0.1, 100);

const metal = new THREE.MeshStandardMaterial({ color: 0x3b4474, metalness: 0.9, roughness: 0.3, flatShading: true });
const dark = new THREE.MeshStandardMaterial({ color: 0x141935, metalness: 0.9, roughness: 0.4, flatShading: true });
const glow = new THREE.MeshStandardMaterial({ color: ORANGE, emissive: ORANGE, emissiveIntensity: 2.2, toneMapped: false });
const whiteGlow = new THREE.MeshStandardMaterial({ color: 0xffffff, emissive: 0xcfe0ff, emissiveIntensity: 1.6, toneMapped: false });

const engine = new THREE.Group();
scene.add(engine);
const spinners = []; // [object, speed, axis]
const pistons = [];

// Base platform
const base = new THREE.Mesh(new THREE.CylinderGeometry(3.4, 3.8, 0.5, 8), dark);
base.position.y = -2;
const baseRing = new THREE.Mesh(new THREE.TorusGeometry(3.45, 0.05, 6, 8), glow);
baseRing.rotation.x = Math.PI / 2;
baseRing.rotation.z = Math.PI / 8;
baseRing.position.y = -1.74;
engine.add(base, baseRing);
const grid = new THREE.GridHelper(40, 40, 0x3a1d10, 0x151a38);
grid.position.y = -2.26;
scene.add(grid);

// Core: glowing column seen through a cage of bars
const coreGlow = new THREE.Mesh(new THREE.CylinderGeometry(0.5, 0.5, 3.6, 16), glow);
engine.add(coreGlow);
for (let i = 0; i < 8; i++) {
  const a = (i / 8) * Math.PI * 2;
  const bar = new THREE.Mesh(new THREE.BoxGeometry(0.26, 3.6, 0.34), metal);
  bar.position.set(Math.cos(a) * 0.85, 0, Math.sin(a) * 0.85);
  bar.rotation.y = -a;
  engine.add(bar);
}
for (const y of [-1.95, 1.95]) {
  const cap = new THREE.Mesh(new THREE.CylinderGeometry(1.25, 1.25, 0.3, 8), dark);
  cap.position.y = y;
  engine.add(cap);
}

// Spinning rings around the core
[[-0.95, 0.6], [0, -0.9], [0.95, 0.7]].forEach(([y, speed]) => {
  const holder = new THREE.Group();
  holder.position.y = y;
  const ring = new THREE.Mesh(new THREE.TorusGeometry(1.35, 0.07, 8, 48), glow);
  ring.rotation.x = Math.PI / 2;
  holder.add(ring);
  for (let k = 0; k < 6; k++) {
    const a = (k / 6) * Math.PI * 2;
    const tooth = new THREE.Mesh(new THREE.BoxGeometry(0.22, 0.22, 0.22), whiteGlow);
    tooth.position.set(Math.cos(a) * 1.35, 0, Math.sin(a) * 1.35);
    holder.add(tooth);
  }
  engine.add(holder);
  spinners.push([holder, speed, 'y']);
});

// Pistons around the core, linked by pipes
for (let k = 0; k < 4; k++) {
  const a = Math.PI / 4 + (k * Math.PI) / 2;
  const arm = new THREE.Group();
  arm.rotation.y = -a;
  const housing = new THREE.Mesh(new THREE.CylinderGeometry(0.36, 0.36, 2.2, 12), dark);
  housing.position.set(2.5, -0.7, 0);
  const band = new THREE.Mesh(new THREE.TorusGeometry(0.38, 0.04, 6, 16), glow);
  band.rotation.x = Math.PI / 2;
  band.position.set(2.5, -0.2, 0);
  const rod = new THREE.Mesh(new THREE.CylinderGeometry(0.15, 0.15, 1.8, 10), metal);
  const head = new THREE.Mesh(new THREE.BoxGeometry(0.6, 0.18, 0.6), metal);
  const pipe = new THREE.Mesh(new THREE.BoxGeometry(1.5, 0.2, 0.2), metal);
  pipe.position.set(1.65, -1.1, 0);
  const pipeGlow = new THREE.Mesh(new THREE.BoxGeometry(1.2, 0.05, 0.05), glow);
  pipeGlow.position.set(1.65, -0.98, 0);
  arm.add(housing, band, rod, head, pipe, pipeGlow);
  engine.add(arm);
  pistons.push({ rod, head, phase: k * 1.6 });
}

// Turbine fan on top
const fan = new THREE.Group();
fan.position.y = 2.55;
for (let k = 0; k < 12; k++) {
  const a = (k / 12) * Math.PI * 2;
  const blade = new THREE.Mesh(new THREE.BoxGeometry(0.9, 0.05, 0.28), metal);
  blade.position.set(Math.cos(a) * 0.9, 0, Math.sin(a) * 0.9);
  blade.rotation.y = -a;
  blade.rotation.z = 0.35;
  fan.add(blade);
}
fan.add(new THREE.Mesh(new THREE.CylinderGeometry(0.3, 0.3, 0.2, 10), glow));
engine.add(fan);
spinners.push([fan, 2.2, 'y']);

// Gears on the floor
const gs = new THREE.Shape();
const TEETH = 12;
for (let i = 0; i < TEETH * 4; i++) {
  const a = (i / (TEETH * 4)) * Math.PI * 2;
  const r = i % 4 < 2 ? 1 : 0.82;
  i ? gs.lineTo(Math.cos(a) * r, Math.sin(a) * r) : gs.moveTo(Math.cos(a) * r, Math.sin(a) * r);
}
gs.closePath();
const gearGeo = new THREE.ExtrudeGeometry(gs, { depth: 0.25, bevelEnabled: false });
[[4.6, 1.5, 1.2, 0.5], [-4.2, -2.2, 1.6, -0.35], [3, -4.4, 0.9, -0.7]].forEach(([x, z, s, speed]) => {
  const holder = new THREE.Group();
  holder.position.set(x, -2.2, z);
  holder.rotation.x = -Math.PI / 2;
  const gear = new THREE.Mesh(gearGeo, metal);
  gear.scale.setScalar(s);
  const hub = new THREE.Mesh(new THREE.TorusGeometry(0.45 * s, 0.05, 6, 20), glow);
  hub.position.z = 0.27;
  gear.add(hub);
  holder.add(gear);
  scene.add(holder);
  spinners.push([gear, speed, 'z']);
});

// Rising sparks
const SPARKS = 220;
const sparkPos = new Float32Array(SPARKS * 3);
const sparkSpeed = new Float32Array(SPARKS);
function resetSpark(i, fromBottom) {
  const a = Math.random() * Math.PI * 2;
  const r = 0.4 + Math.random() * 2.6;
  sparkPos[i * 3] = Math.cos(a) * r;
  sparkPos[i * 3 + 1] = fromBottom ? -1.8 : -1.8 + Math.random() * 5;
  sparkPos[i * 3 + 2] = Math.sin(a) * r;
  sparkSpeed[i] = 0.4 + Math.random() * 0.9;
}
for (let i = 0; i < SPARKS; i++) resetSpark(i, false);
const sparkGeo = new THREE.BufferGeometry();
sparkGeo.setAttribute('position', new THREE.BufferAttribute(sparkPos, 3));
const sparks = new THREE.Points(sparkGeo, new THREE.PointsMaterial({ size: 0.06, color: ORANGE_LIGHT, transparent: true, opacity: 0.9, blending: THREE.AdditiveBlending, depthWrite: false }));
engine.add(sparks);

// Lights
scene.add(new THREE.AmbientLight(0x303a70, 0.6));
const key = new THREE.DirectionalLight(0xbcd0ff, 1.4);
key.position.set(-6, 8, 6);
scene.add(key);
const coreLight = new THREE.PointLight(ORANGE, 40, 14, 2);
engine.add(coreLight);
const lowLight = new THREE.PointLight(ORANGE_LIGHT, 20, 10, 2);
lowLight.position.set(0, -1.6, 0);
engine.add(lowLight);

// Stars
const starPos = new Float32Array(1500 * 3);
for (let i = 0; i < 1500; i++) {
  const r = 30 + Math.random() * 50, th = Math.random() * Math.PI * 2, ph = Math.acos(2 * Math.random() - 1);
  starPos[i * 3] = r * Math.sin(ph) * Math.cos(th);
  starPos[i * 3 + 1] = Math.abs(r * Math.sin(ph) * Math.sin(th)) - 5;
  starPos[i * 3 + 2] = r * Math.cos(ph);
}
const starGeo = new THREE.BufferGeometry();
starGeo.setAttribute('position', new THREE.BufferAttribute(starPos, 3));
scene.add(new THREE.Points(starGeo, new THREE.PointsMaterial({ size: 0.15, color: 0xffffff, transparent: true, opacity: 0.7 })));

// Post-processing
const composer = new EffectComposer(renderer);
composer.addPass(new RenderPass(scene, camera));
const bloom = new UnrealBloomPass(new THREE.Vector2(window.innerWidth, window.innerHeight), 0.5, 0.6, 0.9);
composer.addPass(bloom);
composer.addPass(new OutputPass());

// Keep the engine on the right of the hero text on wide screens
function layout() {
  const w = window.innerWidth, h = window.innerHeight;
  camera.aspect = w / h;
  if (w > 900) camera.setViewOffset(w, h, -w * 0.2, 0, w, h);
  else camera.clearViewOffset();
  camera.updateProjectionMatrix();
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(w, h);
  composer.setSize(w, h);
  bloom.resolution.set(w, h);
}
layout();
window.addEventListener('resize', layout);

const mouse = { x: 0, y: 0 };
window.addEventListener('pointermove', (e) => {
  mouse.x = (e.clientX / window.innerWidth) * 2 - 1;
  mouse.y = (e.clientY / window.innerHeight) * 2 - 1;
});

const clock = new THREE.Clock();
let smoothScroll = 0, mx = 0, my = 0, booted = false;
renderer.setAnimationLoop(() => {
  const t = clock.getElapsedTime();
  const max = Math.max(1, document.documentElement.scrollHeight - window.innerHeight);
  smoothScroll += (Math.min(1, window.scrollY / max) - smoothScroll) * 0.06;
  mx += (mouse.x - mx) * 0.05; my += (mouse.y - my) * 0.05;

  // Slow orbit; scrolling swings the camera around and lowers it
  const ang = 0.7 + t * 0.1 + mx * 0.35 + smoothScroll * Math.PI * 1.2;
  const r = 12.5 - smoothScroll * 2.5;
  camera.position.set(Math.sin(ang) * r, 3.6 - smoothScroll * 1.6 - my * 0.6, Math.cos(ang) * r);
  camera.lookAt(0, 0.1, 0);

  spinners.forEach(([o, s, axis]) => (o.rotation[axis] = t * s));
  pistons.forEach((p) => {
    const y = 0.35 + Math.sin(t * 2.2 + p.phase) * 0.45;
    p.rod.position.set(2.5, y + 0.2, 0);
    p.head.position.set(2.5, y + 1.15, 0);
  });
  for (let i = 0; i < SPARKS; i++) {
    sparkPos[i * 3 + 1] += sparkSpeed[i] * 0.016;
    if (sparkPos[i * 3 + 1] > 3.2) resetSpark(i, true);
  }
  sparkGeo.attributes.position.needsUpdate = true;
  glow.emissiveIntensity = 2.2 + Math.sin(t * 2.4) * 0.35;
  coreLight.intensity = 40 + Math.sin(t * 2.4) * 8;

  composer.render();
  if (!booted) { booted = true; hideBootLoader(); }
});

// ---------- Smooth return to the ship ----------
function returnToShip(href) {
  try { sessionStorage.setItem('arrive', 'services'); } catch (e) { /* storage blocked: the shuttle just opens without the fly-back */ }
  document.documentElement.classList.add('leaving');
  setTimeout(() => { window.location.href = href; }, 480);
}
document.querySelectorAll('[data-return]').forEach((a) => a.addEventListener('click', (e) => {
  if (e.metaKey || e.ctrlKey || e.shiftKey) return;
  e.preventDefault();
  returnToShip(a.getAttribute('href'));
}));
window.addEventListener('keydown', (e) => { if (e.key === 'Escape') returnToShip(routes.home + '#services'); });
window.addEventListener('pageshow', (e) => { if (e.persisted) document.documentElement.classList.remove('leaving'); });

initUISound(undefined, { boot: 'chirp' });

initNav();
