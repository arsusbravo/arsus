// Procedural PBR textures for the ship, drawn on canvases at startup (no image files needed).
// Each builder returns { map, normalMap, roughnessMap } (+ extras), all set to repeat.
import * as THREE from 'three';

let s = 1;
const seed = (v) => (s = v);
const rnd = () => ((s = (s * 16807) % 2147483647) / 2147483647);
const gray = (v) => 'rgb(' + (v | 0) + ',' + (v | 0) + ',' + (v | 0) + ')';

function makeCanvas(w, h = w) {
  const c = document.createElement('canvas');
  c.width = w; c.height = h;
  return [c, c.getContext('2d', { willReadFrequently: true })];
}

function tex(c, isColor) {
  const t = new THREE.CanvasTexture(c);
  t.wrapS = t.wrapT = THREE.RepeatWrapping;
  t.anisotropy = 8;
  if (isColor) t.colorSpace = THREE.SRGBColorSpace;
  return t;
}

// Smooth, seamlessly tiling value noise blended over a canvas
function noise(g, w, h, cells, alpha, mode = 'overlay') {
  const [n, ng] = makeCanvas(cells + 1);
  const img = ng.createImageData(cells + 1, cells + 1);
  const vals = [];
  for (let i = 0; i < cells * cells; i++) vals.push(rnd() * 255);
  for (let y = 0; y <= cells; y++) for (let x = 0; x <= cells; x++) {
    const v = vals[(y % cells) * cells + (x % cells)];
    const k = (y * (cells + 1) + x) * 4;
    img.data[k] = img.data[k + 1] = img.data[k + 2] = v;
    img.data[k + 3] = 255;
  }
  ng.putImageData(img, 0, 0);
  g.save();
  g.globalAlpha = alpha;
  g.globalCompositeOperation = mode;
  g.imageSmoothingEnabled = true;
  g.imageSmoothingQuality = 'high';
  g.drawImage(n, 0.5, 0.5, cells, cells, 0, 0, w, h);
  g.restore();
}

// Height canvas -> tangent-space normal map canvas (OpenGL convention, wraps at edges)
function normalFrom(hc, strength) {
  const w = hc.width, h = hc.height;
  const src = hc.getContext('2d').getImageData(0, 0, w, h).data;
  const [c, g] = makeCanvas(w, h);
  const out = g.createImageData(w, h);
  const d = out.data;
  const H = (x, y) => src[(((y + h) % h) * w + ((x + w) % w)) * 4] / 255;
  for (let y = 0; y < h; y++) {
    for (let x = 0; x < w; x++) {
      const dx = (H(x - 1, y) - H(x + 1, y)) * strength;
      const dy = (H(x, y + 1) - H(x, y - 1)) * strength;
      const l = Math.hypot(dx, dy, 1);
      const k = (y * w + x) * 4;
      d[k] = (dx / l * 0.5 + 0.5) * 255;
      d[k + 1] = (dy / l * 0.5 + 0.5) * 255;
      d[k + 2] = (1 / l * 0.5 + 0.5) * 255;
      d[k + 3] = 255;
    }
  }
  g.putImageData(out, 0, 0);
  return c;
}

function dot(g, x, y, r, fill) {
  g.fillStyle = fill;
  g.beginPath(); g.arc(x, y, r, 0, Math.PI * 2); g.fill();
}

function hazard(g, x, y, w, h, a = 0.9) {
  g.save();
  g.beginPath(); g.rect(x, y, w, h); g.clip();
  g.fillStyle = 'rgba(18,18,22,' + a + ')';
  g.fillRect(x, y, w, h);
  g.fillStyle = 'rgba(254,116,43,' + a + ')';
  for (let i = -h; i < w + h; i += h * 1.4) {
    g.beginPath();
    g.moveTo(x + i, y + h); g.lineTo(x + i + h * 0.7, y + h);
    g.lineTo(x + i + h * 1.7, y); g.lineTo(x + i + h, y);
    g.closePath(); g.fill();
  }
  g.restore();
}

