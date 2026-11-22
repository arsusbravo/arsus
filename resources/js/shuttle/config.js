// Page config written by the Blade layout (#shuttle-config): localized URLs and UI strings.
const el = document.getElementById('shuttle-config');
const config = el ? JSON.parse(el.textContent) : { locale: 'en', routes: {}, t: {} };

export const locale = config.locale;
export const routes = config.routes;

// t('comms.transmit') -> translated string; falls back to the given text when a key is missing
export function t(path, fallback = '') {
  const v = path.split('.').reduce((o, k) => (o && o[k] !== undefined ? o[k] : undefined), config.t);
  return v === undefined ? fallback : v;
}
