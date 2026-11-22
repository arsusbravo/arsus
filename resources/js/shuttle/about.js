import { initUISound } from './ui-sound.js';
import { initMonitor } from './monitor.js';

initMonitor('about');

initUISound(undefined, { boot: 'crt' });
