app.controller('cartIndexController', ['$scope', '$http',
    function($scope, $http) {
        $scope.shoppingCarts = [];
        $scope.selectIndex = [];
        $scope.recommendProducts = [];
        $scope.user = {};
        $scope.isLoading = true;
        $scope.agreeContract = false;

        $scope.addOne = function(index) {
            if ($scope.shoppingCarts[index].amount ==
                parseInt($scope.shoppingCarts[index].price - $scope.shoppingCarts[index].saleCount)) {
                return;
            }
            $scope.shoppingCarts[index].amount = parseInt($scope.shoppingCarts[index].amount) + parseInt($scope.shoppingCarts[index].singlePrice);
        };

        $scope.minusOne = function(index) {
            if ($scope.shoppingCarts[index].amount > $scope.shoppingCarts[index].singlePrice) {
                $scope.shoppingCarts[index].amount -= $scope.shoppingCarts[index].singlePrice;
            }
        };

        $scope.getTotal = function() {
            var total = 0;
            for (var i = 0; i < $scope.selectIndex.length; i++) {
                if ($scope.selectIndex[i]) {
                total += parseInt($scope.shoppingCarts[i].amount);
                }
            }
            return total;
        }

        $scope.selectAll = function(isSelectAll) {
            if (isSelectAll) {
                for (var i in $scope.selectIndex) {
                    $scope.selectIndex[i] = true;
                }
            } else {
                for (var i in $scope.selectIndex) {
                    $scope.selectIndex[i] = false;
                }
            }
        }

        $scope.checkInputValue = function(index) {
            console.log($scope.shoppingCarts[index]);
            if (isNaN($scope.shoppingCarts[index].amount) ||
                $scope.shoppingCarts[index].amount < $scope.shoppingCarts[index].singlePrice) {
                $scope.shoppingCarts[index].amount = $scope.shoppingCarts[index].singlePrice;
            } else if ($scope.shoppingCarts[index].amount >
                parseInt($scope.shoppingCarts[index].price - $scope.shoppingCarts[index].saleCount)) {
                $scope.shoppingCarts[index].amount =
                    $scope.shoppingCarts[index].price - $scope.shoppingCarts[index].saleCount;
            } else if ($scope.shoppingCarts[index].amount % $scope.shoppingCarts[index].singlePrice !== 0) {
                $scope.shoppingCarts[index].amount -= $scope.shoppingCarts[index].amount % $scope.shoppingCarts[index].singlePrice;
            } else {
                $scope.shoppingCarts[index].amount = Math.floor($scope.shoppingCarts[index].amount);
            }
        }


        $scope.deleteOneItem = function(index) {

            $http.post('/shoppingCart/remove', {
                "id": $scope.shoppingCarts[index].shoppingCartId
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).then(function(response) {

                if (response.data.code === 200) {
                    $scope.shoppingCarts.splice(index, 1);
                    $scope.selectIndex.splice(index, 1);
                }

            }, function(response) {
                console.log(response);
            });
        };

        $scope.deleteItems = function() {
            var deleteIds = [];
            for (var i in $scope.selectIndex) {
                if ($scope.selectIndex[i]) {
                    deleteIds.push($scope.shoppingCarts[i].shoppingCartId);
                }
            }
            $http.post('/shoppingCart/removeLot', {
                ids: deleteIds
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).then(function(response) {
                if (response.data.code === 200) {
                    $scope.shoppingCarts = [];
                    $scope.selectIndex = [];
                }
            }, function(response) {
                console.log(response);
            });
        }

        $scope.updateButton = function() {
            if ($scope.agreeContract && $scope.shoppingCarts.length > 0) {
                toggleButton("提交订单", "button-disabled", false);
            } else {
                toggleButton("提交订单", "button-disabled", true);
            }
        }

        $scope.submitOrder = function() {
            if ($scope.agreeContract && $scope.shoppingCarts.length > 0) {
                toggleButton("提交中...", "button-submitting", true);

                var submitList = {
                    orders: []
                };

                for (var i = 0; i < $scope.selectIndex.length; i++) {
                    if ($scope.selectIndex[i]) {
                        submitList.orders.push({
                            productId: $scope.shoppingCarts[i].productId,
                            yungouId: $scope.shoppingCarts[i].yungouId,
                            count: $scope.shoppingCarts[i].amount
                        });
                    }
                }

                $http.post('/cashier/add', submitList, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    transformRequest: function(data) {
                        return $.param(data);
                    }
                }).then(function(response) {
                    if (response.data.code === 200) {
                        window.location = '/payment?cashierid=' + response.data.data;
                    }
                }, function(response) {
                    console.log(response);
                    toggleButton("提交订单", "button-submitting", false);

                });
            }

        }

        var getShoppingCarts = function() {
            // 获取服务器购物车信息
            $http.get('/shoppingCart/intoCart').then(function(response) {
                if (response.data.code === 200 && response.data.data.length > 0) {
                    $scope.shoppingCarts = $scope.shoppingCarts.concat(response.data.data);
                    initSelectIndex();
                }
                $scope.isLoading = false;

            }, function(response) {
                console.log(response);
            });
        };

        var getRecommendProduct = function() {
            $http.get('/product/recommend?limit=10').then(function(response) {
                $scope.recommendProducts = $scope.recommendProducts.concat(response.data.data);
            });
        };

        var getUserInfo = function() {
            $http.get('/user/isLogin').then(function(response) {
                if (response.data.code === 200) {
                    $scope.user = response.data.data[0];

                }
            });
        };

        var initSelectIndex = function() {
            for (var i = 0; i < $scope.shoppingCarts.length; i++) {
                $scope.selectIndex.push(true);
            }
        }

        /**
         * toggle submit button
         * @param  {[String]} text      [button.text]
         * @param  {[String]} className [button.toggleClassname]
         * @param  {[Boolean]} isabled  [disabled= isabled]
         */
        function toggleButton(text, className, isabled) {
            var submitButton = $("#submit-order-button");
            submitButton.text(text);
            submitButton.attr("disabled", isabled);
            submitButton.toggleClass(className);
        }

        // 启动函数
        getUserInfo();
        getShoppingCarts();
        getRecommendProduct();
    }
]);