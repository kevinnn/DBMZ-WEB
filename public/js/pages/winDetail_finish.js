(function() {
    $('.w-msgbox-close').click(function() {
        $('.w-mask').hide();
        $('.w-msgbox').hide();
    });
})();
app.controller('winDetailFinishController', ['$scope', '$http',
    function($scope, $http) {
        $scope.openReceiveGoodConfirmModal = function() {
            $('.w-mask').show();
            $('.w-msgbox').show();
        };

        $scope.closeReceiveGoodConfirmModal = function() {
            $('.w-mask').hide();
            $('.w-msgbox').hide();
        };

        $scope.confirmReceiveGood = function() {
            var winOrderId = window.location.href.split("?winOrderId=").pop().split("&")[0];
            if (!isNaN(winOrderId)) {
                $http.post('/winOrder/confirm', {
                    winOrderId: winOrderId
                }, {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                    },
                    transformRequest: function(data) {
                        return $.param(data);
                    }
                }).then(function(response) {
                    location.reload();
                }, function(response) {
                    console.log("confirmReceiveGood Error", response);
                });
            } else {
                alert('没有查到这一单~');
            }
            $('.w-mask').hide();
            $('.w-msgbox').hide();
        }
    }
]);