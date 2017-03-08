<div class="address-component">
    <div class="address" >
        <div pro="addressform" class="m-address-form" id="pro-view-163">
            <div pro="area" class="w-address-form-item">
                <div class="w-address-form-name">
                    <span class="w-txt-impt">*&nbsp;</span>所在地区
                </div>
                <div class="w-select" tabindex="0" id="province-bar" style="width: 130px;" ng-click="showSelectItem('province', $event)">
                    <span pro="text" ng-model="address.province" >{{address.province}}</span>
                    <i class="w-select-arr">▼</i>
                </div>
                <div class="w-select" tabindex="0" id="city-bar" style="margin: 0px 10px; width: 130px;" ng-click="showSelectItem('city', $event)">
                    <span pro="text" ng-model="address.city">{{address.city}}</span>
                    <i class="w-select-arr">▼</i>
                </div>
                <div class="w-select" id="area-bar" style="width: 130px;" ng-click="showSelectItem('area', $event)">
                    <span pro="text" ng-model="address.area" >{{address.area}}</span>
                    <i class="w-select-arr">▼</i>
                </div>
                <span class="w-input-tips w-address-err-tips" ng-show="errorHint === 0"><i class="ico ico-err-s"></i>请选择区域</span>
            </div>
            <div pro="address" class="w-address-form-item">
                <div class="w-address-form-name address">
                    <span class="w-txt-impt">*&nbsp;</span>街道地址
                </div>
                <div class="w-input w-input-textarea w-address-area" id="pro-view-170">
                    <textarea class="w-input-input"  placeholder="不需要重复填写省/市/区" style="width: 490px" ng-model="address.street" maxlength="150"></textarea>
                    <span class="w-input-tips txt-err" ng-show="errorHint === 1"><i class="ico ico-err-s"></i>详细地址长度5-150个字符，一个汉字为两个字符</span>
                </div>
            </div>
            <div pro="zipcode" class="w-address-form-item">
                <div class="w-address-form-name">邮政编码</div>
                <div class="w-input" id="pro-view-169">
                    <input class="w-input-input" pro="input" type="text" maxlength="6" style="width: 490px;" ng-model="address.postCode">
                </div>
            </div>
            <div pro="receiver" class="w-address-form-item">
                <div class="w-address-form-name">
                    <span class="w-txt-impt">*&nbsp;</span>收 货 人
                </div>
                <div class="w-input" id="pro-view-168">
                    <input class="w-input-input" pro="input" type="text" placeholder="请使用真实姓名，长度不超过8个字" style="width: 490px;" ng-model="address.receiver" maxlength="8">
                    <span class="w-input-tips txt-err" ng-show="errorHint ===2"><i class="ico ico-err-s"></i>收货人名称长度为2-16个字符，一个汉字为两个字符</span>
                </div>
            </div>
            <div pro="identity" class="w-address-form-item">
                <div class="w-address-form-name">身份证号码</div>
                <div class="w-input" id="pro-view-167">
                    <input class="w-input-input" pro="input" type="text" maxlength="18" style="width: 490px;" ng-model="address.idCode">

                </div>
            </div>
            <div pro="mobile" class="w-address-form-item">
                <div class="w-address-form-name">
                    <span class="w-txt-impt">*&nbsp;</span>手机号码
                </div>
                <div class="w-input" id="pro-view-166">
                    <input class="w-input-input" pro="input" type="text" maxlength="11" placeholder="手机号码必须填" style="width: 490px;" ng-model="address.phoneNumber">
                    <span class="w-input-tips txt-err" ng-show="errorHint === 3"><i class="ico ico-err-s"></i>手机号码不对</span>
                </div>
            </div>
            <div pro="default" class="w-address-form-item">
                <div class="w-address-form-name">&nbsp;
                </div>
                <label class="w-checkbox" id="pro-view-165">
                    <input type="checkbox" ng-model="address.status" ng-true-value="'1'" ng-false-value="'0'">
                    <span>设置为默认收货地址</span>
                </label>
            </div>
            <div class="w-address-form-item">
                <div class="w-address-form-name">&nbsp;
                </div>
                <button class="w-button w-button-main" type="button" style="width: 140px; height: 45px; font-size: 18px; font-weight: normal;" ng-click="addressAdd()">
                <span>保存地址</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- 省条 -->
<div class="w-menu" id="province" style="z-index: 1000; width: 158px; left: 414.444px; top: 539.444px; overflow-x: hidden; overflow-y: auto; height: 234.333px; display: none;">
    <div class="w-address-menu-item w-menu-item w-menu-item-hover">省/直辖市</div>
    <div class="w-address-menu-item w-menu-item province-item" data-value="{{item.provinceID}}" ng-repeat='item in provinces' ng-click="updateProvince($event, 'province')">{{item.province}}</div>
</div>

<!-- 市条 -->
<div class="w-menu" id="city" style="z-index: 1000; width: 158px; left: 414.444px; top: 539.444px; overflow-x: hidden; overflow-y: auto; height: 234.333px; display: none;">
    <div class="w-address-menu-item w-menu-item w-menu-item-hover">地级市</div>
    <div class="w-address-menu-item w-menu-item city-item" data-value="{{item.cityID}}" ng-repeat='item in citys' ng-click="updateCity($event, 'city')">{{item.city}}</div>
</div>

<!-- 县区条 -->
<div class="w-menu" id="area" style="z-index: 1000; width: 158px; left: 414.444px; top: 539.444px; overflow-x: hidden; overflow-y: auto; height: 234.333px; display: none;">
    <div class="w-address-menu-item w-menu-item w-menu-item-hover">县/区</div>
    <div class="w-address-menu-item w-menu-item area-item" data-value="{{item.areaID}}" ng-repeat='item in areas' ng-click="updateArea($event, 'area')">{{item.area}}</div>
</div>