function scratches(g, hg, S, count, light = 0.16) {
  for (let i = 0; i < count; i++) {
    const x = rnd() * S, y = rnd() * S, a = rnd() * Math.PI * 2, len = 4 + rnd() * 30;
    g.strokeStyle = 'rgba(235,238,248,' + light * rnd() + ')';
    g.lineWidth = 1;
    g.beginPath(); g.moveTo(x, y); g.lineTo(x + Math.cos(a) * len, y + Math.sin(a) * len); g.stroke();
    if (hg) {
      hg.strokeStyle = gray(105);
      hg.beginPath(); hg.moveTo(x, y); hg.lineTo(x + Math.cos(a) * len, y + Math.sin(a) * len); hg.stroke();
    }
  }
}

// ---------- Exterior hull plating ----------
export function hullTextures() {
  seed(7);
  const S = 1024;
  const [c, g] = makeCanvas(S), [hc, hg] = makeCanvas(S), [rc, rg] = makeCanvas(S);
  g.fillStyle = '#7d828f'; g.fillRect(0, 0, S, S);
  hg.fillStyle = gray(128); hg.fillRect(0, 0, S, S);
  rg.fillStyle = gray(115); rg.fillRect(0, 0, S, S);
  noise(g, S, S, 6, 0.14);
  noise(g, S, S, 24, 0.12);
  noise(g, S, S, 128, 0.08);
  noise(rg, S, S, 16, 0.4);

  const snap = (v) => Math.round(v / 16) * 16;
  const panels = [];
  (function split(x, y, w, h, d) {
    if (d >= 5 || (d >= 2 && rnd() < 0.25) || w < 120 || h < 120) { panels.push([x, y, w, h]); return; }
    const vertical = w > h ? rnd() < 0.75 : rnd() < 0.25;
    if (vertical) { const k = snap(w * (0.3 + rnd() * 0.4)); split(x, y, k, h, d + 1); split(x + k, y, w - k, h, d + 1); }
    else { const k = snap(h * (0.3 + rnd() * 0.4)); split(x, y, w, k, d + 1); split(x, y + k, w, h - k, d + 1); }
  })(0, 0, S, S, 0);

  const stencils = ['ARS-26', 'NO STEP', 'HULL 7C', 'SERVICE 04', 'O2 VENT', 'DECK 2', 'ARS-26', 'KEEP CLEAR'];
  let si = 0;
  panels.forEach(([x, y, w, h], idx) => {
    // per-panel tone, height and roughness
    g.fillStyle = rnd() < 0.5 ? 'rgba(255,255,255,' + rnd() * 0.07 + ')' : 'rgba(0,4,30,' + rnd() * 0.16 + ')';
    g.fillRect(x, y, w, h);
    hg.fillStyle = gray(118 + rnd() * 24); hg.fillRect(x + 3, y + 3, w - 6, h - 6);
    rg.fillStyle = 'rgba(' + (rnd() < 0.5 ? '255,255,255' : '0,0,0') + ',' + rnd() * 0.25 + ')'; rg.fillRect(x, y, w, h);

    // grime streak running down from the panel's top seam
    if (rnd() < 0.55) {
      const gx = x + rnd() * w, gw = 6 + rnd() * 18, len = h * (0.3 + rnd() * 0.7);
      const gr = g.createLinearGradient(0, y, 0, y + len);
      gr.addColorStop(0, 'rgba(25,18,12,0.35)'); gr.addColorStop(1, 'rgba(25,18,12,0)');
      g.fillStyle = gr; g.fillRect(gx, y + 3, gw, len);
      rg.fillStyle = 'rgba(255,255,255,0.15)'; rg.fillRect(gx, y + 3, gw, len);
    }

    // seams (recessed, darker, rougher)
    g.strokeStyle = 'rgba(8,10,18,0.9)'; g.lineWidth = 3; g.strokeRect(x + 1.5, y + 1.5, w - 3, h - 3);
    g.strokeStyle = 'rgba(255,255,255,0.07)'; g.lineWidth = 1; g.strokeRect(x + 4.5, y + 4.5, w - 9, h - 9);
    hg.strokeStyle = gray(25); hg.lineWidth = 5; hg.strokeRect(x + 1.5, y + 1.5, w - 3, h - 3);
    rg.strokeStyle = gray(210); rg.lineWidth = 4; rg.strokeRect(x + 1.5, y + 1.5, w - 3, h - 3);

    // rivet rows
    if (rnd() < 0.6) {
      for (let rx = x + 12; rx < x + w - 8; rx += 22) {
        for (const ry of [y + 10, y + h - 10]) { dot(g, rx, ry, 2.2, 'rgba(205,210,225,0.55)'); dot(hg, rx, ry, 3, gray(215)); }
      }
    }
    // access hatch with handle
    if (w > 150 && h > 150 && rnd() < 0.3) {
      const ix = x + 24, iy = y + 24, iw = Math.min(w - 48, 180), ih = Math.min(h - 48, 120);
      g.strokeStyle = 'rgba(8,10,18,0.85)'; g.lineWidth = 2; g.strokeRect(ix, iy, iw, ih);
      hg.strokeStyle = gray(45); hg.lineWidth = 3; hg.strokeRect(ix, iy, iw, ih);
      g.fillStyle = 'rgba(15,17,28,0.8)'; g.fillRect(ix + iw / 2 - 18, iy + ih - 18, 36, 7);
      hg.fillStyle = gray(60); hg.fillRect(ix + iw / 2 - 18, iy + ih - 18, 36, 7);
    }
    // hazard band + stencil text on a few panels
    if (w > 220 && h > 100 && rnd() < 0.22) hazard(g, x + 10, y + h - 34, w - 20, 22, 0.75);
    if (w > 180 && h > 90 && rnd() < 0.35) {
      g.font = 'bold 22px ui-monospace, Menlo, monospace';
      g.fillStyle = idx % 3 === 0 ? 'rgba(254,116,43,0.7)' : 'rgba(230,232,240,0.55)';
      g.fillText(stencils[si++ % stencils.length], x + 18, y + 36);
    }
  });

  scratches(g, hg, S, 260, 0.18);
  noise(g, S, S, 48, 0.12, 'multiply'); // dirt
  return { map: tex(c, true), normalMap: tex(normalFrom(hc, 3.5)), roughnessMap: tex(rc) };
}

