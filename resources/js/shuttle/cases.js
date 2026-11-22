import { initUISound } from './ui-sound.js';
import { initNav } from './nav.js';

// Mission log pages (hub, accomplished, experiments, mission report) are rendered by Blade.
// This script adds: the smooth "return to the shuttle", the experiment filter chips and the screenshot lightbox.

// ---------- Smooth return to the shuttle (same fly-back as the other room pages) ----------
document.querySelectorAll('[data-return]').forEach((a) => a.addEventListener('click', (e) => {
  if (e.metaKey || e.ctrlKey || e.shiftKey) return;
  e.preventDefault();
  try { sessionStorage.setItem('arrive', a.dataset.return); } catch (err) { /* storage blocked: no fly-back */ }
  document.documentElement.classList.add('leaving');
  setTimeout(() => { window.location.href = a.getAttribute('href'); }, 480);
}));
window.addEventListener('pageshow', (e) => { if (e.persisted) document.documentElement.classList.remove('leaving'); });

// ---------- Experiments: filter chips ----------
const chips = document.querySelector('#chips');
const grid = document.querySelector('#exp-grid');
if (chips && grid) {
  chips.addEventListener('click', (e) => {
    const b = e.target.closest('.chip');
    if (!b) return;
    chips.querySelectorAll('.chip').forEach((c) => { c.classList.toggle('on', c === b); c.setAttribute('aria-pressed', c === b ? 'true' : 'false'); });
    const tag = b.dataset.tag;
    grid.querySelectorAll('.exp-card').forEach((card) => {
      card.hidden = tag !== '*' && !card.dataset.tags.split('|').includes(tag);
    });
  });
}

// ---------- Mission report: screenshot lightbox ----------
const lightbox = document.querySelector('#lightbox');
if (lightbox) {
  const lbImg = lightbox.querySelector('img');
  let opener = null;
  const close = () => { lightbox.hidden = true; if (opener) opener.focus(); };
  document.querySelectorAll('.shot-btn').forEach((b) => b.addEventListener('click', () => {
    opener = b;
    lbImg.src = b.dataset.src;
    lbImg.alt = b.querySelector('img')?.alt || '';
    lightbox.hidden = false;
    lightbox.querySelector('.lb-close').focus();
  }));
  lightbox.addEventListener('click', close);
  window.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !lightbox.hidden) close(); });
}

initUISound(undefined, { boot: 'chirp' });
initNav();
