import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Laravel Echo + Pusher (WebSocket) setup
// Note: make sure to install the npm packages: laravel-echo and pusher-js
// and set the corresponding MIX_* environment variables in your .env
try {
	const Echo = require('laravel-echo');
	// pusher-js uses global Pusher, so attach it to window
	// eslint-disable-next-line no-undef
	window.Pusher = require('pusher-js');

	window.Echo = new Echo({
		broadcaster: 'pusher',
		key: process.env.MIX_PUSHER_APP_KEY || process.env.VUE_APP_PUSHER_KEY || 'local',
		wsHost: process.env.MIX_PUSHER_HOST || window.location.hostname,
		wsPort: process.env.MIX_PUSHER_PORT || 6001,
		wssPort: process.env.MIX_PUSHER_PORT || 6001,
		forceTLS: (process.env.MIX_PUSHER_SCHEME || 'http') === 'https',
		encrypted: (process.env.MIX_PUSHER_ENCRYPTED || 'false') === 'true',
		enabledTransports: ['ws', 'wss'],
		disableStats: true,
	});
} catch (e) {
	// If packages aren't installed yet, fail silently. The developer will
	// need to run `npm install laravel-echo pusher-js` and rebuild assets.
}
