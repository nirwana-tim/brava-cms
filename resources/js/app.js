import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

import './media-editor';

window.Alpine = Alpine;
window.Chart = Chart;

Alpine.start();

// PWA Service Worker Registration
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js').catch((err) => {
            console.warn('[PWA] ServiceWorker registration failed: ', err);
        });
    });
}

