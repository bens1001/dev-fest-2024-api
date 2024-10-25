import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.NEXT_PUBLIC_REVERB_APP_KEY,
    wsHost: import.meta.env.NEXT_PUBLIC_REVERB_HOST,
    wsPort: import.meta.env.NEXT_PUBLIC_REVERB_PORT ?? 80,
    wssPort: import.meta.env.NEXT_PUBLIC_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.NEXT_PUBLIC_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
