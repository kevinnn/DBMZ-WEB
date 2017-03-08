// TAB - 个人
angular.module('YYYG')

// 个人中心
.controller('ProfileCtrl', ['$scope', '$rootScope', '$http', '$ionicModal', 'Service', 'UserService', function($scope,$rootScope,$http,$ionicModal,Service,UserService) {
    var getQueryString = function (name) {
        var href = window.location.href.split('?');
        if (href.length == 2) {
            var search = href[1].split('&');
            var arr = [];
            for(var i = 0,len = search.length; i<len; i++) {
                arr = search[i].split('=');
                if (arr.length == 2 && arr[0] == name) {
                    return arr[1];
                }
            }
            return null;
        } else {
            return null;
        }
    }
    var go = getQueryString('location');
    $scope.signed = false;
    Service.checkLogin(function () {
        if (Service.isLogin) {
            $scope.user = Service.user;
            UserService.wx(Service.user,function () {
                $scope.pointer = true;
            });
            if (go != null) {
                location.hash = "#/tab/profile/"+go;
            }
        }
    })
	$scope.toggle = function() {
		$scope.signed = !$scope.signed;
    };

    $ionicModal.fromTemplateUrl('/public/tpls/mobile/modal/modal-profile-share.html', {
        scope: $scope
    }).then(function(shareModel) {
        $rootScope.shareModel = shareModel;
    });
    $scope.pointer = true;
    $scope.share = function () {
        $scope.pointer = !$scope.pointer;
    }
    $scope.openRedPackage = function ($event) {
        if (!$rootScope.user) {
            $rootScope.checkLoginAndModal($event);
        } else {
            UserService.getHbRecords(function (data) {
                $scope.records = data;
                $rootScope.shareModel.show();
            });
        }
    };
    $scope.form = {};
    //修改密码
    $scope.save = function () {
        $scope.form.emptyError = false;
        $scope.form.sameError = false;
        $scope.form.uniqueError = false;
        $scope.form.pswError = false;
        if (typeof $scope.form.psw === 'undefined' || typeof $scope.form.newPsw === 'undefined' || typeof $scope.form.newPsw2 === 'undefined') {
            $scope.form.emptyError = true;
        } else if ($scope.form.newPsw != $scope.form.newPsw2){
            $scope.form.uniqueError = true;
        } else if ($scope.form.newPsw == $scope.form.psw) {
            $scope.form.sameError = true;
        } else {
            $http.post('/user/updatePassword', {
                oldpwd: $scope.form.psw,
                newpwd: $scope.form.newPsw
            }, {
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
            .success(function (data) {
                if (data.code == 200) {
                    alert('密码修改成功');
                    location.hash = "#/tab/profile";
                } else {
                    $scope.form.pswError = true;
                }
            })
            .error(function (data) {
                console.log(data);
            })
        }
    }
}])

// 个人 - 积分
.controller('CreditCtrl', ['$scope', '$rootScope', '$http', '$ionicPopup', 'Service', 'UserService', function($scope,$rootScope,$http,$ionicPopup,Service,UserService) {
    $scope.init = false;
    $rootScope.showLoading('加载中..');
	// 检查是否登录 未登录返回个人主页
    Service.checkLogin(function() {
        if (!Service.isLogin) window.location.hash = '#/tab/profile';
        else {
            $scope.user = Service.user;
            $scope.amount = parseInt($scope.user.credits);
        }
    });
    UserService.checkSignIn(function() {
        $scope.isSignIn = UserService.isSignIn;
        $scope.init = true;
        $rootScope.hideLoading();
    });
    $scope.signIn = function () {
        UserService.signIn(function (credit) {
            $scope.isSignIn = UserService.isSignIn;
            $scope.user.credits = parseInt($scope.user.credits)+credit;
        });
    }
    $scope.exchange = function () {
        if ($scope.user.credits < 100) {
            alert('积分不足,无法兑换');
            return ;
        }
        var myPopup = confirm("你确定要兑换全部积分吗");
        
        if (myPopup) {
            var amount = parseInt($scope.user.credits/100)*100;
            $http.post('/credit/exchange', {
                amount: amount
            }, {
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
            .success(function (data) {
                if (data.code == 200) {
                    $scope.user.balance = data.data.balance;
                    $scope.user.credits = data.data.credits;
                }
            })
            .error(function (data) {
                console.log(data);
            });
        }
    }
}])

.controller('CreditRecordCtrl', ['$scope', '$rootScope', 'Service', 'UserService', function ($scope, $rootScope, Service, UserService) {

    Array.prototype.unique = function () {
        var self = this,
            obj = {},
            arr = [];
        for(var i=0;i<self.length;i++) {
            if (typeof obj[self[i].id] === 'undefined') {
                obj[self[i].id] = true;
                arr.push(self[i]);
            }
        }
        return arr;
    }
    $scope.records = [];
    $scope.init =false;
    $scope.hasMoreData = true;
    $scope.page = 1;
    $scope.limit = 10;
    $scope.isRefresh = false;
    $scope.getCredit = function () {
        if (!$scope.init) {
            $rootScope.showLoading('加载中..');
        }
        UserService.getCredit(function(data) {
            if (data.length < 10) {
                $scope.hasMoreData = false;
            }
            if ($scope.isRefresh) {
                $scope.records = [];
            }
            $scope.records = $scope.records.concat(data);
            $scope.records = $scope.records.unique();
            if ($scope.page == 1 && !$scope.init) {
                $rootScope.hideLoading();
                $scope.init =true;
            } else if ($scope.isRefresh) {
                $scope.$broadcast('scroll.refreshComplete');
                $scope.isRefresh = false;
            } else {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
            $scope.page++;
        },$scope.limit,$scope.page);
    }

    Service.checkLogin(function () {
        if (Service.isLogin) {
            $scope.getCredit();
        } else {
            window.location.hash = "/mobile.html#tab/index.html";
            window.location.reload();
        }
    });

    $scope.doRefresh = function () {
        $scope.page = 1;
        $scope.isRefresh = true;
        $scope.hasMoreData = true;
        $scope.getCredit();
    }



}])

// 个人 - 余额充值
.controller('RechargeCtrl', ['$scope', '$rootScope', 'Service', function($scope,$rootScope,Service) {
    Service.checkLogin(function () {
        if (Service.isLogin == false) {
            window.location.hash = '/mobile.html#/tab/index.html';
        } else {
            $scope.user = Service.user;
        }
    });
	$scope.recharge_list = [10, 20, 50, 100, 200];
	$scope.current_select = 0;
	$scope.recharge_number = 10;
	$scope.tmp = {}; // angular scope bug
	$scope.tmp.input_number = undefined;
	$scope.select = function(index) {
		$scope.current_select = index;
		if (index < 5) {
			$scope.recharge_number = $scope.recharge_list[index];
		} else {
			jQuery('input.inputNum').select();
		}
	};
	$scope.checkNumber = function (input) {
		if (!parseInt(input) || parseInt(input) <= 0) {
			$scope.tmp.input_number = 1;
        	$scope.recharge_number = 1;
        } else {
        	$scope.tmp.input_number = parseInt(input);
        	$scope.recharge_number = parseInt(input);
        }
	};
	$scope.pay = function () {
        $rootScope.showLoading('等待中..');
        window.location.href = '/views/mobile/recharge.php?fee='+ 100*$scope.recharge_number;
        // window.location.href = '/views/mobile/recharge.php?fee='+ 100*0.01;

        $rootScope.hideLoading();
	};
}])

.controller('RechargeResultCtrl', ['$scope','$rootScope','$stateParams','Service','UserService', function ($scope,$rootScope,$stateParams,Service,UserService) {
    $scope.init = false;
    Service.checkLogin(function () {
        if (!Service.isLogin) {
            location.href = "/mobile.html#/tab/index";
            location.reload();
        }
    });
    $scope.tradeId = $stateParams.tradeId;
    $scope.fee = $stateParams.fee;
    $scope.isCancel = $stateParams.isCancel;

    //重新支付
    $scope.repay = function () {
        location.href = "/views/mobile/recharge.php?fee="+$scope.fee;
    };
    //继续夺宝,返回首页
    $scope.backToIndex = function () {
        location.href = "/mobile.html#/tab/index";
        location.reload();
    }
    //查看记录,返回个人中心
    $scope.backToProfile = function () {
        location.href = "/mobile.html#/tab/profile?location=balance";
    }

    if ($scope.isCancel == 1) {
        $scope.isPay = 0;
        $scope.init = true;
    } else {
        $rootScope.showLoading('加载中..');
        UserService.rechargeReady(function () {
            $scope.isPay = 1;
            $scope.init = true;
            $rootScope.hideLoading();
        },$scope.tradeId);
    }

}])

//夺宝记录
.controller('recordBuyCtrl', ['$scope', '$rootScope', '$http', '$ionicScrollDelegate', 'YungouService', function($scope, $rootScope, $http, $ionicScrollDelegate, YungouService) {
    $scope.allRecords = $scope.remainRecords = $scope.finishRecords = [];
    $scope.hasMoreDataOfAll = $scope.hasMoreDataOfRemain = $scope.hasMoreDataOfFinish = true;
    $scope.allRecordPage = $scope.remainRecordPage = $scope.finishRecordPage = 1;
    $scope.initAll = $scope.initRemain = $scope.initFinish = false;

    // 前往最新一期的云购
    $scope.toLatestYungou = function (yid) {
        YungouService.getLatestYungou(function (yungouId) {
            window.location.hash = "#/tab/profile/yungou-"+yungouId+"-"+$rootScope.timeStamp;
        },yid)
    }

    $scope.getAllRecord = function() {
        if ($scope.allRecordPage == 1 && !$scope.initAll) {
            $rootScope.showLoading('加载中..');
        }
        if ($scope.hasMoreDataOfAll) {

            $http.get('/order/getRecord?limit=10&page=' + $scope.allRecordPage)
                .success(function(data) {
                    if (data.code == 200) {
                        $scope.initAll = true;
                        if ($scope.allRecordPage == 1) {
                            $scope.allRecords = [];
                        }
                        $scope.allRecordPage++;
                        if (data.data.length < 10) {
                            $scope.hasMoreDataOfAll = false;
                        }
                        $scope.allRecords = $scope.allRecords.concat(data.data);
                        $ionicScrollDelegate.resize();
                    }
                })
                .error(function(data) {
                    console.log(data);
                })
                .finally(function() {
                    if ($scope.allRecordPage == 2) {
                        $rootScope.hideLoading();
                        $scope.$broadcast('scroll.refreshComplete');
                    } else {
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                    }
                })
        }
    };

    $scope.getRemainRecord = function() {
        if ($scope.remainRecordPage == 1 && !$scope.initRemain) {
            $rootScope.showLoading('加载中..');
        }
        if ($scope.hasMoreDataOfRemain) {

            $http.get('/order/getRecord?limit=10&page=' + $scope.remainRecordPage + '&status=3')
                .success(function(data) {
                    if (data.code == 200) {
                        $scope.initRemain = true;
                        if ($scope.remainRecordPage == 1) {
                            $scope.remainRecords = [];
                        }
                        $scope.remainRecordPage++;
                        if (data.data.length < 10) {
                            $scope.hasMoreDataOfRemain = false;
                        }
                        $scope.remainRecords = $scope.remainRecords.concat(data.data);
                        $ionicScrollDelegate.resize();
                    }
                })
                .error(function(data) {
                    console.log(data);
                })
                .finally(function() {
                    if ($scope.remainRecordPage == 2) {
                        $rootScope.hideLoading();
                        $scope.$broadcast('scroll.refreshComplete');
                    } else {
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                    }
                })
        }
    };

    $scope.getFinishRecord = function() {
        if ($scope.finishRecordPage == 1 && !$scope.initFinish) {
            $rootScope.showLoading('加载中..');
        }
        if ($scope.hasMoreDataOfFinish) {

            $http.get('/order/getRecord?limit=10&page=' + $scope.finishRecordPage + '&status=2')
                .success(function(data) {
                    if (data.code == 200) {
                        $scope.initFinish = true;
                        if ($scope.finishRecordPage == 1) {
                            $scope.finishRecords = [];
                        }
                        $scope.finishRecordPage++;
                        if (data.data.length < 10) {
                            $scope.hasMoreDataOfFinish = false;
                        }
                        $scope.finishRecords = $scope.finishRecords.concat(data.data);
                        $ionicScrollDelegate.resize();
                    }
                })
                .error(function(data) {
                    console.log(data);
                })
                .finally(function() {
                    if ($scope.finishRecordPage == 2) {
                        $rootScope.hideLoading();
                        $scope.$broadcast('scroll.refreshComplete');
                    } else {
                        $scope.$broadcast('scroll.infiniteScrollComplete');
                    }
                })
        }
    };

    //下拉刷新
    $scope.doRefresh = function() {
        switch ($scope.currentStatus) {
            case 0:
                $scope.allRecordPage = 1;
                $scope.hasMoreDataOfAll = true;
                $scope.getAllRecord();
                break;
            case 1:
                $scope.remainRecordPage = 1;
                $scope.hasMoreDataOfRemain = true;
                $scope.getRemainRecord();
                break;
            case 2:
                $scope.finishRecordPage = 1;
                $scope.hasMoreDataOfFinish = true;
                $scope.getFinishRecord();
                break;
        }
    }

    $scope.currentStatus = 0;
    $scope.changeStatus = function(status) {
        if ($scope.currentStatus != status) {
            $ionicScrollDelegate.scrollTop(false);
        }
        $scope.currentStatus = status;
        switch (status) {
            case 0:
                if (!$scope.initAll) {
                    $scope.getAllRecord();
                }
                break;
            case 1:
                if (!$scope.initRemain) {
                    $scope.getRemainRecord();
                }
                break;
            case 2:
                if (!$scope.initFinish) {
                    $scope.getFinishRecord();
                }
                break;
        }
    };
    if ($rootScope.user) {
        $scope.changeStatus(0);
    } else {
        location.hash = "#/tab/index";
        location.reload();
    }
}])

// 个人中心 - 设置
.controller('ProfileSettingCtrl', ['$scope', '$rootScope', '$http', '$ionicActionSheet', function($scope, $rootScope, $http, $ionicActionSheet) {
    $scope.nickNameHide = true;
    $scope.inputNickName = function () {
        $scope.nickNameHide = false;
    }
    $scope.cancelNickName = function () {
        $scope.nickNameHide = true;
        $scope.nickName = $scope.user.userName;
    }
    $scope.updateNickName = function () {
        if ($scope.nickName != '') {

            $rootScope.showLoading('修改中..');
            $http.post('/user/updateNickname',{
                nickname: $scope.nickName
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
            .success(function (data) {
                if (data.code == 200) {
                    $scope.nickNameHide = true;
                    $rootScope.user.userName = $scope.nickName;
                }
            })
            .error(function (data) {
                console.log(data);
            })
            .finally(function () {
                $rootScope.hideLoading();
            });
        }
    }
    // 检查是否登录 未登录返回上一层
    $http.get('/user/isLogin').success(function(data) {
        if (data.code === 200) {
            $rootScope.user = data.data[0];
            $scope.user =$rootScope.user;
            $rootScope.getCart();
            $scope.nickName = $scope.user.userName;
        } else {
            window.location.hash = '#/tab/profile';
        }
    });
    // 修改头像
    $scope.updateAvatar = function() {
        jQuery('#cameraInput').click();
    };
    //判断图片格式和大小
    var imgFilter = function (file) {
        var typeCheck = function (names) {
            var types = ['jpg','png','jpeg','bmp'];
            return names.length == 2 && names[1] in types
        }
        var sizeCheck = function (size) {
            return size < 65535;
        }
        var names = file.name.split('.');
        var size = file.size;
        // return typeCheck(names) && sizeCheck(size);
        return true;
    }
    // 上传头像 TODO
    $scope.uploadFile = function(files) {
        if (!imgFilter(files[0])) {
            alert('图片格式错误或图片太大');
            return ;
        }
        $rootScope.showLoading('上传中..');
        var uploadComplete = function (evt) {
            var response = evt.target.responseText;
            response = JSON.parse(response);
            if (response.code == 200) {
                $rootScope.user.avatorUrl = response.data;
                $rootScope.hideLoading();
            }
        }
        var fd = new FormData();
        fd.append('file', files[0]);

        var xhr = new XMLHttpRequest();
        
        xhr.open("POST", '/uploader/user_avatorUrl');
 
        xhr.addEventListener("load",uploadComplete, false);

        xhr.send(fd);

        // $http.post(uploadUrl, fd, {
        //    	withCredentials: true,
        //    	headers: {'Content-Type': undefined },
        //    	transformRequest: angular.identity
        // }).success().error();
    };
    // 退出登录
    $scope.logout = function() {
        $http.get('/user/logout').success(function(data) {
            if (data.code === 200) {
                window.location.hash = '#/tab/index';
                window.location.reload();
            }
        });
    };

}])

// 中奖细节
.controller('winDetailCtrl', ['$scope', '$rootScope', '$stateParams', '$http', '$ionicPopup', 'Service', function($scope, $rootScope, $stateParams, $http, $ionicPopup, Service) {
    $scope.winOrderId = $stateParams.winOrderId;

    $scope.getWinOrder = function () {
        $rootScope.showLoading('加载中..');
        $http.get('/winOrder/getById?winOrderId='+$scope.winOrderId)
        .success(function (data) {
            console.log(data);
            if (data.code == 200) {
                $scope.record = data.data;                
            } else {
                window.location.hash = '#/tab/profile';
            }
        })  
        .error(function (data) {
            console.log(data);
        })  
        .finally(function () {
            $rootScope.hideLoading();
        })    
    }

    Service.checkLogin(function() {
        if (!Service.isLogin) window.location.hash = '#/tab/profile';
        else $scope.getWinOrder();
    });
    


    $scope.showConfirm = function() {
        var confirm = window.confirm("您确认收到中奖商品了么？");
        if (confirm) {
            $http.post('/winOrder/confirm', {
                winOrderId: $scope.winOrderId
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
            .success(function (data) {
                if (data.code == 200) {
                    $scope.getWinOrder();
                }
            })
            .error(function (data) {
                console.log(data);
            });
        } else {
            
        }
    };
}])

// 余额变动
.controller('BalanceCtrl', ['$scope', '$rootScope', '$http', 'Service', 'UserService', function($scope, $rootScope, $http, Service, UserService) {
    Array.prototype.unique = function () {
        var self = this,
            obj = {},
            arr = [];
        for(var i=0;i<self.length;i++) {
            if (typeof obj[self[i].id] === 'undefined') {
                obj[self[i].id] = true;
                arr.push(self[i]);
            }
        }
        return arr;
    }
    $scope.records = [];
    $scope.init =false;
    $scope.hasMoreData = true;
    $scope.page = 1;
    $scope.limit = 10;
    $scope.isRefresh = false;
    $scope.getBalance = function () {
        if (!$scope.init) {
            $rootScope.showLoading('加载中..');
        }
        UserService.getBalance(function(data) {
            if (data.length < 10) {
                $scope.hasMoreData = false;
            }
            if ($scope.isRefresh) {
                $scope.records = [];
            }
            $scope.records = $scope.records.concat(data);
            $scope.records = $scope.records.unique();
            if ($scope.page == 1 && !$scope.init) {
                $rootScope.hideLoading();
                $scope.init =true;
            } else if ($scope.isRefresh) {
                $scope.$broadcast('scroll.refreshComplete');
                $scope.isRefresh = false;
            } else {
                $scope.$broadcast('scroll.infiniteScrollComplete');
            }
            $scope.page++;
        },$scope.limit,$scope.page);
    }

    Service.checkLogin(function () {
        if (Service.isLogin) {
            $scope.getBalance();
        } else {
            window.location.hash = "/mobile.html#tab/index.html";
            window.location.reload();
        }
    });

    $scope.doRefresh = function () {
        $scope.page = 1;
        $scope.isRefresh = true;
        $scope.hasMoreData = true;
        $scope.getBalance();
    }

}])



// Ta的中心
.controller('UserRecordCtrl', ['$scope', '$rootScope', '$http', '$stateParams', '$ionicScrollDelegate', 'YungouService', function($scope,$rootScope,$http,$stateParams,$ionicScrollDelegate,YungouService) {

	$scope.uid = $stateParams.uid;
	$scope.records = [];
	$scope.hasMoreDataOfRecords = true;
	$scope.recordsPage = 1;
	$scope.initOfRecords = false;
	$scope.winRecords = [];
	$scope.hasMoreDataOfWinRecords = true;
	$scope.winRecordsPage = 1;
	$scope.initOfWinRecords = false;
	$scope.shows = [];
	$scope.hasMoreDataOfShows = true;
	$scope.showsPage = 1;
	$scope.initOfShows = false;
	$scope.otherUser = {};
    //去这一期云购
    $scope.toYungou = function (yid) {
        window.location.hash = "#/tab/"+$scope.activedTab+"/yungou-"+yid+"-"+$scope.timeStamp;
    };
    //去最新一期云购
    $scope.toLatestYungou = function (yid) {
        YungouService.getLatestYungou(function (yid) {
            window.location.hash = "#/tab/"+$scope.activedTab+"/yungou-"+yid+"-"+$scope.timeStamp;
        },yid);
    }

	// 控制导航滑动块
	$scope.current_item = 0;
	$scope.current_bodyWidth = document.body.offsetWidth;
	$scope.selectItem = function(item) {
		if ($scope.current_item != item) {
			$ionicScrollDelegate.scrollTop(false);
		}
		$scope.current_item = item;
		
		switch(item) {
			case 0:
				if (!$scope.initOfRecords) {
					$scope.getRecord();
				}
				break;
			case 1:
				if (!$scope.initOfWinRecords) {
					$scope.getWinRecord();
				}
				break;
			case 2:
				if (!$scope.initOfShows) {
					$scope.getShow();
				}
				break;
		}
	};
	//获取用户信息
	$rootScope.showLoading('加载中..');
	$http.get('/user/getUserMobile?uid='+$scope.uid)
	.success(function (data) {
		if (data.code == 200) {
			$scope.otherUser = data.data[0];
		}
	})
	.error(function (data) {
		console.log(data);
	})
	.finally(function () {
		$rootScope.hideLoading();
		$scope.selectItem(0);
	});

	$scope.doRefresh = function () {
		switch($scope.current_item) {
			case 0:
				$scope.recordsPage = 1;
				$scope.hasMoreDataOfRecords = true;
				$scope.getRecord();
				break;
			case 1:
				$scope.winRecordsPage = 1;
				$scope.hasMoreDataOfWinRecords = true;
				$scope.getWinRecord();
				break;
			case 2:
				$scope.showsPage = 1;
				$scope.hasMoreDataOfShows = true;
				$scope.getShow();
				break;
		}
	}
	//获取订单记录
	$scope.getRecord = function () {
		if ($scope.recordsPage == 1 && !$scope.initOfRecords) {
			$rootScope.showLoading('加载中..');
		}
		if ($scope.hasMoreDataOfRecords) {

			$http.get('/order/getRecord?page='+$scope.recordsPage+'&limit=10&id='+$scope.uid)
			.success(function (data) {
				if (data.code == 200) {
					$scope.initOfRecords = true;
					if ($scope.recordsPage ==1) {
						$scope.records = [];
					}
					$scope.recordsPage++;
					if (data.data.length < 10) {
						$scope.hasMoreDataOfRecords = false;
					}
					$scope.records = $scope.records.concat(data.data);
					$ionicScrollDelegate.resize();
				}
			})
			.error(function (data) {
				console.log(data);
			})
			.finally(function () {
				if ($scope.recordsPage == 2) {
					$rootScope.hideLoading();
					$scope.$broadcast('scroll.refreshComplete');
				} else {
					$scope.$broadcast('scroll.infiniteScrollComplete');
				}
			});
		}
	}
	//获取中奖记录
	$scope.getWinRecord = function () {
		if ($scope.winRecordsPage == 1 && !$scope.initOfWinRecords) {
			$rootScope.showLoading('加载中..');
		}
		if ($scope.hasMoreDataOfWinRecords) {
			$http.get('/order/getWinRecord?page='+$scope.winRecordsPage+'&limit=10&id='+$scope.uid)
			.success(function (data) {
				if (data.code == 200) {
					$scope.initOfWinRecords = true;
					if ($scope.winRecordsPage ==1) {
						$scope.winRecords = [];
					}
					$scope.winRecordsPage++;
					if (data.data.length < 10) {
						$scope.hasMoreDataOfWinRecords = false;
					}
					$scope.winRecords = $scope.winRecords.concat(data.data);
					$ionicScrollDelegate.resize();
				}
			})
			.error(function (data) {
				console.log(data);
			})
			.finally(function () {
				if ($scope.winRecordsPage == 2) {
					$rootScope.hideLoading();
					$scope.$broadcast('scroll.refreshComplete');
				} else {
					$scope.$broadcast('scroll.infiniteScrollComplete');
				}
			});
		}
	}
	//获取晒单记录
	$scope.getShow = function () {
		if ($scope.showsPage == 1 && !$scope.initOfShows) {
			$rootScope.showLoading('加载中..');
		}
		if ($scope.hasMoreDataOfShows) {

			$http.get('/show/getAllByUser?limit=10&page='+$scope.showsPage+'&userId='+$scope.uid)
			.success(function (data) {
				if (data.code == 200) {
					$scope.initOfShows = true;
					if ($scope.showsPage ==1) {
						$scope.shows = [];
					}
					$scope.showsPage++;
					if (data.data.length < 10) {
						$scope.hasMoreDataOfShows = false;
					}
					$scope.shows = $scope.shows.concat(data.data);
					$ionicScrollDelegate.resize();
				}
			})
			.error(function (data) {
				console.log(data);
			})
			.finally(function () {
				if ($scope.showsPage == 2) {
					$rootScope.hideLoading();
					$scope.$broadcast('scroll.refreshComplete');
				} else {
					$scope.$broadcast('scroll.infiniteScrollComplete');
				}
			})
		}
	}
}])

.controller('recordWinCtrl', ['$scope', '$rootScope', '$http', 'Service', function($scope, $rootScope, $http, Service) {
    $scope.records = [];
    $scope.page = 1;
    $scope.hasMoreData = true;
    $scope.isRefresh = false;
    $scope.isLoad = false;
    $scope.init = false;

    $scope.getWinRecord = function () {
        if ($scope.page == 1) {
            $rootScope.showLoading('加载中..');
        }
        if ($scope.hasMoreData) {
            $http.get('/order/getWinRecord?page='+$scope.page+'&limit=10')
            .success(function (data) {
                if (data.code == 200) {
                    if ($scope.page ==1) {
                        $scope.records = [];
                    }
                    $scope.page++;
                    if (data.data.length < 10) {
                        $scope.hasMoreData = false;
                    }
                    $scope.records = $scope.records.concat(data.data);
                }
            })
            .error(function (data) {
                console.log(data);
            })
            .finally(function () {
                if ($scope.page == 2) {
                    $rootScope.hideLoading();
                }
                if ($scope.isRefresh) {
                    $scope.$broadcast('scroll.refreshComplete');
                }
                if ($scope.isLoad) {
                    $scope.$broadcast('scroll.infiniteScrollComplete');
                }
                $scope.init = true;
            });
        }
    };
    Service.checkLogin(function() {
        if (!Service.isLogin) window.location.hash = '#/tab/profile';
        else $scope.getWinRecord();
    });

    $scope.doRefresh = function () {
        $scope.page = 1;
        $scope.hasMoreData = true;
        $scope.isRefresh = true;
        $scope.getWinRecord();
    }
    $scope.loadMoreData = function () {
        $scope.isLoad = true;
        $scope.getWinRecord();
    }

}])

// 收货地址
.controller('addressCtrl', ['$scope', '$rootScope', '$http', '$stateParams', '$ionicPopup', '$ionicHistory', 'Service', 'AddressService', function($scope, $rootScope, $http, $stateParams, $ionicPopup, $ionicHistory, Service, AddressService) {
    $scope.comeFromProfile = false;
    $scope.winOrderId = $stateParams.winOrderId;
    $scope.selectAddressId = -1;
    $scope.viewTitle = "地址列表";
    // 检查是否登录 未登录返回个人页面
    Service.checkLogin(function(){
        if (!Service.isLogin) window.location.hash = '#/tab/profile';
    });
    // 获取收货地址
    $rootScope.showLoading('加载中..');
    AddressService.getAll(function () {
        $scope.addresses = AddressService.addresses;
        for(var i=0,len=$scope.addresses.length;i<len;i++) {
            $scope.addresses[i].status==1 && ($scope.selectAddressId = $scope.addresses[i].id);
        }
        $rootScope.hideLoading();
    });

    $scope.modifyAddress = function(addressId) {
        location.href = '/mobile.html#/tab/profile/addressEdit-' + addressId;
    }

    $scope.selectAddress = function(index) {
        $scope.selectAddressId = index;
    }
    $scope.editAddress = function() {};

    $scope.showConfirm = function() {
        if ($scope.selectAddressId == -1) {
            alert('您还没有选择地址');
            return ;
        }
        var confirmPopup = confirm('您确定是这个地址了么?');
        if (confirmPopup) {
            $http.get('/winOrder/confirmAddress?winOrderId='+$scope.winOrderId+'&addressId='+$scope.selectAddressId)
            .success(function (data) {
                if (data.code == 200) {
                    $ionicHistory.goBack();
                }
            })
            .error(function (data) {
                console.log(data);
            })
        }
    };

    // 添加收货地址
    $scope.addAddress = function () {
        if ($scope.addresses.length >= 5) {
            alert('地址不能超过5条');
        } else {
            window.location.hash = '#/tab/profile/addressEdit-0';
        }
    };
}])

// 个人 - 我的收货地址列表
.controller('AddressCtrl', ['$scope', '$rootScope', 'Service', 'AddressService', function($scope,$rootScope,Service,AddressService) {
    $scope.viewTitle = '地址列表';

    // 检查是否登录 未登录返回个人页面
    Service.checkLogin(function(){
        if (!Service.isLogin) window.location.hash = '#/tab/profile';
    });

    // 是否从个人页面点击过来
    $scope.comeFromProfile = true;

    // 获取收货地址
    $rootScope.showLoading('加载中..');
    AddressService.getAll(function () {
        $scope.addresses = AddressService.addresses;
        $rootScope.hideLoading();
    });

    // 添加收货地址
    $scope.addAddress = function () {
        if ($scope.addresses.length >= 5) {
            alert('地址不能超过5条');
        } else {
            window.location.hash = '#/tab/profile/addressEdit-0';
        }
    };

    // 编辑收货地址
    $scope.editAddress = function (address) {
        $rootScope.editAddress = clone(address);
        window.location.hash = '#/tab/profile/addressEdit-'+address.id;
    };
}])

// 个人 - 添加收货地址 & 编辑收货地址
.controller('AddressEditCtrl', ['$scope', '$rootScope', '$stateParams', '$ionicHistory', 'Service', 'AddressService', function($scope,$rootScope,$stateParams,$ionicHistory,Service,AddressService) {
    $scope.addressId = $stateParams.addressId;
    // 检查是否登录 未登录返回个人页面
    Service.checkLogin(function(){
        if (!Service.isLogin) window.location.hash = '#/tab/profile';
    });

    if ($scope.addressId == 0) {
        $scope.viewTitle = '添加地址';
        $scope.deleteText = '';
        $scope.address = {};
    } else {
        $scope.viewTitle = '编辑地址';
        $scope.deleteText = '删除';
        if (!$rootScope.editAddress) {
            window.location.hash = '#/tab/profile/address';
        } else {
            $scope.address = $rootScope.editAddress;
            updateCity();
            updateArea();
        }
    }

    // 获取省份信息
    AddressService.getProvinces(function() {
        $scope.provinces = AddressService.provinces;
    });

    // 选取省份
    $scope.selectProvince = function () {
        $scope.address.cityID = undefined;
        $scope.address.areaID = undefined;
        updateCity();
    }

    // 选取城市
    $scope.selectCity = function () {
        $scope.address.areaID = undefined;
        updateArea();
    }

    // 更新城市
    function updateCity () {
        AddressService.getCities($scope.address.provinceID, function () {
            $scope.cities = AddressService.cities;
        });
    };

    // 更新县区
    function updateArea () {
        AddressService.getAreas($scope.address.cityID, function () {
            $scope.areas = AddressService.areas;
        });
    };

    // 保存地址
    $scope.saveAddress = function () {
        if (!$scope.address.status) $scope.address.status = 0;
        if ($scope.address.receiver && $scope.address.phoneNumber && $scope.address.provinceID && $scope.address.cityID && $scope.address.areaID && $scope.address.street) {
            if ($scope.addressId != 0) {
                $scope.updateAddress();
            } else {
                $rootScope.showLoading('保存中..');
                AddressService.addAddress($scope.address, function(result, code) {
                    $rootScope.hideLoading();
                    if (result) {
                        $ionicHistory.goBack();
                    } else if (code === 405) {
                        alert('地址不能超过5条，添加失败。')
                    } else {
                        alert('地址信息不完整或格式不正确');
                    }
                });
            }
        } else {
            alert('地址信息不完整或格式不正确');
        }
    };

    // 更新地址
    $scope.updateAddress = function () {
        $rootScope.showLoading('保存中..');
        AddressService.updateAddress($scope.address, function(result) {
            $rootScope.hideLoading();
            if (result) {
                $ionicHistory.goBack();
            } else {
                alert('地址信息不完整或格式不正确');
            }
        });
    };

    // 删除地址
    $scope.deleteAddress = function () {
        if ($scope.addressId == 0) {
            $ionicHistory.goBack();
            return;
        }
        var deleteConfirm = confirm('确定删除地址？');
        if (deleteConfirm == true) {
            $rootScope.showLoading('删除中..');
            AddressService.deleteAddress($rootScope.editAddress.id, function(result) {
                $rootScope.hideLoading();
                if (result) {
                    $ionicHistory.goBack();
                } else {
                    alert('删除出错！');
                }
            });
        }
    };
}])

//  晒单
.controller('exposeCtrl', ['$scope', '$rootScope', '$http', 'Service', function($scope, $rootScope, $http, Service) {
    $scope.remainRecords = [];
    $scope.finishRecords = [];

    $scope.finishRecordPage = 1;
    $scope.remainRecordPage = 1;

    $scope.hasMoreDataOfFinish = true;
    $scope.hasMoreDataOfRemain = true;

    $scope.isRefresh = false;
    $scope.isLoad = false;

    $scope.getRemain = function () {
        if ($scope.remainRecordPage == 1 && !$scope.isRefresh)
            $rootScope.showLoading('加载中..');
        $http.get('/show/getUnshowByUser?limit=10&page='+$scope.remainRecordPage)
        .success(function (data) {
            if (data.code == 200) {
                $scope.remainRecordPage == 1 && ($scope.remainRecords = []);
                $scope.remainRecordPage++;
                $scope.remainRecords = $scope.remainRecords.concat(data.data);
                data.data.length < 10 && ($scope.hasMoreDataOfRemain = false);
            }
        })
        .error(function (data) {
            console.log(data);
        })
        .finally(function() {
            if ($scope.isRefresh) {
                $scope.$broadcast('scroll.refreshComplete');
                $scope.isRefresh = false;
            }
            if ($scope.isLoad) {
                $scope.$broadcast('scroll.infiniteScrollComplete');
                $scope.isLoad = false;
            }
            if ($scope.remainRecordPage == 2 && $scope.hasMoreDataOfRemain && !$scope.isRefresh) {
                $rootScope.hideLoading();
            }
            if (!$scope.hasMoreDataOfRemain) $scope.getFinish(10-($scope.remainRecords.length%10));
        })
    }

    $scope.getFinish = function (limit) {
        $http.get('/show/getAllByUser?limit='+limit+'&page='+$scope.finishRecordPage)
        .success(function (data) {
            if (data.code == 200) {
                $scope.finishRecordPage == 1 && ($scope.finishRecords = []);
                $scope.finishRecordPage++;
                $scope.finishRecords = $scope.finishRecords.concat(data.data);
                data.data.length < 10 && ($scope.hasMoreDataOfFinish = false);
            }
        })
        .error(function (data) {
            console.log(data);
        })
        .finally(function () {
            if ($scope.isRefresh) {
                $scope.$broadcast('scroll.refreshComplete');
                $scope.isRefresh = false;
            }
            if ($scope.isLoad) {
                $scope.$broadcast('scroll.infiniteScrollComplete');
                $scope.isLoad = false;
            }
            if ($scope.finishRecordPage == 2 && $scope.remainRecordPage == 2 && !$scope.isRefresh) {
                $rootScope.hideLoading();
            }
        });
    }

    //下拉刷新
    $scope.doRefresh = function () {
        $scope.finishRecordPage = 1;
        $scope.remainRecordPage = 1;

        $scope.hasMoreDataOfFinish = true;
        $scope.hasMoreDataOfRemain = true;

        $scope.isRefresh = true;

        $scope.getRemain();
    }

    //回到首页
    $scope.toIndex = function () {
        window.location.hash = "#/tab/index";
    }

    //到晒单编辑页面
    $scope.toAddShow = function (yid) {
        window.location.hash = "#tab/profile/expose/add-"+yid;
    }

    $scope.loadMoreData = function () {
        console.log(1);
        $scope.isLoad = true;
        if ($scope.hasMoreDataOfRemain) {
            $scope.getFinish(10);
        } else {
            $scope.getRemain();
        }
    }

    // 检查是否登录 未登录返回个人页面
    Service.checkLogin(function(){
        if (!Service.isLogin) window.location.hash = '#/tab/profile';
        else $scope.getRemain();
    });

    $scope.addressCount = 1;

    if (location.href.split("?").pop() === "0") {
        $scope.addressCount = 0;
    }
}])

// 晒单编辑
.controller('exposeAddCtrl', ['$window', '$stateParams', '$scope', '$rootScope', '$ionicActionSheet', '$timeout', '$http', function($window,$stateParams, $scope,$rootScope, $ionicActionSheet, $timeout, $http) {
    $scope.yungouId = $stateParams.yid;
    $scope.exposeImgList = [];
    $scope.show = {};
    var fileList = [];
    var imgUrls = [];
    var reader = new FileReader();
    $scope.uploadFile = function(files) {
        reader.onload = function (e) {
            $scope.exposeImgList.push(e.target.result);
            $rootScope.hideLoading();
        }
        var isRepeat = false;
        for (var j = 0,l = fileList.length; j < l; j++) {
            if (fileList[j].name == files[0].name && fileList[j].size == files[0].size) {
                isRepeat = true;
                break;
            }
        }
        if (!isRepeat) {
            $rootScope.showLoading('上传中..');
            fileList.push(files[0]);
            reader.readAsDataURL(files[0]);
        }
        // fd.append(files[0].name, files[0]);

        // $http.post(uploadUrl, fd, {
        //      withCredentials: true,
        //      headers: {'Content-Type': undefined },
        //      transformRequest: angular.identity
        // }).success().error();
    };

    $scope.add = function () {
        if (typeof $scope.show.showTitle === 'undefined' || typeof $scope.show.showContent === 'undefined' || $scope.show.showTitle.length < 4 || $scope.show.showTitle.length > 20 || $scope.show.showContent.length < 15 || $scope.show.showContent.length > 255) {
            alert('标题或内容字符数不正确');
        } else  if (fileList.length == 0 || fileList.length >= 5) {
            alert('图片不能为空');
        } else {
            $rootScope.showLoading('发布中..');
            var uploadComplete = function (evt) {
                var response = evt.target.responseText
                response = JSON.parse(response);
                if (response.code == 200) {
                    imgUrls.push(response.data);
                }
                if (imgUrls.length == fileList.length) {
                    add();
                }
            }
            var add = function () {
                $http.post('/show/add',{
                    title: $scope.show.showTitle,
                    content: $scope.show.showContent,
                    imgUrls: imgUrls.join(','),
                    yungouId: $scope.yungouId,
                    orderId: $scope.record.orderId,
                    term: $scope.record.term
                },{
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    transformRequest: function(data) {
                        return $.param(data);
                    }
                })
                .success(function (data) {
                    if (data.code == 200) {
                        $rootScope.hideLoading();
                        location.href = "#/tab/profile/expose/success-"+$scope.yungouId;
                        $rootScope.isExposeAdd = true;
                    }
                })
                .error(function (data) {
                    console.log(data);
                })
            }
            for (var i = 0; i < fileList.length; i++) {
                    var fd = new FormData();
                    fd.append('file',fileList[i]);
                    var xhr = new XMLHttpRequest();
 
                    xhr.open("POST", "/uploader/show");
             
                    xhr.addEventListener("load",uploadComplete, false);

                    xhr.send(fd);
            }
        }
    }
    $rootScope.showLoading('加载中..');
    $http.get('/yungou/getYgForShow?yid='+$scope.yungouId)
    .success(function (data) {
        if (data.code == 200) {
            $scope.record = data.data;
        }
    })
    .error(function (data) {
        console.log(data);
    })
    .finally(function () {
        $rootScope.hideLoading();
    })

    $scope.deleteOneExposeImg = function(index,$event) {
        $rootScope.showLoading('删除中..');
        $scope.exposeImgList.splice(index, 1);
        fileList.splice(index,1);
        $('#cameraInput').val('');
        $rootScope.hideLoading();
        $event.stopPropagation();
    }

    $scope.scrollToTop = function() {
        window.scrollTo(0, 0);
    }
}])

.controller('exposeSuccessCtrl', ['$scope', '$stateParams', '$rootScope', '$http', 'Service', function ($scope,$stateParams,$rootScope,$http,Service) {
    $scope.yid = $stateParams.yid;
    Service.checkLogin(function () {
        if (Service.isLogin == false) {
            location.hash = "#/tab/index?";
            location.reload();
        } else {
            $scope.user = Service.user;
        }
    });
    if (typeof $rootScope.isExposeAdd === 'undefined' || !$rootScope.isExposeAdd) {
        window.location.hash = "#/tab/index";
        window.location.reload();
    } else {
        $rootScope.isExposeAdd = false;
    }
}])

// 代理控制器
.controller('AgencyCtrl', ['$scope', '$http', '$ionicModal', function($scope,$http,$ionicModal) {
    $http.get('/delegate/getMemberNumAndCash')
    .success(function (data) {
        if (data.code == 200) {
            $scope.member = data.data;
        }
    })
    .error(function (data) {
        console.log(data);
    });

    $http.get('/delegate/getThisMonthStatics')
    .success(function (data) {
        if (data.code == 200) {
            $scope.month = data.data;
        }
    })
    .error(function (data) {
        console.log(data);
    });

    $scope.getWithdrawRecords = function () {
        $http.get('/delegate/getWithdrawRecords?isapproved=2&page=1&limit=5')
        .success(function (data) {
            console.log(data);
            if (data.code == 200) {
                $scope.withdraw = data.data;
            }
        })
        .error(function (data) {
            console.log(data);
        });
    }

    $scope.getWithdrawRecords();

    $scope.withdrawFn = function () {
        $scope.money = $('#typeIn').val();
        if (typeof $scope.member != 'undefined' && $scope.money <= $scope.member.cash && $scope.money > 10) {
            $http.post('/delegate/applyWithdraw', {
                amount: $scope.money
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
            .success(function (data) {
                if (data.code == 200) {
                    $scope.modal.hide();
                    $scope.getWithdrawRecords();
                }
            })
            .error(function (data) {
                console.log(data);
            });
        }
    }
    $ionicModal.fromTemplateUrl('/public/tpls/mobile/modal/modal-agency-withdraw.html', {
        scope: $scope,
        animation: 'slide-in-up'
      }).then(function(modal) {
        $scope.modal = modal;
      });


}])

.controller('SuggestCtrl', ['$scope','$rootScope','$http','Service', function ($scope,$rootScope,$http,Service) {
    Service.checkLogin(function () {
        if (Service.isLogin == false) {
            window.location.hash = "#tab/profile";
            window.location.reload();
        }
    })
    $scope.data = {};
    $scope.data.theme = '1';
    $scope.saveSuggest = function () {
        $scope.data.contentError = false;
        if (typeof $scope.data.email === 'undefined' ) {
            $scope.data.email = '';
        }
        if (typeof $scope.data.content === 'undefined' || $scope.data.content.length < 12 ||$scope.data.content.length > 200) {
            $scope.data.contentError = true;
        } else {
            $http.post('/user/suggest', $scope.data, {
                headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
            .success(function (data) {
                if (data.code == 200) {
                    alert('提交成功');
                    window.location.hash = "#tab/profile";
                    window.location.reload();
                }
            })
        }
    } 
}])

.controller('HelpCtrl', ['$scope', '$rootScope', '$http', 'Service', function ($scope,$rootScope,$http,Service) {
    Service.checkLogin(function () {
        if (Service.isLogin == false) {
            window.location.hash = "#tab/profile";
            window.location.reload();
        }
    })
}])

;