// Lit windows for the hull (emissive map, same layout scale as the plating)
export function hullWindows() {
  seed(77);
  const S = 1024;
  const [c, g] = makeCanvas(S);
  g.fillStyle = '#000'; g.fillRect(0, 0, S, S);
  for (const col of [S * 0.3, S * 0.72]) {
    for (let y = 30; y < S - 20; y += 30) {
      if (rnd() < 0.55) {
        g.fillStyle = rnd() < 0.8 ? 'rgba(255,205,150,0.95)' : 'rgba(156,194,255,0.9)';
        g.fillRect(col - 8, y, 16, 10);
      }
    }
  }
  return tex(c, true);
}

// ---------- Generic worn metal for props (beveled edges on every face) ----------
export function metalTextures() {
  seed(3);
  const S = 512;
  const [c, g] = makeCanvas(S), [hc, hg] = makeCanvas(S), [rc, rg] = makeCanvas(S);
  g.fillStyle = '#8e93a3'; g.fillRect(0, 0, S, S);
  hg.fillStyle = gray(150); hg.fillRect(0, 0, S, S);
  rg.fillStyle = gray(105); rg.fillRect(0, 0, S, S);
  noise(g, S, S, 6, 0.15);
  noise(g, S, S, 48, 0.12);
  noise(rg, S, S, 12, 0.35);
  // brushed grain
  for (let i = 0; i < 600; i++) {
    const y = rnd() * S, x = rnd() * S, len = 40 + rnd() * 220;
    g.strokeStyle = rnd() < 0.5 ? 'rgba(255,255,255,' + rnd() * 0.06 + ')' : 'rgba(0,0,0,' + rnd() * 0.06 + ')';
    g.lineWidth = 1;
    g.beginPath(); g.moveTo(x, y); g.lineTo(x + len, y); g.stroke();
  }
  scratches(g, hg, S, 140, 0.22);
  // beveled, worn edges (every face of a box gets them)
  const bevel = [[3, 'rgba(6,8,16,0.75)', gray(40), 6], [10, 'rgba(10,12,24,0.35)', gray(95), 8], [15, 'rgba(255,255,255,0.10)', gray(175), 2]];
  bevel.forEach(([inset, col, hcol, lw]) => {
    g.strokeStyle = col; g.lineWidth = lw; g.strokeRect(inset, inset, S - inset * 2, S - inset * 2);
    hg.strokeStyle = hcol; hg.lineWidth = lw; hg.strokeRect(inset, inset, S - inset * 2, S - inset * 2);
  });
  rg.strokeStyle = gray(180); rg.lineWidth = 8; rg.strokeRect(4, 4, S - 8, S - 8);
  noise(g, S, S, 24, 0.12, 'multiply');
  return { map: tex(c, true), normalMap: tex(normalFrom(hc, 2.5)), roughnessMap: tex(rc) };
}

