(function() {


    // 夺宝记录 JS
    $(".w-select.m-user-comm-selectTitle").click(function(e) {
        $("#pro-view-71").show();
        $("#pro-view-71").offset({
            top: $(this).offset().top + $(this).outerHeight(),
            left: $(this).offset().left
        });
    });




    // ------------------------------------------------------------------
    // 个人资料 JS
    $('a[pro="UC_mobileEdit"]').click(function() {
        $('div[pro="mobileShow"]').hide();
        $('div[pro="mobileEdit"]').show();
    });
    $('a[pro="UC_nicknameEdit"]').click(function() {
        $('div[pro="nameShow"]').hide();
        $('div[pro="nameEdit"]').show();
    });
    $('div[pro="mobileEdit"] button[name="cancel"]').click(function() {
        // to do post
        $('div[pro="mobileShow"]').show();
        $('div[pro="mobileEdit"]').hide();
    });
    $('div[pro="nameEdit"] button[name="cancel"]').click(function() {
        // to do post
        $('div[pro="nameShow"]').show();
        $('div[pro="nameEdit"]').hide();
    });

    $('.w-user-avatarEdit-tips').click(function() {
        $('#avatorUrlUpdate').css("visibility", "");
        $('.w-mask').show();
        $('.m-user-editMsgbox').show();
    });
    $('.w-msgbox-close').click(function() {
        $('.w-mask').hide();
        $('.w-msgbox').hide();
    });

    // --------------------------------------------------------------------
    // 收货地址 JS



    // public function


})();


