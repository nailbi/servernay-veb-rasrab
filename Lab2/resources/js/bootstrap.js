import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Laravel Echo — клиент для подписки на каналы вещания и прослушивания
 * событий, которые транслирует Laravel. Транспорт — Pusher Channels.
 * Ключ и кластер берутся из .env (VITE_PUSHER_APP_KEY, VITE_PUSHER_APP_CLUSTER).
 */
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

if (import.meta.env.VITE_PUSHER_APP_KEY) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'eu',
        forceTLS: true,
    });
} else {
    console.warn('Pusher: VITE_PUSHER_APP_KEY не задан в .env — пуш-уведомления отключены.');
}