// ---------- Interior wall panels (one tile = 2x2 panels) ----------
export function wallTextures() {
  seed(21);
  const S = 1024, P = S / 2, F = 34;
  const [c, g] = makeCanvas(S), [hc, hg] = makeCanvas(S), [rc, rg] = makeCanvas(S);
  g.fillStyle = '#3d4363'; g.fillRect(0, 0, S, S);
  hg.fillStyle = gray(150); hg.fillRect(0, 0, S, S);
  rg.fillStyle = gray(140); rg.fillRect(0, 0, S, S);
  noise(g, S, S, 8, 0.16);
  noise(g, S, S, 64, 0.12);
  noise(rg, S, S, 16, 0.3);

  for (let j = 0; j < 2; j++) for (let i = 0; i < 2; i++) {
    const x = i * P, y = j * P, type = (i + j * 2) % 4;
    const ix = x + 6 + F, iy = y + 6 + F, iw = P - 6 - 2 * F, ih = P - 6 - 2 * F;
    // seams between panels
    g.fillStyle = 'rgba(6,8,16,0.92)'; g.fillRect(x, y, P, 6); g.fillRect(x, y, 6, P);
    hg.fillStyle = gray(20); hg.fillRect(x, y, P, 6); hg.fillRect(x, y, 6, P);
    // recessed centre with a bevel
    g.fillStyle = 'rgba(0,0,25,0.22)'; g.fillRect(ix, iy, iw, ih);
    hg.fillStyle = gray(95); hg.fillRect(ix, iy, iw, ih);
    hg.strokeStyle = gray(122); hg.lineWidth = 8; hg.strokeRect(ix - 4, iy - 4, iw + 8, ih + 8);
    g.strokeStyle = 'rgba(255,255,255,0.08)'; g.lineWidth = 2; g.strokeRect(ix - 8, iy - 8, iw + 16, ih + 16);
    rg.fillStyle = gray(120 + rnd() * 50); rg.fillRect(ix, iy, iw, ih);
    // corner screws
    for (const [sx, sy] of [[x + 6 + F / 2, y + 6 + F / 2], [x + P - F / 2, y + 6 + F / 2], [x + 6 + F / 2, y + P - F / 2], [x + P - F / 2, y + P - F / 2]]) {
      dot(g, sx, sy, 5, 'rgba(200,205,222,0.6)'); dot(hg, sx, sy, 6, gray(220));
      g.strokeStyle = 'rgba(10,12,20,0.8)'; g.lineWidth = 1.5;
      g.beginPath(); g.moveTo(sx - 3, sy); g.lineTo(sx + 3, sy); g.stroke();
    }
    if (type === 1) { // vent grille
      for (let k = 0; k < 11; k++) {
        const vy = iy + 40 + k * 32;
        g.fillStyle = 'rgba(4,5,10,0.9)'; g.fillRect(ix + 50, vy, iw - 100, 14);
        hg.fillStyle = gray(15); hg.fillRect(ix + 50, vy, iw - 100, 14);
        g.fillStyle = 'rgba(255,255,255,0.08)'; g.fillRect(ix + 50, vy + 14, iw - 100, 2);
      }
    } else if (type === 2) { // split panel + label plate
      g.fillStyle = 'rgba(6,8,16,0.85)'; g.fillRect(ix + iw / 2 - 2, iy, 4, ih);
      hg.fillStyle = gray(30); hg.fillRect(ix + iw / 2 - 2, iy, 4, ih);
      g.fillStyle = 'rgba(210,215,230,0.18)'; g.fillRect(ix + 30, iy + 30, 120, 40);
      hg.fillStyle = gray(130); hg.fillRect(ix + 30, iy + 30, 120, 40);
    } else if (type === 3) { // cable conduit
      const cy = iy + ih * 0.62;
      const gr = g.createLinearGradient(0, cy - 24, 0, cy + 24);
      gr.addColorStop(0, 'rgba(10,12,22,0.8)'); gr.addColorStop(0.45, 'rgba(190,196,215,0.45)'); gr.addColorStop(1, 'rgba(10,12,22,0.8)');
      g.fillStyle = gr; g.fillRect(ix, cy - 24, iw, 48);
      const hgr = hg.createLinearGradient(0, cy - 24, 0, cy + 24);
      hgr.addColorStop(0, gray(110)); hgr.addColorStop(0.5, gray(240)); hgr.addColorStop(1, gray(110));
      hg.fillStyle = hgr; hg.fillRect(ix, cy - 24, iw, 48);
      for (let bx = ix + 30; bx < ix + iw; bx += 110) { g.fillStyle = 'rgba(30,34,50,0.9)'; g.fillRect(bx, cy - 28, 14, 56); hg.fillStyle = gray(250); hg.fillRect(bx, cy - 28, 14, 56); }
    }
    g.font = 'bold 20px ui-monospace, Menlo, monospace';
    g.fillStyle = 'rgba(255,190,150,0.5)';
    g.fillText('SEC-' + (i + j * 2 + 1) + (type === 1 ? '  AIR' : ''), ix + 12, iy + ih - 14);
  }
  scratches(g, hg, S, 160, 0.14);
  noise(g, S, S, 32, 0.12, 'multiply');
  return { map: tex(c, true), normalMap: tex(normalFrom(hc, 4)), roughnessMap: tex(rc) };
}

