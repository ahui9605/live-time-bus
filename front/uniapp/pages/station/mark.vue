<template>
	<view class="p-30">
		<uni-forms ref="form" :modelValue="formData" :rules="rules" label-position="top" label-width="120">
			<uni-forms-item label="Station Name" name="title" required>
				<uni-easyinput v-model="formData.title" placeholder="please input station name" />
			</uni-forms-item>
			<uni-forms-item label="Coordinate" name="coordinate" required>
				<uni-easyinput v-model="formData.coordinate" placeholder="please input station coordinate"/>
			</uni-forms-item>
		</uni-forms>
		<button type="primary" @click="submit">Submit</button>
	</view>
</template>

<script>
import apis from '@/config/api.js';
import request from '@/lib/http/request.js';
import response from '@/lib/http/response.js';
export default {
	data() {
		return {
			formData: {},
			rules: {
				title: {
					rules: [
						{
							required: true,
							errorMessage: 'please input the station name'
						},
						{
							maxLength: 90,
							errorMessage: 'the station name`s length must lower than 90'
						}
					]
				},
				coordinate: {
					rules: [
						{
							required: true,
							errorMessage: 'please input station coordinate'
						}
					]
				}
			}
		}
	},

	onShow() {
		if (!getApp().globalData.checkLogin()) {
			uni.navigateBack(-1)
			return;
		}
	},
	
	methods: {
		submit: function() {
			var _this = this;
			_this.$refs.form.validate().then(res=>{
				request.post(apis.getApi('stationStore'), _this.formData, function(res) {
					if (res.code == 0) {
						response.success(res.message)
						uni.navigateBack(-1);
					} else {
						response.error(res.message);
					}
				}, {
					"Content-Type": "application/json"
				})
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
