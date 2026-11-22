import { initUISound } from './ui-sound.js';
import { initMonitor } from './monitor.js';
import { routes, t } from './config.js';

initMonitor('contact');

// The form posts to the Laravel contact route (HomeController@submitContact), which validates,
// rate-limits (3 per hour), checks the "website" honeypot and mails ContactFormMail.
const CONTACT_EMAIL = 'info@arsus.nl';
const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';

// Time-based spam check: a human needs at least a few seconds to fill the form.
// Reset whenever the form becomes fillable again (first load, or after "Send another").
const MIN_FILL_TIME = 3000;
let formLoadTime = Date.now();

const form = document.querySelector('#comms-form');
const sent = document.querySelector('#sent');
const sentText = document.querySelector('#sent-text');
const formError = document.querySelector('#form-error');
const btnText = form.querySelector('.btn-text');
const submitBtn = form.querySelector('button[type="submit"]');
const cap = document.querySelector('#radar-cap');
const status = document.querySelector('.tx .status');
const TRANSMIT = t('comms.transmit', 'Transmit') + ' \u2192';
const setStatus = (key, fallback) => { status.innerHTML = '<i></i> '; status.append(t(key, fallback)); };

// ---------- Validation (same rules as the server) ----------
const rules = {
  name: (v) => (v.length < 2 ? t('comms.error_name', 'Please enter your name (at least 2 characters)') : ''),
  email: (v) => (/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v) ? '' : t('comms.error_email', 'Please enter a valid email address')),
  subject: (v) => (v ? '' : t('comms.error_subject', 'Please select a mission type')),
  message: (v) => (v.length < 10 ? t('comms.error_message', 'Please write at least 10 characters') : ''),
};
function setFieldError(el, msg) {
  el.closest('.field').querySelector('.err').textContent = msg;
  el.setAttribute('aria-invalid', msg ? 'true' : 'false');
}
function validateField(el) {
  const rule = rules[el.name];
  const msg = rule ? rule(el.value.trim()) : '';
  setFieldError(el, msg);
  return !msg;
}
form.querySelectorAll('input, select, textarea').forEach((el) => {
  if (!rules[el.name]) return;
  el.addEventListener('blur', () => validateField(el));
  el.addEventListener('input', () => { if (el.getAttribute('aria-invalid') === 'true') validateField(el); });
});

function showError(message) {
  formError.innerHTML = '';
  formError.append(message + ' ' + t('comms.also_email', 'You can also email') + ' ');
  const a = document.createElement('a');
  a.href = 'mailto:' + CONTACT_EMAIL; a.textContent = CONTACT_EMAIL;
  formError.append(a, '.');
  formError.hidden = false;
}

// ---------- Submit ----------
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  formError.hidden = true;
  const fields = [...form.querySelectorAll('input, select, textarea')].filter((el) => rules[el.name]);
  const invalid = fields.filter((el) => !validateField(el));
  if (invalid.length) { invalid[0].focus(); return; }

  if (Date.now() - formLoadTime < MIN_FILL_TIME) {
    showError(t('comms.error_too_fast', 'Please take your time filling out the form.'));
    return;
  }

  const data = Object.fromEntries(new FormData(form).entries());
  Object.keys(data).forEach((k) => (data[k] = String(data[k]).trim()));

  submitBtn.disabled = true;
  btnText.textContent = t('comms.transmitting', 'Transmitting\u2026');
  try {
    let res;
    try {
      res = await fetch(routes.contactSubmit, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify(data), // includes the "website" honeypot so the server can drop bots
      });
    } catch (netErr) {
      throw new Error(t('comms.error_network', 'No connection to the comms relay. Please try again.'));
    }
    const json = await res.json().catch(() => ({}));
    if (res.status === 422 && json.errors) {
      // server-side validation: show our own (translated) message under each failing field
      let first = null;
      Object.keys(json.errors).forEach((name) => {
        const el = form.elements[name];
        if (!el || !rules[name]) return;
        setFieldError(el, rules[name]('') || json.errors[name][0]);
        first = first || el;
      });
      if (first) { first.focus(); return; }
      throw new Error(json.message || t('comms.error_generic', 'The transmission failed. Please try again.'));
    }
    if (!res.ok || json.success === false) throw new Error(json.message || t('comms.error_generic', 'The transmission failed. Please try again.'));
    showSent(json.message || t('comms.sent_text', ''));
  } catch (err) {
    showError(err.message || t('comms.error_generic', 'The transmission failed. Please try again.'));
  } finally {
    submitBtn.disabled = false;
    btnText.textContent = TRANSMIT;
  }
});