// ---------- Diamond-plate floor (one tile = one plate) ----------
export function floorTextures() {
  seed(5);
  const S = 512, step = 32;
  const [c, g] = makeCanvas(S), [hc, hg] = makeCanvas(S), [rc, rg] = makeCanvas(S);
  g.fillStyle = '#4a5068'; g.fillRect(0, 0, S, S);
  hg.fillStyle = gray(100); hg.fillRect(0, 0, S, S);
  rg.fillStyle = gray(150); rg.fillRect(0, 0, S, S);
  noise(g, S, S, 6, 0.35);
  noise(g, S, S, 32, 0.15);
  for (let y = step / 2; y < S; y += step) {
    for (let x = step / 2; x < S; x += step) {
      const a = (((x - step / 2) + (y - step / 2)) / step) % 2 ? 0.78 : -0.78;
      for (const [cv, col, lr] of [[g, 'rgba(205,210,228,0.28)', 0], [hg, gray(225), 0], [rg, gray(80), 0]]) {
        cv.save(); cv.translate(x, y); cv.rotate(a);
        cv.fillStyle = col; cv.beginPath(); cv.ellipse(0, 0, 11, 3.2, 0, 0, Math.PI * 2); cv.fill();
        cv.restore();
      }
    }
  }
  // plate seams on the tile border
  g.strokeStyle = 'rgba(5,6,12,0.9)'; g.lineWidth = 4; g.strokeRect(0, 0, S, S);
  hg.strokeStyle = gray(10); hg.lineWidth = 6; hg.strokeRect(0, 0, S, S);
  for (const [sx, sy] of [[14, 14], [S - 14, 14], [14, S - 14], [S - 14, S - 14]]) { dot(g, sx, sy, 4, 'rgba(200,205,222,0.6)'); dot(hg, sx, sy, 5, gray(230)); }
  scratches(g, hg, S, 120, 0.2);
  noise(g, S, S, 16, 0.15, 'multiply');
  return { map: tex(c, true), normalMap: tex(normalFrom(hc, 3)), roughnessMap: tex(rc) };
}

