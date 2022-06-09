export const notificationItemElement = (notification) => {
	const span_notifications__read_at = document.createElement('span_notifications__read_at');
	span_notifications__read_at.classList.add('notifications__read-at');
	span_notifications__read_at.append(document.createTextNode(` ${notification.created_at.dfh}`))

	const span_notifications__created_at = document.createElement('span_notifications__read_at');
	span_notifications__created_at.classList.add('notifications__created-at');
	span_notifications__created_at.append(document.createTextNode(` ${notification.created_at.fdy}:`))

	const i = document.createElement('i');
	i.setAttribute('aria-hidden', 'true');
	i.classList.add('fa', 'fa-clock');

	const div_notifications__date_wrapper = document.createElement('div');
	div_notifications__date_wrapper.classList.add('notifications__date-wrapper');
	div_notifications__date_wrapper.append(i, span_notifications__created_at, span_notifications__read_at);

	const h6 = document.createElement('h6');
	h6.classList.add('notifications__body');
	h6.append(document.createTextNode(notification.body));

	const span_notifications__badge = document.createElement('span');
	span_notifications__badge.classList.add('notifications__badge', 'bg-danger');

	const span_notifications__user_photo = document.createElement('span');
	span_notifications__user_photo.classList.add('notifications__user-photo');
	span_notifications__user_photo.style.backgroundImage = `url(${notification.sender.photo})`;

	const div_notifications__content = document.createElement('div');
	div_notifications__content.classList.add('notifications__content', 'ml-3');
	div_notifications__content.append(h6, div_notifications__date_wrapper);

	const a = document.createElement('a');
	a.setAttribute('target', '_blank');
	a.setAttribute('href', notification.click_action);
	a.classList.add('flex');
	a.append(span_notifications__user_photo, div_notifications__content, span_notifications__badge);

	const li = document.createElement('li');
	li.classList.add('notifications__item');
	li.dataset.id = notification.id;
	if (notification.read_at.self) {
			li.dataset.read_at = notification.read_at.self;
			li.classList.add('notifications__item--read');
	}

	li.appendChild(a);

	return li;
}