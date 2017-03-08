$(document).ready(function() {
    var yNav = document.getElementById("yNav") || {};
    var offTop = yNav.offsetTop;
    window.onscroll = function() {
        if (document.body.scrollTop >= offTop) {
            $("#yNav").addClass("yNavIndexOutfixed");
        } else {
            $("#yNav").removeClass("yNavIndexOutfixed");
        }
    }
});




var app = angular.module('YYYG', ['ngDialog', 'filters']);

// angular 过滤器
var filters = angular.module('filters', []);
filters.filter('range', function() {
    return function(input, total) {
        total = parseInt(total);
        for (var i = 0; i < total; i++) {
            input.push(i);
        }
        return input;
    };
});
filters.filter('calCount', function() {
    return function(shoppingCarts) {
        var total = 0;
        for (var i = 0; i < shoppingCarts.length; i++) {
            total += shoppingCarts[i].amount;
        }
        return total;
    };
});

// ng-repeat结束后的调用
app.directive('onFinishRender', function($timeout) {
    return {
        restrict: 'A',
        link: function(scope, element, attr) {
            if (scope.$last === true) {
                $timeout(function() {
                    scope.$emit('ngRepeatFinished');
                });
            }
        }
    }
})

app.factory('pageService', [function() {
    var factory = {};
    var init = function(id, pageCount) {
        factory[id] = factory.id || {};
        factory[id].pageCount = pageCount;
    };
    factory.init = init;
    var setPage = function(id, page) {
        factory[id].pageArr = new Array();
        factory[id].page = page;
        var start = page - 2;
        var end = page + 2;
        if (start <= 0) {
            start = 1;
            end = factory[id].pageCount < 5 ? factory[id].pageCount : 5;
        } else if (end > factory[id].pageCount) {
            end = factory[id].pageCount;
            start = factory[id].pageCount - 4 <= 0 ? 1 : factory[id].pageCount - 4;
        }
        for (; start <= end; start++) {
            factory[id].pageArr.push(start);
        }
    }
    factory.setPage = setPage;
    return factory;
}]);


app.factory('intervalService', ['$interval',
    function($interval) {
        var factory = [];
        factory.init = function(id, timeArray, waitingTime) {
            factory[id] = {};
            factory[id].time = [];
            factory[id].waitingTime = waitingTime || 70;

            factory[id].remainTime = timeArray;
            for (var i = 0; i < factory[id].remainTime.length; i++) {
                factory[id].time[i] = convertTime(factory[id].remainTime[i]);
            }
        };


        var convertTime = function(t) {
            var time = [];
            time[0] = Math.floor(t / 6000) % 100;
            time[1] = Math.floor(t / 100 - time[0] * 60) % 100;
            time[2] = Math.floor(t - time[0] * 6000 - time[1] * 100) % 100;

            return time;
        };

        factory.countdown = function(id, callback) {
            if (factory[id].intervalFlag === undefined) {
                factory[id].before = new Date();
                factory[id].intervalFlag = $interval(function() {
                    var now = new Date();
                    for (var i = 0; i < factory[id].remainTime.length; i++) {
                        if (factory[id].remainTime[i] > 0) {
                            if ((now.getTime() - factory[id].before.getTime()) / 10 > factory[id].waitingTime) {
                                factory[id].remainTime[i] -= Math.ceil((now.getTime() - factory[id].before.getTime()) / 10) - 1;

                            } else {
                                factory[id].remainTime[i] -= factory[id].waitingTime / 10 - 1;

                            }
                            factory[id].time[i] = convertTime(factory[id].remainTime[i]);

                        } else if (factory[id].remainTime[i] <= 0) {
                            factory[id].remainTime[i] = 0;
                            callback(i, id);
                            factory[id].remainTime.splice(i, 1);
                            i--;
                        }

                    }
                    factory[id].before = now;


                    if (factory[id].remainTime.length === 0) {
                        $interval.cancel(factory[id].intervalFlag);
                        factory[id].intervalFlag = undefined;
                    }

                }, factory[id].waitingTime);
            }
        };

        return factory;
    }
]);

