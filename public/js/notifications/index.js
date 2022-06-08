import {
	fetchRecentNotifications,
} from './service.js';

fetchRecentNotifications(4)
	.then((response) => {
		const notifications = response.data;
		console.log(notifications);
	})
	.catch((error) => {
		console.log('index');
		console.log(error);
	})