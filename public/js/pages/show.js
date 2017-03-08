// app.directive('scrollLoading', function($window) {
//     return {
//         link: function(scope, element, attrs) {
//             console.log("scope", scope);
//             angular.element($window).bind("scroll", function() {
//             });
//         }
//     }

// });

app.controller('showController', ['intervalService', '$window', '$scope', '$http', '$interval', '$window',
    function(intervalService, $window, $scope, $http, $interval, $window) {
        $scope.div = [
            [],
            [],
            [],
            []
        ];

        var lastStartIndex = 0;
        $scope.amount = 0;
        var RECORD_PER_TIME = 9;
        $scope.isLoading = true;
        var isLoading = true;

        $scope.getAllShow = function(lastId, limit, callback) {
            if (arguments.length === 2) {
                callback = limit;
                limit = lastId;
                lastId = "";
            }
            $http.get('/show/getAll?lastId=' + lastId + '&limit=' + limit).then(function(response) {
                if (response.data.code === 200) {
                    var i = 0;
                    while (i < response.data.data.length) {
                        $scope.div[(i + lastStartIndex) % 4].push(response.data.data[i]);
                        i++;
                    }
                    lastStartIndex = (i + lastStartIndex) % 4;
                }
                callback();

            }, function(response) {
                console.log("error", response);
            })
        };
        
        $scope.getAllShowCount = function(callback) {
            $http.get('/show/getCountAll').then(function(response) {
                if (response.data.code === 200) {
                    $scope.amount = response.data.data;
                    callback();
                } else {
                    console.log("获取总数失败");
                }
            }, function(response) {
                console.log("getAllShowCount error",response);
            })
        }

        $scope.getAllShow(RECORD_PER_TIME, function() {
            $scope.isLoading = false;
            isLoading = false;
        });


        $scope.getAllShowCount(function() {
        });

        // 窗口函数
        angular.element($window).bind('scroll', function() {
            if (!isLoading) {
                if ($("body").height() - $window.scrollY < 1500) {
                    isLoading = true;
                    $scope.getAllShow(
                        $scope.div[(lastStartIndex+3)%4][$scope.div[(lastStartIndex+3)%4].length - 1].showId,
                        RECORD_PER_TIME, function() {
                            isLoading = false;
                        });
                }
            }
        });
    }
]);