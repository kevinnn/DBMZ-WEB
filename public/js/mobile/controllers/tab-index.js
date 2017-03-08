// TAB - 首页
angular.module('YYYG')

// 首页控制器
.controller('IndexCtrl', ['$scope', '$rootScope', '$http', '$ionicSlideBoxDelegate', function($scope, $rootScope, $http, $ionicSlideBoxDelegate) {
    // 获取轮播数据
    $http.get('/banner/getAll').success(function(data) {
        if (data.code === 200) {
            $rootScope.banners = data.data;
            $ionicSlideBoxDelegate.update();
        }
    }).error(function(data) {
        console.error(data);
    });
    // 下拉刷新
    $scope.doRefresh = function() {
        if ($rootScope.current_category === 0) {
            $http.get('/yungou/getAll').success(function(data) {
                if (data.code === 200) {
                    $rootScope.yungous = data.data;
                    if ($rootScope.current_sort == 3) {
                        $rootScope.isUp = !$rootScope.isUp;
                        $rootScope.isDown = !$rootScope.isDown;
                    }
                    $rootScope.selectSort($rootScope.current_sort);
                }
            }).finally(function() {
                $scope.$broadcast('scroll.refreshComplete');
            });
        } else {
            $http.get('/yungou/getCategory?id=' + $rootScope.current_category).success(function(data) {
                if (data.code === 200) {
                    $rootScope.yungous = data.data;
                    if ($rootScope.current_sort == 3) {
                        $rootScope.isUp = !$rootScope.isUp;
                        $rootScope.isDown = !$rootScope.isDown;
                    }
                    $rootScope.selectSort($rootScope.current_sort);
                }
            }).finally(function() {
                $scope.$broadcast('scroll.refreshComplete');
            });
        }
    };
    // 排序 0-最新 1-最热 2-进度 3-总需人次 TODO with bug
    $rootScope.current_sort = 0;
    $rootScope.isUp = false;
    $rootScope.isDown = false;
    $rootScope.selectSort = function(type) {
        if ($rootScope.current_sort !== type) {
            $rootScope.current_sort = type;
            $rootScope.sort();
            $rootScope.isUp = false;
            $rootScope.isDown = false;
        }
        if (type == 3) {
            if (!$rootScope.isUp) {
                $rootScope.isUp = true;
                $rootScope.isDown = false;
            } else {
                $rootScope.isUp = false;
                $rootScope.isDown = true;
            }
            $rootScope.sort();
        }
    };
}])

// 商品云购页面
.controller('YungouCtrl', ['$scope', '$rootScope', '$http', '$stateParams', '$ionicHistory', '$ionicSlideBoxDelegate', '$interval', '$ionicModal', 'YungouService', function($scope, $rootScope, $http, $stateParams, $ionicHistory, $ionicSlideBoxDelegate,$interval,$ionicModal,YungouService) {
    $rootScope.cartDetailCount.textContent = $rootScope.shoppingCarts.length;
    $($rootScope.cartDetailCount).css({
        position:'absolute',
        top: '2px',
        fontSize: '12px',
        lineHeight: '8px',
        color: '#fff',
        right: '8px',
        background: '#DF3051',
        borderRadius: '50%',
        zIndex: '9999',
        padding: '5px'
    });
    $('ion-view.yungou-page').append($rootScope.cartDetailCount);
    $scope.yid = $stateParams.yid;
    $scope.orderPerTime = 5;
    $scope.hasMoreData = true;
    $scope.myOrders = [];
    $scope.timeoutArr = [];
    $scope.numbersArr = [];
    $scope.toLatestYungou = function () {
        YungouService.getLatestYungou(function (id) {
            location.hash = "#tab/"+$rootScope.activedTab+'/yungou-'+id+'-'+$rootScope.timeStamp;
        },$scope.yid);
    }
    YungouService.getLatestYungou(function (id) {
        $scope.lastId = id;
    },$scope.yid)
    $scope.toIndex = function () {
        location.hash = "#/tab/index";
        location.reload();
    }
    $rootScope.showLoading('加载中..');
    $scope.start = function () {
        (YungouService.countDown($scope.timeoutArr, function(e) {
            $scope.yungou.status = 2;
            $scope.timer = $interval(function () {
                $http.get('/yungou/getWin?id=' + $scope.yungou.id).success(function(data) {
                    $scope.winner = data.data;
                    $interval.cancel($scope.timer);
                }).error(function(data) {
                    console.error(data);
                });
            },1000);
        }))();
    }
    // 获取云购信息
    $scope.getYungou = function () {

        $http.get('/yungou/getByYungou?yid=' + $scope.yid).success(function(data) {
            if (data.code === 200) {
                $scope.product = data.data.product;
                $scope.yungou = data.data.yungou;
                $ionicSlideBoxDelegate.update();
                $rootScope.hideLoading();
                // 获取获奖者
                if ($scope.yungou.status == 2) {
                    $http.get('/yungou/getWin?id=' + $scope.yungou.id).success(function(data) {
                        $scope.winner = data.data;
                    }).error(function(data) {
                        console.error(data);
                    });
                } else if ($scope.yungou.status == 1) {
                    $scope.start();
                    $scope.timeoutArr.push($scope.yungou.timeout*1000);
                }
                $scope.showContent = function() {
                    $rootScope.content = $scope.product.content;
                    $rootScope.contentModal.show();
                };
            } else {
                $ionicHistory.goBack();
                $rootScope.hideLoading();
            }
        }).error(function(data) {
            console.error(data);
        });
    }
    $scope.showNumbers = function (title,term,result) {
        $scope.createModal(function () {
            $scope.result = result;
            $scope.title = title;
            $scope.term = term;
            $scope.numbers = $scope.numbersArr;
            $scope.numberModal.show();
        });
    }
    $scope.createModal = function (callback) {
        $ionicModal.fromTemplateUrl('/public/tpls/mobile/modal/modal-record-number.html', {
            scope: $scope
        }).then(function(numberModal) {
            $scope.numberModal = numberModal;
            callback();
        });
    }
    $scope.showWinnerNumber = function (title,term,userId,result) {
        $scope.createModal(function () {
            $http.get('/order/getNumbers?id='+$scope.yid+'&userId='+userId)
            .success(function (data) {
                if (data.code == 200) {
                    var numbersArr = data.data.numbers,
                        numbers = [];
                    $scope.result = result;
                    $scope.title = title;
                    $scope.term = term;
                    numbersArr.forEach(function(elem) {
                        for (var i = elem.numberStart, len = elem.numberEnd; i <= len; i++) {
                            numbers.push(i);
                        }
                    });
                    $scope.numbers = numbers;
                    $scope.numberModal.show();
                }
                
            })
            .error(function (data) {
                console.log(data);
            })
            .finally(function () {
            });
        });
    }
    $scope.getYungou();
    $http.get('/user/isLogin').success(function(data) {
        if (data.code === 200) {
            // 获取登录用户的云购参与情况
            $http.get('/order/getNumbers?id=' + $scope.yid + "&userId=" + data.data[0].id).success(function(data) {
                $scope.count = data.data.count;
                if (data.code === 200 && data.data.count > 0) {
                    data.data.numbers.forEach(function(elem) {
                        for (var i = elem.numberStart, len = elem.numberEnd; i <= len; i++) {
                            $scope.numbersArr.push(i);
                        }
                    });
                }
            }).error(function(data) {
                console.error(data);
            });
        }
    });
    // 获取云购购买订单
    $http.get('/order/orderByYungouMobile?yid=' + $scope.yid + '&limit=' + $scope.orderPerTime).success(function(data) {
        if (data.code === 200 && data.data.length > 0) {
            $scope.orders = data.data;
            if (data.data.length < $scope.orderPerTime) {
                $scope.hasMoreData = false;
            }
        } else {
            $scope.hasMoreData = false;
        }
    }).error(function(data) {
        console.error(data);
    });
    // 上拉载入
    $scope.loadMore = function() {
        console.log('loadMore');
        if (!$scope.hasMoreData) {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            return;
        }
        if ($scope.orders.length === 0) {
            $scope.$broadcast('scroll.infiniteScrollComplete');
            return;
        }
        $http.get('/order/orderByYungouMobile?yid=' + $scope.yid + '&limit=' + $scope.orderPerTime + '&buyTime=' + $scope.orders[$scope.orders.length - 1].buyTime).success(function(data) {
            if (data.code === 200 && data.data.length > 0) {
                data.data.forEach(function(elem) {
                    $scope.orders.push(elem);
                });
                if (data.data.length < $scope.orderPerTime) {
                    $scope.hasMoreData = false;
                }
            } else {
                $scope.hasMoreData = false;
            }
            $scope.$broadcast('scroll.infiniteScrollComplete');
        });
    };
}])

