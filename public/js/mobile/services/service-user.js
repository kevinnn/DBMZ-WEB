// 用户数据
angular.module('YYYG')

.factory('UserService', ['$http', '$interval', function ($http,$interval) {
	var service = {};

	// 是否签到
	service.checkSignIn = function (callback) {
		if (typeof service.isSignIn != 'undefined') {
			callback();
		} else {
			$http.get('/credit/isSignIn').success(function(data){
				if (data.code === 200) {
					service.isSignIn = true;
				} else {
					service.isSignIn = false;
				}
			}).finally(function(){
				callback();
			});
		}
	};

	// 签到
	service.signIn = function (callback) {
		var credit = 0;
		if (service.isSignIn) {
			callback(credit);
		} else {
			$http.get('/credit/signIn')
			.success(function (data) {
				if (data.code == 200) {
					service.isSignIn = true;
					credit = 10;
				}
			})
			.error(function (data) {
				console.log(data);
			})
			.finally(function () {
				callback(credit);
			})
		}
	};

	service.rechargeReady = function (callback,tradeId) {
		var timer = null;
		timer = $interval(function () {
			$http.get('/balance/getByTradeId?tradeId='+tradeId)
			.success(function (data) {
				if (data.code == 200) {
					$interval.cancel(timer);
					callback();
				}
			})
			.error(function (data) {
				console.log(data);
			})
		},2000)
	};

	//获取余额明细
	service.getBalance = function (callback,limit,page) {
		$http.get('/balance/getBalanceByUser?limit='+limit+'&page='+page)
		.success(function (data) {
			if (data.code ==200) {
				callback.call(null,data.data);
			}
		})
		.error(function (data) {
			console.log(data);
		})
	}

	//获取积分明细
	service.getCredit = function (callback,limit,page) {
		$http.get('/credit/getRecord?limit='+limit+'&page='+page)
		.success(function (data) {
			if (data.code ==200) {
				callback.call(null,data.data);
			}
		})
		.error(function (data) {
			console.log(data);
		})
	}

	service.wx = function (user) {
		var callback = function () {
		};
		if (arguments.length == 2) {
			callback = [].pop.call(arguments);
		}
		$http.get('/wx/getJsApiConfig?url='+encodeURIComponent(location.href.split('#')[0]))
	    .success(function (data) {
	        if (data.code == 200) {
	            wx.config({
	                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	                appId: data.data.appId, // 必填，公众号的唯一标识
	                timestamp: data.data.timestamp, // 必填，生成签名的时间戳
	                nonceStr: data.data.nonceStr, // 必填，生成签名的随机串
	                signature: data.data.signature,// 必填，签名，见附录1
	                jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	            });
	            wx.checkJsApi({
	                jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'], // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
	                success: function(res) {
	                    // 以键值对的形式返回，可用的api值true，不可用为false
	                    // 如：{"checkResult":{"chooseImage":true},"errMsg":"checkJsApi:ok"}
	                }
	            });
	            wx.ready(function(){
	                wx.onMenuShareTimeline({
	                    title: user.userName+'分享了1元红包，你也可以领取参与收获惊喜！', // 分享标题
	                    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5978bb99a4b31667&redirect_uri=http%3a%2f%2ftest.dangke.co%2fwx%2foauthwithcode&response_type=code&scope=snsapi_base&state='+user.code+'#wechat_redirect', // 分享链接
	                    imgUrl: 'http://7xs9hx.com1.z0.glb.clouddn.com/champion.jpg', // 分享图标
	                    success: function () {
	                        // 用户确认分享后执行的回调函数
	                        callback();
	                    },
	                    cancel: function () {
	                        // 用户取消分享后执行的回调函数
	                    }
	                });
	                wx.onMenuShareAppMessage({
	                    title: user.userName+'送你1元红包，领取夺宝惊喜无限！', // 分享标题
	                    desc: '只需花一元就有机会获得梦想奖品，勇夺武林萌主，尽享"逆袭"激情！', // 分享描述
	                    link: 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5978bb99a4b31667&redirect_uri=http%3a%2f%2ftest.dangke.co%2fwx%2foauthwithcode&response_type=code&scope=snsapi_base&state='+user.code+'#wechat_redirect', // 分享链接
	                    imgUrl: 'http://7xs9hx.com1.z0.glb.clouddn.com/champion.jpg', // 分享图标
	                    type: 'link', // 分享类型,music、video或link，不填默认为link
	                    dataUrl: '', // 如果type是music或video，则要提供数据链接，默认为空
	                    success: function () { 
	                        // 用户确认分享后执行的回调函数
	                        callback();
	                    },
	                    cancel: function () { 
	                        // 用户取消分享后执行的回调函数
	                    }
	                });
	            });
	        }
	    })
	    .error(function (data) {
	        console.log(data);
	    });

	}

	service.getHbRecords = function (callback) {
		$http.get('/user/getHbRecords')
		.success(function (data) {
			console.log(data);
			if (data.code == 200) {
				callback.call(null,data.data);
			}
		})
		.error(function (data) {
			console.log(data);
		})
	}

	return service;
}]);