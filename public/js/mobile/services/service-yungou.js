// 云购数据
angular.module('YYYG')

.factory('YungouService', ['$http', '$interval', function ($http,$interval) {
	var service = {};

	// 倒计时
	service.countDown = function (timeoutArr, callback) {
		var each = function (arr, callback) {
			for (var i = 0; i < arr.length; i++) {
				callback.call(timeoutArr, i);
			}
		},
		timer = null;
		return function () {

			var finish = [],
			last = new Date(),
			more = 0;
			if (timer) {
				$interval.cancel(timer);
				timer = null;
			}
			timer = $interval(function() {
				
				var now = new Date();
				more = (now - last) > 20 ? parseInt(now-last-20) : 0;
				last = now;
				each(timeoutArr,function(e) {
					if (this[e] <= 0) {
						this[e] = 0;
						if (finish.indexOf(e) == -1) {
							finish.push(e);
							if (finish.length == timeoutArr.length) $interval.cancel(timer);
							callback(e);
						}
					} else {
						if (this[e]-20 < 0) {
							this[e] = 0;
						} else {
							this[e] -= (20+more);
						}
					}
				});
			},20);
		}
	}

	// 获取最新一期的云购
	service.getLatestYungou = function (callback,yungouId) {
		$http.get('/yungou/getLatestYungou?yid='+yungouId)
		.success(function (data) {
			if (data.code == 200) {
				callback.call(null,data.data.id);
			}
		})
		.error(function (data) {
			console.log(data);
		});
	};

	// 根据productId获取最新一期的云购
	service.toLatestYungou = function (callback,productId) {
		$http.get('/yungou/getLatestByProduct?pid='+productId)
		.success(function (data) {
			if (data.code == 200) {
				callback.call(null,data.data.id);
			}
		})
		.error(function (data) {
			console.log(data);
		})
	}

	// 获取开奖后的计算
	service.getCompute = function (callback,yungouId) {
		$http.get('/yungou/compute?id='+yungouId)
		.success(function (data) {
			if (data.code == 200) {
				callback.call(null,data.data);
			}
		})
		.error(function (data) {
			console.log(data);
		});
	};

	// 获取云购的A,B,result,sscTerm
	service.getResult = function (callback,yungouId) {
		$http.get('/yungou/getResult?id='+yungouId)
		.success(function (data) {
			if (data.code === 200) {
				callback.call(null,data.data[0]);
			}
		})
		.error(function (data) {
			console.log(data);
		});
	};


	return service;
}]);