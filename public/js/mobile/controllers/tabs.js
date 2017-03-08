// TAB控制器 & 抽屉菜单控制器 - 全局控制
angular.module('YYYG')

// TAB控制器
.controller('TabCtrl', ['$scope', '$rootScope', '$http', '$interval', '$timeout', '$ionicModal', '$ionicLoading', 'YungouService', function($scope, $rootScope, $http, $interval, $timeout, $ionicModal, $ionicLoading, YungouService) {
    $rootScope.setCartCount = function () {
        $rootScope.cartCount.textContent = $rootScope.shoppingCarts.length;
        $rootScope.cartDetailCount.textContent = $rootScope.shoppingCarts.length;
        if ($rootScope.shoppingCarts.length == 0) {
            $($rootScope.cartCount).css({
                display: 'none'
            });
            $($rootScope.cartDetailCount).css({
                display: 'none'
            });
        } else {
            $($rootScope.cartCount).css({
                display: 'block'
            });
            $($rootScope.cartDetailCount).css({
                display: 'block'
            });
        }
    }

    $scope.newSign = document.createElement('div');
    $scope.className = 'new-sign';
    $scope.id = "newSign";


    // 判断是否微信浏览器
    $rootScope.is_weixin = (function(){
        if (navigator.userAgent.toLowerCase().match(/MicroMessenger/i) == 'micromessenger') {
            return true;
        } else {
            return false;
        }
    })();

    //购物车全局变量
    $rootScope.shoppingCarts = $.parseJSON(window.localStorage.getItem('localCarts')) || [];
    //云购页面的购物车数量
    $rootScope.cartDetailCount = document.createElement('b');
    //tab的购物车数量
    $rootScope.cartCount = document.createElement('b');
    
    

    $($rootScope.cartCount).css({
        position:'absolute',
        top: '2px',
        fontSize: '12px',
        lineHeight: '8px',
        color: '#fff',
        left: '71%',
        background: '#DF3051',
        borderRadius: '50%',
        zIndex: '9999',
        padding: '5px'
    });
    $rootScope.setCartCount();
    $(document).ready(function() {
        $(document.getElementsByTagName('ion-tabs')[0].firstChild).append($rootScope.cartCount);
    })

    // 处理本地购物车存储
    $rootScope.mergeShoppingCart = function() {
        var localCarts = $.parseJSON(window.localStorage.getItem('localCarts'));
        if (localCarts) {
            for (var i = 0; i < localCarts.length; i++) {
                $rootScope.addToCart(localCarts[i]);
            }
            window.localStorage.removeItem('localCarts');
        }
        $rootScope.setCartCount();
    }

    //获取购物车清单
    $rootScope.getCart = function() {
        $http.get('/shoppingCart/intoCart')
            .success(function(data) {
                if (data.code === 200) {
                    $rootScope.shoppingCarts = data.data;
                }
                $rootScope.mergeShoppingCart();
            })
            .error(function(data) {
                console.log(data);
            });
    }

    //添加到购物车
    $rootScope.addToCart = function(item, $event) {
        if (typeof item.amount === "undefined") {
            var i = {};
            i.amount = item.product.singlePrice;
            i.price = item.product.price;
            i.productId = item.product.id;
            i.saleCount = item.yungou.saleCount;
            i.singlePrice = item.product.singlePrice;
            i.term = item.yungou.term;
            i.thumbnailUrl = item.product.thumbnailUrl;
            i.title = item.product.title;
            i.yungouId = item.yungou.id;
            item = i;
        }
        if ($rootScope.user) {
            // 登录用户添加购物车
            $http.post('/shoppingCart/add', {
                yungouId: item.yungouId,
                amount: item.amount
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).success(function(data, status, headers, config) {
                if (data.code == 200) {
                    var isNewYungou = true;
                    for (var i = 0; i < $rootScope.shoppingCarts.length; i++) {
                        if ($rootScope.shoppingCarts[i].yungouId == item.yungouId) {
                            isNewYungou = false;
                            if (parseInt($rootScope.shoppingCarts[i].amount) + parseInt(item.amount) > $rootScope.shoppingCarts[i].price)
                                $rootScope.shoppingCarts[i].amount = $rootScope.shoppingCarts[i].price;
                            else
                                $rootScope.shoppingCarts[i].amount = parseInt($rootScope.shoppingCarts[i].amount) + parseInt(item.amount);
                            break;
                        }
                    }
                    if (isNewYungou) {
                        $rootScope.shoppingCarts.push(item);
                    }
                }
                $rootScope.setCartCount();
            }).error(function(data) {});
        } else {
            // 未登录用户添加购物车
            var isNewYungou = true;
            for (var i = 0; i < $rootScope.shoppingCarts.length; i++) {
                if ($rootScope.shoppingCarts[i].yungouId == item.yungouId) {
                    isNewYungou = false;
                    if (parseInt($rootScope.shoppingCarts[i].amount) + parseInt(item.amount) > $rootScope.shoppingCarts[i].price)
                        $rootScope.shoppingCarts[i].amount = $rootScope.shoppingCarts[i].price;
                    else
                        $rootScope.shoppingCarts[i].amount = parseInt($rootScope.shoppingCarts[i].amount) + parseInt(item.amount);
                    break;
                }
            }
            if (isNewYungou) {
                $rootScope.shoppingCarts.push(item);
            }
            window.localStorage.setItem('localCarts', JSON.stringify($rootScope.shoppingCarts));
            $rootScope.setCartCount();
        }
        return false;
    };

    //添加购物车动画
    $rootScope.toCartCartoon = function(id, content, defaultType) {
        var imgLeft, imgTop, cartLeft, cartTop, thumbnail, current, scrollTop;

        if (/android|webos|blackberry|iemobile|opera mini/i.test(navigator.userAgent.toLowerCase())) {
            if (defaultType) {
                current = document.getElementById(content);
                scrollTop = current.scrollTop;
            } else {
                current = document.getElementById(content);
                scrollTop = current.scrollTop;
            }
        } else {
            if (defaultType) {
                current = document.getElementById(content).firstChild;
                scrollTop = 0 - parseInt($(current).css('transform').split(',')[5].split(')')[0]);
            } else {
                current = document.getElementById(content).firstChild;
                scrollTop = 0 - parseInt($(current).css('transform').split(',')[5].split(')')[0]);
            }
        }
        if (defaultType) {
            var imgElement = document.getElementById(id);
            imgLeft = imgElement.offsetLeft;
            imgTop = imgElement.offsetTop;

            var cartElement = imgElement.parentNode.parentNode.parentNode.parentNode;
            imgTop -= scrollTop;
            current = imgElement.offsetParent;
            while (current !== null) {
                imgTop += current.offsetTop;
                imgLeft += current.offsetLeft;
                current = current.offsetParent;
            }
            thumbnail = $("<img src='" + imgElement.src + "' width=" + imgElement.offsetWidth + " height=" + imgElement.offsetHeight + " style='display:block;position:absolute;left:" + imgLeft + "px;top:" + imgTop + "px;'>");
            cartLeft = (cartElement.offsetWidth) * 0.7 + parseInt($(cartElement).css('margin-left').replace('px', ''));
            cartTop = (document.documentElement.clientHeight - 20);
        } else {
            imgLeft = document.documentElement.clientWidth * 0.5 - 100;
            imgTop = 44;
            imgTop -= scrollTop;
            thumbnail = $("<img src='" + id + "' width=200 height=200 style='display:block;position:absolute;left:" + imgLeft + "px;top:" + imgTop + "px;'>");
            cartLeft = (document.documentElement.clientWidth) - 15;
            cartTop = 20;
        }
        $(document.body).append(thumbnail);
        thumbnail.css("zIndex", 9999).animate({
            position: "absolute",
            width: 0,
            height: 0,
            left: cartLeft + "px",
            top: cartTop + "px",
            display: "block",
            "z-Index": 9999
        }, 700, function() {
            thumbnail.remove();
        });
    };


    // 判断是否登录
    function isLogin() {
        $http.get('/user/isLogin').success(function(data) {
            if (data.code === 200) {
                $rootScope.user = data.data[0];
                if ($rootScope.user.showHb == 1 && $rootScope.user.invitedBy != '') {
                    $('ion-side-menu-content').append($scope.newSign);
                    $($scope.newSign).css('display','block');
                    $http.get('/user/notShowHb')
                    .success(function (data) {
                        if (data.code == 200) {
                            $rootScope.user.showHb = 0;
                        }
                    })
                    .error(function (data) {
                        console.log(data);
                    });
                }
                $rootScope.getCart();
            }
        });
    }
    isLogin();
    $interval(isLogin,10*1000);

    // 检查是否登录 未登录：弹出登录界面 & 前往微信授权
    $rootScope.checkLoginAndModal = function($event) {
        if (!$rootScope.user) {
            if ($rootScope.is_weixin) {
                window.location.href = 'http://'+window.location.host+'/wx/login?returnAddress=/tab/'+$rootScope.activedTab;
            } else {
                $rootScope.loginModal.show();
            }
            if ($event) {
                $event.preventDefault();
            }
        }
    }

    // 登录
    $rootScope.loginUser = {};
    $scope.logining = false;
    $scope.loginBtnText = '登录';
    $ionicModal.fromTemplateUrl('/public/tpls/mobile/modal/modal-login.html', {
        scope: $scope
    }).then(function(loginModal) {
        $rootScope.loginModal = loginModal;
    });
    // $ionicModal.fromTemplateUrl('/public/tpls/mobile/modal/modal-record-number.html', {
    //     scope: $scope
    // }).then(function(numberModal) {
    //     $rootScope.numberModal = numberModal;
    // });
    // 登录 - 登录函数
    $scope.login = function() {
        $scope.logining = true;
        $scope.loginBtnText = '登录中..';
        $http.post('/user/login', {
            phoneNumber: $rootScope.loginUser.phone,
            password: $rootScope.loginUser.password
        }, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            transformRequest: function(data) {
                return $.param(data);
            }
        }).success(function(data) {
            if (data.code === 200) {
                $rootScope.user = data.data[0];
                if (window.location.hash.split('/').length <= 3) {
                    window.location.reload(); // 在tab
                } else {
                    window.location.hash = '#/tab/index';
                    window.location.reload();
                }
            } else if (data.code === 405) {
                alert('手机或密码错误');
            }
        }).error(function(data) {
            console.error(data);
        }).finally(function() {
            $scope.logining = false;
            $scope.loginBtnText = '登录';
        });
    }

    // 注册
    $rootScope.registerUser = {};
    $scope.registering = false;
    $scope.codeBtnText = '发送验证码';
    $scope.countDown = 60;
    $scope.codeBtnDisabled = false;
    $scope.registerBtnTxt = '立即注册';
    $scope.timer = undefined;
    $ionicModal.fromTemplateUrl('/public/tpls/mobile/modal/modal-register.html', {
        scope: $scope
    }).then(function(registerModal) {
        $rootScope.registerModal = registerModal;
    });
    // 注册 - 发送验证码
    $scope.sendCode = function() {
        $http.get('/user/isUserExit?phoneNumber=' + $rootScope.registerUser.phone).success(function(data) {
            if (data.code == 404) {
                $scope.codeBtnDisabled = true;
                $http.get('/user/sendVerificationCode?phoneNumber=' + $rootScope.registerUser.phone).success(function(data) {
                    if (data.code === 200) {
                        $scope.timer = $interval(function() {
                            if ($scope.countDown <= 0) {
                                $scope.codeBtnDisabled = false;
                                $scope.codeBtnText = '发送验证码';
                                $interval.cancel($scope.timer);
                                $scope.timer = null;
                                $scope.countDown = 60;
                                return;
                            }
                            $scope.codeBtnText = (--$scope.countDown) + '秒重发..';
                        }, 1000);
                    } else {
                        $scope.codeBtnText = '获取验证码';
                        alert('发送短信太过频繁！');
                    }
                })
            } else {
                // 手机号已被注册
                alert('该手机号已被注册！');
            }
        }).error(function(data) {
            console.error(data);
        });
    };
    // 注册 - 注册函数
    $scope.register = function() {
        $scope.registering = true;
        $scope.registerBtnTxt = '注册中...';
        $http.post('/user/register', {
            phoneNumber: $rootScope.registerUser.phone,
            password: $rootScope.registerUser.password,
            code: $rootScope.registerUser.code,
            invitedCode: $rootScope.registerUser.invitedCode
        }, {
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            transformRequest: function(data) {
                return $.param(data);
            }
        }).success(function(data) {
            if (data.code === 200) {
                // 注册成功
                $scope.registering = false;
                $scope.codeBtnText = '发送验证码';
                $scope.countDown = 60;
                $scope.codeBtnDisabled = false;
                $scope.registerBtnTxt = '立即注册';
                if ($scope.timer) {
                    $interval.cancel($scope.timer);
                    $scope.timer = null;
                }
                if (window.location.hash.split('/').length <= 3) {
                    window.location.reload(); // 在tab
                } else {
                    window.location.hash = '#/tab/index';
                    window.location.reload();
                }
            } else if (data.code === 405) {
                $scope.registering = false;
                $scope.registerBtnTxt = '立即注册';
                alert('验证码不正确');
            }
        });
    }

    // 全局变量：获取当前时间戳
    $rootScope.timeStamp = new Date().getTime();
    $interval(function() {
        $rootScope.timeStamp = new Date().getTime();
    }, 1000);

    // tab 路由跟踪
    $rootScope.activedTab = window.location.hash.split('/')[2];
    $rootScope.activedTab = window.location.hash.split('/')[2];
    if (window.location.hash.split('/').length <= 3) {
        $rootScope.hideTabs = '';
    } else {
        $rootScope.hideTabs = 'tabs-item-hide';
    }
    angular.element(window).on('hashchange', function() {
        $rootScope.activedTab = window.location.hash.split('/')[2];
        if (window.location.hash.split('/').length <= 3) {
            $rootScope.hideTabs = '';
        } else {
            $rootScope.hideTabs = 'tabs-item-hide';
        }
    });

    // 图文 Modal
    $ionicModal.fromTemplateUrl('/public/tpls/mobile/page/page-yungou-content.html', {
        scope: $scope
    }).then(function(contentModal) {
        $rootScope.contentModal = contentModal;
    });
    
    

    //新注册用户点击红包跳到个人中心的balance页面
    $scope.newSign.addEventListener('click',function () {
        $($scope.newSign).css({
            display: 'none'
        });
        var target = 'profile';
        if (location.href.slice(0-target.length) != target)
            location.hash = "#/tab/profile?location=balance";
        else
            location.hash = "#/tab/profile/balance";
        
    });

    // 根据productId前往最新一期的云购
    $rootScope.toLatestYungou = function (pid,e) {
        if (pid != '') {
            YungouService.toLatestYungou(function (id) {
                location.hash = "#tab/"+$rootScope.activedTab+'/yungou-'+id+'-'+$rootScope.timeStamp;
            },pid);
            e.preventDefault();
        }

    }
}])

