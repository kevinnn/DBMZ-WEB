// TAB - 最新揭晓
angular.module('YYYG')

// 最新揭晓
.controller('ResultCtrl', ['$scope', '$rootScope', '$http', '$interval', 'YungouService', function($scope, $rootScope, $http, $interval, YungouService) {
	$scope.hasMoreData = true;
	$scope.isRefresh = false;
	$scope.isLoad = false;
	$scope.remainRecords = [];
    $scope.finishRecords = [];
    $scope.timeoutArr = [];
    $scope.init = false;
    $scope.start = function () {
    	(YungouService.countDown($scope.timeoutArr, function(e) {
    		$scope.timer = $interval(function () {
				$http.get('/order/winOrder?yungouId='+$scope.remainRecords[e].yungouId)
				.success(function (data) {
					if (data.code == 200) {
						if (data.data.length == 1) {
							$scope.remainRecords[e].winUser = data.data[0];
							$interval.cancel($scope.timer);
						}
					}
				})
				.error(function (data) {
					console.log(data);
				})
			},1000);
		}))();
    }
    $scope.getResult = function () {
	    var url = '/yungou/fastStart?limit=10';
	    if (typeof $scope.startTime !== 'undefined') {
	    	url += '&startTime='+$scope.startTime;
	    } else if (!$scope.init) {
		    $rootScope.showLoading('加载中..');
	    }
	    $http.get(url)
	    .success(function (data) {
	    	if (data.code == 200) {
	    		if ($scope.isRefresh) {
	    			$scope.remainRecords = [];
			    	$scope.finishRecords = [];
			    	$scope.timeoutArr = [];
			    	$scope.start();
	    		}
	    		data = data.data;
	    		for(var i=0;i<data.length;i++) {
	    			if (data[i].status==1) {
	    				$scope.remainRecords.push(data[i]);
	    				$scope.timeoutArr.push(data[i].timeout*1000);
	    			} else {
	    				$scope.finishRecords.push(data[i]);
	    			}
	    		}
	    		data.length > 0 && ($scope.startTime=data[data.length-1].startTime);
	    		if (data.length < 10) {
	    			$scope.hasMoreData = false;
	    		} else {
	    			$scope.hasMoreData = true;
	    		}
	    	}
	    })
	    .error(function (data) {
	        console.log(data);
	    })
	    .finally(function () {
	        $rootScope.hideLoading();
	        if ($scope.isRefresh) {
	        	$scope.isRefresh = false;
		        $scope.$broadcast('scroll.refreshComplete');
	        }
	        if ($scope.isLoad) {
	        	$scope.isLoad = false;
	            $scope.$broadcast('scroll.infiniteScrollComplete');
	        }
	        $scope.init = true;
	    });
	};
	$scope.start();
	$scope.getResult();
    $scope.doRefresh = function() {
    	$scope.startTime = undefined;
    	$scope.hasMoreData = true;
    	$scope.isRefresh = true;
    	$scope.getResult();
    };
    $scope.loadMoreData = function () {
    	$scope.isLoad = true;
    	$scope.getResult();
    }
}]);