// ---------- Sliding door half (portrait) ----------
export function doorTextures() {
  seed(33);
  const W = 512, H = 1024;
  const [c, g] = makeCanvas(W, H), [hc, hg] = makeCanvas(W, H), [rc, rg] = makeCanvas(W, H);
  g.fillStyle = '#3d4675'; g.fillRect(0, 0, W, H);
  hg.fillStyle = gray(120); hg.fillRect(0, 0, W, H);
  rg.fillStyle = gray(115); rg.fillRect(0, 0, W, H);
  noise(g, W, H, 6, 0.3);
  noise(g, W, H, 48, 0.12);
  noise(rg, W, H, 12, 0.35);
  // raised outer frame
  g.strokeStyle = 'rgba(255,255,255,0.07)'; g.lineWidth = 26; g.strokeRect(13, 13, W - 26, H - 26);
  hg.strokeStyle = gray(185); hg.lineWidth = 26; hg.strokeRect(13, 13, W - 26, H - 26);
  g.strokeStyle = 'rgba(5,7,16,0.8)'; g.lineWidth = 3; g.strokeRect(28, 28, W - 56, H - 56);
  hg.strokeStyle = gray(60); hg.lineWidth = 4; hg.strokeRect(28, 28, W - 56, H - 56);
  // horizontal ribs
  for (let k = 0; k < 6; k++) {
    const y = 110 + k * 105;
    g.fillStyle = 'rgba(4,6,16,0.65)'; g.fillRect(48, y, W - 96, 16);
    hg.fillStyle = gray(35); hg.fillRect(48, y, W - 96, 16);
    g.fillStyle = 'rgba(255,255,255,0.09)'; g.fillRect(48, y + 16, W - 96, 3);
  }
  // hazard band
  hazard(g, 36, H - 200, W - 72, 120, 0.92);
  hg.fillStyle = gray(150); hg.fillRect(36, H - 200, W - 72, 120);
  g.font = 'bold 26px ui-monospace, Menlo, monospace';
  g.fillStyle = 'rgba(232,234,242,0.6)';
  g.fillText('AUTHORIZED CREW', 52, H - 228);
  scratches(g, hg, W, 70, 0.1);
  noise(g, W, H, 24, 0.12, 'multiply');
  return { map: tex(c, true), normalMap: tex(normalFrom(hc, 3)), roughnessMap: tex(rc) };
}

// ---------- Diamond-quilted padding (mattresses, seats) ----------
export function quiltTextures(base, stitch) {
  seed(9);
  const S = 512, sp = 64;
  const [hc, hg] = makeCanvas(S);
  const img = hg.createImageData(S, S);
  for (let y = 0; y < S; y++) {
    for (let x = 0; x < S; x++) {
      const u = (((x + y) % sp) + sp) % sp, v = (((x - y) % sp) + sp) % sp;
      const du = Math.min(u, sp - u) / (sp / 2), dv = Math.min(v, sp - v) / (sp / 2);
      const h = Math.sqrt(Math.min(du, dv));
      const k = (y * S + x) * 4;
      img.data[k] = img.data[k + 1] = img.data[k + 2] = 20 + h * 215;
      img.data[k + 3] = 255;
    }
  }
  hg.putImageData(img, 0, 0);

  const [c, g] = makeCanvas(S);
  g.fillStyle = base; g.fillRect(0, 0, S, S);
  noise(g, S, S, 128, 0.18);
  g.save(); g.globalCompositeOperation = 'multiply'; g.globalAlpha = 0.55; g.drawImage(hc, 0, 0); g.restore();
  // stitches along the seams
  g.fillStyle = stitch;
  for (let k = 0; k <= (2 * S) / sp; k++) {
    for (let x = 0; x < S; x += 9) {
      const y1 = ((k * sp - x) % S + S) % S, y2 = ((x - k * sp) % S + S) % S;
      g.fillRect(x, y1, 3, 2);
      g.fillRect(x, y2, 3, 2);
    }
  }
  const [rc, rg] = makeCanvas(S);
  rg.fillStyle = gray(200); rg.fillRect(0, 0, S, S);
  noise(rg, S, S, 32, 0.3);
  return { map: tex(c, true), normalMap: tex(normalFrom(hc, 4)), roughnessMap: tex(rc) };
}

// Clone a texture with its own repeat (the image is shared)
export function repeat(t, x, y = x) {
  const c = t.clone();
  c.wrapS = c.wrapT = THREE.RepeatWrapping;
  c.repeat.set(x, y);
  c.needsUpdate = true;
  return c;
}

