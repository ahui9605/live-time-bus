import response from '@/lib/http/response.js'

/**
 * Get请求
 * @param {Object} url
 * @param {Object} query
 * @param {Object} header
 */
function get(url, data, callback, header) {
	try {
		let value = uni.getStorageSync(getApp().globalData.loginTokenKey);
		if (value) {
			var accessToken = JSON.parse(value);
			if (typeof(header) != 'object' || header == null) {
				header = {}
			}
			
			header.Authorization = accessToken.access_token.access_token;
		}
		
		uni.request({
		    url: url,
		    data: data,
			method: 'GET',
			header: header,
			timeout: 5000,
			dataType: 'json',
		    success: function(res) {
				if (typeof(res.data) != 'object') {
					response.error('service unavailable')
					return;
				}
				
				if (res.data.code == 4004) {
					uni.removeStorageSync(getApp().globalData.loginTokenKey);
					uni.navigateTo({
						url: '/pages/my/login'
					})
					
					return;
				}
				
				callback(res.data)
			},
			fail: function(res) {
				response.error(res.errMsg)
			},
			complete: function() {
				//uni.hideToast()
			}
		});
	} catch (e) {
		console.log(e)
		response.error('service unavailable')
	}
}

/**
 * Post请求
 * @param {Object} url
 * @param {Object} data
 * @param {Object} header
 */
function post(url, data, callback, header) {
	try {
		let value = uni.getStorageSync(getApp().globalData.loginTokenKey);
		if (value) {
			var accessToken = JSON.parse(value);
			if (typeof(header) != 'object' || header == null) {
				header = {}
			}
			
			header.Authorization = accessToken.access_token.access_token;
		}
		
		uni.request({
		    url: url,
		    data: data,
			method: 'POST',
			header: header,
			timeout: 5000,
			dataType: 'json',
		    success: function(res) {
				if (typeof(res.data) != 'object') {
					response.error('service unavailable')
					return;
				}
				
				if (res.data.code == 4004) {
					uni.removeStorageSync(getApp().globalData.loginTokenKey);
					uni.navigateTo({
						url: '/pages/my/login'
					})
					
					return;
				}
				
				callback(res.data)
			},
			fail: function(res) {
				response.error(res.errMsg)
			},
			complete: function() {
				//uni.hideToast()
			}
		});
	} catch (e) {
		console.log(e)
		response.error('service unavailable')
	}
}

function upload(url, filepath, callback, formData, header) {
	try {
		let value = uni.getStorageSync(getApp().globalData.loginTokenKey);
		if (value) {
			var accessToken = JSON.parse(value);
			if (typeof(header) != 'object' || header == null) {
				header = {}
			}
			
			header.Authorization = accessToken.access_token.access_token;
		}
		
		uni.uploadFile({
			url: url,
			filePath: filepath,
			name: 'file',
			header: header,
			formData: formData,
			success: function(res) {
				var res = JSON.parse(res.data)
				if (res.code == 4004) {
					uni.removeStorageSync(getApp().globalData.loginTokenKey);
					uni.navigateTo({
						url: '/pages/my/login'
					})
					
					return;
				}
				
				callback(res);
			},
			fail: function(res) {
				response.error(res.errMsg)
			},
			complete: function() {
				//uni.hideToast()
			}
		});
	} catch (e) {
		console.log(e)
		response.error('service unavailable')
	}
}

export default {
	get: get,
	post: post,
	upload: upload
}