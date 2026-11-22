// Procedural ship audio (Web Audio API, no sound files).
// Ambience: deep-space drone outside, ship hum inside, piston thumps in the engine room,
// computer chirps in mission control, radar pings on the bridge.
// Effects: pneumatic doors, sign hover/click, fly-in whoosh.
// Browsers only allow audio after a user gesture, so sound starts from the toggle button.

const STORE_KEY = 'arsus-sound';

export function createAudio() {
  let ctx = null, master = null, noiseBuf = null, brownBuf = null;
  let enabled = false;
  const amb = {};
  const timers = { thump: 0, bleep: 0, ping: 0 };

  const clamp01 = (v) => Math.max(0, Math.min(1, v));

  function makeNoise(brown) {
    const len = ctx.sampleRate * 2;
    const buf = ctx.createBuffer(1, len, ctx.sampleRate);
    const d = buf.getChannelData(0);
    let last = 0;
    for (let i = 0; i < len; i++) {
      const w = Math.random() * 2 - 1;
      if (brown) { last = (last + 0.02 * w) / 1.02; d[i] = last * 3.5; } else d[i] = w;
    }
    return buf;
  }
  function noiseSource(brown) {
    const s = ctx.createBufferSource();
    s.buffer = brown ? brownBuf : noiseBuf;
    s.loop = true;
    return s;
  }
  function osc(type, freq) {
    const o = ctx.createOscillator();
    o.type = type; o.frequency.value = freq;
    return o;
  }
  function gain(v = 0) {
    const g = ctx.createGain();
    g.gain.value = v;
    return g;
  }
  function filter(type, freq, q = 0.7) {
    const f = ctx.createBiquadFilter();
    f.type = type; f.frequency.value = freq; f.Q.value = q;
    return f;
  }

  function build() {
    ctx = new (window.AudioContext || window.webkitAudioContext)();
    noiseBuf = makeNoise(false);
    brownBuf = makeNoise(true);
    const comp = ctx.createDynamicsCompressor();
    comp.connect(ctx.destination);
    master = gain(0);
    master.connect(comp);

    // Deep-space drone: slow pad + dark rumble
    amb.space = gain(0); amb.space.connect(master);
    const padLP = filter('lowpass', 500);
    padLP.connect(amb.space);
    [[55, 0.09], [82.6, 0.06], [110.7, 0.05], [164.4, 0.025]].forEach(([f, v]) => {
      const o = osc('sine', f); const g = gain(v);
      o.connect(g).connect(padLP); o.start();
    });
    const lfo = osc('sine', 0.06); const lfoAmt = gain(220);
    lfo.connect(lfoAmt).connect(padLP.frequency); lfo.start();
    const rumble = noiseSource(true); const rumbleLP = filter('lowpass', 260); const rumbleG = gain(0.35);
    rumble.connect(rumbleLP).connect(rumbleG).connect(amb.space); rumble.start();

    // Ship interior hum: mains-like drone + air handling
    amb.hum = gain(0); amb.hum.connect(master);
    const humLP = filter('lowpass', 420);
    humLP.connect(amb.hum);
    [['sine', 50, 0.22], ['sine', 100, 0.1], ['triangle', 150, 0.04], ['sine', 200.5, 0.02]].forEach(([t, f, v]) => {
      const o = osc(t, f); const g = gain(v);
      o.connect(g).connect(humLP); o.start();
    });
    const air = noiseSource(false); const airBP = filter('bandpass', 700, 0.6); const airG = gain(0.035);
    air.connect(airBP).connect(airG).connect(amb.hum); air.start();
  }

  // ---------- one-shot helpers ----------
  function env(g, t0, attack, peak, release) {
    g.gain.setValueAtTime(0.0001, t0);
    g.gain.exponentialRampToValueAtTime(Math.max(0.0002, peak), t0 + attack);
    g.gain.exponentialRampToValueAtTime(0.0001, t0 + attack + release);
  }
  function tone(type, f0, f1, dur, vol, delay = 0) {
    const t0 = ctx.currentTime + delay;
    const o = osc(type, f0); const g = gain(0);
    o.frequency.setValueAtTime(f0, t0);
    if (f1 !== f0) o.frequency.exponentialRampToValueAtTime(f1, t0 + dur);
    o.connect(g).connect(master);
    env(g, t0, 0.008, vol, dur);
    o.start(t0); o.stop(t0 + dur + 0.05);
  }
  function hiss(f0, f1, dur, vol, q = 0.8, delay = 0) {
    const t0 = ctx.currentTime + delay;
    const s = noiseSource(false); const bp = filter('bandpass', f0, q); const g = gain(0);
    bp.frequency.setValueAtTime(f0, t0);
    bp.frequency.exponentialRampToValueAtTime(f1, t0 + dur);
    s.connect(bp).connect(g).connect(master);
    env(g, t0, 0.03, vol, dur);
    s.start(t0); s.stop(t0 + dur + 0.1);
  }

  const api = {
    get enabled() { return enabled; },

    setEnabled(on) {
      enabled = on;
      try { localStorage.setItem(STORE_KEY, on ? 'on' : 'off'); } catch (e) { /* private mode: not remembered */ }
      if (on && !ctx) build();
      if (!ctx) return;
      if (on) ctx.resume();
      master.gain.setTargetAtTime(on ? 0.8 : 0, ctx.currentTime, 0.25);
    },

    wantsSound() {
      try { return localStorage.getItem(STORE_KEY) === 'on'; } catch (e) { return false; }
    },

    // per frame: camera z decides which room's ambience plays
    update(t, dt, camZ, inside) {
      if (!enabled || !ctx) return;
      const now = ctx.currentTime;
      amb.space.gain.setTargetAtTime(inside ? 0.12 : 0.55, now, 0.6);
      amb.hum.gain.setTargetAtTime(inside ? 0.45 : 0.04, now, 0.6);
      const near = (zc, range) => (inside ? clamp01(1 - Math.abs(camZ - zc) / range) : 0);

      const engine = near(-2, 6);
      timers.thump -= dt;
      if (engine > 0.05 && timers.thump <= 0) {
        timers.thump = 0.72;
        tone('sine', 115, 42, 0.22, 0.5 * engine);
        hiss(1800, 600, 0.25, 0.06 * engine, 1, 0.12);
      }
      const computer = near(-10, 6);
      timers.bleep -= dt;
      if (computer > 0.05 && timers.bleep <= 0) {
        timers.bleep = 0.12 + Math.random() * 0.45;
        const notes = [1046, 1174, 1318, 1568, 1760, 2093];
        tone(Math.random() < 0.5 ? 'square' : 'sine', notes[(Math.random() * notes.length) | 0], notes[(Math.random() * notes.length) | 0], 0.06, 0.035 * computer);
      }
      const bridge = near(-18, 6);
      timers.ping -= dt;
      if (bridge > 0.05 && timers.ping <= 0) {
        timers.ping = 2.6;
        tone('sine', 1480, 1440, 1.1, 0.12 * bridge);
        tone('sine', 1480, 1440, 0.9, 0.04 * bridge, 0.32);
      }
    },

    door(opening, vol = 1) {
      if (!enabled || !ctx || vol <= 0.02) return;
      tone('sine', opening ? 90 : 70, opening ? 55 : 40, 0.2, 0.35 * vol);
      tone('triangle', 160, 120, 0.12, 0.08 * vol);
      hiss(opening ? 3200 : 900, opening ? 700 : 2600, 0.7, 0.32 * vol, 0.9, 0.05);
      if (!opening) tone('sine', 60, 35, 0.25, 0.3 * vol, 0.62);
    },

    running() { return !!ctx && ctx.state === 'running'; },

    // page-open beep: 'chirp' = soft rising computer arpeggio, 'crt' = monitor power-on + chirp
    boot(kind = 'chirp') {
      if (!enabled || !ctx) return;
      let d = 0;
      if (kind === 'crt') {
        hiss(5200, 4200, 0.12, 0.05, 1.2);
        tone('sine', 7600, 7400, 0.6, 0.008);
        d = 0.22;
      }
      tone('sine', 660, 660, 0.09, 0.06, d);
      tone('sine', 880, 880, 0.09, 0.06, d + 0.09);
      tone('sine', 1320, 1320, 0.18, 0.07, d + 0.18);
      tone('triangle', 2640, 2640, 0.12, 0.012, d + 0.18);
    },

    hover() {
      if (!enabled || !ctx) return;
      tone('sine', 1320, 1760, 0.06, 0.05);
    },

    click() {
      if (!enabled || !ctx) return;
      tone('sine', 880, 880, 0.08, 0.08);
      tone('sine', 1320, 1320, 0.12, 0.08, 0.08);
    },

    // asteroid glancing off the hull: a low thump + a short metallic knock
    bump(vol = 1) {
      if (!enabled || !ctx || vol <= 0.02) return;
      tone('sine', 120, 42, 0.2, 0.55 * vol);
      tone('triangle', 210, 75, 0.1, 0.18 * vol, 0.01);
      hiss(2400, 500, 0.12, 0.22 * vol, 1.1, 0.005);
    },

    whoosh(dur) {
      if (!enabled || !ctx) return;
      const t0 = ctx.currentTime;
      const s = noiseSource(false); const lp = filter('lowpass', 300, 1.2); const g = gain(0);
      lp.frequency.setValueAtTime(300, t0);
      lp.frequency.exponentialRampToValueAtTime(2600, t0 + dur * 0.5);
      lp.frequency.exponentialRampToValueAtTime(250, t0 + dur);
      g.gain.setValueAtTime(0.0001, t0);
      g.gain.exponentialRampToValueAtTime(0.28, t0 + dur * 0.45);
      g.gain.exponentialRampToValueAtTime(0.0001, t0 + dur);
      s.connect(lp).connect(g).connect(master);
      s.start(t0); s.stop(t0 + dur + 0.1);
      tone('sawtooth', 70, 140, dur, 0.04);
    },
  };
  return api;
}
