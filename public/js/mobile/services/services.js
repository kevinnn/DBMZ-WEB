// 全局服务
angular.module('YYYG')

.factory('Service', ['$http', function($http) {
    var service = {};

    // 检查是否登录
    service.checkLogin = function (callback) {
        if (service.isLogin != undefined) {
            callback();
        } else {
            $http.get('/user/isLogin').success(function(data){
                if (data.code === 200) {
                    service.isLogin = true;
                    service.user = data.data[0];
                } else {
                    service.isLogin = false;
                }
            }).error(function(data){
                service.isLogin = false;
            }).finally(function(){
                callback();
            });
        }
    }

    return service;
}]);