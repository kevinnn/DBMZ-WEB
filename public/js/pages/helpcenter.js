app.controller('helpcenterController', ['$scope', function($scope) {
    $scope.currentView = window.location.href.split("?").pop();
    $scope.changeView = function(viewString) {
        $scope.currentView = viewString;
    }
}]);