// angular ------------------------------------------------
app.controller("profileController", ['addressService', 'pageService', '$scope', '$http', '$interval',

    function(addressService, pageService, $scope, $http, $interval) {
        var pageSize = 10;
        //todo
        // view ['account', 'recordBuy', 'recordWin', 'balance', 'credit', 'expose', 'invite', 'setting', 'address', 'shareEdit']
        $scope.currentView = "account";

        // 我的账户 夺宝记录 中奖记录部分
        $scope.recordCount = []; // 0 总记录条数 1 正在进行条数 2 即将揭晓条数 3 已揭晓条数
        $scope.winRecordCount = 0;
        $scope.record = []; // recordBuy记录显示容器
        $scope.accountRecord = []; //acount记录容器
        $scope.recordWin = []; // 中奖记录容器
        $scope.creditContainer = []; // 积分显示容器
        $scope.currentRecordBuyPage = 0;
        $scope.currentRecordWinPage = 0;
        $scope.lookNumberIndex = 0; // 用户点击查看 record的下标
        $scope.selectRecordStatus = 0; //用户选择记录的status 0 参与成功 1 即将揭晓 2 正在进行 3 已揭晓
        $scope.Math = Math;
        $scope.pageService = pageService;
        $scope.filterKey = {
            status: ''
        };
        $scope.isLoading = true;
        $scope.recordBuyPageCount;
        // 公共函数
        var isFunction = function(func) {
            var getType = {};
            return func && getType.toString.call(func) === '[object Function]';
        };

        $scope.setUserId = function(userId) {
            $scope.userId = userId;
        }

        $scope.getWinRecord = function(page, limit, callback) {
            $http.get('/order/getWinRecord?page=' + page + "&limit=" + limit).then(function(response) {
                if (response.data.code === 200) {
                    callback(response.data.data);
                }
            }, function(response) {
                console.log(response);
            });
        }

        $scope.getCountWinRecord = function(callback) {
            $http.get('/order/getCountWinRecord').then(function(response) {
                if ($scope.currentView === "recordWin") {
                    $scope.winRecordCount = parseInt(response.data.data);
                    callback();

                }
            })
        }

        $scope.getRecord = function(page, limit, status, callback) {
            var callbackFunc = callback;
            if (isFunction(status)) {
                callbackFunc = status;
                status = "";
            }
            $http.get('/order/getRecord?page=' + page + "&limit=" + limit + (status === "" ? "" : ("&status=" + status))).then(function(response) {
                if (response.data.code === 200) {
                    callbackFunc(response.data.data);
                }
            }, function(response) {
                console.log(response);
            });


        };

        $scope.getRecordCount = function(callback) {
            $http.get('/order/getCountRecord').then(function(response) {
                if ($scope.currentView === "recordBuy" || $scope.currentView === "account") {
                    $scope.recordCount = response.data.data;
                    callback();
                }
            }, function(response) {
                console.log(response);
            });
        };



        $scope.setFilterKey = function(key) {
            $scope.filterKey.status = key;
        };



        // 晒单编辑逻辑部分
        $scope.isPreview = false;
        $scope.selectDeleteShowImage = -1;
        $scope.show = {
            title: "",
            content: "",
            imgUrls: [],
            imgThumbs: []
            // yungouId orderId termId
        };

        $scope.togglePreview = function() {
            if (checkShowInput()) {
                $scope.isPreview = !$scope.isPreview;
            }
        }

        $scope.mouseoverShowImage = function(index) {
            $(".imgList .item .mask").eq(index).show();
            $(".imgList .item .close").eq(index).show();
        }

        $scope.mouseoutShowImage = function(index) {
            $(".imgList .item .mask").eq(index).hide();
            $(".imgList .item .close").eq(index).hide();
        }

        $scope.deleteShowImage = function(index) {
            $scope.selectDeleteShowImage = index;
            $(".w-mask").show();
            $("#delete-show-image-confirm-modal").show();
        }

        $scope.deleteShowImageConfirm = function(deleteFlag) {
            if (deleteFlag) {
                $scope.show.imgUrls.splice($scope.selectDeleteShowImage, 1);
                $scope.show.imgThumbs.splice($scope.selectDeleteShowImage, 1);
            }
            $(".w-mask").hide();
            $("#delete-show-image-confirm-modal").hide();
        }

        $scope.submitShow = function() {
            $http.post('/show/add', {
                title: $scope.show.title,
                content: $scope.show.content,
                imgUrls: $scope.show.imgUrls.join(),
                yungouId: $scope.show.yungouId,
                orderId: $scope.show.orderId,
                term: $scope.show.term
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            }).then(function(response) {
                $scope.show = {
                    title: "",
                    content: "",
                    imgUrls: [],
                    imgThumbs: []
                    // yungouId orderId termId
                };
            }, function(response) {
                console.log("submitShow Error", response);
            });
            $(".w-mask").hide();
            $("#show-confirm-modal").hide();
            location.href = '/profile?expose';
        }

        $scope.showShowConfirmModal = function() {
            $scope.isPreview = false;
            if (checkShowInput()) {
                if ($scope.show.imgUrls.length < 1) {
                    alert("至少上传一张图片~");
                    return;
                }
                $(".w-mask").show();
                $("#show-confirm-modal").show();
            }
        }

        $scope.closeShowConfirmModal = function() {
            $(".w-mask").hide();
            $("#show-confirm-modal").hide();
        }

        var checkShowInput = function() {
            $(".titleTips").text("");
            $(".contTips").text("");
            if ($scope.show.title === "") {
                $(".titleTips").text("请填写晒单主题");
                return false;
            } else if ($scope.show.content.length < 30) {
                $(".contTips").text("晒单内容不少于30字哟~");
                return false;
            }
            return true;
        }

        $scope.showImageUploader = WebUploader.create({
            // 自动上传。
            auto: true,

            // swf文件路径
            swf: '/public/webuploader/Uploader.swf',

            // 文件接收服务端。
            server: '/uploader/show',
            duplicate: true,
            // 只允许选择文件，可选。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });

        // 判断是否上传超过四张图
        $scope.showImageUploader.on('beforeFileQueued', function(file) {
            if ($scope.show.imgThumbs.length >= 4) {
                console.log("上传超过四张");
                alert("不可以上传超过四张图哟~");
                return false;
            } else {
                return true;
            }
        })

        $scope.showImageUploader.on('fileQueued', function(file) {
            $scope.showImageUploader.makeThumb(file, function(error, ret) {
                if (error) {
                    console.log("Uploader makeThumb Error", error);
                } else {
                    $scope.show.imgThumbs.push(ret);
                    $scope.$apply();
                }
            }, 140, 140);
        });

        // 文件上传成功，给item添加成功class, 用样式标记上传成功。
        $scope.showImageUploader.on('uploadSuccess', function(file, response) {
            if (response.code === 200) {
                $scope.show.imgUrls.push(response.data);
            }
        });

        // 文件上传失败，显示上传出错。
        $scope.showImageUploader.on('uploadError', function(file, response) {
            $scope.show.imgThumbs.pop();
            $scope.$apply();
            console.log("uploadError", file, response);
        });


        // 我的晒单部分
        $scope.showRecord = [];
        $scope.showRecordCount = 0;
        var SHOW_PAGE_SIZE = 9;

        $scope.getShowRecordCount = function(callback) {
            $http.get('/show/getCountAllByUser').then(function(response) {
                if (response.data.code === 200) {
                    $scope.showRecordCount = response.data.data;
                }
                callback();
            }, function(response) {
                console.log("getShowRecordCount Error", response);
            });
        };

        $scope.getShowRecord = function(limit, page, callback) {
            $http.get('/show/getAllByUser?limit=' + limit + "&page=" + page).then(function(response) {
                if (response.data.code === 200) {
                    $scope.showRecord = response.data.data;
                }
                callback();
            }, function(response) {
                console.log("getShowRecord Error", response);
            });
        };

        // address 地址逻辑部分
        $scope.addressService = addressService;
        $scope.provinces = [];
        $scope.cities = [];
        $scope.areas = [];
        $scope.address = {};
        $scope.addressModify = {};
        $scope.addressModifyId = -1;
        $scope.errorHint = -1; // 0 地区错误 1 街道错误 2 收件人未填 3 手机错误
        $scope.selectAddressIndex = $scope.addressService.defaultIndex;
        $scope.mouseoverAddressIndex = -1;
        $scope.address.province = "省/直辖市";
        $scope.address.city = "地级市";
        $scope.address.area = "县/区";

        $scope.setAddressDefault = function(index) {
            $scope.addressService.setDefault(index);
            $scope.addressService.update(index);
        }

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
            $scope.address.provinceID = $(e.target).attr("data-value");
            $scope.getCity($scope.address.provinceID);

            $scope.cities = [];
            $scope.areas = [];
            $scope.address.city = "地级市";
            $scope.address.area = "县/区";
            $scope.hideItem(e.target, str);
        };

        $scope.updateCity = function(e, str) {
            $scope.address.city = $(e.target).text();
            $scope.address.cityID = $(e.target).attr("data-value");
            $scope.getArea($scope.address.cityID);
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
            $scope.errorHint = $scope.addressService.isValid($scope.address);
            if ($scope.errorHint === -1) {
                $scope.addressService.add($scope.address);
                $scope.address = {};
                $scope.address.province = "省/直辖市";
                $scope.address.city = "地级市";
                $scope.address.area = "县/区";
            } else {
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

            $scope.errorHint = $scope.addressService.isValid($scope.addressModify);

            if ($scope.errorHint === -1) {
                if ($scope.addressModify.status == '1') {
                    $scope.addressService.setDefault($scope.addressModifyId);
                }
                $scope.addressService.address[$scope.addressModifyId] = $scope.addressModify;
                $scope.addressService.update($scope.addressModifyId);
                $('.w-mask').hide();
                $('#modifyAddress-form').hide();
            }
        };

        $scope.closeAddressModify = function() {
            $('.w-mask').hide();
            $('#modifyAddress-form').hide();
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

        $scope.mouseoverAddressList = function(index) {
            $scope.mouseoverAddressIndex = index;
        }

        $scope.mouseoutAddressList = function(index) {
            $scope.mouseoverAddressIndex = -1;
        }


        // 我的积分逻辑
        $scope.getCreditRecord = function(page, limit, callback) {
            $http.get('/credit/getRecord?page=' + page + '&limit=' + limit).then(function(response) {
                $scope.creditContainer = response.data.data;
                callback();
            }, function(response) {
                console.log(response);
            })
        }

        // 页面显示逻辑
        $scope.showNumberRecord = {};
        $scope.viewNumber = function(index) {
            if ($scope.currentView === 'account') {
                $scope.showNumberRecord = $scope.accountRecord[index];

            } else if ($scope.currentView === 'recordBuy') {
                $scope.showNumberRecord = $scope.accountRecord[index];
            }
            $scope.lookNumberIndex = index;
            $('.w-mask').show();
            $('.w-msgbox-moduleCode').show();
        }


        $scope.changePage = function(index) {
            $scope.isLoading = true;
            if ($scope.currentView === 'recordBuy') {
                if ($scope.currentRecordBuyPage !== index + 1) {
                    $scope.record = [];
                    $scope.currentRecordBuyPage = index + 1;
                    $scope.pageService.setPage('recordBuy', index + 1);
                    $scope.getRecord(index + 1, pageSize, function(data) {
                        if ($scope.currentView === 'recordBuy') {
                            $scope.isLoading = false;
                            $scope.record = data;
                        }
                    });
                }

            } else if ($scope.currentView === 'recordWin') {
                if ($scope.currentRecordWinPage !== index + 1) {
                    $scope.record = [];
                    $scope.currentRecordWinPage = index + 1;
                    $scope.pageService.setPage('recordWin', index + 1);
                    $scope.getWinRecord(index + 1, pageSize, function(data) {
                        if ($scope.currentView === 'recordWin') {
                            $scope.isLoading = false;
                            $scope.recordWin = data;
                        }
                    });
                }
            } else if ($scope.currentView === 'expose') {
                $scope.pageService.setPage('show', index + 1);
                $scope.getShowRecord(SHOW_PAGE_SIZE, index + 1, function() {
                    if ($scope.currentView === 'expose') {
                        $scope.isLoading = false;
                    }
                });
            }
            window.scrollTo(0, 0);
        }


        // 即将揭晓 正在进行 已揭晓
        $scope.changeRecordStatus = function(status) {
            $scope.selectRecordStatus = status;
            $scope.isLoading = true;
            $scope.pageService.init('recordBuy', Math.ceil($scope.recordCount[status] / pageSize));
            $scope.pageService.setPage('recordBuy', 1);
            if (status === 0) {
                $scope.getRecord(1, pageSize, function(data) {
                    if ($scope.currentView === 'recordBuy') {
                        $scope.isLoading = false;
                        $scope.record = data;
                    }
                });
            } else {
                $scope.getRecord(1, pageSize, status - 1, function(data) {
                    if ($scope.currentView === 'recordBuy') {
                        $scope.isLoading = false;
                        $scope.record = data;
                    }
                });
            }


        }

        $scope.changeView = function(viewString, index) {
            $(".w-menu").hide();
            $scope.currentView = viewString;
            window.scrollTo(0, 0);
            if (viewString === "account") {
                if ($scope.accountRecord.length === 0) {
                    $scope.isLoading = true;
                    $scope.getRecord(1, 6, function(data) {
                        if ($scope.currentView === "account") {
                            $scope.isLoading = false;
                            $scope.accountRecord = data;
                        }
                    });
                } else {
                    $scope.isLoading = false;
                }
            } else if (viewString === "recordBuy") {
                $scope.selectRecordStatus = 0; // 恢复顶部参与成功、即将揭晓、正在进行、已揭晓样式
                if ($scope.record.length === 0) {
                    $scope.isLoading = true;

                    $scope.getRecordCount(function() {
                        $scope.pageService.init('recordBuy', Math.ceil($scope.recordCount[0] / pageSize));
                        $scope.currentRecordBuyPage = 1;
                        $scope.pageService.setPage('recordBuy', 1);
                    });
                    $scope.getRecord(1, pageSize, function(data) {
                        if ($scope.currentView === "recordBuy") {
                            $scope.isLoading = false;
                            $scope.record = data;
                        }
                    });
                } else {
                    $scope.isLoading = false;

                }
            } else if (viewString === "recordWin") {
                if ($scope.recordWin.length === 0) {
                    $scope.isLoading = true;
                    $scope.getCountWinRecord(function() {
                        $scope.pageService.init('recordWin', Math.ceil($scope.winRecordCount / pageSize));
                        $scope.currentRecordWinPage = 1;
                        $scope.pageService.setPage('recordWin', 1);
                    });
                    $scope.getWinRecord(1, pageSize, function(data) {
                        if ($scope.currentView === 'recordWin') {
                            $scope.isLoading = false;
                            $scope.recordWin = data;
                        }
                    });
                } else {
                    $scope.isLoading = false;

                }
            } else if (viewString === "expose") {
                if ($scope.showRecordCount === 0) {
                    $scope.isLoading = true;
                    $scope.getShowRecord(SHOW_PAGE_SIZE, 1, function() {
                        $scope.isLoading = false;
                    });
                    $scope.getShowRecordCount(function() {
                        $scope.pageService.init("show", Math.ceil($scope.showRecordCount / SHOW_PAGE_SIZE));
                        $scope.pageService.setPage("show", 1);
                    });
                }
            } else if (viewString === "address") {
                $scope.getProvinces();

                $scope.isLoading = true;
                $scope.addressService.getAllUserAddress(function() {
                    $scope.isLoading = false;
                });
            } else if (viewString === "credit") {
                $scope.isLoading = true;
                $scope.creditContainer = [];
                $scope.getCreditRecord(1, 10, function() {
                    $scope.isLoading = false;
                })
            } else if (viewString === "shareEdit") { // 晒单编辑
                $scope.showImageUploader.addButton({
                    id: '#showImgeUrlPicker',
                    innerHTML: '选择文件'
                });
                $scope.show.orderId = $scope.recordWin[index].orderId;
                $scope.show.yungouId = $scope.recordWin[index].yungouId;
                $scope.show.term = $scope.recordWin[index].term;
            } else if (viewString === "setting") {} else if (viewString === "invite") {} else if (viewString === "balance") {} else {
                location.href = '/profile';
            }
        };

        // 页面入口
        if (location.href.match(/\?/) !== null) {
            $scope.changeView(location.href.split("?").pop());
        } else {
            $scope.changeView('account');
        }

        $scope.error = {};
        $scope.error.userName = {};
        $scope.error.phoneNumber = {};
        $scope.error.code = {};
        $scope.reset = function(clear) {
            $scope.error = {};
            $scope.error.userName = {};
            $scope.error.phoneNumber = {};
            $scope.error.code = {};

            if (clear) {
                document.getElementById("userName").value = $scope.user.userName;
                document.getElementById("phoneNumber").value = "";
                document.getElementById("code").value = "";
            }

        }
        //保存userName
        $scope.save = function() {
            var userName = document.getElementById("userName").value;
            if (userName == $scope.user.userName) {
                $('div[pro="nameShow"]').show();
                $('div[pro="nameEdit"]').hide();
                return;
            }
            if (userName == "") {
                $scope.error.userName.empty = true;
                return;
            }
            $http.post("/user/update", {
                userName: userName
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
                .success(function(data) {
                    if (data.code == 200) {
                        $('div[pro="nameShow"]').show();
                        $('div[pro="nameEdit"]').hide();
                        $scope.user.userName = userName;
                        $scope.reset(true);
                    }
                })
                .error(function(data) {
                    console.log(data);
                });
        }

        //修改手机号码
        $scope.codeBtnText = "获取验证码"
        $scope.codeBtnDisabled = false;
        $scope.checkPhoneNumber = function() {
            var pattern = new RegExp(/^(1[3-9]\d{9}$)/),
                isInvalid = false;

            var a = 0;
            ($scope.phoneNumber == $scope.user.phoneNumber && ($scope.error.phoneNumber.unique = true) || ($scope.phoneNumber == "" || $scope.phoneNumber === undefined) && ($scope.error.phoneNumber.empty = true) || !pattern.test($scope.phoneNumber) && ($scope.error.phoneNumber.invalid = true)) && (isInvalid = true);

            return isInvalid;
        }
        $scope.checkCode = function() {
            var pattern = new RegExp(/^(\d{6}$)/),
                isInvalid = false;

            ($scope.code == "" && ($scope.error.code.empty = true) || !pattern.test($scope.code) && ($scope.error.code.invalid = true)) && (isInvalid = true);

            return isInvalid;

        }
        $scope.sendCode = function() {
            $scope.codeBtnDisabled = true;
            if ($scope.checkPhoneNumber()) return;
            var countDown = 60;
            $http.get('/user/sendVerificationCode?phoneNumber=' + $scope.phoneNumber)
                .success(function(data) {
                    if (data.code == 200) {
                        var timer = $interval(function() {
                            if (countDown <= 0) {
                                $scope.codeBtnDisabled = false;
                                $scope.codeBtnText = '获取验证码';
                                $interval.cancel(timer);
                                timer = null;
                                countDown = 60;
                                return;
                            }
                            $scope.codeBtnText = (--countDown) + '秒重发...';
                        }, 1000);
                    } else {
                        $scope.codeBtnText = "发送失败";
                        $timeout(function() {
                            $scope.codeBtnDisabled = false;
                            $scope.codeBtnText = "获取验证码";
                        }, 2000);
                    }
                })
        }

        $scope.savePhoneNumber = function() {
            if ($scope.checkPhoneNumber() || $scope.checkCode()) {
                return;
            }
            $http.post("/user/update", {
                phoneNumber: $scope.phoneNumber,
                verifyCode: $scope.code
            }, {
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                },
                transformRequest: function(data) {
                    return $.param(data);
                }
            })
                .success(function(data) {
                    if (data.code == 200) {
                        $scope.user.phoneNumber = $scope.phoneNumber;
                        $('div[pro="mobileShow"]').show();
                        $('div[pro="mobileEdit"]').hide();
                        $scope.reset(true);
                    } else {
                        $scope.error.code.incorrect = true;
                    }
                })
                .error(function(data) {
                    console.log(data);
                });
        }

        // 事件函数
        $scope.$on('ngRepeatFinished', function(ngRepeatFinishedEvent) {
            $scope.isLoading = false;
        });

        $scope.$on("userLoaded", function(d, data) {
            $scope.user = data;
        });

        jQuery(function() {
            var $ = jQuery,
                $imgUrlList = $('#imgUrlList'),
                // 优化retina, 在retina下这个值是2
                ratio = window.devicePixelRatio || 1,

                // 缩略图大小
                thumbnailWidth = 100 * ratio,
                thumbnailHeight = 100 * ratio,

                // Web Uploader实例
                uploader;

            // 初始化Web Uploader
            var uploader = WebUploader.create({

                // 自动上传。
                auto: true,

                // swf文件路径
                swf: '/public/webuploader/Uploader.swf',

                // 文件接收服务端。
                server: '/uploader/user_avatorUrl',

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#imgUrlPicker',

                // 只允许选择文件，可选。
                accept: {
                    title: 'Images',
                    extensions: 'gif,jpg,jpeg,bmp,png',
                    mimeTypes: 'image/*'
                }
            });

            // 当有文件添加进来的时候
            uploader.on('fileQueued', function(file) {
                var $li = $(
                        '<div id="' + file.id + '" class="file-item thumbnail">' +
                        '<img>' +
                        '<div class="info">' + file.name + '</div>' +
                        '</div>'
                    ),
                    $img = $li.find('img');

                $imgUrlList.html($li);

                // 创建缩略图
                uploader.makeThumb(file, function(error, src) {
                    if (error) {
                        $img.replaceWith('<span>不能预览</span>');
                        return;
                    }

                    $img.attr('src', src);
                }, thumbnailWidth, thumbnailHeight);
            });

            uploader.on('beforeFileQueued', function (file) {
                if (!confirm("是否确认修改")) {
                    return false;
                }
                return true;
            });

            // 文件上传过程中创建进度条实时显示。
            uploader.on('uploadProgress', function(file, percentage) {
                var $li = $('#' + file.id),
                    $percent = $li.find('.progress span');

                // 避免重复创建
                if (!$percent.length) {
                    $percent = $('<p class="progress"><span></span></p>')
                        .appendTo($li)
                        .find('span');
                }

                $percent.css('width', percentage * 100 + '%');
            });

            // 文件上传成功，给item添加成功class, 用样式标记上传成功。
            uploader.on('uploadSuccess', function(file, response) {
                if (response.code == 200) {
                    $scope.user.avatorUrl = response.data;
                    $scope.$apply();
                    $('.w-mask').hide();
                    $('.m-user-editMsgbox').hide();
                }
                $('#' + file.id).addClass('upload-state-done');
            });

            // 文件上传失败，现实上传出错。
            uploader.on('uploadError', function(file) {
                var $li = $('#' + file.id),
                    $error = $li.find('div.error');

                // 避免重复创建
                if (!$error.length) {
                    $error = $('<div class="error"></div>').appendTo($li);
                }

                $error.text('上传失败');
            });

            // 完成上传完了，成功或者失败，先删除进度条。
            uploader.on('uploadComplete', function(file) {
                $('#' + file.id).find('.progress').remove();
            });
        });
    }
]);