<template>
	<view class="p-30">
		<uni-forms ref="form" :model="formData" :rules="rules" label-position="top">
			<uni-forms-item label="Avatar" name="avatar">
				<uni-file-picker
					v-model="formData.avatar"
					fileMediatype="image" 
					file-extname="jpg,png" 
					limit=1 
					mode="grid" 
					@select="select" 
					@progress="progress" 
					@success="success" 
					@fail="fail" 
				/>
			</uni-forms-item>
			<uni-forms-item label="Description" name="desc">
				<uni-easyinput type="textarea" v-model="formData.desc" placeholder=""></uni-easyinput>
			</uni-forms-item>
		</uni-forms>
		<button type="primary" @click="submit">Update</button>
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
				avatar: [],
				desc: ''
			}
		}
	},
	onShow() {
		var _this = this
		if (getApp().globalData.checkLogin()) {
			request.get(apis.getApi('myProfile'), null, function(res) {
				if (res.code == 0) {
					var avatar = new Array();
					avatar.push({
						name: 'avatar',
						extname: 'png',
						url: res.data.avatar
					})
					
					_this.formData.avatar = avatar
					_this.formData.desc = res.data.desc
				} else {
					response.error(res.message)
				}
			}, null)
		} else {
			uni.navigateBack(-1)
		}
	},
	methods: {
		// 获取上传状态
		select(e) {
			var _this = this
			if (e.tempFiles[0].size / (1024 * 1024) > 2) {
				response.error('the image size over 2M')
				return false;
			}
			
			var tempFilePath = e.tempFilePaths[0];
			request.upload(apis.getApi('upload'), tempFilePath, function(res) {
				if (res.code == 0) {
					_this.formData.avatar.push({
						name: res.data.filename,
						extname: res.data.ext,
						url: res.data.filepath
					})
				} else {
					response.error(res.message)
				}
			}, {type: 'image'})
		},
		
		// 获取上传进度
		progress(e) {
			response.loading(e.progress)
		},
		
		// 上传成功
		success(e) {
			response.success('success')
		},
		
		// 上传失败
		fail(e) {
			response.error(e.errorMessage)
		},
		
		edit() {
			request.post(apis.getApi('myEdit'), this.formData, function(res) {
				if (res.code == 0) {
					uni.switchTab({
						url: "/pages/my/index"
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
				this.edit()
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
