<script>
import apis from '@/config/api.js';
import request from '@/lib/http/request.js';
import repsonse from '@/lib/http/response.js';
export default {
	globalData: {
		loginTokenKey: "login_token",

		// 检测登录
		checkLogin: function() {
			var _this = this
			try {
				var value = uni.getStorageSync(getApp().globalData.loginTokenKey);
				if (value) {
					var accessToken = JSON.parse(value);
					if (accessToken.access_token.expired_at <= (new Date()).getTime() + 600000) {
						// 提前10分钟更新
						_this.refresh()
					}
					
					return true;
				}
			} catch (e) {
				console.log(e)
			}
			
			return false;
		},
		
		refresh: function() {
			request.post(apis.getApi('myRefresh'), null, function(res) {
				if (res.code == 0) {
					var timestamp=(new Date()).getTime();
					res.data.access_token.expired_at = timestamp + res.data.access_token.expired_in * 1000;
					uni.setStorage({
						key: getApp().globalData.loginTokenKey,
						data: JSON.stringify(res.data),
					});
				} else {
					console.log(res.message)
				}
			}, {
				"Content-Type": "application/json"
			})
		}
	},
	onLaunch() {
		uni.onNetworkStatusChange(function (res) {
			if (!res.isConnected) {
				repsonse.error('network disconnect');
			}
		});
	}
}
</script>

<style>
	/*每个页面公共css */
</style>
