import { notificationItemElement } from './utils.js';
import {
	fetchRecentNotifications,
	fetchUnreadNotifications,
  updateUnreadNotificationsById,
} from './service.js';

const updateCardNotifications = (filter='all') => {
	const cardNotificationsList = document.querySelector('.card-notifications__list');

	cardNotificationsList.classList.add('card-notifications__list--loading');

	if (filter === 'unread') {
		fetchUnreadNotifications()
			.then((response) => {
				appendNotifications(response.data);
			})
			.catch((error) => {
				console.log(error);
			});

			return;
	}

	fetchRecentNotifications()
		.then((response) => {
			appendNotifications(response.data);
		})
		.catch((error) => {
			console.log(error);
		});
}

const appendNotifications = ( notifications) => {
	const cardNotificationsList = document.querySelector('.card-notifications__list');

	setTimeout(() => {
		cardNotificationsList.innerHTML = '';

		notifications.forEach((notification) => {
			cardNotificationsList.append(notificationItemElement(notification));
		})

		cardNotificationsList.classList.remove('card-notifications__list--loading');

		// document.querySelector('.card-notifications__filter').style.pointerEvents = 'auto';
	}, 1000);
}


const highLightSelectedFilter = (filters, selectedFilter) => {
	Array.from(filters).forEach((filter) => {
		filter.classList.add('btn-outline-primary');

		if (filter.classList.contains('btn-primary')) {
			filter.classList.remove('btn-primary')
		}
		})
	
	selectedFilter.classList.remove('btn-outline-primary');
	selectedFilter.classList.add('btn-primary');
}
	
updateCardNotifications();

document.querySelector('.card-notifications__list')
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

document.querySelector('.card-notifications__filter-read_at')
	.addEventListener('click', function(e) {
		const filters = e.currentTarget.children;
		const selectedFilter = e.target;

		if (!selectedFilter.dataset.filter) return;

		highLightSelectedFilter(filters, selectedFilter);

		updateCardNotifications(selectedFilter.dataset.filter);
});