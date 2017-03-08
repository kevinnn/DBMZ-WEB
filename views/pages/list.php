<?php
include dirname(dirname(dirname(__FILE__))).'/config/config.php';
include __ROOT__.'/controller/categoryController.php';
?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<?php include '../components/head.php';?>
		<title>ListTODO</title>
		<meta name="description" content="TODO">
		<meta name="keywords" content="TODO">
	</head>
	<body ng-app="YYYG">
		<?php include '../components/header.php';?>
		<div ng-controller="ListController" class='list-page'>
			<div ng-cloak class="g-wrap">
				<!-- 导航 -->
				<section class="list_Nav clearfix" ng-cloak>
					<div class="m-list-mod-hd">
						<h3>
						<a>{{categoryInfo.name}}</a> <span class="extra">（共 <b class="txt-red count">{{yungous.length}}</b> 件相关商品）</span>
						</h3>
					</div>
				</section>
				<!-- 所有商品 bar -->
				<!-- 				<div class="m-list-mod-bd-1" ng-show="cid==='listAll'">
						<ul class="m-list-allType-list">
								<li class="">
										<a href="/list/101.html">
												<span class="icon"><i class="ico ico-type ico-type-1 first"></i><i class="ico ico-type ico-type-1 second"></i></span>
												<b class="name">手机平板</b>
										</a>
								</li>
								<li class="">
										<a href="/list/102.html">
												<span class="icon"><i class="ico ico-type ico-type-2 first"></i><i class="ico ico-type ico-type-2 second"></i></span>
												<b class="name">电脑办公</b>
										</a>
								</li>
								<li class="">
										<a href="/list/103.html">
												<span class="icon"><i class="ico ico-type ico-type-3 first"></i><i class="ico ico-type ico-type-3 second"></i></span>
												<b class="name">数码影音</b>
										</a>
								</li>
								<li class="">
										<a href="/list/104.html">
												<span class="icon"><i class="ico ico-type ico-type-4 first"></i><i class="ico ico-type ico-type-4 second"></i></span>
												<b class="name">女性时尚</b>
										</a>
								</li>
								<li class="">
										<a href="/list/105.html">
												<span class="icon"><i class="ico ico-type ico-type-5 first"></i><i class="ico ico-type ico-type-5 second"></i></span>
												<b class="name">美食天地</b>
										</a>
								</li>
								<li class="">
										<a href="/list/106.html">
												<span class="icon"><i class="ico ico-type ico-type-6 first"></i><i class="ico ico-type ico-type-6 second"></i></span>
												<b class="name">潮流新品</b>
										</a>
								</li>
														<li class="">
											<a href="/list/7-0-1-1.html">
														<span class="icon"><i class="ico ico-type ico-type-7 first"></i><i class="ico ico-type ico-type-7 second"></i></span>
														<b class="name">网易周边</b>
											</a>
								</li>
								<li class=" last">
										<a href="/list/107.html">
												<span class="icon"><i class="ico ico-type ico-type-8 first"></i><i class="ico ico-type ico-type-8 second"></i></span>
												<b class="name">其他商品</b>
										</a>
								</li>
						</ul>
				</div> -->
				<!-- 横幅 -->
				<header ng-cloak>
					<div class="slogan" ng-style="{'background-image':'url('+categoryInfo.bannerUrl+')'}">
						<div class="slogan-border"></div>
					</div>
				</header>
			</div>
			<!-- 云购列表 -->
			<div class="g-wrap g-body-bd list_Body clearfix">
				<div class="m-list-mod m-list-goodsList clearfix">
					<!-- 排序 -->
					<div class="m-list-mod-hd" ng-hide="searchKey">
						<h6>排序：</h6>
						<ul class="m-list-sortList">
							<li ng-class="{selected:sort_type===0}">
								<a ng-click="clickSort(0)" href="">最新
								</a>
							</li>
							<li ng-class="{selected:sort_type===1}">
								<a ng-click="clickSort(1)" href="">最热
								</a>
							</li>
							<li ng-class="{selected:sort_type===2}">
								<a ng-click="clickSort(2)" href="">进度
								</a>
							</li>
							<li ng-class="{selected:sort_type===3}">
								<a ng-click="clickSort(3)" href="">总需人次
									<i ng-show="sort_type===3 && sort_reverse===false" title="升序" class="ico ico-arrow-sort ico-arrow-sort-gray-up"></i>
									<i ng-show="sort_type===3 && sort_reverse===true"title="降序" class="ico ico-arrow-sort ico-arrow-sort-gray-down"></i>
								</a>
							</li>
						</ul>
					</div>
					<!-- 搜索 -->
					<div class="m-search-mod-hd" ng-show="searchKey">
						<h4>商品搜索 — <span class="txt-red">{{searchKey}}</span><span class="extra">（共 <b class="txt-red count">{{yungous.length}}</b> 件相关商品）</span></h4>
					</div>
					<!-- 云购列表 -->
					<div class="m-list-mod-bd">
						<p ng-show="yungous.length === 0">加载中...</p>
						<ul class="w-quickBuyList f-clear">
							<li class="w-quickBuyList-item" ng-repeat="yungou in yungous.slice(perPage*(currentPage-1), perPage*currentPage)" ng-cloak>
								<div class="w-goods w-goods-l w-goods-ing">
									<i class="ico ico-label ico-label-ten" ng-show="yungou.product.singlePrice == 10"></i>
									<div class="w-goods-pic">
										<a href="/detail/{{yungou.product.id}}-{{yungou.yungou.term}}.html" title="{{yungou.product.title}}" target="_blank">
											<img width="200" height="200" alt="{{yungou.product.title}}" ng-src="{{yungou.product.thumbnailUrl}}" id="{{yungou.product.id}}">
										</a>
									</div>
									<p class="w-goods-title f-txtabb"><a title="{{yungou.product.title}}" href="/detail/{{yungou.product.id}}-{{yungou.yungou.term}}.html" target="_blank">{{yungou.product.title}}</a></p>
									<p class="w-goods-price">总需：{{yungou.product.price}} 人次</p>
									<div class="w-progressBar">
										<p class="w-progressBar-wrap">
											<span class="w-progressBar-bar" ng-style="{'width':(yungou.yungou.saleCount*100/yungou.product.price)+'%'}"></span>
										</p>
										<ul class="w-progressBar-txt f-clear">
											<li class="w-progressBar-txt-l">
												<p><b ng-bind="yungou.yungou.saleCount"></b></p>
												<p>已参与人次</p>
											</li>
											<li class="w-progressBar-txt-r">
												<p><b ng-bind="yungou.product.price-yungou.yungou.saleCount"></b></p>
												<p>剩余人次</p>
											</li>
										</ul>
									</div>
									<div class="w-goods-opr">
										我要参与
										<div class="w-goods-opr-number">
											<div class="w-number w-number-inline">
												<a class="w-number-btn w-number-btn-minus" href="javascript:void(0);" ng-click="minusAmount(yungou)">－</a>
												<input class="w-number-input" type="text" ng-model="yungou.amount" ng-blur="checkAmount(yungou)">
												<a class="w-number-btn w-number-btn-plus" href="javascript:void(0);" ng-click="addAmount(yungou)">＋</a>
											</div>
										</div> 人次
										<p class="w-goods-opr-buy">
											<a ng-click="addToPay(yungou)" class="w-button w-button-main w-button-l w-goods-btn-quickBuy" href="/cartIndex?yungouId={{yungou.yungou.id}}&amount={{yungou.amount}}" style="width:90px;">立即夺宝</a>
											<a ng-click="addToCart(yungou);toCartCartoon(yungou.product.id)" class="w-button w-button-l w-goods-btn-addToCart" href="javascript:void(0);" style="width:90px;">加入清单</a>
										</p>
									</div>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<!-- 分页 -->
			<div class="g-wrap g-body-ft list_Ft f-clear" ng-show="pageNum!=0">
				<div class="m-list-pager">
					<div class="w-pager">
						<a class="w-button w-button-aside" ng-class="{'w-button-disabled':currentPage===1}" ng-click="goPage(1)" href="javascript:void(0)" ng-disabled="currentPage===1">
							<span>首页</span>
						</a>
						<a class="w-button" ng-class="{'w-button-main':currentPage===page+1,'w-button-aside':currentPage!==page+1}" ng-click="goPage(page+1)" href="javascript:void(0)" ng-repeat="page in [] | range:pageNum">
							<span ng-bind="{{page+1}}"></span>
						</a>
						<a class="w-button w-button-aside" ng-class="{'w-button-disabled':currentPage===pageNum}" ng-click="goPage(pageNum)" href="javascript:void(0)" ng-disabled="currentPage===pageNum">
							<span>末页</span>
						</a>
					</div>
				</div>
			</div>
			<div tag="moduleRecommend" class="g-wrap" style="margin-top:30px;" >
				<div class="w-goodsRecommend" >
					<div class="w-hd">
						<h3 class="w-hd-title">推荐夺宝</h3><span>根据你的浏览记录推荐的商品</span></div>
						<div class="w-recommend-bd">
							<ul class="w-goodsList f-clear" pro="goodsList">
								<li class="w-goodsList-item" ng-repeat="item in recommendProducts">
									<div class="w-goods w-goods-brief">
										<div class="w-goods-pic">
											<a href="/detail/{{item.id}}.html" title="{{item.title}}" target="_blank"><img width="200" height="200" alt="{{item.title}}" ng-src="{{item.thumbnailUrl}}"></a>
										</div>
										<p class="w-goods-title f-txtabb"><a title="{{item.title}}" href="/detail/{{item.id}}.html" target="_blank">{{item.title}}</a></p>
										<p class="w-goods-price">总需：{{item.price}}人次</p>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php include '../components/footer.php';?>
		<script type="text/javascript" src="/public/js/app.js"></script>
		<script type="text/javascript" src="/public/js/pages/list.js"></script>
	</body>
</html>