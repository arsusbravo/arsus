// Mobile navigation: a menu button that opens a full-screen menu on small screens.

import { t } from './config.js';

export function initNav() {
  const header = document.querySelector('header.nav');
  const nav = header && header.querySelector('nav');
  if (!nav) return;
  nav.id = 'site-menu';
  // number + room name per link come from the Blade markup (data-num / data-room, translated)
  nav.querySelectorAll('a').forEach((a, i) => a.style.setProperty('--n', i));

  const btn = document.createElement('button');
  btn.className = 'nav-toggle';
  btn.type = 'button';
  btn.setAttribute('aria-controls', 'site-menu');
  btn.setAttribute('aria-expanded', 'false');
  btn.setAttribute('aria-label', t('nav.menu_open', 'Open menu'));
  btn.innerHTML = '<span></span><span></span><span></span>';
  header.append(btn);

  const isOpen = () => header.classList.contains('open');
  function set(open) {
    header.classList.toggle('open', open);
    document.body.classList.toggle('menu-open', open);
    btn.setAttribute('aria-expanded', open ? 'true' : 'false');
    btn.setAttribute('aria-label', open ? t('nav.menu_close', 'Close menu') : t('nav.menu_open', 'Open menu'));
    if (open) nav.querySelector('a')?.focus({ preventScroll: true });
  }
  btn.addEventListener('click', (e) => { e.stopPropagation(); set(!isOpen()); });
  nav.addEventListener('click', (e) => { if (e.target.closest('a')) set(false); });
  // Esc closes the menu first (and stops page-level Esc handlers, e.g. "return to the shuttle")
  window.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && isOpen()) { e.stopImmediatePropagation(); set(false); btn.focus(); }
  }, true);
  window.matchMedia('(min-width: 761px)').addEventListener('change', (e) => { if (e.matches) set(false); });
}
