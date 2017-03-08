// TAB - 购物车
angular.module('YYYG')

// 购物车
.controller('CartCtrl', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http) {

	//删除一件商品
	$scope.deleteOneItem = function(index) {
		if (confirm('确定要删除该商品吗?')) {
			if ($rootScope.user) {
				$http.post('/shoppingCart/remove', {
					"id": $rootScope.shoppingCarts[index].shoppingCartId
				}, {
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
					},
					transformRequest: function(data) {
						return $.param(data);
					}
				}).then(function(response) {

					if (response.data.code === 200) {
						$rootScope.shoppingCarts.splice(index, 1);
					}
					$rootScope.setCartCount();

				}, function(response) {
					console.log(response);
				});
			} else {
				$rootScope.shoppingCarts.splice(index, 1);
				if ($rootScope.shoppingCarts.length > 0) {
					window.localStorage.setItem('localCarts', JSON.stringify($rootScope.shoppingCarts));
				} else {
					window.localStorage.removeItem('localCarts');
				}
				$rootScope.setCartCount();
			}
		}
	};

	//下拉刷新 
	$scope.doRefresh = function() {
		$http.get('/shoppingCart/intoCart')
			.success(function(data) {
				if (data.code === 200) {
					$rootScope.shoppingCarts = data.data;
					$rootScope.setCartCount();
				}
			})
			.error(function(data) {
				console.log(data);
			})
			.finally(function() {
				$scope.$broadcast('scroll.refreshComplete');
			});
	}

	//加减数量
	$scope.addOne = function(index) {
		if ($rootScope.shoppingCarts[index].amount ==
			parseInt($rootScope.shoppingCarts[index].price - $rootScope.shoppingCarts[index].saleCount)) {
			return;
		}
		$rootScope.shoppingCarts[index].amount = parseInt($rootScope.shoppingCarts[index].amount) + parseInt($rootScope.shoppingCarts[index].singlePrice);
	}

	$scope.minusOne = function(index) {
		if ($rootScope.shoppingCarts[index].amount > $rootScope.shoppingCarts[index].singlePrice) {
			$rootScope.shoppingCarts[index].amount -= $rootScope.shoppingCarts[index].singlePrice;
		}
	}

	//输入框限制
	$scope.checkInputValue = function(index) {
		if (isNaN($rootScope.shoppingCarts[index].amount) ||
			$rootScope.shoppingCarts[index].amount < $rootScope.shoppingCarts[index].singlePrice) {
			$rootScope.shoppingCarts[index].amount = $rootScope.shoppingCarts[index].singlePrice;
		} else if ($rootScope.shoppingCarts[index].amount >
			parseInt($rootScope.shoppingCarts[index].price - $rootScope.shoppingCarts[index].saleCount)) {
			$rootScope.shoppingCarts[index].amount =
				$rootScope.shoppingCarts[index].price - $rootScope.shoppingCarts[index].saleCount;
		} else if ($rootScope.shoppingCarts[index].amount % $rootScope.shoppingCarts[index].singlePrice !== 0) {
			$rootScope.shoppingCarts[index].amount -= $rootScope.shoppingCarts[index].amount % $rootScope.shoppingCarts[index].singlePrice;
		} else {
			$rootScope.shoppingCarts[index].amount = Math.floor($rootScope.shoppingCarts[index].amount);
		}
	}

	//获取所有商品的总价
	$scope.getTotal = function() {
		var total = 0;
		for (var i = 0; i < $rootScope.shoppingCarts.length; i++) {
			total += parseInt($rootScope.shoppingCarts[i].amount);
		}
		return total;
	}

	//获取商品的件数
	$scope.getAmount = function() {
		return $rootScope.shoppingCarts.length;
	}

	$scope.addCashier = function () {
		if ($rootScope.user) {
			$rootScope.showLoading('结算中..');
			var data = [];
			for (var i = 0,len=$rootScope.shoppingCarts.length; i < len; i++) {
				data.push({
				   productId: $rootScope.shoppingCarts[i].productId,
				   yungouId: $rootScope.shoppingCarts[i].yungouId,
				   count: $rootScope.shoppingCarts[i].amount
				});
			}
			$http.post('/cashier/add', {
				orders: data
			}, {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
				},
				transformRequest: function(data) {
					return $.param(data);
				}
			})
			.success(function(data, status, headers, config) {
				if (data.code == 200) {
					$rootScope.shoppingCarts = [];
					$rootScope.setCartCount();
					location.hash = "#tab/cart/payment-"+data.data;
				}
			})
			.error(function (data) {
				console.log(data);
			})
			.finally(function () {
				$rootScope.hideLoading();
			})
		} else {
			$rootScope.checkLoginAndModal();
		}
	}
}])

