app.controller('addressController', ['addressService', '$scope',
    function(addressService, $scope) {
        $scope.addressService = addressService;
        $scope.provinces = [];
        $scope.cities = [];
        $scope.areas = [];
        $scope.address = {};
        $scope.addressModify = {};
        $scope.addressModifyId = -1;

        $scope.address.province = "省/直辖市";
        $scope.address.city = "地级市";
        $scope.address.area = "县/区";

        $scope.showSelectItem = function(tag, event) {
            $(".w-menu").hide();
            $("#" + tag).show();
            $("#" + tag).offset({
                top: $("#" + tag + "-bar").offset().top + $("#" + tag + "-bar").outerHeight(),
                left: $("#" + tag + "-bar").offset().left
            });
        }

        $scope.updateProvince = function(e, str) {
            $scope.address.province = $(e.target).text();
            $scope.getCity($(e.target).attr("data-value"));
            $scope.cities = [];
            $scope.areas = [];
            $scope.address.city = "地级市";
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateCity = function(e, str) {
            $scope.address.city = $(e.target).text();
            $scope.getArea($(e.target).attr("data-value"));
            $scope.areas = [];
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateArea = function(e, str) {
            $scope.address.area = $(e.target).text();
            $scope.hideItem(e.target, str);
        };

        $scope.updateProvinceModify = function(e, str) {
            $scope.addressModify.province = $(e.target).text();
            $scope.getCity($(e.target).attr("data-value"));
            $scope.cities = [];
            $scope.areas = [];
            $scope.address.city = "地级市";
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateCityModify = function(e, str) {
            $scope.addressModify.city = $(e.target).text();
            $scope.getArea($(e.target).attr("data-value"));
            $scope.areas = [];
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateAreaModify = function(e, str) {
            $scope.addressModify.area = $(e.target).text();
            $scope.hideItem(e.target, str);
        };

        $scope.hideItem = function(target, tag) {
            $("." + tag + "-item").removeClass("w-menu-item-hover");
            $(target).addClass("w-menu-item-hover");
            $("." + tag + "-item").parent().hide();
        }

        $scope.addressAdd = function() {
            $scope.address.province = $scope.address.province;
            $scope.address.city = $scope.address.city;
            $scope.address.area = $scope.address.area;
            if ($scope.addressService.isValid($scope.address)) {
                $scope.addressService.add($scope.address);
                $scope.address = {};
                $scope.address.province = "省/直辖市";
                $scope.address.city = "地级市";
                $scope.address.area = "县/区";
            } else {
                //to do 添加地址失败
                console.log("地址添加失败");
            }
        };

        $scope.addressUpdate = function(index) {
            $('.w-mask').show();
            $('#modifyAddress-form').show();
            $scope.addressModifyId = index;
            $scope.addressModify = $scope.addressService.address[index];
        };

        $scope.addressDelete = function(index) {
            $scope.addressService.delete(index);
        }

        $scope.updateAddressModify = function() {
            $('.w-mask').hide();
            $('#modifyAddress-form').hide();
            $scope.addressService.address[$scope.addressModifyId] = $scope.addressModify;
        };

        $scope.closeAddressModify = function() {
            $('.w-mask').hide();
            $('#modifyAddress-form').hide();
        }

        $scope.getProvinces = function() {
            $http.get('/address/province').then(function(response) {
                console.log(response);
                $scope.provinces = response.data.data;
            }, function(response) {
                console.log(response);
            });
        };

        $scope.getCity = function(proId) {
            $http.get('/address/city?provinceId=' + proId).then(function(response) {
                console.log(response);
                $scope.citys = response.data.data;
            }, function(response) {
                console.log(response);
            });
        };

        $scope.getArea = function(cityId) {
            $http.get('/address/area?cityId=' + cityId).then(function(response) {
                console.log(response);
                $scope.areas = response.data.data;
            }, function(response) {
                console.log(response);
            });
        };
    }
]);