function showSent(text) {
  if (text) sentText.textContent = text;
  form.hidden = true;
  sent.hidden = false;
  sent.focus();
  cap.textContent = t('comms.radar_sent', 'TRANSMISSION SENT');
  setStatus('comms.status_sent', 'TRANSMISSION DELIVERED');
  pingAt = performance.now();
}
document.querySelector('#again').addEventListener('click', () => {
  formLoadTime = Date.now();
  form.reset();
  form.querySelectorAll('.err').forEach((o) => (o.textContent = ''));
  sent.hidden = true;
  form.hidden = false;
  cap.textContent = t('comms.radar_scan', 'SCANNING FOR SIGNALS\u2026');
  setStatus('comms.status_open', 'CHANNEL OPEN');
  form.name.focus();
});

// ---------- Radar ----------
const canvas = document.querySelector('#radar');
const g = canvas.getContext('2d');
const TAU = Math.PI * 2;
let seed = 11;
const rnd = () => ((seed = (seed * 16807) % 2147483647) / 2147483647);
const blips = Array.from({ length: 7 }, () => ({ ang: rnd() * TAU, r: 0.2 + rnd() * 0.75 }));
let pingAt = -1e9;
const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function drawRadar(now) {
  const w = canvas.width, h = canvas.height, t = now / 1000;
  g.fillStyle = '#050a1c'; g.fillRect(0, 0, w, h);
  const cx = w / 2, cy = h / 2, R = w / 2 - 12;
  g.strokeStyle = 'rgba(254,116,43,0.6)'; g.lineWidth = 2;
  for (let i = 1; i <= 4; i++) { g.beginPath(); g.arc(cx, cy, (R * i) / 4, 0, TAU); g.stroke(); }
  g.beginPath(); g.moveTo(cx - R, cy); g.lineTo(cx + R, cy); g.moveTo(cx, cy - R); g.lineTo(cx, cy + R); g.stroke();
  const a = reduce ? 0 : t * 1.6;
  for (let i = 0; i < 30; i++) {
    g.fillStyle = 'rgba(255,140,77,' + 0.4 * (1 - i / 30) + ')';
    g.beginPath(); g.moveTo(cx, cy); g.arc(cx, cy, R, a - (i + 1) * 0.04, a - i * 0.04); g.closePath(); g.fill();
  }
  blips.forEach((b) => {
    const d = (((a - b.ang) % TAU) + TAU) % TAU;
    g.fillStyle = 'rgba(255,255,255,' + Math.max(reduce ? 0.6 : 0, 1 - d / 3.5) + ')';
    g.beginPath(); g.arc(cx + Math.cos(b.ang) * b.r * R, cy + Math.sin(b.ang) * b.r * R, 6, 0, TAU); g.fill();
  });
  // outgoing ping after a transmission
  const age = (now - pingAt) / 1800;
  if (age >= 0 && age < 1) {
    g.strokeStyle = 'rgba(53,224,122,' + (1 - age) + ')'; g.lineWidth = 4;
    g.beginPath(); g.arc(cx, cy, R * age, 0, TAU); g.stroke();
  }
  g.fillStyle = '#35e07a'; g.beginPath(); g.arc(cx, cy, 5, 0, TAU); g.fill();
}
function loop(now) { drawRadar(now); requestAnimationFrame(loop); }
requestAnimationFrame(loop);

initUISound(undefined, { boot: 'crt' });