// 计算详情
.controller('CalcuateCtrl', ['$scope', '$stateParams', 'YungouService', function($scope,$stateParams,YungouService) {
    $scope.yungouId = $stateParams.sid;
    $scope.init = false;
    $scope.records = [];
    YungouService.getResult(function (data) {
        $scope.A = data.A;
        $scope.B = data.B;
        $scope.result = data.result;
        $scope.sscTerm = data.sscTerm;
        $scope.init = true;
    },$scope.yungouId);
    YungouService.getCompute(function (data) {
        $scope.records = data;
    },$scope.yungouId)
    $scope.popOut = false;
    $scope.toggle = function() {
        $scope.popOut = !$scope.popOut;
    };
}])

// 往期夺宝
.controller('HistoryCtrl', ['$scope', '$stateParams', '$rootScope', '$http', function($scope, $stateParams, $rootScope, $http) {
    $scope.pid = $stateParams.pid;
    $scope.records = [];
    $scope.hasMoreData = true;
    $rootScope.showLoading('加载中..');
    $http.get('/yungou/getHistoryMobile?limit=10&productId=' + $scope.pid)
        .success(function(data) {
            if (data.code == 200) {
                $scope.records = $scope.records.concat(data.data);
                if ($scope.records.length > 0) {
                    $scope.startTime = $scope.records[$scope.records.length - 1].startTime;
                }
            }
        })
        .error(function(data) {
            console.log(data);
        })
        .finally(function() {
            $rootScope.hideLoading();
        });

    $scope.doRefresh = function() {
        $http.get('/yungou/getHistoryMobile?limit=10&productId=' + $scope.pid)
            .success(function(data) {
                if (data.code == 200) {
                    $scope.records = data.data;
                }
            })
            .error(function(data) {
                console.log(data);
            })
            .finally(function() {
                $scope.$broadcast('scroll.refreshComplete');
            });
    };

    $scope.loadMore = function() {
        $http.get('/yungou/getHistoryMobile?limit=10&productId=' + $scope.pid + "&startTime=" + $scope.startTime)
            .success(function(data) {
                if (data.code == 200) {
                    if (data.data.length < 10) {
                        $scope.hasMoreData = false;
                    }
                    $scope.records = $scope.records.concat(data.data);
                    if ($scope.records.length > 0) {
                        $scope.startTime = $scope.records[$scope.records.length - 1].startTime;
                    }
                }
            })
            .error(function(data) {
                console.log(data);
            })
            .finally(function() {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            });
    };
}])

;