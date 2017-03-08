<div class="w-msgbox" id="modifyAddress-form" address-id="{{addressModifyId}}" style="top: 25%;width: 720px;display: none; animation: ngdialog-flyin .5s;">
    <a pro="close" href="javascript:void(0);" class="w-msgbox-close" ng-click="closeAddressModify()">×</a>
    <!-- addressModifyId 在判断是新建还是修改 -->
    <div class="w-msgbox-hd" pro="header" ng-hide="addressModifyId === -1">修改收货地址</div>
    <div class="w-msgbox-hd" pro="header" ng-show="addressModifyId === -1">新建收货地址</div>
    <div class="w-msgbox-bd" pro="entry">
        <div pro="addressform" class="m-address-form">
            <div pro="area" class="w-address-form-item">
                <div class="w-address-form-name">
                    <span class="w-txt-impt">*&nbsp;</span>所在地区
                </div>
                <div class="w-select" id="provinceModify-bar" style="width: 130px;" ng-click="showSelectItem('provinceModify')">
                    <span pro="text">{{addressModify.province}}</span><i class="w-select-arr">▼</i>
                </div>
                <div class="w-select" id="cityModify-bar" style="margin: 0px 10px; width: 130px;" ng-click="showSelectItem('cityModify')">
                    <span pro="text">{{addressModify.city}}</span><i class="w-select-arr">▼</i>
                </div>
                <div class="w-select" id="areaModify-bar" style="width: 130px;" ng-click="showSelectItem('areaModify')">
                    <span pro="text">{{addressModify.area}}</span><i class="w-select-arr">▼</i>
                </div>
                <span class="w-input-tips w-address-err-tips" ng-show="errorHint === 0"><i class="ico ico-err-s"></i>请选择区域</span>
            </div>
            <div pro="address" class="w-address-form-item">
                <div class="w-address-form-name address" ng-model="addressModify.street">
                    <span class="w-txt-impt">*&nbsp;</span>街道地址
                </div>
                <div class="w-input w-input-textarea w-address-area" id="pro-view-422">
                    <textarea class="w-input-input" pro="input" placeholder="不需要重复填写省/市/区" ng-model="addressModify.street" maxlength="150"></textarea>
                    <span class="w-input-tips txt-err" ng-show="errorHint === 1"><i class="ico ico-err-s"></i>详细地址长度5-150个字符，一个汉字为两个字符</span>
                </div>
            </div>
            <div pro="zipcode" class="w-address-form-item">
                <div class="w-address-form-name" >邮政编码</div>
                <div class="w-input">
                    <input class="w-input-input" type="text" maxlength="6" style="width: 482px;" ng-model="addressModify.postCode">
                </div>
            </div>
            <div pro="receiver" class="w-address-form-item">
                <div class="w-address-form-name">
                    <span class="w-txt-impt">*&nbsp;</span>收 货 人
                </div>
                <div class="w-input">
                    <input class="w-input-input" type="text" placeholder="请使用真实姓名，长度不超过8个字" style="width: 482px;" ng-model="addressModify.receiver" maxlength="8">
                    <span class="w-input-tips txt-err" ng-show="errorHint ===2"><i class="ico ico-err-s"></i>收货人名称长度为2-16个字符，一个汉字为两个字符</span>
                </div>
            </div>
            <div pro="identity" class="w-address-form-item">
                <div class="w-address-form-name">身份证号码</div>
                <div class="w-input" >
                    <input class="w-input-input"  type="text" maxlength="18" style="width: 482px;" ng-model="addressModify.idCode">
                </div>
            </div>
            <div pro="mobile" class="w-address-form-item">
                <div class="w-address-form-name">
                    <span class="w-txt-impt">*&nbsp;</span>手机号码
                </div>
                <div class="w-input">
                    <input class="w-input-input" type="text" maxlength="11" placeholder="手机号码必须填" style="width: 482px;" ng-model="addressModify.phoneNumber">
                    <span class="w-input-tips txt-err" ng-show="errorHint === 3"><i class="ico ico-err-s"></i>手机号码不对</span>
                </div>
            </div>
            <div pro="default" class="w-address-form-item">
                <div class="w-address-form-name">&nbsp;</div>
                <label class="w-checkbox" >
                    <input type="checkbox" ng-model="addressModify.status" ng-true-value="'1'" ng-false-value="'0'"> <span>设置为默认收货地址</span>
                </label>
            </div>
            <div class="w-address-form-item">
                <div class="w-address-form-name">&nbsp;</div>
                <button class="w-button w-button-main" type="button" ng-click="updateAddressModify()" style="width: 140px; height: 45px; font-size: 18px; font-weight: normal;">
                <span>保存地址</span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="w-msgbox" id="adddressConform-form" id="pro-view-78" style="top: 25%; display: none;">
    <a pro="close" href="javascript:void(0);" class="w-msgbox-close" ng-click="closeModal('adddressConform-form')">×</a>
    <div class="w-msgbox-hd" pro="header"></div>
    <div class="w-msgbox-bd w-msgbox-bd-hasIcon" pro="entry">
        <i class="w-msgbox-ico ico ico-alert-m"></i>
        <div class="w-msgbox-cont">
            <h2 class="w-msgbox-title">确定提交以下收货地址吗？</h2>
            <div>
                <p>收货人：{{addressService.address[selectAddressIndex].receiver}}</p>
                <p>联系电话：{{addressService.address[selectAddressIndex].phoneNumber}}</p>
                <p>收货地址：{{addressService.address[selectAddressIndex].province + ", " + addressService.address[selectAddressIndex].city + ", " + addressService.address[selectAddressIndex].area + ", " + addressService.address[selectAddressIndex].street}}</p>
                <p class="txt-red">（注意：确认之后不能修改！）</p>
            </div>
        </div>
    </div>
    <div pro="footer" class="w-msgbox-ft">
        <a href="/profile/winDetail_finish.html?winOrderId=<?php echo $_GET['winOrderId'];?>&addressId={{addressService.address[selectAddressIndex].id}}">
            <button class="w-button w-button-main" type="button"><span>确定</span></button>
        </a>
        
        <button class="w-button w-button-aside" type="button" ng-click="closeModal('adddressConform-form')"><span>取消</span></button>
    </div>
