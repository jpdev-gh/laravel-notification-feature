export const fetchRecentNotifications = async (take=0) => {
	take = take ? `?take=${take}`: '';

	try {
		const response = await axios.get(`/notifications${take}`);

		return response.data;
	} catch (error) {
		return Promise.reject(error.response);
	}
}