<?php
include dirname(dirname(dirname(__FILE__))).'/config/config.php';
include __ROOT__.'/controller/userController.php';
include __ROOT__.'/controller/showController.php';
$Show = json_decode(showController::getDetail(), true);
$show = $Show['data'];
$imgUrls = $show['imgUrls'];
$User = json_decode(userController::isLogin(),true);
if ($User['code'] == 200) {
	$user = $User['data'][0];
}
?>
<!DOCTYPE html>
<html lang="zh-CH">
	<head>
		<?php include '../components/head.php';?>
		<title>shareDetail</title>
		<meta name="description" content="TODO">
		<meta name="keywords" content="TODO">
	</head>
	<body ng-app="YYYG">
		<?php include '../components/header.php';?>
		<div class="g-body">
			<div class="m-user">
				<div class="g-wrap">
					<div class="m-user-frame-wraper">
						<div class="m-user-frame-colMain m-user-frame-colMain-noLeft">
							<div class="m-user-frame-content" pro="userFrameWraper">
								<ul class="w-crumbs f-clear">
									<li class="w-crumbs-item">当前位置：</li>
									<?php if (isset($user['id']) && $user['id'] == $show['userId']): ?>
									<li class="w-crumbs-item"><a href="/profile">我的夺宝</a><span class="w-crumbs-split">&gt;</span></li>
									<li class="w-crumbs-item">
										<a href="/profile?expose">我的晒单</a>
										<span class="w-crumbs-split">&gt;</span>
									</li>
									<li class="w-crumbs-item w-crumbs-active">晒单详情</li>
									<?php else: ?>
									<li class="w-crumbs-item"><a href="/user/<?php echo $show['userId'];?>.html">Ta的夺宝</a><span class="w-crumbs-split">&gt;</span></li>
									<li class="w-crumbs-item">
										<a href="/user/<?php echo $show['userId'];?>.html?expose">Ta的晒单</a>
										<span class="w-crumbs-split">&gt;</span>
									</li>
									<li class="w-crumbs-item w-crumbs-active">晒单详情</li>
									<?php endif ?>
								</ul>
								<div class="pro-view-9">
									<div class="m-user-comm-infoBox f-clear">
										<img class="m-user-comm-infoBox-face" onerror="this.src='http://mimg.127.net/p/yy/lib/img/avatar/160.jpeg'" src="<?php echo $show['avatorUrl'];?>" width="160" height="160">
										<div class="m-user-comm-infoBox-cont">
											<ul>
												<li class="item nickname">
													<span class="txt"><?php echo $show['userName'];?></span>
												</li>
												<li class="item"><span class="txt">ID：<strong><?php echo $show['userId'];?></strong></span></li>
											</ul>
										</div>
									</div>
									<div class="m-user-shareDetail-panel">
										<div class="m-user-shareDetail-header">
											<h1 class="title"><?php echo $show['title'];?></h1>
											<div class="time">晒单时间：<?php echo $show['createdTime'];?></div>
										</div>
										<div class="m-user-shareDetail-winDetail">
											<div class="owner">
												<div class="avatar">
													<a href="/user/<?php echo $show['userId'];?>.html" title="<?php echo $show['title'];?>"><img width="90" height="90" onerror="this.src='http://mimg.127.net/p/yy/lib/img/avatar/90.jpeg'" src="<?php echo $show['avatorUrl'];?>">
													</a>
												</div>
												<div class="info">
													<div class="name">获得者：<a href="/user/<?php echo $show['userId'];?>.html" title="<?php echo $show['userName'];?>"><?php echo $show['userName'];?></a></div>
													<div class="total">总共参与：<strong class="txt-impt"><?php echo $show['count'];?></strong>人次</div>
													<div class="code">幸运号码：<strong class="txt-impt"><?php echo $show['result'];?></strong></div>
													<div class="time">揭晓时间：<?php echo $show['createdTime'];?>.000</div>
												</div>
											</div>
											<div class="goods">
												<div class="pic">
													<a href="/detail/347-3749.html" target="_blank"><img width="90" height="90" src="<?php echo $show['thumbnailUrl'];?>"></a>
												</div>
												<div class="info">
													<div class="name"><a href="/detail/<?php echo $show['productId'];?>-<?php echo $show['term'];?>.html" target="_blank" style="color:#808080;"><?php echo $show['productTitle'];?></a></div>
													<div class="period">期号：<?php echo $show['term'];?> </div>
													<div class="total">总需：<?php echo $show['price'];?>人次</div>
													<div class="more"><a href="/detail/<?php echo $show['productId'];?>.html" target="_blank">最新一期正在进行中…</a></div>
												</div>
											</div>
										</div>
										<div class="m-user-shareDetail-cont">
											<i class="ico ico-quote ico-quote-former"></i>
											<i class="ico ico-quote ico-quote-after"></i>
											<div class="text">
												<?php echo $show['content'];?>
											</div>
										</div>
										<div class="m-user-shareDetail-pics">
											<?php for ($i = 0, $size = count($imgUrls); $i < $size; $i++) {?>
											<div class="item"><img src="<?php echo $imgUrls[$i];?>"></div>
											<?php }?>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="m-user-frame-clear"></div>
					</div>
				</div>
			</div>
		</div>
		<?php include '../components/footer.php';?>
		<script type="text/javascript" src="../../public/js/app.js"></script>
	</body>
</html>