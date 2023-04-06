<template>
	<view class="p-30">
		<uni-forms ref="form" :model="formData" :rules="rules" label-position="top">
			<uni-forms-item label="Username" name="username" required>
				<uni-easyinput class="uni-mt-5" prefixIcon="person" type="text" v-model="formData.username" placeholder=""></uni-easyinput>
			</uni-forms-item>
			<uni-forms-item label="Password" name="password" required>
				<uni-easyinput class="uni-mt-5" prefixIcon="eye-slash" type="password" v-model="formData.password" placeholder=""></uni-easyinput>
			</uni-forms-item>
		</uni-forms>
		<button type="primary" @click="submit">Login</button>
	</view>
</template>

<script>
import apis from '@/config/api.js';
import request from '@/lib/http/request.js';
import response from '@/lib/http/response.js';
export default {
	data() {
		return {
			formData: {
				username: '',
				password: ''
			},
			rules: {
				username: {
					rules: [
						{
							required: true,
							errorMessage: 'please input your username'
						}
					]
				},
				password: {
					rules: [
						{
							required: true,
							errorMessage: 'please input your password'
						}
					]
				}
			}
		}
	},
	onLoad() {
		if (getApp().globalData.checkLogin()) {
			uni.navigateBack()
		}
	},
	methods: {
		// 登录
		login() {
			request.post(apis.getApi('myLogin'), this.formData, function(res) {
				if (res.code == 0) {
					var timestamp=(new Date()).getTime();
					res.data.access_token.expired_at = timestamp + res.data.access_token.expired_in * 1000;
					uni.setStorage({
						key: getApp().globalData.loginTokenKey,
						data: JSON.stringify(res.data),
						success: function () {
							uni.reLaunch({
								url: "/pages/index/index"
							})
						}
					});
				} else {
					response.error(res.message)
				}
			}, {
				"Content-Type": "application/json"
			})
		},
		
		submit() {
			var _this = this
			_this.$refs.form.validate().then(res=>{
				_this.login();
			}).catch(e =>{
				console.log(e);
			})
		}
	}
}
</script>

<style>
.p-30 {
	padding: 30px;
}
</style>
