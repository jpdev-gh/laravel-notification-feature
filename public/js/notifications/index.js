import { notificationItemElement } from './utils.js';
import {
	fetchRecentNotifications,
	fetchUnreadNotificationsCount,
	updateUnreadNotificationsById,
} from './service.js';

const updateHeaderNotifications = (notifications) => {
	const headerNotificationsList = document.querySelector('.header-notifications__list');

	headerNotificationsList.innerHTML = '';

	notifications.forEach(notification => {
		headerNotificationsList.append(notificationItemElement(notification));
	});
}

fetchRecentNotifications(4)
	.then((response) => {
		updateHeaderNotifications(response.data);
	})
	.catch((error) => {
		console.log('index');
		console.log(error);
	})


document.querySelector('.header-notifications__list')
	.addEventListener('click', (e) => {
		const notificationItem = e.target.closest('.notifications__item');

		if (!notificationItem) return;

		if (notificationItem.dataset.read_at) return

		updateUnreadNotificationsById(notificationItem.dataset.id)
			.then((response) => {
					notificationItem.classList.add('notifications__item--read');
			})
			.catch((error) => {
					console.log(error);
			})
	});
