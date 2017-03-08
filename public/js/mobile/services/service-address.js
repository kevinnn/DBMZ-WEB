// 地址数据
angular.module('YYYG')

.factory('AddressService', ['$http',function($http) {
    var service = {};
    service.addresses = [];

    // 获取用户所有地址
    service.getAll = function(callback) {
        if (service.addresses.length > 0) {
            callback();
        } else {
            $http.get('/address/getAll').success(function(data) {
                if (data.code === 200) {
                    service.addresses = data.data;
                }
            }).finally(function(){
                callback();
            });
        }
    };

    // 获取省份信息
    service.provinces = [];
    service.getProvinces = function (callback) {
        if (service.provinces.length > 0) {
            callback();
        } else {
            $http.get('/address/province').success(function(data){
                if (data.code === 200) {
                    service.provinces = data.data;
                }
            }).finally(function(){
                callback();
            });
        }
    };

    // 获取某省份下所有城市
    service.getCities = function (provinceID, callback) {
        service.cities = [];
        $http.get('/address/city?provinceId='+provinceID).success(function(data){
            if (data.code === 200) {
                service.cities = data.data;
            }
        }).finally(function(){
            callback();
        });
    };

    // 获取某城市下所有县区
    service.getAreas = function (cityID, callback) {
        service.areas = [];
        $http.get('/address/area?cityId='+cityID).success(function(data){
            if (data.code === 200) {
                service.areas = data.data;
            }
        }).finally(function(){
            callback();
        });
    };

    // 添加地址
    service.addAddress = function (address, callback) {
        $http.post('/address/addMobile', address, {
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            transformRequest: function(data) { return $.param(data); }
        }).success(function(data) {
            if (data.code === 200) {
                // 更新 service 地址数据
                if (data.data.status == 1) {
                    // 设置为默认地址
                    for (var i = 0; i < service.addresses.length; i++) {
                        service.addresses[i].status = 0;
                    }
                }
                service.addresses.push(data.data);
                callback(true);
            } else {
                callback(false,data.code);
            }
        }).error(function(data){
            callback(false,404);
        });
    };

    // 更新地址
    service.updateAddress = function (address, callback) {
        var params = {
            id: address.id,
            provinceID: address.provinceID,
            cityID: address.cityID,
            areaID: address.areaID,
            idCode: address.idCode,
            phoneNumber: address.phoneNumber,
            postCode: address.postCode,
            receiver: address.receiver,
            status: address.status,
            street: address.street
        };
        $http.post('/address/update', {address:params}, {
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            transformRequest: function(data) { return $.param(data); }
        }).success(function(data) {
            if (data.code === 200) {
                // 更新 service 地址数据
                angular.forEach(service.addresses, function(elem,index) {
                    if (elem.id == data.data.id) {
                        service.addresses[index] = data.data;
                    } else if (data.data.status == 1) {
                        service.addresses[index].status = 0;
                    }
                });
                callback(true);
            } else {
                callback(false);
            }
        }).error(function(data){
            callback(false);
        });
    };

    // 删除地址
    service.deleteAddress = function (addressId, callback) {
        var index = -1;
        for (var i = 0; i < service.addresses.length; i++) {
            if (service.addresses[i].id == addressId) {
                index = i;
                break;
            }
        }
        if (index == -1) {
            callback(true);
        } else {
            $http.post('/address/remove', {id:addressId}, {
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
                transformRequest: function(data) { return $.param(data); }
            }).success(function(data) {
                if (data.code === 200) {
                    service.addresses.splice(index, 1);
                    callback(true);
                } else {
                    callback(false);
                }
            }).error(function(data){
                callback(false);
            });
        }
    };

    return service;
}]);