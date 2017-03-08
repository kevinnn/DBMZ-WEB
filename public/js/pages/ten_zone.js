app.controller('tenZoneController', ['pageService', '$scope', '$http',
    function(pageService, $scope, $http) {
        $scope.tenZone = [];
        $scope.tenZoneCount = 0;
        $scope.isLoading = true;
        $scope.Math = Math;
        $scope.pageService = pageService;
        var TEN_ZONE_PER_PAGE = 30;
        $scope.getTenZone = function(page, callback) {
            $http.get('/yungou/getTenZone?limit=' + TEN_ZONE_PER_PAGE + "&page=" + page).then(function(response) {
                if (response.data.code === 200) {
                    $scope.tenZone = response.data.data;
                }
                callback();
            }, function(response) {
                console.log("getTenZone Error", response);
            });
        }

        $scope.getCountTenZone = function(callback) {
            $http.get('/yungou/getCountTenZone').then(function(response) {
                if (response.data.code === 200) {
                    $scope.tenZoneCount = response.data.data;
                }
                callback();
            }, function(response) {
                console.log("getCountTenZone Error", response);
            });
        }

        $scope.changePage = function(index) {
            $scope.isLoading = true;
            $scope.pageService.setPage("tenZone", index + 1);
            $scope.getTenZone(index + 1, function() {
                $scope.isLoading = false;
            })
        }

        $scope.getTenZone(1, function() {
            $scope.isLoading = false;
        });

        $scope.getCountTenZone(function() {
            $scope.pageService.init("tenZone",
                Math.ceil($scope.tenZoneCount / TEN_ZONE_PER_PAGE));
            $scope.pageService.setPage("tenZone", 1);
        });


    }
]);