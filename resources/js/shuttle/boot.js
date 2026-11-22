// Fades out the full-screen #boot-loader once the first WebGL frame has actually been drawn,
// so the page never shows a blank/half-built scene before the loader clears.
export function hideBootLoader() {
  const el = document.querySelector('#boot-loader');
  if (!el) return;
  requestAnimationFrame(() => el.classList.add('hide'));
}
