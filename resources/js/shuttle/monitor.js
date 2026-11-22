// Shared behaviour for the "ship monitor" pages (crew file, comms): typewriter text and the CRT-off exit.
const sleep = (ms) => new Promise((r) => setTimeout(r, ms));

export function initMonitor(key) {
  const root = document.querySelector('#crew');
  const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  async function runTyping() {
    const els = [...root.querySelectorAll('[data-type]')];
    const full = els.map((el) => el.textContent);
    if (reduce) return;
    els.forEach((el) => (el.textContent = ''));
    await sleep(500);
    for (let i = 0; i < els.length; i++) {
      const el = els[i];
      el.classList.add('typing');
      for (const ch of full[i]) {
        el.textContent += ch;
        await sleep(ch === ' ' ? 10 : 12 + Math.random() * 14);
      }
      el.classList.remove('typing');
      await sleep(150);
    }
  }

  function leave(href) {
    try { sessionStorage.setItem('arrive', key); } catch (e) { /* storage blocked: the ship just opens without the fly-back */ }
    setTimeout(() => document.documentElement.classList.add('leaving'), 200);
    root.classList.remove('on');
    root.classList.add('off');
    setTimeout(() => (window.location.href = href), 450);
  }

  root.addEventListener('click', (e) => {
    const a = e.target.closest('[data-close]');
    if (!a) return;
    e.preventDefault();
    leave(a.getAttribute('href'));
  });
  window.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return;
    if (e.target.closest && e.target.closest('input, textarea, select')) return; // never throw away a half-written message
    leave(root.querySelector('[data-close]').getAttribute('href'));
  });
  window.addEventListener('pageshow', (e) => {
    if (e.persisted) { root.classList.remove('off'); root.classList.add('on'); document.documentElement.classList.remove('leaving'); }
  });

  runTyping();
  return { root };
}