// 购物车 - 支付详情
.controller('PaymentCtrl', ['$scope', '$rootScope', '$http', '$stateParams', '$interval', '$ionicHistory', function($scope,$rootScope,$http,$stateParams,$interval,$ionicHistory) {
	if ($rootScope.user) {
		$scope.user = $rootScope.user;
	} else {
		location.hash = "#/tab/index";
		location.reload();
	}
	$scope.cashierid = $stateParams.cashierid;
	if (typeof $scope.cashierid === 'undefined') {
		location.hash = "#/tab/index";
		location.reload();
	}
	$scope.records = [];
	$scope.timeout = 0;
	$scope.count = 0;
	$scope.balancePay = 0;
	$scope.balanceBtnCheck = false;
	$scope.wxHide = false;
	$rootScope.showLoading('加载中');
	$http.get('/cashier/getCashier?cashierid='+$scope.cashierid)
	.success(function(data) {
		if (data.code === 200) {
			if (data.data.length == 0 || data.data[0].isPay == 1) {
				location.hash = "#/tab/index";
				location.reload();
			}
			$scope.records = data.data;
			if ($scope.records.length > 0) {
				$scope.timeout = $scope.records[0].timeout;
			}
			for(var i = 0; i < $scope.records.length; i++) {
				$scope.count += parseInt($scope.records[i].count);
			}
			$interval(function () {
				if ($scope.timeout <= 0) {
					location.hash = "#/tab/index";
					location.reload();
				}
				$scope.timeout--;
			},1000);
		}
	})
	.error(function(data) {
		console.log(data);
	})
	.finally(function () {
		$rootScope.hideLoading();
	});
	$scope.chooseBalance = function () {
		if ($scope.balanceBtnCheck) {
			$scope.balanceBtnCheck = false;
			$scope.balancePay = 0;
			$scope.wxHide = false;
		}
		else if (!$scope.balanceBtnCheck && $scope.user.balance > 0) {
			$scope.balanceBtnCheck = true;
			if ($scope.user.balance >= $scope.count) {
				$scope.balancePay = $scope.count;
				//隐藏微信支付
				$scope.wxHide = true;
			} else {
				$scope.balancePay = $scope.user.balance;
			}
		}
	}
	$scope.pay = function() {
		$rootScope.showLoading('等待中..');
		if ($scope.count - $scope.balancePay > 0) {
			$rootScope.hideLoading();
			window.location.href = '/views/mobile/payment.php?cashierid='+$scope.cashierid+'&fee=' + 100*($scope.count-$scope.balancePay).toFixed(2);
		} else {
			//全部余额支付
			$http.post('/pay', {
				cashierid:$scope.cashierid
			}, {
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
				},
				transformRequest: function(data) {
					return $.param(data);
				}
			})
			.success(function (data) {
				window.location.href = '#/tab/cart/payresult-' + $scope.cashierid + '-' + 100*($scope.count-$scope.balancePay).toFixed(2) + '-0';
			})
			.error(function (data) {
				console.log(data);
			})
			.finally(function () {
				$rootScope.hideLoading();
			})
		}
	}
	// 支付详情后退
	$scope.exit = function () {
		var exitConfirm = confirm('您的订单尚未支付，确定要终止支付操作吗？');
		if (exitConfirm == true) {
			$ionicHistory.goBack();
		}
	}
}])

// 购物车 - 支付结果
.controller('PayresultCtrl', ['$scope', '$rootScope', '$http', '$stateParams', '$interval', function($scope, $rootScope, $http, $stateParams,$interval) {
	$scope.init = false;
	$scope.cashierid = $stateParams.cashierid;
	$scope.fee = $stateParams.fee;
	$scope.isCancel = $stateParams.isCancel;
	$scope.showNumbers = function (title,term,start,end) {
		$rootScope.title = title;
		$rootScope.term = term;
		$rootScope.numbers = [];
		for (; start <= end; start++) {
			$rootScope.numbers.push(start);
		}
		$rootScope.numberModal.show();
	}
	$http.get('/user/isLogin').success(function(data) {
		if (data.code === 200) {
			$rootScope.user = data.data[0];
		} else {
			location.hash = "#/tab/index";
			location.reload();
		}
	});
	if (typeof $scope.cashierid === 'undefined') {
		location.hash = "#/tab/index";
		location.reload();
	}
	$scope.successOrders = [];
	$scope.failOrders = [];
	$scope.successCount = 0;
	$rootScope.showLoading('加载中..');
	$scope.timer = $interval(function () {
		$http.get('/cashier/getDetailMobile?cashierid='+$scope.cashierid)
		.success(function (data) {
			if (data.code == 200) {
				if (!$scope.init && ($scope.isCancel==1 || (data.data.length > 0 && data.data[0].payStatus == 2))) {
					$interval.cancel($scope.timer);
					$scope.isPay = data.data.length > 0 ? 1:0;
					data = data.data;
					for (var i = 0,len=data.length; i < len; i++) {
						if (data[i].status == 1) {
							$scope.successOrders.push(data[i]);
							$scope.successCount += parseInt(data[i].count);
						} else {
							$scope.failOrders.push(data[i]);
						}
					}
					$rootScope.hideLoading();
					$scope.init = true;
				}

			}
		})
		.error(function (data) {
			console.log(data);
		});
	},2000);

	//重新支付
	$scope.repay = function () {
		location.href = "/views/mobile/payment.php?cashierid="+$scope.cashierid+"&fee="+$scope.fee;
	};
	//继续夺宝,返回首页
	$scope.backToIndex = function () {
		location.href = "/mobile.html#/tab/index";
		location.reload();
	}
	//查看记录,返回个人中心
	$scope.backToProfile = function (type) {
		location.href = "/mobile.html#/tab/profile?location="+type;
		location.reload();
	}
}])

;