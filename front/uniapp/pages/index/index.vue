<template>
	<view class="container">
		<map id="map" :latitude="latitude" :longitude="longitude" :markers="covers" :scale="scale" show-location="true" @markertap="markertap" @regionchange="regionChange"></map>
	</view>
	<uni-fab :pattern="fab.pattern" :content="fab.content" horizontal="right" vertical="bottom" direction="vertical" @trigger="trigger"></uni-fab>
	<uni-popup ref="popup" type="bottom" background-color="#ffffff">
		<uni-list>
			<uni-list-item :title="popupInfo.title" :thumb="popupInfo.thumb" thumbSize="lg" ellipsis="1" :note="popupInfo.note"></uni-list-item>
		</uni-list>
	</uni-popup>
</template>

<script>
import location from '@/lib/tools/location.js'
import apis from '@/config/api.js';
import request from '@/lib/http/request.js';
import response from '@/lib/http/response.js';
export default {
	data() {
		return {
			scale: 3,
			popupInfo: {},
			reginTimerId: 0,
			showTimerId: 0,
			fab: {
				pattern: {
					color: '#7A7E83',
					backgroundColor: '#fff',
					selectedColor: '#3c3e49',
					buttonColor: '#3c3e49',
					iconColor: '#fff'
				},
				content: [
					{
						iconPath: '/static/icon/location.png',
						selectedIconPath: '/static/icon/location-active.png',
						identify: 'location',
						active: false
					},
					{
						iconPath: '/static/icon/refresh.png',
						selectedIconPath: '/static/icon/refresh-active.png',
						identify: 'refresh',
						active: false
					}
				]
			},
			latitude: 38.902037,
			longitude: -76.998016,
			covers: [],
		}
	},

	onLoad () {
		var _this = this;

		// 显示监听位置变化
		location.watchPosition(function(position) {
			_this.sendLocation(position)
		}, function(error) {
			response.error(error)
		});

		// 显示当前位置
		_this.showLocation();
	},

	onShow() {
		this.initShowTimer();
	},

	onHide() {
		var _this = this;
		if (_this.showTimerId > 0) {
			clearTimeout(_this.showTimerId)
			_this.showTimerId = 0;
		}
	},

	methods: {
		initShowTimer: function() {
			var _this = this;
			if (_this.showTimerId > 0) {
				return;
			}

			_this.showTimer();
		},
		
		showTimer: function() {
			var _this = this;
			_this.showTimerId = setTimeout(function() {
				_this.showTimer();
				_this.getRegion();
			}, 5000)
		},

		trigger: function(e) {
			var _this = this;
			for (var i = 0; i < _this.fab.content.length; i++) {
				if (_this.fab.content[i].identify == e.item.identify) {
					_this.fab.content[i].active = true;
				} else {
					_this.fab.content[i].active = false;
				}
			}

			if (e.item.identify == 'location') {
				_this.showLocation();
			} else if (e.item.identify == 'refresh') {
				uni.reLaunch({
					url: "/pages/index/index",
				});
			}
		},

		// 显示定位
		showLocation: function() {	
			var _this = this
			if (_this.showTimerId > 0) {
				clearTimeout(_this.showTimerId);
				_this.showTimerId = 0;
			}

			if (!_this.mapContext) {
				_this.mapContext = uni.createMapContext("map", _this);
			}
			
			_this.getLocation(function(res) {
				_this.latitude = res.latitude;
				_this.longitude = res.longitude;
				_this.mapContext.moveToLocation({
					latitude: _this.latitude,
					longitude: _this.longitude,
					success: function(res) {
						_this.scale = 16;
						_this.getRegion();
					},
					fail: function() {
						response.error('Located failed');
					}
				});
			});
		},

		regionChange: function(e) {
			var _this = this;
			if (e.type == 'end') {
				if (_this.reginTimerId > 0) {
					clearTimeout(_this.reginTimerId);
				}
				
				_this.reginTimerId = setTimeout(function() {
					_this.getRegion();
				}, 1000);
			}
		},
		
		// 获取显示区域
		getRegion: function(callback) {
			var _this = this
			if (_this.showTimerId > 0) {
				clearTimeout(_this.showTimerId);
				_this.showTimerId = 0;
			}
			
			if (!_this.mapContext) {
				_this.mapContext = uni.createMapContext("map", _this);
			}
			
			_this.mapContext.getRegion({
				success: function (res) {
					request.post(apis.getApi('coordinateList'), {
						southwest: res.southwest, 
						northeast: res.northeast,
					}, function(res) {
						if (res.code != 0) {
							if (_this.showTimerId > 0) {
								clearTimeout(_this.showTimerId);
							}
						} else {
							_this.initShowTimer();
							_this.showMarkers(res.data)
						}
					}, {
						"Content-Type": "application/json"
					})
				},
				fail: function() {
					response.error('Get Region Failed');
				}
			})
		},

		// 显示坐标位置
		showMarkers: function(res) {
			var covers = []
			for (var i = 0; i < res.bus.length; i++) {
				covers.push({
					id: 100000+res.bus[i].id,
					title: res.bus[i].user.username,
					desc: 'school bus',
					latitude: res.bus[i].latitude,
					longitude: res.bus[i].longitude,
					iconPath: '/static/bus.png',
					width: 50,
					height: 50,
				});
			}
			
			for (var j = 0; j < res.station.length; j++) {
				covers.push({
					id: 200000+res.station[j].id,
					title: res.station[j].title,
					desc: 'bus station',
					latitude: res.station[j].latitude,
					longitude: res.station[j].longitude,
					iconPath: '/static/station.png',
					width: 50,
					height: 50
				});
			}
			
			this.covers = covers;
		},
		
		// 获取当前定位
		getLocation: function(callback) {
			response.showLoading('Locating');
			location.getCurrentPosition(function(res) {
				response.hideLoading();
				callback(res);
			}, function(error) {
				response.hideLoading();
				response.error(error);
			});
		},
		
		// 上报坐标位置
		sendLocation: function(position) {
			var _this = this;
			if (getApp().globalData.checkLogin()) {
				try {
					var value = uni.getStorageSync(getApp().globalData.loginTokenKey);
					if (value) {
						var accessToken = JSON.parse(value);
						if (accessToken.user.is_reporting) {
							request.post(apis.getApi('coordinateReport'), {
								latitude: position.latitude, 
								longitude: position.longitude,
							}, function(res) {
								if (res.code != 0) {
									if (_this.reportErrorShowTimers >= 3) {
										_this.reportErrorShowTimers = 0;
										response.error('service unavaliable, report stop')
									} else {
										_this.reportErrorShowTimers++;
									}
								}
							}, {
								"Content-Type": "application/json"
							})
						}
					}
				} catch (e) {
					console.log(e)
				}
			}
		},
		
		markertap: function(e) {
			var _this = this;
			var id = e.detail.markerId;
			for (var i = 0; i < _this.covers.length; i++) {
				var res = JSON.parse(JSON.stringify(_this.covers[i]))
				if (res.id == id) {
					_this.popupInfo = {
						title: res.title,
						thumb: res.iconPath,
						note: res.desc,
					};

					_this.$refs.popup.open('top')
					break;
				}
			}
		}
	}
}
</script>

<style>
uni-page-body, uni-page-body .container, uni-page-body .container uni-map {
	height: 100%;
}

.bg-white {
	background-color: #ffffff;
}

.position-absolute {
	position: absolute;
}

.top-0 {
	top: 0;
}

.bottom-30 {
	bottom: 30px;
}

.bottom-0 {
	bottom: 0;
}

.start-0 {
	left: 0;
}

.end-0 {
	right: 0;
}

.end-20 {
	right: 20px;
}

.z-index-999 {
	z-index: 999;
}

.p-0 {
	padding: 0;
}

.py-10 {
	padding-top: 10px;
	padding-bottom: 10px;
}

.px-15 {
	padding-left: 15px;
	padding-right: 15px;
}

.text-center {
	text-align: center;
}

.btn-circle {
	border-radius: 100%;
	padding: 2px;
}

map {
	width: 100%;
	height: calc(100% - var(--window-top) - var(--window-bottom));
}	
</style>