app.factory('addressService', ['$http',
    function($http) {
        var address = {};
        address.defaultIndex = 0;
        address.address = [];

        address.getAllUserAddress = function(callback) {
            $http.get('/address/getAll').then(function(response) {
                address.address = response.data.data;
                findDefault();
                callback();

            }, function(response) {
            });
        }

        address.add = function(a) {
            if (address.address.length < 5) {
                address.address.push({
                    province: a.province,
                    city: a.city,
                    area: a.area,
                    street: a.street,
                    postCode: a.postCode,
                    receiver: a.receiver,
                    idCode: a.idCode,
                    phoneNumber: a.phoneNumber,
                    provinceID: a.provinceID,
                    cityID: a.cityID,
                    areaID: a.areaID,
                    status: a.status
                });
                if (a.status === '1') {
                    address.setDefault(address.address.length - 1);
                }
                $http.post('/address/add', {
                    street: a.street,
                    postCode: a.postCode,
                    receiver: a.receiver,
                    idCode: a.idCode,
                    phoneNumber: a.phoneNumber,
                    provinceID: a.provinceID,
                    cityID: a.cityID,
                    areaID: a.areaID,
                    status: a.status
                }, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    transformRequest: function(data) {
                        return $.param(data);
                    }
                }).then(function(response) {
                    if (response.data.data && response.data.code === 200) {
                        address.address[address.address.length - 1].id = parseInt(response.data.data);

                    } else {
                        alert(response.data.msg);
                    }
                }, function(response) {
                });
            } else {
                alert("地址已经超过五条了喔~ 如需添加请删除不用的地址~");
            }

        };

        address.delete = function(index) {
            $http.post('/address/remove', {
                id: address.address[index].id
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).then(function(response) {
            }, function(response) {
            });
            address.address.splice(index, 1);
        };

        address.isValid = function(a) {
            if (a.province === "" || a.province === "省/直辖市") {
                return 0;
            }
            if (a.city === "" || a.province === "地级市") {
                return 0;
            }
            if (a.area === "" || a.area === "县/区") {
                return 0;
            }
            if (a.street === "" || a.street === undefined) {
                return 1;
            }
            if (a.receiver === "" || a.receiver === undefined) {
                return 2;
            }
            if (a.phoneNumber === undefined || isNaN(a.phoneNumber) || a.phoneNumber.length != 11) {
                return 3;
            }
            return -1;
        }

        address.setDefault = function(index) {
            address.address[address.defaultIndex].status = '0';
            address.defaultIndex = index;
            address.address[index].status = '1';
            //address.update(index);
        }

        address.update = function(index) {
            $http.post('/address/update', {
                address: address.format(address.address[index])
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).then(function(response) {
            }, function(response) {
            });
        };

        address.format = function(a) {
            return {
                street: a.street,
                postCode: a.postCode,
                receiver: a.receiver,
                idCode: a.idCode,
                phoneNumber: a.phoneNumber,
                provinceID: a.provinceID,
                cityID: a.cityID,
                areaID: a.areaID,
                status: a.status,
                id: a.id
            };
        }


        var findDefault = function() {
            for (var i = 0; i < address.address.length; i++) {
                if (address.address[i]['status'] === '1') {
                    address.defaultIndex = i;
                    break;
                }
            }
        }

        return address;
    }
]);

app.factory('userService', ['$http',
    function($http) {
        var userService = {};
        userService.user = {};
        var isFetching = false;
        userService.isLogin = function(callback) {
            if (userService.user !== {} || isFetching) {
                callback(user);
            } else {
                isFetching = true;
                $http.get('/user/isLogin').then(function(response) {
                    isFetching = false;
                }, function(response) {
                });
            }

        }
    }
]);



// 头部控制器
app.controller('HeaderController', function($rootScope, $scope, $http, $interval, $timeout, ngDialog) {
    $rootScope.user = null;
    $rootScope.login = login;
    $rootScope.register = register;
    $rootScope.logout = logout;
    $rootScope.checkLogin = checkLogin;
    $rootScope.shoppingCarts = [];
    $rootScope.isSignIn = false;
    $rootScope.signIn = signIn;
    $rootScope.toCartCartoon = toCartCartoon;
    $scope.pageSite = window.location.href.split("/").pop();
    $scope
    // 登录
    function login() {
        ngDialog.open({
            template: '/public/tpls/login.html',
            className: 'ngdialog-theme-default',
            controller: ['$rootScope', '$scope', '$http',
                function($rootScope, $scope, $http) {
                    // TODO
                    $scope.phoneNumber = '';
                    $scope.password = '';
                    $scope.loginBtnTxt = '登录';
                    $scope.loginBtnDisabled = false;
                    $scope.login = function() {
                        if ($scope.loginForm.$valid) {

                            $scope.loginBtnTxt = '登录中...';
                            $scope.loginBtnDisabled = true;
                            $http.post('/user/login', {
                                phoneNumber: $scope.phoneNumber,
                                password: $scope.password
                            }, {
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                                },
                                transformRequest: function(data) {
                                    return $.param(data);
                                }
                            }).success(function(data, status, headers, config) {
                                $scope.loginBtnTxt = '登录';
                                $scope.loginBtnDisabled = false;
                                if (data.code === 200) {
                                    $scope.closeThisDialog(0);
                                    window.location.reload();
                                } else if (data.code === 405) {
                                    alert('手机或密码错误');
                                }
                            }).error(function(data) {
                                console.error(data);
                            });
                        }
                    };
                    $rootScope.register = function() {
                        $scope.closeThisDialog(0);
                        register();
                    };
                }
            ]
        });
    };
    // 注册
    function register() {
        ngDialog.open({
            template: '/public/tpls/register.html',
            className: 'ngdialog-theme-default',
            controller: ['$rootScope', '$scope', '$http',
                function($rootScope, $scope, $http) {
                    var countDown = 60;
                    $scope.codeBtnDisabled = false;
                    $scope.codeBtnText = '发送验证码';
                    $scope.registerBtnTxt = '注册';
                    $scope.register = function() {
                        $scope.registerBtnDisabled = true;
                        $scope.registerBtnTxt = '注册中...';
                        $http.post('/user/register', {
                            phoneNumber: $scope.phoneNumber,
                            password: $scope.password,
                            code: $scope.code,
                            inviteCode: $scope.inviteCode
                        }, {
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                            },
                            transformRequest: function(data) {
                                return $.param(data);
                            }
                        }).success(function(data, status, headers, config) {
                            if (data.code == 200) {
                                location.reload();
                            } else if (data.code == 407) {
                                $scope.registerForm.inviteCode.$error.uncorrect = true; 
                            } else {
                                $scope.registerForm.code.$error.uncorrect = true;
                            }
                            $scope.registerBtnTxt = '注册';
                            $scope.registerBtnDisabled = false;
                        })
                        .error(function (data) {
                        });
                    }
                    $scope.reset = function() {
                        if ($scope.registerForm.phoneNumber.$error.unique) {
                            $scope.registerForm.phoneNumber.$error = {};
                            $scope.codeBtnDisabled = false;
                        }
                        if ($scope.registerForm.code.$error.uncorrect) {
                            $scope.registerForm.code.$error = {};
                        }
                    }
                    $scope.sendCode = function() {
                        $http.get('/user/isUserExit?phoneNumber=' + $scope.phoneNumber)
                            .success(function(data) {
                                if (data.code == 200) {
                                    $scope.registerForm.phoneNumber.$error.unique = true;
                                    $scope.codeBtnDisabled = true;
                                } else {
                                    $http.get('/user/sendVerificationCode?phoneNumber=' + $scope.phoneNumber)
                                        .success(function(data) {
                                            $scope.codeBtnDisabled = true;
                                            if (data.code == 200) {
                                                var timer = $interval(function() {
                                                    if (countDown <= 0) {
                                                        $scope.codeBtnDisabled = false;
                                                        $scope.codeBtnText = '获取验证码';
                                                        $interval.cancel(timer);
                                                        timer = null;
                                                        countDown = 60;
                                                        return;
                                                    }
                                                    $scope.codeBtnText = (--countDown) + '秒重发...';
                                                }, 1000);
                                            } else {
                                                $scope.codeBtnText = "发送失败";
                                                $timeout(function() {
                                                    $scope.codeBtnDisabled = false;
                                                    $scope.codeBtnText = "获取验证码";
                                                }, 2000);
                                            }
                                        })


                                }
                            })
                            .error(function (data) {
                            });

                    };
                    $rootScope.login = function() {
                        $scope.closeThisDialog(0);
                        login();
                    };
                }
            ]
        });
    };
    //检查是否登录
    function checkLogin(event) {
        if (!$rootScope.user) {
            login();
            event.preventDefault();
        }
    }
    //签到
    function signIn() {
        if ($rootScope.user) {
            $http.post('/credit/signIn', {}, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
                .success(function(data) {
                    if (data.code == 200) {
                        $scope.isSignIn = true;
                        $("#spanSignIn").css("opacity", 1).animate({
                            opacity: 0
                        }, 2000);
                    }
                })
                .error(function(data) {
                });
        }
    }
    // 注销
    function logout() {
        $http.get('/user/logout').success(function(data, status, headers, config) {
            $rootScope.user = null;
            window.location.href = '/';
        }).error(function(data) {
        });
    };

    $rootScope.addToCart = addToCart;
    $rootScope.deleteFromCart = deleteFromCart;

    // 添加购物车
    function addToCart(params) {
        if ($rootScope.user) {
            // 登录用户添加购物车
            $http.post('/shoppingCart/add', {
                yungouId: params.yungou,
                amount: params.amount
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).success(function(data, status, headers, config) {
                if (data.code === 200) {
                    var isNewYungou = true;
                    for (var i = 0; i < $rootScope.shoppingCarts.length; i++) {
                        if ($rootScope.shoppingCarts[i].yungou === params.yungou) {
                            isNewYungou = false;
                            if ($rootScope.shoppingCarts[i].amount < params.product.price)
                                $rootScope.shoppingCarts[i].amount += parseInt(params.amount);
                        }
                    }
                    if (isNewYungou) {
                        $rootScope.shoppingCarts.push(params);
                    }
                } else {}
            }).error(function(data) {
            });
        } else {
            // 未登录用户添加购物车
            var isNewYungou = true;
            for (var i = 0; i < $rootScope.shoppingCarts.length; i++) {
                if ($rootScope.shoppingCarts[i].yungou === params.yungou) {
                    isNewYungou = false;
                    if ($rootScope.shoppingCarts[i].amount < params.product.price)
                        $rootScope.shoppingCarts[i].amount += parseInt(params.amount);
                }
            }
            if (isNewYungou) {
                $rootScope.shoppingCarts.push(params);
            }
            window.localStorage.setItem('localCarts', JSON.stringify($rootScope.shoppingCarts));
        }
    };
    // 删除购物车
    function deleteFromCart(params) {

        if ($rootScope.user) {
            // 登录用户删除购物车
            $http.post('/shoppingCart/remove', {
                id: params.shoppingCartId
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).success(function(data, status, headers, config) {
                if (data.code === 200) {
                    var index = $rootScope.shoppingCarts.indexOf(params);
                    if (index != -1) {
                        $rootScope.shoppingCarts.splice(index, 1);
                    }
                } else {}
            }).error(function(data) {
            });
        } else {
            // 未登录用户删除购物车
            var index = $rootScope.shoppingCarts.indexOf(params);
            if (index != -1) {
                $rootScope.shoppingCarts.splice(index, 1);
            }
            if ($rootScope.shoppingCarts.length > 0) {
                window.localStorage.setItem('localCarts', JSON.stringify($rootScope.shoppingCarts));
            } else {
                window.localStorage.removeItem('localCarts');
            }
        }
    };

    // 检查是否登录 & 更新购物车信息：服务器 + 本地存储
    $http.get('/user/isLogin').success(function(data) {
        if (data.code === 200) {
            $rootScope.user = data.data[0];
            $rootScope.$broadcast("userLoaded",data.data[0]);
            // 获取服务器购物车信息
            $http.get('/shoppingCart/list').success(function(data, status, headers, config) {
                if (data.code === 200 && data.data.length > 0) {
                    $rootScope.shoppingCarts = $rootScope.shoppingCarts.concat(data.data);
                }
            }).error(function(data) {
            });
            $http.get('/credit/isSignIn')
                .success(function(data) {
                    if (data.code == 200) {
                        $scope.isSignIn = true;
                    }
                })
                .error(function(data) {
                })
        }

        // 处理本地购物车存储
        var localCarts = $.parseJSON(window.localStorage.getItem('localCarts'));
        if (localCarts) {
            if ($rootScope.user) {
                for (var i = 0; i < localCarts.length; i++) {
                    $rootScope.addToCart({
                        yungou: localCarts[i].yungou,
                        amount: localCarts[i].amount,
                        product: localCarts[i].product
                    });
                }
                window.localStorage.removeItem('localCarts');
            } else {
                $rootScope.shoppingCarts = $rootScope.shoppingCarts.concat(localCarts);
            }
        }
    });

    function toCartCartoon(id) {
        var imgElement = document.getElementById(id);
        var imgLeft = imgElement.offsetLeft;
        var imgTop = imgElement.offsetTop;
        var current = imgElement.offsetParent;
        while (current !== null) {
            imgLeft += current.offsetLeft;
            imgTop += current.offsetTop;
            current = current.offsetParent;
        }
        var thumbnail = $("<img src='" + imgElement.src + "' width="+imgElement.offsetWidth+" height="+imgElement.offsetHeight+" style='display:block;position:absolute;left:" + imgLeft + "px;top:" + imgTop + "px;'>");
        var cartElement = document.getElementById("cart-btn");
        var cartLeft = cartElement.offsetLeft;
        var cartTop = document.body.scrollTop;
        var offTop = document.getElementById("yNav").offsetTop;
        if (cartTop < offTop) {
            cartTop = offTop;
        }
        cartTop += (document.getElementById("yNav").offsetHeight / 2);
        current = cartElement.offsetParent;
        while (current !== null) {
            cartLeft += current.offsetLeft;
            current = current.offsetParent;
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

    // 搜索button函数
    $(".btnHSearch").click(function() {
        location.href = "/list/listAll.html?search="
            + $("#q").val();
    });
});