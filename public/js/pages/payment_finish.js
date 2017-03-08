(function() {
    $('.w-msgbox-close').click(function() {
        $('.w-mask').hide();
        $('.w-msgbox').hide();
    });

})();
app.controller('paymentFinishController', ['$scope', '$http',
    function($scope, $http) {

        $scope.orders = [];
        $scope.isLoading = true;
        $scope.lookNumberIndex = 0;
        $scope.isAllSuccessPay = true;
        $scope.isAllFailPay = true;
        $scope.cashierid = window.location.href.split("cashierid=").pop();
        $scope.getOrderByCashier = function(cashierid, callback) {
            $http.get('/cashier/getDetail?cashierid=' + cashierid).then(function(response) {
                $scope.orders = response.data.data;
                // 判断是否有失败的订单
                for (var i = 0; i < $scope.orders.length; i++) {
                    if($scope.orders[i].status === '0') {
                        $scope.isAllSuccessPay = false;
                    } else if ($scope.orders[i].status === '1'){
                        $scope.isAllFailPay = false;
                    }
                }
                console.log("isAllFailPay", $scope.isAllFailPay, "isAllSuccessPay", $scope.isAllSuccessPay)
                callback();
            }, function(response) {
                console.log("getOrderByCashier Error");
            });
        }

        $scope.showOrderNumberModal = function(index) {
            $scope.lookNumberIndex = index;
            $('.w-mask').show();
            $('#order-number-modal').show();

        }

        $scope.recommendProducts = [];
        var getRecommendProduct = function() {
            $http.get('/product/recommend?limit=5').then(function(response) {
                $scope.recommendProducts = $scope.recommendProducts.concat(response.data.data);
            }, function(response) {
                console.log("getRecommendProduct Error", response);
            });
        };

        $scope.getOrderByCashier($scope.cashierid, function() {
            $scope.isLoading = false;
        });
        getRecommendProduct();

    }
]);