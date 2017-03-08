<header ng-controller="HeaderController" ng-cloak>
	<div class="g-header" module="header/Header" id="pro-view-1" module-id="module-2" module-launched="true">
		<div class="m-toolbar" module="toolbar/Toolbar" id="pro-view-2" module-id="module-3" module-launched="true">
			<div class="g-wrap f-clear">
				<div class="m-toolbar-l">
					<span class="m-toolbar-welcome">欢迎来到网易一元夺宝！</span>
				</div>
				<ul class="m-toolbar-r">
					<li class="m-toolbar-login" ng-show='user'>
						<div id="pro-view-3">
							<a class="m-toolbar-nickname" href="/profile" title="<?php echo $user['userName'];?>"><?php echo $user['userName'];?></a>
							<a class="m-toolbar-logout-btn" href="javascript:void(0)" ng-click="logout()">[ 退出 ]</a>
						</div>
					</li>
					<li class="m-toolbar-myDuobao">
						<a class="m-toolbar-myDuobao" href="/profile">我的夺宝<!-- <i class="ico ico-arrow-gray-s ico-arrow-gray-s-down"></i> --></a>
						<!-- 						<ul class="m-toolbar-myDuobao-menu">
							<li><a href="/user/duobao.do">夺宝记录</a></li>
							<li class="m-toolbar-myDuobao-menu-win"><a href="/user/win.do">幸运记录</a></li>
							<li class="m-toolbar-myDuobao-menu-mall"><a href="/user/mallrecord.do">购买记录</a></li>
							<li><a href="/cashier/recharge/info.do">账户充值</a></li>
						</ul> -->
						<!-- 					<li class="m-toolbar-myBonus"><a href="/user/bonus.do">我的红包</a> -->
						<var>|</var>
						
					</li>
					<li>
						<a href="http://weibo.com/u/5249249076" target="_blank"><img width="16" height="13" style="float:left;margin:8px 3px 0 0;" src="http://mimg.127.net/p/one/web/lib/img/common/icon_weibo_s.png">官方微博</a>
						<var>|</var>
					</li>
					<li><a href="/groups.do">官方交流群</a></li>
				</ul>
			</div>
		</div>
	</div>
</header>