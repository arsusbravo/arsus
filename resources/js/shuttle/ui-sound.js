// Button beeps, page-open beep and the Sound toggle, shared by every page.
import { createAudio } from './audio.js';
import { t } from './config.js';

const SEL = 'a[href], button, select, .chip, [role="button"]';

export function initUISound(sfx = createAudio(), { boot = 'chirp' } = {}) {
  const btn = document.querySelector('#sound-toggle');
  const reflect = () => {
    if (!btn) return;
    btn.setAttribute('aria-pressed', sfx.enabled ? 'true' : 'false');
    btn.setAttribute('aria-label', sfx.enabled ? t('sound.turn_off', 'Turn sound off') : t('sound.turn_on', 'Turn sound on'));
    btn.classList.toggle('on', sfx.enabled);
  };

  if (btn) {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      sfx.setEnabled(!sfx.enabled);
      if (sfx.enabled) sfx.boot('chirp');
      reflect();
    });
  }

  // Remembered "on": try right away (plays the page-open beep if the browser allows audio),
  // otherwise audio starts with the visitor's first click or key press.
  if (sfx.wantsSound()) {
    sfx.setEnabled(true);
    setTimeout(() => { if (sfx.running()) sfx.boot(boot); }, 150);
    const resume = () => { sfx.setEnabled(true); reflect(); };
    window.addEventListener('pointerdown', resume, { once: true, capture: true });
    window.addEventListener('keydown', resume, { once: true, capture: true });
  }
  reflect();

  // hover beep (once per element) and click beep on anything interactive
  let last = null;
  document.addEventListener('pointerover', (e) => {
    const el = e.target.closest(SEL);
    if (el === last) return;
    last = el;
    if (el && el !== btn) sfx.hover();
  });
  document.addEventListener('click', (e) => {
    const el = e.target.closest(SEL);
    if (!el || el === btn) return;
    sfx.click();
    // Plain links would unload the page before the beep is heard: give it a moment.
    // Links that already animate before leaving (zoom, return, close) are left alone.
    const a = el.closest('a[href]');
    if (!a || !sfx.enabled || e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey) return;
    if (a.target === '_blank' || a.hasAttribute('download') || a.matches('[data-zoom], [data-return], [data-close], [data-back]')) return;
    const url = new URL(a.href, location.href);
    if (url.origin !== location.origin || url.protocol === 'mailto:' || (url.pathname === location.pathname && url.hash)) return;
    if (url.pathname === location.pathname && url.search === location.search) return;
    e.preventDefault();
    setTimeout(() => { location.href = a.href; }, 120);
  });
  return sfx;
}
