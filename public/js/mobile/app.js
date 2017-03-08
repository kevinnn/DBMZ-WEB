angular.module('YYYG', ['ionic','YYYG.filters'])

.config(['$stateProvider', '$httpProvider', '$urlRouterProvider', '$ionicConfigProvider', function($stateProvider, $httpProvider, $urlRouterProvider, $ionicConfigProvider) {
    $ionicConfigProvider.backButton.text('返回').icon('ion-chevron-left');
    $ionicConfigProvider.navBar.alignTitle('center');
    // 路由配置
    $stateProvider
    // TAB - 首页 最新揭晓 晒单 购物车 个人
    .state('tab', {
        url: '/tab',
        abstract: true,
        templateUrl: '/public/tpls/mobile/tab/tabs.html',
        controller: 'TabCtrl'
    })
        .state('tab.index', {
            url: '/index',
            views: {
                'tab-index': {
                    templateUrl: '/public/tpls/mobile/tab/tab-index.html',
                    controller: 'IndexCtrl'
                }
            }
        })
        .state('tab.result', {
            url: '/result',
            views: {
                'tab-result': {
                    templateUrl: '/public/tpls/mobile/tab/tab-result.html',
                    controller: 'ResultCtrl'
                }
            }
        })
        .state('tab.show', {
            url: '/show',
            views: {
                'tab-show': {
                    templateUrl: '/public/tpls/mobile/tab/tab-show.html',
                    controller: 'ShowCtrl'
                }
            }
        })
        .state('tab.cart', {
            url: '/cart',
            views: {
                'tab-cart': {
                    templateUrl: '/public/tpls/mobile/tab/tab-cart.html',
                    controller: 'CartCtrl'
                }
            }
        })
        .state('tab.profile', {
            url: '/profile',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/tab/tab-profile.html',
                    controller: 'ProfileCtrl'
                }
            }
        })

    // 商品中奖晒单列表
    .state('tab.index-show-page', { // 复用-首页
        url: '/index/show-product-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
        views: {
            'tab-index': {
                templateUrl: '/public/tpls/mobile/tab/tab-show.html',
                controller: 'ShowCtrl'
            }
        }
    })
        .state('tab.result-show-page', {
            url: '/result/show-product-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-result': {
                    templateUrl: '/public/tpls/mobile/tab/tab-show.html',
                    controller: 'ShowCtrl'
                }
            }
        })
        .state('tab.show-show-page', {
            url: '/show/show-product-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-show': {
                    templateUrl: '/public/tpls/mobile/tab/tab-show.html',
                    controller: 'ShowCtrl'
                }
            }
        })
        .state('tab.profile-show-page', {
            url: '/profile/show-product-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/tab/tab-show.html',
                    controller: 'ShowCtrl'
                }
            }
        })
    // 往期揭晓
    .state('tab.index-history', { // 复用-首页
        url: '/index/history-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
        views: {
            'tab-index': {
                templateUrl: '/public/tpls/mobile/page/page-history.html',
                controller: 'HistoryCtrl'
            }
        }
    })
        .state('tab.result-history', { // 复用-最新揭晓
            url: '/result/history-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-result': {
                    templateUrl: '/public/tpls/mobile/page/page-history.html',
                    controller: 'HistoryCtrl'
                }
            }
        })
        .state('tab.show-history', { // 复用-晒单
            url: '/show/history-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-show': {
                    templateUrl: '/public/tpls/mobile/page/page-history.html',
                    controller: 'HistoryCtrl'
                }
            }
        })
        .state('tab.profile-history', { // 复用-个人
            url: '/profile/history-{pid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/page/page-history.html',
                    controller: 'HistoryCtrl'
                }
            }
        })

    // 云购页面
    .state('tab.index-yungou', { // 复用-首页
        url: '/index/yungou-{yid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
        views: {
            'tab-index': {
                templateUrl: '/public/tpls/mobile/page/page-yungou.html',
                controller: 'YungouCtrl'
            }
        }
    })
        .state('tab.result-yungou', { // 复用-最新揭晓
            url: '/result/yungou-{yid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-result': {
                    templateUrl: '/public/tpls/mobile/page/page-yungou.html',
                    controller: 'YungouCtrl'
                }
            }
        })
        .state('tab.show-yungou', { // 复用-晒单
            url: '/show/yungou-{yid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-show': {
                    templateUrl: '/public/tpls/mobile/page/page-yungou.html',
                    controller: 'YungouCtrl'
                }
            }
        })
        .state('tab.profile-yungou', { // 复用-个人中心
            url: '/profile/yungou-{yid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/page/page-yungou.html',
                    controller: 'YungouCtrl'
                }
            }
        })


    // 晒单详情
    .state('tab.index-show', { // 复用-首页
        url: '/index/show-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
        views: {
            'tab-index': {
                templateUrl: '/public/tpls/mobile/page/page-show.html',
                controller: 'ShowDetailCtrl'
            }
        }
    })
        .state('tab.result-show', { // 复用-最新揭晓
            url: '/result/show-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-result': {
                    templateUrl: '/public/tpls/mobile/page/page-show.html',
                    controller: 'ShowDetailCtrl'
                }
            }
        })
        .state('tab.show-show', { // 复用-晒单
            url: '/show/show-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-show': {
                    templateUrl: '/public/tpls/mobile/page/page-show.html',
                    controller: 'ShowDetailCtrl'
                }
            }
        })
        .state('tab.profile-show', { // 复用-个人
            url: '/profile/show-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/page/page-show.html',
                    controller: 'ShowDetailCtrl'
                }
            }
        })

    // 计算详情
    .state('tab.index-calcuate', { // 复用-首页
        url: '/index/calcuate-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
        views: {
            'tab-index': {
                templateUrl: '/public/tpls/mobile/page/page-calcuate.html',
                controller: 'CalcuateCtrl'
            }
        }
    })
        .state('tab.result-calcuate', { // 复用-最新揭晓
            url: '/result/calcuate-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-result': {
                    templateUrl: '/public/tpls/mobile/page/page-calcuate.html',
                    controller: 'CalcuateCtrl'
                }
            }
        })
        .state('tab.show-calcuate', { // 复用-晒单
            url: '/show/calcuate-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-show': {
                    templateUrl: '/public/tpls/mobile/page/page-calcuate.html',
                    controller: 'CalcuateCtrl'
                }
            }
        })
        .state('tab.profile-calcuate', { // 复用-个人
            url: '/profile/calcuate-{sid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/page/page-calcuate.html',
                    controller: 'CalcuateCtrl'
                }
            }
        })

    // 支付详情
    .state('tab.payment', {
        url: '/cart/payment-{cashierid:[0-9]+}',
        views: {
            'tab-cart': {
                templateUrl: '/public/tpls/mobile/page/page-payment.html',
                controller: 'PaymentCtrl'
            }
        }
    })

    // 支付结果
    .state('tab.payresult', {
        url: '/cart/payresult-{cashierid:[0-9]+}-{fee:[0-9]+}-{isCancel:[0-1]{1}}',
        views: {
            'tab-cart': {
                templateUrl: '/public/tpls/mobile/page/page-payresult.html',
                controller: 'PayresultCtrl'
            }
        }
    })
    .state('tab.payresult-yungou', {
        url: '/cart/yungou-{yid:[0-9]{1,10}}-{time:[0-9]{1,20}}',
        views: {
            'tab-cart': {
                templateUrl: '/public/tpls/mobile/page/page-yungou.html',
                controller: 'YungouCtrl'
            }
        }
    })

    .state('tab.rechargeResult',{
        url: '/profile/rechargeResult-{tradeId:[0-9]+}-{fee:[0-9]+}-{isCancel:[0-1]{1}}',
        views: {
            'tab-profile': {
                templateUrl: '/public/tpls/mobile/page/page-rechargeResult.html',
                controller: 'RechargeResultCtrl'
            }
        }
    })

    // 个人中心内页
    .state('tab.profile-setting', {
        url: '/profile/setting',
        views: {
            'tab-profile': {
                templateUrl: '/public/tpls/mobile/profile/profile-setting.html',
                controller: 'ProfileSettingCtrl'
            }
        }
    })
        .state('tab.profile-userRecord', {
            url: '/profile/userRecord-{uid:[0-9]{1,10}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/page/page-userRecord.html',
                    controller: 'UserRecordCtrl'
                }
            }
        })

        .state('tab.profile-editPsd', {
            url: '/profile/editPsd',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-editPsd.html',
                    controller: 'ProfileCtrl'
                }
            }
        })
        .state('tab.profile-recharge', {
            url: '/profile/recharge',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-recharge.html',
                    controller: 'RechargeCtrl'
                }
            }
        })
        .state('tab.profile-credit', {
            url: '/profile/credit',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-credit.html',
                    controller: 'CreditCtrl'
                }
            }
        })
        .state('tab.profile-credit-record', {
            url: '/profile/creditRecord',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-credit-record.html',
                    controller: 'CreditRecordCtrl'
                }
            }
        })
        .state('tab.profile-balance', {
            url: '/profile/balance',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-balance.html',
                    controller: 'BalanceCtrl'
                }
            }
        })
        .state('tab.profile-recordBuy', {
            url: '/profile/recordBuy',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-recordBuy.html',
                    controller: 'recordBuyCtrl'
                }
            }
        })
        .state('tab.profile-recordWin', {
            url: '/profile/recordWin',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-recordWin.html',
                    controller: 'recordWinCtrl'
                }
            }
        })
        .state('tab.profile-expose', {
            url: '/profile/expose',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-expose.html',
                    controller: 'exposeCtrl'
                }
            }
        })
        .state('tab.profile-expose-add', {
            url: '/profile/expose/add-{yid:[0-9]{1,10}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-expose-add.html',
                    controller: 'exposeAddCtrl'
                }
            }
        })
        .state('tab.profile-expose-success', {
            url: '/profile/expose/success-{yid:[0-9]{1,10}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-expose-success.html',
                    controller: 'exposeSuccessCtrl'
                }
            }
        })
        .state('tab.profile-address', {
            url: '/profile/address',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-address.html',
                    controller: 'AddressCtrl'
                }
            }
        })
        .state('tab.profile-addressEdit', {
            url: '/profile/addressEdit-{addressId:[0-9]{1,10}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-addressEdit.html',
                    controller: 'AddressEditCtrl'
                }
            }
        })
        .state('tab.profile-winDetail', {
            url: '/profile/winDetail-{winOrderId:[0-9]{1,10}}',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-winDetail.html',
                    controller: 'winDetailCtrl'
                }
            }
        })
        .state('tab.profile-agency', {
            url: '/profile/agency',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-agency.html',
                    controller: 'AgencyCtrl'
                }
            }
        })
        .state('tab.profile-suggest', {
            url: '/profile/suggest',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-suggest.html',
                    controller: 'SuggestCtrl'
                }
            }
        })
        .state('tab.profile-help', {
            url: '/profile/help',
            views: {
                'tab-profile': {
                    templateUrl: '/public/tpls/mobile/profile/profile-help.html',
                    controller: 'HelpCtrl'
                }
            }
        })

    // winDetail 确认地址
    .state('tab.profile-winDetail-address', {
        url: '/profile/winDetail-{winOrderId:[0-9]{1,10}}/address',
        views: {
            'tab-profile': {
                templateUrl: '/public/tpls/mobile/profile/profile-address.html',
                controller: 'addressCtrl'
            }
        }
    })
    // 最新揭晓
    .state('tab.result-userRecord', { //复用他人中心
        url: '/result/userRecord-{uid:[0-9]{1,10}}',
        views: {
            'tab-result': {
                templateUrl: '/public/tpls/mobile/page/page-userRecord.html',
                controller: 'UserRecordCtrl'
            }
        }
    })
    .state('tab.index-userRecord',{ //复用-他人中心
        url: '/index/userRecord-{uid:[0-9]{1,10}}',
        views: {
            'tab-index': {
                templateUrl: '/public/tpls/mobile/page/page-userRecord.html',
                controller: 'UserRecordCtrl'
            }
        }
    });
    $urlRouterProvider.otherwise('/tab/index');
}]);

// 过滤器
angular.module('YYYG.filters', [])
.filter('range', [function() {
    return function(input, total) {
        total = parseInt(total);
        for (var i = 0; i < total; i++) {
            input.push(i);
        }
        return input;
    };
}])
.filter('dateTransfer', [function () {
    return function (timeout) {
        var minute = parseInt(timeout/60);
        var second = timeout%60;
        return minute+"分"+second+"秒";
    }
}])
.filter('minute', [function () {
    return function (timeout) {
        if (typeof timeout === 'undefined' || !timeout || timeout <= 0) 
            return '00';
        var time = parseInt(timeout/(60*1000));
        return time < 10 ? '0'+time : time;
    }
}])
.filter('second', [function () {
    return function (timeout) {
        if (typeof timeout === 'undefined' || !timeout || timeout <= 0) 
            return '00';
        var time = parseInt(timeout/1000)%60;
        return time < 10 ? '0'+time : time;
    }
}])
.filter('microsecond', [function () {
    return function (timeout) {
        if (typeof timeout === 'undefined' || !timeout || timeout <= 0) 
            return '00';
        var time = parseInt(timeout/10)%100;
        return time < 10 ? '0'+time : time;
    }
}]);