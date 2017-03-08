app.directive('scroll', function($window) { 
    return function(scope, element, attrs) {
        var raw = element[0];
        angular.element($window).bind("scroll", function () {
            if (raw.offsetHeight*2/3 <= this.pageYOffset) {
                scope.$apply(attrs.scroll);
            }
        });
    }; 
});
app.controller('resultsController', ['intervalService', '$scope', '$http',
    function(intervalService, $scope, $http) {
        // to do use http request
        $scope.Math = Math;
        $scope.intervalService = intervalService;
        $scope.finishedItems = [];
        $scope.remainItems = [];
        $scope.remainTimes = [];
        $scope.pages = [];
        $scope.getFastStart = getFastStart;
        $scope.currentPage = 0;

        $scope.init = false;

        $scope.revealListRevealItems = [];

        var second = (new Date()).valueOf()/1000;
        function getFastStart() {
            if (!$scope.init) {
                $scope.init = true;
            } else {
                return;
            }
            $scope.pages.push(++$scope.currentPage);
            $scope.remainItems[$scope.currentPage] = $scope.remainItems[$scope.currentPage]||[];
            $scope.finishedItems[$scope.currentPage] = $scope.finishedItems[$scope.currentPage]||[];
            $scope.remainTimes[$scope.currentPage] = $scope.remainTimes[$scope.currentPage]||[];
            var url = "/yungou/fastStart?limit=15";
            if ($scope.currentPage != 1) {
                url = "/yungou/fastStart?limit=15&startTime="+$scope.startTime;
            }
            $http.get(url)
            .success(function (data) {
                if (data.code==200) {
                    data = data.data;
                    if (data.length == 0) {
                        $scope.init = true;
                        return;
                    }
                    for(var index in data) {
                        if (data[index].status == 1) {
                            $scope.remainItems[$scope.currentPage].push(data[index]);
                            $scope.remainTimes[$scope.currentPage].push(data[index].timeout*100);
                        } else {
                            $scope.finishedItems[$scope.currentPage].push(data[index]);
                        }
                        if (index == data.length-1) $scope.startTime = data[index].startTime;
                    }
                    console.log($scope.remainItems)
                    $scope.intervalService.init($scope.currentPage, $scope.remainTimes[$scope.currentPage]);
                    $scope.intervalService.countdown($scope.currentPage, function(e,id) {
                        $scope.remainTimes[id][e] = 0;
                        $http.get("/order/winOrder?yungouId="+$scope.remainItems[id][e].yungouId)
                        .success(function(data) {
                            if (data.code == 200) {
                                data = data.data;
                                $scope.remainItems[id][e].winUser = data[0];
                            }
                            $scope.finishedItems[id].unshift($scope.remainItems[id][e]);
                            $scope.remainItems[id].splice(e,1);
                        })
                    });
                }
                $scope.init = false;
            })
            .error(function (data) {
                console.log(data);
            });
        }
        $scope.getFastStart();
        
        $http.get("/yungou/fastNotStart?limit=50")
        .success(function (data) {
            if (data.code == 200) {
                data = data.data;
                $scope.revealListRevealItems=data;
            }
        })
        .error(function (data) {
            console.log(data);
        });

    }
]);