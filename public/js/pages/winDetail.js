(function() {

})();
app.controller('winDetailController', ['addressService', '$scope', '$http',
    function(addressService, $scope, $http) {
        $scope.currentStatus = window.location.href.split("?").pop();
        $scope.addressService = addressService;
        $scope.provinces = [];
        $scope.cities = [];
        $scope.areas = [];
        $scope.address = {
            province: "省/直辖市",
            city: "地级市",
            area: "县/区",
            status: "0",
            cityID: "",
            provinceID: "",
            areaID: "",
            street: "",
            receiver: "",
            postCode: "",
            idCode: "",
            phoneNumber: ""
        };
        $scope.addressModify = {};
        $scope.addressModifyId = -1;
        $scope.selectAddressIndex = $scope.addressService.defaultIndex;
        $scope.mouseoverAddressIndex = -1;
        $scope.isLoading = true;
        $scope.isCreatingAddress = false;

        $scope.addressService.getAllUserAddress(function() {
            $scope.isLoading = false;
        })
        $scope.showSelectItem = function(tag, event) {
            $(".w-menu").hide();
            $("#" + tag).show();
            $("#" + tag).offset({
                top: $("#" + tag + "-bar").offset().top + $("#" + tag + "-bar").outerHeight(),
                left: $("#" + tag + "-bar").offset().left
            });
        }


        $scope.setAddressDefault = function(index) {
            $scope.addressService.setDefault(index);
            $scope.addressService.update(index);
        }

        $scope.updateProvince = function(e, str) {
            $scope.address.province = $(e.target).text();
            $scope.address.provinceID = $(e.target).attr("data-value");
            $scope.getCity($(e.target).attr("data-value"));
            $scope.cities = [];
            $scope.areas = [];
            $scope.address.city = "地级市";
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateCity = function(e, str) {
            $scope.address.city = $(e.target).text();
            $scope.address.cityID = $(e.target).attr("data-value");
            $scope.getArea($(e.target).attr("data-value"));
            $scope.areas = [];
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateArea = function(e, str) {
            $scope.address.area = $(e.target).text();
            $scope.address.areaID = $(e.target).attr("data-value");
            $scope.hideItem(e.target, str);
        };

        $scope.updateProvinceModify = function(e, str) {
            $scope.addressModify.province = $(e.target).text();
            $scope.addressModify.provinceID = $(e.target).attr("data-value");
            $scope.getCity($(e.target).attr("data-value"));
            $scope.cities = [];
            $scope.areas = [];
            $scope.address.city = "地级市";
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateCityModify = function(e, str) {
            $scope.addressModify.city = $(e.target).text();
            $scope.addressModify.cityID = $(e.target).attr("data-value");
            $scope.getArea($(e.target).attr("data-value"));
            $scope.areas = [];
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateAreaModify = function(e, str) {
            $scope.addressModify.area = $(e.target).text();
            $scope.addressModify.areaID = $(e.target).attr("data-value");
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
            $scope.errorHint = $scope.addressService.isValid($scope.address);
            if ($scope.errorHint === -1) {
                $scope.addressService.add($scope.address);
                $scope.address = {
                    province: "省/直辖市",
                    city: "地级市",
                    area: "县/区",
                    status: "0",
                    cityID: "",
                    provinceID: "",
                    areaID: "",
                    street: "",
                    receiver: "",
                    postCode: "",
                    idCode: "",
                    phoneNumber: ""
                }
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

            if ($scope.addressModifyId === -1) {
                // ID === -1 说明通过modal打开
                $scope.errorHint = $scope.addressService.isValid($scope.address);
                if ($scope.errorHint === -1) {
                    $scope.addressAdd();
                    $('.w-mask').hide();
                    $('#modifyAddress-form').hide();
                }

            } else {
                $scope.errorHint = $scope.addressService.isValid($scope.addressModify);
                if ($scope.errorHint === -1) {
                    $('.w-mask').hide();
                    $('#modifyAddress-form').hide();
                    $scope.addressService.address[$scope.addressModifyId] = $scope.addressModify;
                    if ($scope.addressModify.status === "1") {
                        $scope.addressService.setDefault($scope.addressModifyId);
                    }
                    $scope.addressService.update($scope.addressModifyId);
                    $scope.addressModifyId = -1;
                }
            }
        };

        $scope.closeAddressModify = function() {
            $('.w-mask').hide();
            $('#modifyAddress-form').hide();
            $scope.addressModifyId = -1;
        }

        $scope.getProvinces = function() {
            $http.get('/address/province').then(function(response) {
                $scope.provinces = response.data.data;
            }, function(response) {
                console.log(response);
            });
        };

        $scope.getCity = function(proId) {
            $http.get('/address/city?provinceId=' + proId).then(function(response) {
                $scope.citys = response.data.data;
            }, function(response) {
                console.log(response);
            });
        };

        $scope.getArea = function(cityId) {
            $http.get('/address/area?cityId=' + cityId).then(function(response) {
                $scope.areas = response.data.data;
            }, function(response) {
                console.log(response);
            });
        };

        $scope.addAddressByModal = function() {
            if ($scope.addressService.address.length < 5) {
                $('.w-mask').show();
                $('#modifyAddress-form').show();
                $scope.addressModify = $scope.address;
            } else {
                alert("地址已经超过五条了喔~");
            }
        };

        $scope.selectThisAddress = function(index) {
            $scope.selectAddressIndex = index;
        }

        $scope.mouseoverAddressList = function(index) {
            $scope.mouseoverAddressIndex = index;
        }

        $scope.mouseoutAddressList = function(index) {
            $scope.mouseoverAddressIndex = -1;
        }

        $scope.closeModal = function(id) {
            $("#" + id).hide();
            $('.w-mask').hide();
        }

        $scope.openAddressConformForm = function(id) {
            $("#" + id).show();
            $('.w-mask').show();
        }

        $scope.getProvinces();
    }
]);