// 抽屉菜单控制器
.controller('SideMenuCtrl', ['$scope', '$rootScope', '$http', '$ionicSideMenuDelegate', '$ionicLoading', function($scope, $rootScope, $http, $ionicSideMenuDelegate, $ionicLoading) {
    // 打开左抽屉
    $rootScope.toggleLeftSideMenu = function() {
        $ionicSideMenuDelegate.toggleLeft();
    };

    // 控制loading
    $rootScope.showLoading = function(text) {
        $ionicLoading.show({
            template: text
        });
    };
    $rootScope.hideLoading = function() {
        $ionicLoading.hide();
    };
    // 获取分类数据
    $rootScope.categories = [];
    $http.get('/category/getAll').success(function(data) {
        if (data.code === 200) {
            $rootScope.categories = data.data;
        }
    }).error(function(data) {
        console.log(data);
    });

    // 选择分类
    $rootScope.yungous = [];
    $rootScope.index_title = '全部商品';
    $rootScope.selectCategory = function(categoryId, categoryName, toggleLeft) {
        $rootScope.index_title = categoryName;
        if (toggleLeft) {
            $rootScope.toggleLeftSideMenu();
        }
        if ($rootScope.current_category === categoryId) {
            return;
        }
        $rootScope.yungous = [];
        $rootScope.current_category = categoryId;
        $rootScope.current_sort = 0;
        if (categoryId === 0) { // 0：全部商品
            $rootScope.showLoading('加载中..');
            $http.get('/yungou/getAll').success(function(data) {
                if (data.code === 200) {
                    $rootScope.yungous = data.data;
                    $rootScope.sort();
                }
            }).finally(function() {
                $rootScope.hideLoading();
            });
        } else { // 具体分类
            $rootScope.showLoading('加载中..');
            $http.get('/yungou/getCategory?id=' + categoryId).success(function(data) {
                if (data.code === 200) {
                    $rootScope.yungous = data.data;
                }
            }).finally(function() {
                $rootScope.hideLoading();
            });
        }
    };
    $rootScope.sort = function () {
        $rootScope.yungous = $rootScope.yungous.sort(function(a, b) {
            switch ($rootScope.current_sort) {
                case 0:
                    if (b.product.priority == a.product.priority) {
                        return Date.parse(b.yungou.createdTime) - Date.parse(a.yungou.createdTime) || $rootScope.yungous.indexOf(b)-$rootScope.yungous.indexOf(a);
                    }
                    return parseInt(b.product.priority) - parseInt(a.product.priority) || $rootScope.yungous.indexOf(b)-$rootScope.yungous.indexOf(a);
                    break;
                case 1:
                    if (b.yungou.term == a.yungou.term) {
                        return Date.parse(b.yungou.createdTime) - Date.parse(a.yungou.createdTime) || $rootScope.yungous.indexOf(b)-$rootScope.yungous.indexOf(a);
                    }
                    return b.yungou.term - a.yungou.term || $rootScope.yungous.indexOf(b)-$rootScope.yungous.indexOf(a);
                    break;
                case 2:
                    return (a.product.price - a.yungou.saleCount) - (b.product.price - b.yungou.saleCount) || $rootScope.yungous.indexOf(a)-$rootScope.yungous.indexOf(b);
                    break;
                case 3:
                    if ($rootScope.isUp)
                        return a.product.price - b.product.price || $rootScope.yungous.indexOf(a)-$rootScope.yungous.indexOf(b);
                    if ($rootScope.isDown) 
                        return b.product.price - a.product.price || $rootScope.yungous.indexOf(b)-$rootScope.yungous.indexOf(a);
                    break;
            }
        });
    };
    // 默认选择全部分类
    $rootScope.selectCategory(0, $rootScope.index_title, false);
}])

;