(function() {
    $('.w-msgbox-close').click(function() {
        $('.w-mask').hide();
        $('.w-msgbox').hide();
    });
})();
app.controller('userProfileController', ['pageService', '$rootScope', '$scope', '$http',
    function(pageService, $rootScope, $scope, $http) {
        // view ['recordBuy', 'recordWin', 'expose']
        $scope.userId = window.location.pathname.split("/").pop();
        if (isNaN($scope.userId.split('.')[0])) {
            window.location = '/';
        } else {
            $scope.userId = parseInt($scope.userId.split('.')[0]); // 有可能非数字
        }



        var PAGE_SIZE = 10;
        var DAY_BEFORE = 365;
        var WIN_PAGE_SIZE = 12;
        var SHOW_PAGE_SIZE = 9;

        $scope.pageService = pageService;
        $scope.currentView = "recordOtherBuy";
        $scope.isLoading = 0;
        $scope.record = [];
        $scope.recordWin = [];
        $scope.showRecord = [];
        $scope.showRecordCount = 0;
        $scope.currentRecordBuyPage = 0;
        $scope.recordCount = 0;
        $scope.lookNumberIndex = 0;
        $scope.recordWinCount = 0;
        $scope.isLoading = true;
        $scope.Math = Math;
        $scope.getRecord = function(id, page, limit, before, callback) {
            $http.get('/order/getRecord?id=' + id + "&page=" + page +
                "&limit=" + limit + "&before=" + before).then(function(response) {
                $scope.record = response.data.data;
                callback();
            }, function(response) {
                console.log("getRecord error", response);
            });
        };

        $scope.getCountRecord = function(id, before, callback) {
            $http.get('/order/getCountRecord?id=' + id + '&before=' + before).then(function(response) {
                $scope.recordCount = response.data.data[0];
                callback();
            }, function(response) {
                console.log("getCountRecord", response);
            });
        };

        $scope.getWinRecord = function(id, page, limit, callback) {
            $http.get('/order/getWinRecord?id=' + id + '&page=' + page + '&limit=' + limit).then(function(response) {
                $scope.recordWin = response.data.data;
                callback();
            }, function(response) {
                console.log("getWinRecord error", response);
            });
        };

        $scope.getCountWinRecord = function(id, callback) {
            $http.get('/order/getCountWinRecord?id=' + id).then(function(response) {
                $scope.recordWinCount = response.data.data;
                callback();
            }, function(response) {
                console.log("getCountWinRecord error", response);
            });
        };

        $scope.getShowRecord = function(id, limit, page, callback) {
            $http.get('/show/getAllByUser?userId=' + $scope.userId + "&limit=" + limit + "&page=" + page).then(function(response) {
                if (response.data.code === 200) {
                    $scope.showRecord = response.data.data;
                }
                callback();
            }, function(response) {
                console.log("getShowRecord Error", response);
            });
        };

        $scope.getShowRecordCount = function(id, callback) {
            $http.get('/show/getCountAllByUser?userId=' + $scope.userId).then(function(response) {
                if (response.data.code === 200) {
                    $scope.showRecordCount = response.data.data;
                }
                callback();
            }, function(response) {
                console.log("getShowRecordCount Error", response);
            })
        };



        $scope.changePage = function(index) {
            $scope.isLoading = true;

            if ($scope.currentView === 'recordOtherBuy') {
                $scope.pageService.setPage('record', index + 1);
                $scope.getRecord($scope.userId, index + 1, PAGE_SIZE, DAY_BEFORE, function() {
                    $scope.isLoading = false;
                });
            } else if ($scope.currentView === 'recordOtherWin') {
                $scope.pageService.setPage('recordWin', index + 1);
                $scope.getWinRecord($scope.userId, index + 1, WIN_PAGE_SIZE, function() {
                    $scope.isLoading = false;
                });
            } else if ($scope.currentView === 'expose') {
                $scope.pageService.setPage('show', index + 1);
                $scope.getShowRecord($scope.userId, SHOW_PAGE_SIZE, index + 1, function() {
                    $scope.isLoading = false;
                });
            } else {
                location.href = '/user/' + $scope.userId + '.html';
            }
            window.scrollTo(0, 0);
        };

        $scope.viewNumber = function(index) {
            $('.w-mask').show();
            $('.w-msgbox-moduleCode').show();
            $('.w-msgbox-moduleCode').offset({
                top: (document.documentElement.scrollTop || document.body.scrollTop) + 165,
                left: (document.documentElement.scrollLeft || document.body.scrollLeft) + 500
            })
            $scope.lookNumberIndex = index;
        }


        $scope.changeView = function(viewString) {
            if (viewString === "recordOtherBuy") {
                if ($scope.record.length === 0) {
                    $scope.isLoading = true;
                    $scope.getRecord($scope.userId, 1, PAGE_SIZE, DAY_BEFORE, function() {
                        $scope.isLoading = false;
                    });

                    $scope.getCountRecord($scope.userId, DAY_BEFORE, function() {
                        $scope.pageService.init('record',
                            Math.ceil($scope.recordCount / PAGE_SIZE) >= 5 ? 5 : Math.ceil($scope.recordCount / PAGE_SIZE));
                        $scope.pageService.setPage('record', 1);
                    });
                }
            } else if (viewString === "recordOtherWin") {
                if ($scope.recordWin.length === 0) {

                    $scope.isLoading = true;
                    $scope.getWinRecord($scope.userId, 1, WIN_PAGE_SIZE, function() {
                        $scope.isLoading = false;
                    });
                    $scope.getCountWinRecord($scope.userId, function() {
                        $scope.pageService.init('recordWin', Math.ceil($scope.recordWinCount / WIN_PAGE_SIZE));
                        $scope.pageService.setPage('recordWin', 1);
                    });
                }

            } else if (viewString === "expose") {
                if ($scope.showRecordCount === 0) {
                    $scope.isLoading = true;
                    $scope.getShowRecord($scope.userId, SHOW_PAGE_SIZE, 1, function() {
                        $scope.isLoading = false;
                    });
                    $scope.getShowRecordCount($scope.userId, function() {
                        $scope.pageService.init("show", Math.ceil($scope.showRecordCount / SHOW_PAGE_SIZE));
                        $scope.pageService.setPage("show", 1);
                    });
                }
            } else {
                location.href = '/user/'+ $scope.userId + '.html';
            }
            $scope.currentView = viewString;
        };

        if (window.location.href.match(/\?/) !== null) {
            $scope.changeView(window.location.href.split('?').pop());
        } else {
            $scope.changeView('recordOtherBuy');
        }
    }
]);