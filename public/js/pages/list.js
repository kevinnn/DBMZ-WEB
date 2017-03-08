app.controller('ListController', function($rootScope, $scope, $http, $location, $filter) {
    var pathname = window.location.pathname;
    $rootScope.cid = pathname.split('/')[pathname.split('/').length - 1].split('.')[0];
    $scope.cid = isNaN($rootScope.cid) ? $rootScope.cid : parseInt($rootScope.cid);
    $scope.yungous = [];
    $scope.pageNum = 0;
    $scope.currentPage = 1;
    $scope.perPage = 20;
    $scope.sort_type = 0;
    $scope.sort_reverse = false;
    $scope.recommendProducts = [];
    $scope.searchKey = window.location.href.split('?search=').length === 2 ? decodeURI(window.location.href.split('?search=')[window.location.href.split('?search=').length - 1]) : "";
    $scope.search = {
        product: {
            title: $scope.searchKey
        }
    };

    // list 全部商品
    // warning：在前端处理数据的搜索的过滤，实际上数据量大有可能造成卡顿
    if ($rootScope.cid === "listAll") {
        // 获取全部商品
        $http.get('/yungou/getAll').then(function(response) {
            if (response.data.code === 200) {
                for (var i = 0; i < response.data.data.length; i++) {
                    response.data.data[i].amount = response.data.data[i].product.singlePrice;
                }
                $scope.yungous = $filter('filter')(response.data.data, $scope.search);
                sort();
            }
        }, function(response) {
            console.log(response);
        });
        $scope.categoryInfo = {
            name: "所有商品"
        };
    } else {
        // 获取分类云购
        $http.get('/yungou/getCategory?id=' + $scope.cid).success(function(data) {
            if (data.code === 200) {
                for (var i = 0; i < data.data.length; i++) {
                    data.data[i].amount = data.data[i].product.singlePrice;
                }
                $scope.yungous = $filter('filter')(data.data, $scope.search);
                sort();
            }
        }).error(function(data) {
            console.log(data);
        });
        // 获取分类信息
        $http.get('/category/getById?id=' + $scope.cid).success(function(data) {
            if (data.code === 200 && data.data) {
                $scope.categoryInfo = data.data;
            }
        }).error(function(data) {
            console.log(data);
        });
    }

    // 添加参与数量
    $scope.addAmount = function(yungou) {
        yungou.amount = parseInt(yungou.amount) + parseInt(yungou.product.singlePrice);

        if (yungou.amount >= (yungou.product.price - yungou.yungou.saleCount)) {
            yungou.amount = yungou.product.price - yungou.yungou.saleCount;
        }
    };
    // 减少参与数量
    $scope.minusAmount = function(yungou) {
        yungou.amount = parseInt(yungou.amount) - parseInt(yungou.product.singlePrice);
        if (yungou.amount <= 0) {
            yungou.amount = yungou.product.singlePrice;
        }
    };
    // 检查数量
    $scope.checkAmount = function(yungou) {
        if (!parseInt(yungou.amount)) {
            yungou.amount = yungou.product.singlePrice;
        } else if (parseInt(yungou.amount) <= 0) {
            yungou.amount = yungou.product.singlePrice;
        } else if (parseInt(yungou.amount) >= (yungou.product.price - yungou.yungou.saleCount)) {
            yungou.amount = yungou.product.price - yungou.yungou.saleCount;
        } else {
            yungou.amount = Math.floor(parseInt(yungou.amount) / yungou.product.singlePrice) * yungou.product.singlePrice;
        }
    };
    // 加入清单
    $scope.addToCart = function(yungou) {
        $rootScope.addToCart({
            amount: parseInt(yungou.amount),
            yungou: parseInt(yungou.yungou.id),
            product: {
                thumbnailUrl: yungou.product.thumbnailUrl,
                title: yungou.product.title,
                price : parseInt(yungou.product.price)
            }
        });
    };
    // 立即夺宝
    $scope.addToPay = function(yungou) {
        location.href = "/cartIndex?yungouId="+yungou.yungou.id+"&amount="+yungou.amount;
    };
    // 点击排序
    $scope.clickSort = function(type) {
        if (type === $scope.sort_type) {
            $scope.sort_reverse = !$scope.sort_reverse;
        } else {
            $scope.sort_reverse = false;
            $scope.currentPage = 1;
        }
        $scope.sort_type = type;
        sort();
        console.log($scope.yungous);
    };
    // 换页
    $scope.goPage = function(n) {
        n = (n >= $scope.pageNum) ? $scope.pageNum : n;
        n = (n <= 1) ? 1 : n;
        $scope.currentPage = n;
        window.scrollTo(0, 150);
    };
    // 云购排序 0-剩余人次 1-即将揭晓 2-最新商品 3-总需人次
    function sort() {
        $scope.yungous = $scope.yungous.sort(function(a, b) {
            switch ($scope.sort_type) {
                case 2:
                    a = parseInt(a.product.price - a.yungou.saleCount);
                    b = parseInt(b.product.price - b.yungou.saleCount);
                    return (a - b);
                case 1:
                    return (parseInt(b.yungou.term) - parseInt(a.yungou.term));
                case 0:
                    a = Date.parse(a.yungou.createdTime);
                    b = Date.parse(b.yungou.createdTime);
                    return (a - b);
                case 3:
                    a = parseInt(a.product.price);
                    b = parseInt(b.product.price);
                    return $scope.sort_reverse ? (b - a) : (a - b);
            }
        });
        $scope.pageNum = Math.ceil($scope.yungous.length / $scope.perPage);
    };

    var getRecommendProduct = function() {
        $http.get('/product/recommend?limit=10').then(function(response) {
            $scope.recommendProducts = $scope.recommendProducts.concat(response.data.data);
        });
    };

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
        var thumbnail = $("<img src='" + imgElement.src + "' style='display:block;position:absolute;width:200px;height:200px;left:" + imgLeft + "px;top:" + imgTop + "px;'>");
        var cartElement = document.getElementById("cart-btn");
        var cartLeft = cartElement.offsetLeft;
        var cartTop = document.body.scrollTop;
        var offTop = document.getElementById("yNav").offsetTop;
        if (cartTop < offTop) {
            carTop = offTop;
        }
        cartTop += document.getElementById("yNav").offsetHeight / 2;
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
    }

    getRecommendProduct();
});