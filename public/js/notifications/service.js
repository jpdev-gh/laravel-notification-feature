export const fetchRecentNotifications = async (take=0) => {
	take = take ? `?take=${take}`: '';

	try {
		const response = await axios.get(`/notifications${take}`);

		return response.data;
	} catch (error) {
		return Promise.reject(error.response);
	}
}

export const fetchUnreadNotifications = async () => {
	try {
		const response = await axios.get('/notifications?read_at=unread');

		return response.data;
	} catch (errors) {
		return Promise.reject(error.response);
	}
}

export const fetchUnreadNotificationsCount = async () => {
	try {
		const response = await axios.get(`/notifications/count`);

		return response.data;
	} catch (errors) {
		return Promise.reject(error.response);
	}
}

export const updateUnreadNotificationsById = async (id) => {
	try {
		const response = await axios.patch(`/notifications/${id}`);

		return response.data;
	} catch (error) {
		return Promise.reject(error.response);
	}
}

export const fetchFilteredByDateRangeNotifications = async (date_start, date_end) => {
	try {
		const response = await axios.get(`/notifications?date_start=${start}&date_end=${date_end}`);

		return response.data;
	} catch (errors) {
		return Promise.reject(error.response);
	}
}