</div>
<!-- 收货地址 所在地区条-->
<!-- 省条 -->
<div class="w-menu" id="provinceModify" style="z-index: 1000; width: 158px; left: 414.444px; top: 539.444px; overflow-x: hidden; overflow-y: auto; height: 234.333px; display: none;">
    <div class="w-address-menu-item w-menu-item w-menu-item-hover">省/直辖市</div>
    <div class="w-address-menu-item w-menu-item provinceModify-item" data-value="{{item.provinceID}}" ng-repeat='item in provinces' ng-click="updateProvinceModify($event, 'provinceModify')">{{item.province}}</div>
</div>
<!-- 市条 -->
<div class="w-menu" id="cityModify" style="z-index: 1000; width: 158px; left: 414.444px; top: 539.444px; overflow-x: hidden; overflow-y: auto; height: 234.333px; display: none;">
    <div class="w-address-menu-item w-menu-item w-menu-item-hover">地级市</div>
    <div class="w-address-menu-item w-menu-item cityModify-item" data-value="{{item.cityID}}" ng-repeat='item in citys' ng-click="updateCityModify($event, 'cityModify')">{{item.city}}</div>
</div>
<!-- 县区条 -->
<div class="w-menu" id="areaModify" style="z-index: 1000; width: 158px; left: 414.444px; top: 539.444px; overflow-x: hidden; overflow-y: auto; height: 234.333px; display: none;">
    <div class="w-address-menu-item w-menu-item w-menu-item-hover">县/区</div>
    <div class="w-address-menu-item w-menu-item areaModify-item" data-value="{{item.areaID}}" ng-repeat='item in areas' ng-click="updateAreaModify($event, 'areaModify')">{{item.area}}</div>
</div>