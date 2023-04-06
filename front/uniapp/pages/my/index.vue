<template>
	<view v-if="isLogin" class="vh-100">
		<view class="mb-10">
			<uni-list>
				<uni-list-item ellipsis="1" :title="userinfo.username" showArrow="true" :note="userinfo.desc" :thumb="userinfo.avatar" thumb-size="lg" :rightText="userinfo.role_name" clickable @click="goto('/pages/my/edit')"></uni-list-item>
			</uni-list>
		</view>
		<view class="mb-10" v-if="userinfo.role_id == 2">
			<uni-list>
				<uni-list-item title="Start Reporting" note="When the switch is opened, your coordinate will be reported to the server" showSwitch="true" :switchChecked="userinfo.is_reporting == 1" @switchChange="startReporting"></uni-list-item>
				<uni-list-item title="Mark Station" note="Report the coordinate of the station, the station will be shown on the map" showArrow link to="/pages/station/mark"></uni-list-item>
			</uni-list>
		</view>
		<view class="px-10">
			<button type="default" @click="logout">Logout</button>
		</view>
	</view>
	<view v-if="!isLogin" class="d-flex flex-column align-items-center justify-content-center vh-100">
		<uni-row class="w-100" gutter="30">
			<uni-col :xs="{span: 20, push: 2}" :sm="{span: 16, push: 4}" :md="{span: 10, push: 2}" :lg="{span: 8, push: 4}" :xl="{span: 6, push: 6}">
				<button type="primary" @click="goto('/pages/my/login')">Login</button>
			</uni-col>
			<uni-col :xs="{span: 20, push: 2}" :sm="{span: 16, push: 4}" :md="{span: 10, push: 2}" :lg="{span: 8, push: 4}" :xl="{span: 6, push: 6}">
				<button type="primary" @click="goto('/pages/my/register')">Register</button>
			</uni-col>
		</uni-row>
	</view>
</template>

<script>
import apis from '@/config/api.js';
import request from '@/lib/http/request.js';
import response from '@/lib/http/response.js';
export default {
	data() {
		return {
			isLogin: false,
			userinfo: {}
		}
	},
	onShow() {
		var _this = this
		if(getApp().globalData.checkLogin()) {
			_this.isLogin = true;
			request.get(apis.getApi('myProfile'), null, function(res) {
				if (res.code == 0) {
					_this.userinfo = res.data
				} else {
					response.error(res.message)
				}
			}, null)
		}
	},
	methods: {
		goto: function(url) {
			uni.navigateTo({
				url:url
			})
		},
		
		startReporting: function(e) {
			var isReporting = e.value ? 1 : 0;
			request.post(apis.getApi('myEdit'), {is_reporting: isReporting}, function(res) {
				if (res.code == 0) {
					let value = uni.getStorageSync(getApp().globalData.loginTokenKey);
					if (value) {
						var accessToken = JSON.parse(value);
						accessToken.user.is_reporting = isReporting;
						uni.setStorageSync(getApp().globalData.loginTokenKey, JSON.stringify(accessToken))
					}
				} else {
					response.error(res.message)
				}
			}, {
				"Content-Type": "application/json"
			})
		},
		
		// 退出
		logout: function() {
			request.post(apis.getApi('myLogout'), null, function(res) {
				if (res.code == 0) {
					try {
						uni.clearStorageSync()
						uni.reLaunch({
							url: '/pages/index/index',
						})
					} catch (e) {
						response.error(e)
					}
				} else {
					response.error(res.message)
				}
			}, {
				"Content-Type": "application/json"
			})
		}
	}
}
</script>

<style>
uni-page-body {
	background-color: #efefef;
	height: 100%;
}

.position-absolute {
	position: absolute;
}

.start-10 {
	left: 10px;
}

.end-10 {
	right: 10px;
}

.bottom-30 {
	bottom: 30px;
}

.vh-100 {
	height: 100%;
}

.w-100 {
	width: 100%;
} 

.align-items-center {
	align-items: center;
}

.justify-content-center {
	justify-content: center;
}

.justify-content-between {
	justify-content: space-between;
}

.text-align-right {
	text-align: right;
}

.m-auto {
	margin: auto auto;
}

.mb-10 {
	margin-bottom: 10px;
}

.mb-5 {
	margin-bottom: 5px;
}

.me-5 {
	margin-right: 5px;
}

.ms-5 {
	margin-left: 5px;
}

.me-15 {
	margin-right: 15px;
}

.ms-15 {
	margin-left: 15px;
}

.p-20 {
	padding: 20px;
}

.px-10 {
	padding-left: 10px;
	padding-right: 10px;
}

.py-30 {
	padding-top: 30px;
	padding-bottom: 30px;
}

.d-flex {
	display: flex;
}

@media screen and (max-width:767px) {
	.uni-row .uni-col {
		margin-bottom: 15px !important;
	}
	
	.avatar {
		width: 80px !important; 
		height: 80px !important;
	}
	
	.mb-xs-10 {
		margin-bottom: 10px;
	}
	
	.text-align-xs-left {
		text-align: left !important;
	}
}

@media screen and (min-width: 767px) and (max-width: 1023px) {
	.uni-row .uni-col {
		margin-bottom: 20px !important;
	}
	
	.avatar {
		width: 100px !important; 
		height: 100px !important;
	}
}

@media screen and (min-width: 1023px) {
	.avatar {
		width: 150px !important; 
		height: 150px !important;
	}
}
</style>
