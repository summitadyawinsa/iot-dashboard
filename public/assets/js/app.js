import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env_iot.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env_iot.VITE_PUSHER_APP_CLUSTER ?? 'ap1',
    wsHost: import.meta.env_iot.VITE_PUSHER_HOST ? import.meta.env_iot.VITE_PUSHER_HOST : `ws-${import.meta.env_iot.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env_iot.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env_iot.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env_iot.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Echo.channel("message").listen("MessageCreated", (event) => {
    console.log('Berhasil listen ke pusher') ;
    console.log(event) ;
});