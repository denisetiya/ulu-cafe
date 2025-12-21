import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// Get config from window (injected by blade) or fallback to VITE for local dev
const config = window.ReverbConfig || {
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: import.meta.env.VITE_REVERB_HOST,
    port: import.meta.env.VITE_REVERB_PORT,
    scheme: import.meta.env.VITE_REVERB_SCHEME,
};

if (config.key) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: config.key,
        wsHost: config.host,
        wsPort: config.port ?? 80,
        wssPort: config.port ?? 443,
        forceTLS: (config.scheme ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
