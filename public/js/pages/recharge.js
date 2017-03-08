app.controller('rechargeController', ['$scope', '$http',
    function($scope, $http) {
        $scope.selectAmount = 0;
        $scope.inputAmount = "";
        $scope.isSelectOtherAmount = false;
        $scope.selectPayType = -1;
        $scope.agreeContract = false;
        $scope.changeSelectAmount = function(amount, flag) {
            if (flag) {
                $scope.selectAmount = $scope.inputAmount;
            } else {
                $scope.selectAmount = amount;
            }
            $scope.isSelectOtherAmount = flag;
            $scope.updateButton();

        }
        $scope.changeSelectPayType = function(type) {
            $scope.selectPayType = type;
            $scope.updateButton();
        }

        $scope.checkInputAmount = function() {
            if (isNaN($scope.inputAmount) || $scope.inputAmount === 0) {
                $scope.inputAmount = 1;
            } else {
                $scope.inputAmount = Math.floor($scope.inputAmount);
            }
            $scope.selectAmount = $scope.inputAmount;
            $scope.updateButton();
        }

        $scope.updateButton = function() {
            var submitButton = $("#submit-recharge-button");

            if ($scope.agreeContract && $scope.selectAmount > 0 && $scope.selectPayType !== -1) {
                submitButton.attr("disabled", false);
                submitButton.removeClass("button-disabled");
            } else {
                submitButton.attr("disabled", true);
                submitButton.addClass("button-disabled");
            }
        }

        $scope.submitRecharge = function() {
            if ($scope.agreeContract && $scope.selectAmount > 0 && $scope.selectPayType !== -1) {
                console.log($scope.selectAmount, $scope.selectPayType);
            }
        }
    }
])