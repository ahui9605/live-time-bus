<template>
	<view class="p-30">
		<uni-forms ref="form" :model="formData" :rules="rules" label-position="top">
			<uni-forms-item label="I am" name="role_id" required>
				<uni-data-checkbox v-model="formData.role_id" :localdata="roles" />
			</uni-forms-item>
			<uni-forms-item label="Username" name="username" required>
				<uni-easyinput class="uni-mt-5" prefixIcon="person" type="text" v-model="formData.username" placeholder=""></uni-easyinput>
			</uni-forms-item>
			<uni-forms-item label="Password" name="password" required minimum="8">
				<uni-easyinput class="uni-mt-5" prefixIcon="eye-slash" type="password" v-model="formData.password" placeholder=""></uni-easyinput>
			</uni-forms-item>
			<uni-forms-item label="ConfirmPassword" name="repassword" required>
				<uni-easyinput class="uni-mt-5" prefixIcon="eye-slash" type="password" v-model="formData.repassword" placeholder=""></uni-easyinput>
			</uni-forms-item>
			<uni-forms-item label="Description" name="desc">
				<uni-easyinput type="textarea" v-model="formData.desc" placeholder=""></uni-easyinput>
			</uni-forms-item>
		</uni-forms>
		<button type="primary" @click="submit">Register</button>
	</view>
</template>

<script>
import apis from '@/config/api.js';
import request from '@/lib/http/request.js';
import response from '@/lib/http/response.js';
export default {
	data() {
		return {
			// 表单数据
			formData: {
				role_id: 1,
				username: '',
				password: '',
				repassword: '',
				desc: ''
			},
			roles: [{
				text: 'general user',
				value: 1
			}, {
				text: 'school bus driver',
				value: 2
			}],
			rules: {
				role_id: {
					rules: [
						{
							required: true,
							errorMessage: 'please input your username'
						}
					]
				},
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
				},
				repassword: {
					rules: [
						{
							required: true,
							errorMessage: 'please confirm your password'
						}
					]
				}
			}
		}
	},
	onLoad() {
		if (getApp().globalData.checkLogin()) {
			uni.navigateBack(-1)
		}
	},
	methods: {
		register() {
			request.post(apis.getApi('myRegister'), this.formData, function(res) {
				if (res.code == 0) {
					uni.navigateTo({
						url: "/pages/my/login"
					})
				} else {
					response.error(res.message)
				}
			}, {
				"Content-Type": "application/json"
			})
		},
		
		// 提交表单
		submit() {
			this.$refs.form.validate().then(res=>{
				this.register()
			}).catch(err =>{
				console.log(err);
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
