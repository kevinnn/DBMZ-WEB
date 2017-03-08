// TAB - 晒单
angular.module('YYYG')

// 晒单分享
.controller('ShowCtrl', ['$scope', '$rootScope', '$http', '$stateParams', function($scope, $rootScope, $http, $stateParams) {
    $scope.pid = $stateParams.pid;
    $scope.lastID = 0;
    $scope.shareInfos = [];
    $scope.init = false;
    $scope.perAmount = 5;
    $scope.hasMoreData = true;

    // 获取晒单
    $rootScope.showLoading('加载中..');
    if (typeof $scope.pid === 'undefined') {
        $scope.url = "/show/getAll?";
    } else {
        $scope.url = "/show/getByProduct?productId=" + $scope.pid + "&";
    }
    $http.get($scope.url + 'limit=' + $scope.perAmount).success(function(data) {
        if (data.code === 200 && data.data.length > 0) {
            $scope.shareInfos = data.data;
            $scope.firstID = $scope.shareInfos[0].showId;
            $scope.lastID = $scope.shareInfos[$scope.shareInfos.length - 1].showId;
            if (data.data.length < $scope.perAmount) {
                $scope.hasMoreData = false;
            }
        } else {
            $scope.hasMoreData = false;
        }
    }).error(function(data) {
        console.error(data);
    }).finally(function() {
        $rootScope.hideLoading();
        $scope.init = true;
    });

    // 下拉刷新 
    $scope.doRefresh = function() {
        $http.get($scope.url + 'firstId=' + $scope.firstID + '&limit=' + $scope.perAmount).success(function(newItems) {
            if (newItems.code === 200) {
                if (newItems.data.length < $scope.perAmount) {
                    $scope.hasMoreData = false;
                }
                newItems.data.forEach(function(elem) {
                    $scope.shareInfos.unshift(elem);
                });
                $scope.firstID = $scope.shareInfos[0].showId;
            }
        })
            .finally(function() {
                $scope.$broadcast('scroll.refreshComplete');
            });
    };

    // 上拉载入
    $scope.loadMore = function() {
        if (!$scope.hasMoreData) {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            return;
        }
        if ($scope.lastID === 0) {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            return;
        }
        $http.get($scope.url + 'lastId=' + $scope.lastID + '&limit=' + $scope.perAmount).success(function(items) {
            if (items.code === 200 && items.data.length > 0) {
                items.data.forEach(function(elem) {
                    $scope.shareInfos.push(elem);
                    $scope.lastID = $scope.shareInfos[$scope.shareInfos.length - 1].showId;
                });
                if (items.data.length < $scope.perAmount) {
                    $scope.hasMoreData = false;
                }
                $scope.$broadcast('scroll.infiniteScrollComplete');
            } else {
                $scope.hasMoreData = false;
            }
        });
    };
}])

// 晒单详情
.controller('ShowDetailCtrl', ['$scope', '$rootScope', '$http', '$stateParams', '$ionicHistory', function($scope, $rootScope, $http, $stateParams, $ionicHistory) {
    $scope.sid = $stateParams.sid;
    $http.get('/show/getDetail?showId=' + $scope.sid).success(function(data) {
        if (data.code === 200) {
            $scope.showInfo = data.data;
            console.log($scope.showInfo);
        } else {
            $ionicHistory.goBack();
        }
    }).error(function(data) {
        console.error(data);
    });
}])

;