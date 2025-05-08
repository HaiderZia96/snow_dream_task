import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Set up axios defaults
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = import.meta.env.VITE_APP_URL || 'http://localhost:8000';

// Set up Pusher
window.Pusher = Pusher;

// Set up Laravel Echo
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: `${window.axios.defaults.baseURL}/broadcasting/auth`,
    auth: {
        headers: {
            Authorization: `Bearer ${localStorage.getItem('token')}`,
        },
    },
});

