function error(message) {
	uni.showToast({
		icon: "error",
		mask: true,
		title: message,
		duration: 3000
	})
}

function success(message) {
	uni.showToast({
		icon: "success",
		mask: true,
		title: message,
		duration: 2000
	})
}

function showLoading(message) {
	uni.showLoading({
		mask: true,
		title: message
	})
}

function confrim(content, callback) {
	uni.showModal({
		title: 'Confirm',
		content: content,
		showCancel: true,
		cancelText: 'Cancel',
		confirmText: 'Confirm',
		success: function(res) {
			if (res.confirm) {
				typeof callback == 'function' && callback()
			}
		},
	})
}

function hideLoading() {
	uni.hideLoading()
}

export default {
	error: error,
	success: success,
	showLoading: showLoading,
	hideLoading: hideLoading,
	confrim: confrim,
}