/**
 * 获取当前定位
 */
function getCurrentPosition(succeedCallback, failedCallback) {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			const pos = {
				  latitude: position.coords.latitude,
				  longitude: position.coords.longitude,
			};
					
			typeof succeedCallback == 'function' && succeedCallback(pos)
		}, function(error) {
			var errMsg = "Error: The Geolocation service failed."
			switch(error.code) {
				case error.PERMISSION_DENIED:
                    errMsg = "User denied the request for Geolocation."
                    break;
                case error.POSITION_UNAVAILABLE:
                    errMsg = "Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    errMsg = "The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    errMsg = "An unknown error occurred."
                    break;
			}

			typeof failedCallback == 'function' && failedCallback(errMsg)
		});   
	} else {
		typeof failedCallback == 'function' && failedCallback("Error: Your browser doesn't support geolocation.")
	}
}

/**
 * 监听位置变化
 */
function watchPosition(succeedCallback, failedCallback) {
	if (navigator.geolocation) {
		const watchId = navigator.geolocation.watchPosition(function(position) {
			const pos = {
				latitude: position.coords.latitude,
				longitude: position.coords.longitude,
			};

			typeof succeedCallback == 'function' && succeedCallback(pos)
		}, function(error) {
			var errMsg = "Error: The Geolocation service failed."
			switch(error.code) {
				case error.PERMISSION_DENIED:
                    errMsg = "User denied the request for Geolocation."
                    break;
                case error.POSITION_UNAVAILABLE:
                    errMsg = "Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    errMsg = "The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    errMsg = "An unknown error occurred."
                    break;
			}

			typeof failedCallback == 'function' && failedCallback(errMsg)
		});
	} else {
		typeof failedCallback == 'function' && failedCallback("Error: Your browser doesn't support geolocation.")
	}
}

export default {
	getCurrentPosition: getCurrentPosition,
	watchPosition: watchPosition
}