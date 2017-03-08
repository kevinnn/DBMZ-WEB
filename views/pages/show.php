<?php
include dirname(dirname(dirname(__FILE__))).'/config/config.php';

?>
<!DOCTYPE html>
<html lang="zh-CH">
	<head>
		<?php include '../components/head.php';?>
		<title>shareOrderTODO</title>
		<meta name="description" content="TODO">
		<meta name="keywords" content="TODO">
	</head>
	<body ng-app="YYYG">
		<?php include '../components/header.php';?>
		<div class="shareOrder clearfix" ng-controller="showController">
			<div>
				<div class="m-share">
					<!-- <div class="m-share-slogan">
							<div class="g-wrap">
									<img src="http://mimg.127.net/p/yy/lib/img/promotion/share_slogan_dec.png" class="m-share-slogan-dec">
							</div>
							<div class="m-share-slogan-border"></div>
					</div> -->
					<div class="g-wrap f-clear">
						<div class="m-share-hd">
							<h3>共 <span class="txt-impt" id="spTotal">{{amount}}</span> 条晒单</h3>
						</div>
						<?php include './components/loading.php';?>
						<div class="m-shareList" ng-hide="isLoading">
							<div class="group group-first" id="dvList-1">
								<div class="item" ng-repeat="item in div[0] track by $index">
									<div class="pic">
										<table>
											<tbody>
												<tr>
													<td valign="middle" align="center">
														<a target="_blank" href="/showDetail?showId={{item.showId}}"><img ng-src="{{item.imgUrls[0]}}" onerror="this.src='../../public/img/app/loading.png'"></a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="name"><a target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.productTitle}}</a></div>
									<div class="code">幸运号码：<strong class="txt-impt">{{item.result}}</strong></div>
									<div class="post">
										<div class="title"><a target="_blank" href="/showDetail?showId={{item.showId}}"><strong>{{item.title}}</strong></a></div>
										<div class="author"><a target="_blank" href="/user/{{item.userId}}.html" title="{{item.userName}}">{{item.userName}}</a><span class="time">{{item.createdTime}}</span></div>
										<div class="abbr">
											{{item.content}}
										</div>
									</div>
								</div>
							</div>
							<div class="group" id="dvList-2">
								<div class="item" ng-repeat="item in div[1] track by $index">
									<div class="pic">
										<table>
											<tbody>
												<tr>
													<td valign="middle" align="center">
														<a target="_blank" href="/showDetail?showId={{item.showId}}"><img ng-src="{{item.imgUrls[0]}}" onerror="this.src='../../public/img/app/loading.png'"></a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="name"><a target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.productTitle}}</a></div>
									<div class="code">幸运号码：<strong class="txt-impt">{{item.result}}</strong></div>
									<div class="post">
										<div class="title"><a target="_blank" href="/showDetail?showId={{item.showId}}"><strong>{{item.title}}</strong></a></div>
										<div class="author"><a target="_blank" href="/user/{{item.userId}}.html" title="{{item.userName}}">{{item.userName}}</a><span class="time">{{item.createdTime}}</span></div>
										<div class="abbr">
											{{item.content}}
										</div>
									</div>
								</div>
							</div>
							<div class="group" id="dvList-3">
								<div class="item" ng-repeat="item in div[2] track by $index">
									<div class="pic">
										<table>
											<tbody>
												<tr>
													<td valign="middle" align="center">
														<a target="_blank" href="/showDetail?showId={{item.showId}}"><img ng-src="{{item.imgUrls[0]}}" onerror="this.src='../../public/img/app/loading.png'"></a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="name"><a target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.productTitle}}</a></div>
									<div class="code">幸运号码：<strong class="txt-impt">{{item.result}}</strong></div>
									<div class="post">
										<div class="title"><a target="_blank" href="/showDetail?showId={{item.showId}}"><strong>{{item.title}}</strong></a></div>
										<div class="author"><a target="_blank" href="/user/{{item.userId}}.html" title="{{item.userName}}">{{item.userName}}</a><span class="time">{{item.createdTime}}</span></div>
										<div class="abbr">
											{{item.content}}
										</div>
									</div>
								</div>
							</div>
							<div class="group group-last" id="dvList-4">
								<div class="item" ng-repeat="item in div[3] track by $index">
									<div class="pic">
										<table>
											<tbody>
												<tr>
													<td valign="middle" align="center">
														<a target="_blank" href="/showDetail?showId={{item.showId}}"><img ng-src="{{item.imgUrls}}" onerror="this.src='../../public/img/app/loading.png'"></a>
													</td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="name"><a target="_blank" href="/detail/{{item.productId}}-{{item.term}}.html">{{item.productTitle}}</a></div>
									<div class="code">幸运号码：<strong class="txt-impt">{{item.result}}</strong></div>
									<div class="post">
										<div class="title"><a target="_blank" href="/showDetail?showId={{item.showId}}"><strong>{{item.title}}</strong></a></div>
										<div class="author"><a target="_blank" href="/user/{{item.userId}}.html" title="{{item.userName}}">{{item.userName}}</a><span class="time">{{item.createdTime}}</span></div>
										<div class="abbr">
											{{item.content}}
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="m-share-blank" id="BottomBlank" style="display:none;">已经到达列表底部～</div>
					</div>
				</div>
				<div class="w-loading" id="pro-view-10" style="display: none;"><b class="w-loading-ico"></b><span class="w-loading-txt">正在努力加载……</span></div>
			</div>
		</div>
		<?php include '../components/footer.php';?>
		<script type="text/javascript" src="../../public/js/app.js"></script>
		<script type="text/javascript" src="../../public/js/pages/show.js"></script>
	</body>
</html>