// World-scale box projection: rewrites UVs so textures keep the same size on any face
export function boxUV(geo, size) {
  const pos = geo.attributes.position, nor = geo.attributes.normal;
  const uv = new Float32Array(pos.count * 2);
  for (let i = 0; i < pos.count; i++) {
    const ax = Math.abs(nor.getX(i)), ay = Math.abs(nor.getY(i)), az = Math.abs(nor.getZ(i));
    let u, v;
    if (ax >= ay && ax >= az) { u = pos.getZ(i); v = pos.getY(i); }
    else if (ay >= az) { u = pos.getX(i); v = pos.getZ(i); }
    else { u = pos.getX(i); v = pos.getY(i); }
    uv[i * 2] = u / size;
    uv[i * 2 + 1] = v / size;
  }
  geo.setAttribute('uv', new THREE.BufferAttribute(uv, 2));
  return geo;
}

// ---------- Asteroid rock (craters, grit) ----------
export function rockTextures() {
  seed(55);
  const S = 512;
  const [c, g] = makeCanvas(S), [hc, hg] = makeCanvas(S);
  g.fillStyle = '#6d6358'; g.fillRect(0, 0, S, S);
  hg.fillStyle = gray(128); hg.fillRect(0, 0, S, S);
  noise(g, S, S, 4, 0.5); noise(g, S, S, 16, 0.35); noise(g, S, S, 64, 0.25); noise(g, S, S, 256, 0.2);
  noise(hg, S, S, 8, 0.6); noise(hg, S, S, 32, 0.4); noise(hg, S, S, 128, 0.3);
  for (let i = 0; i < 70; i++) {
    const x = rnd() * S, y = rnd() * S, r = 4 + Math.pow(rnd(), 2.5) * 45;
    const pit = hg.createRadialGradient(x, y, 0, x, y, r);
    pit.addColorStop(0, 'rgba(0,0,0,0.75)'); pit.addColorStop(0.75, 'rgba(0,0,0,0.25)'); pit.addColorStop(0.9, 'rgba(255,255,255,0.45)'); pit.addColorStop(1, 'rgba(255,255,255,0)');
    hg.fillStyle = pit; hg.beginPath(); hg.arc(x, y, r, 0, Math.PI * 2); hg.fill();
    const tone = g.createRadialGradient(x, y, 0, x, y, r);
    tone.addColorStop(0, 'rgba(20,16,12,0.45)'); tone.addColorStop(0.85, 'rgba(20,16,12,0.1)'); tone.addColorStop(1, 'rgba(255,240,220,0.12)');
    g.fillStyle = tone; g.beginPath(); g.arc(x, y, r, 0, Math.PI * 2); g.fill();
  }
  return { map: tex(c, true), normalMap: tex(normalFrom(hc, 5)) };
}

// Soft radial glow (sun)
export function glowTexture(inner = 'rgba(255,255,255,1)', mid = 'rgba(255,200,140,0.35)') {
  const [c, g] = makeCanvas(256);
  const gr = g.createRadialGradient(128, 128, 0, 128, 128, 128);
  gr.addColorStop(0, inner); gr.addColorStop(0.15, inner); gr.addColorStop(0.4, mid); gr.addColorStop(1, 'rgba(0,0,0,0)');
  g.fillStyle = gr; g.fillRect(0, 0, 256, 256);
  return tex(c, true);
}

// Faint reflections and dust on cockpit glass (used additively)
export function glassSheen() {
  seed(91);
  const [c, g] = makeCanvas(512, 292);
  g.clearRect(0, 0, 512, 292);
  for (const [x, w, a] of [[60, 70, 0.03], [170, 26, 0.022], [330, 110, 0.018]]) {
    const gr = g.createLinearGradient(x, 0, x + w, 0);
    gr.addColorStop(0, 'rgba(255,255,255,0)'); gr.addColorStop(0.5, 'rgba(200,220,255,' + a + ')'); gr.addColorStop(1, 'rgba(255,255,255,0)');
    g.save(); g.translate(x, 0); g.transform(1, 0, -0.45, 1, 0, 0); g.translate(-x, 0);
    g.fillStyle = gr; g.fillRect(x, 0, w, 292); g.restore();
  }
  for (let i = 0; i < 160; i++) { g.fillStyle = 'rgba(255,255,255,' + rnd() * 0.12 + ')'; g.fillRect(rnd() * 512, rnd() * 292, 1.2, 1.2); }
  const t = tex(c, true);
  t.wrapS = t.wrapT = THREE.ClampToEdgeWrapping;
  return t;
}
