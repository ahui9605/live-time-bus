const api = 'http://127.0.0.1:9501';
const ws = 'https://127.0.0.1:9501';
const apis = {
	myLogin: '/my/login',
	myLogout: '/my/logout',
	myRegister: '/my/register',
	myEdit: '/my/edit',
	myProfile: '/my/profile',
	myRefresh: '/my/refresh',
	upload: '/attachment/upload',
	coordinateReport: '/coordinate/store',
	coordinateList: '/coordinate/list',
	stationStore: '/station/store'
}

/**
 * 获取Api
 * @param {Object} identify
 */
function getApi(identify) {
	return api + apis[identify]
}

export default {
	api: api,
	ws: ws,
	getApi: getApi
}