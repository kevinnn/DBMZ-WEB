app.controller('rechargeFinishController', ['$scope', '$http',
    function($scope, $http) {
        $scope.code = location.search.match(/^\?.*/g)[0].substr(-3, 3);
        $scope.recommendProducts = [];
        var getRecommendProduct = function() {
            $http.get('/product/recommend?limit=5').then(function(response) {
                $scope.recommendProducts = $scope.recommendProducts.concat(response.data.data);
            });
        };
        getRecommendProduct();

    }
]);