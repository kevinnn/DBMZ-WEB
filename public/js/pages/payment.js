(function() {

})();

app.controller('paymentController', ['$scope', '$http', '$interval',
    function($scope, $http, $interval) {
        $scope.time = $('#idCountDown').attr('time');
        $scope.balance = $('#balance').attr('balance');
        $scope.amount = $('#amount').attr('amount');
        $scope.stillPay = $scope.amount;
        $scope.hour = 0
        $scope.minute = 0;
        $scope.second = 0;
        $scope.canPay = false;

        $scope.updateStillPay = function(checked) {
            if (checked) {
                $scope.stillPay -= $scope.balance;
                if ($scope.stillPay <= 0) {
                    $scope.canPay = true;
                }
            } else {
                $scope.stillPay = $scope.amount;
            }
        };

        $scope.countdown = function() {
            var intervalFlag = $interval(function() {
                $scope.time--;
                convertTime();
                if ($scope.time === 0) {
                    $interval.cancel(intervalFlag);
                    // 订单时间结束动作
                    alert("请重新发起订单！");
                }
            }, 1000);
        };

        $scope.submitOrder = function() {

            toggleButton("提交中...", "button-submitting", true);

            var submitList = {
                cashierid: location.search.match(/\d+/)[0]
            };


            $http.post('/pay', submitList, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).then(function(response) {
                //if (response.data.code === 200) {
                    window.location = '/payment_finish?cashierid=' + location.search.match(/\d+/)[0];
                //}

            }, function(response) {
                console.log(response);

            });

            /**
             * toggle submit button
             * @param  {[String]} text      [button.text]
             * @param  {[String]} className [button.toggleClassname]
             * @param  {[Boolean]} isabled  [disabled= isabled]
             */
            function toggleButton(text, className, isabled) {
                var submitButton = $("#submit-payment-button");
                submitButton.text(text);
                submitButton.attr("disabled", isabled);
                submitButton.addClass(className);
            }
        }

        var convertTime = function() {
            $scope.hour = Math.floor($scope.time / 3600);
            $scope.minute = Math.floor($scope.time / 60) - $scope.hour * 60;
            $scope.second = $scope.time - $scope.hour * 3600 - $scope.minute * 60;
        }


        /**
         * isabledBalance Pay
         * @return disabled $(balance) input checkbox or not
         */
        var isabledBalance = function() {
            // 非严格判断
            if ($scope.balance == 0) {
                $('.useCoin input').attr("disabled", true);
            }
        }

        isabledBalance();
        $scope.countdown();
    }
]);