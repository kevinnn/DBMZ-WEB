<?php
include dirname(dirname(dirname(__FILE__))).'/config/config.php';
include __ROOT__.'/controller/categoryController.php';
include __ROOT__.'/controller/productController.php';
include __ROOT__.'/controller/yungouController.php';

$product = json_decode(productController::productById($_GET['pid']),true);
if ($product['code'] != 200) {
	header('location:/');
}
$product = $product['data'][0];
$yungou = json_decode(yungouController::yungouByTerm($_GET['pid'],$_GET['tid']),true);
if ($yungou['code'] != 200) {
	header('location:/');
}
$yungou = $yungou['data'][0];
$category = json_decode(categoryController::category($product['categoryId']),true)['data'];
?>

<!DOCTYPE html>
<html lang="zh-CH">

<head>
	<?php include '../components/head.php';?>

	<title>DetailsTODO</title>
	<meta name="description" content="TODO">
    <meta name="keywords" content="TODO">
</head>

<body ng-app="YYYG">

	<?php include '../components/header.php';?>

	<div class="details clearfix" ng-controller="DetailController">
	    <div class="m-detail m-detail-willReveal">
	        <div class="g-wrap g-body-hd f-clear">
	            <div class="g-main" ng-cloak>

	            	<!-- 面包屑导航 -->
	                <div class="w-dir">
	                    <a href="/">首页</a> &gt; </a>
                    	<a href="<?php echo '/list/'.$category['id'].'.html' ?>" target="_blank"><?php echo $category['name']; ?></a> &gt; 
                    	<span class="txt-gray"><?php echo $product['title'];?></span>
	                </div>

					<!-- 商品图片展示 -->
	                <div class="g-main-l m-detail-show" ng-cloak>
	                    <div class="w-gallery">
	                        <div class="w-gallery-fullsize">
	                            <div class="w-gallery-picture">
	                                <img ng-src="{{product.imgUrls[imgIndex]}}" id="picture">
	                            </div>
	                        </div>
	                        <i class="ico ico-arrow ico-arrow-red ico-arrow-red-up" ng-style="{'left': leftPad+'px'}"></i>
	                        <div class="w-gallery-thumbnail">
	                            <ul class="w-gallery-thumbnail-list">
	                            	<?php
	                            		$index = 0;
			                    		foreach ($product['imgUrls'] as $key => $value) {
			                    	?>
			                    	<!-- TODO with 七牛图片处理 -->
	                                <li class="w-gallery-thumbnail-item" ng-mouseover="hoverIn(<?php echo $index; ?>)" ng-class="{'w-gallery-thumbnail-item-odd':imgIndex==<?php echo $index; ?>,'w-gallery-thumbnail-item-selected':imgIndex==<?php echo $index; ?>}">
	                                    <img src="<?php echo $value;?>">
	                                </li>
	                                <?php
			                    			$index++;
	                                	} 
	                                ?>
	                            </ul>
	                        </div>
	                    </div>
	                </div>

	                <!-- 商品右侧 -->
	                <div class="g-main-m m-detail-main">
	                    <div class="m-detail-main-intro">
	                        <div class="m-detail-main-title">
	                            <h1 title="<?php echo $product['title']; ?>"><?php echo $product['title']; ?></h1>
	                        </div>
	                        <p class="m-detail-main-desc" title="<?php echo $product['subTitle']; ?>"><?php echo $product['subTitle']; ?></p>
	                    </div>

	                    <!-- 正在进行中 -->
		                <div ng-show="yungou.status === 0">
						    <div class="m-detail-main-ing m-detail-main-onlyOne">
						        <div class="m-detail-main-onlyOne-content clearfix">
						            <div class="m-detail-main-one-header clearfix">
						                <h2 class="m-detail-main-type-title">一元夺宝</h2>
						                <h3 class="m-detail-main-type-subtitle">
						                	<span class="period">期号 <?php echo $yungou['term']; ?></span>
						                	每满总需人次，即抽取1人获得该商品
						                </h3>
						            </div>
						            <div class="m-detail-main-one-progress clearfix">
						                <div class="w-progressBar f-clear">
						                    <div class="w-progressBar-wrap">
						                        <span class="w-progressBar-bar" style="width:<?php echo floor($yungou['saleCount']*100/$product['price']).'%'; ?>;"></span>
						                    </div>
						                    <div class="w-progressBar-txt">已完成<?php echo floor($yungou['saleCount']*100/$product['price']).'%'; ?></div>
						                </div>
						            </div>
						            <div class="m-detail-main-one-intro clearfix">
						                <div class="m-detail-main-one-price">总需人次<?php echo $product['price']; ?></div>
						                <div class="m-detail-main-one-remain">剩余人次<?php echo $product['price']-$yungou['saleCount']; ?></div>
						            </div>
						            <div class="m-detail-main-one-count f-clear">
						                <span>参与</span>
						                <div class="m-detail-main-count-number m-detail-main-count-buyTimes f-clear">
						                    <div class="w-number">
						                    	<a class="w-number-btn w-number-btn-minus" href="javascript:void(0);" ng-click="minusAmount()">－</a>
						                        <input class="w-number-input" type="text" ng-model="amount" ng-blur="checkAmount()">
						                        <a class="w-number-btn w-number-btn-plus" href="javascript:void(0);" ng-click="addAmount()">＋</a>
						                    </div>
						                </div>
						                <span style="padding-left: 0;">元</span>
						                <i ng-class="{m_num_click:num_type===10}" ng-click="setAmount(10)">10</i>
						                <i ng-class="{m_num_click:num_type===20}" ng-click="setAmount(20)">20</i>
						                <i ng-class="{m_num_click:num_type===50}" ng-click="setAmount(50)">50</i>
						                <i ng-class="{m_num_click:num_type===100}" ng-click="setAmount(100)">100</i>
						                <em ng-class="{m_num_click:num_type===0}" ng-click="setAmount(0)">包尾</em>
						                <strong class="w_tail">人次</strong>
						                <div class="m_span" ng-class="{m_hide:spanHide===1}" id="spanHide" ng-cloak>
						                	<span ng-bind="span"></span><i></i>
						             	</div>
						            </div>
						            
						            
						            <div class="m-detail-main-one-operation f-clear">
						                <a ng-click="addToPay()" class="m-detail-main-type-btn m-detail-main-one-buy" href="javascript:void(0)">立即夺宝</a>
						                <a ng-click="toCartCartoon('picture');addToCart()" class="m-detail-main-type-btn m-detail-main-one-cart" href="javascript:void(0)">
						                	<i class="ico ico-miniCart"></i>
						                	<span class="btn-txt">加入清单</span>
						                </a>
						            </div>
						            <!-- 未登录用户 -->
						            <div class="m-detail-main-one-codes" ng-show="!user">
            							<a href="javascript:void(0)" ng-click="login()">请登录</a>查看你的夺宝号码
    								</div>
    								<!-- TODO 登录用户没参与 -->
						            <div class="m-detail-main-one-codes" ng-show="user&&numbersCount == 0">
						                你还没参与本期商品哦~
						            </div>
						            <!-- TODO 登录用户有参与 -->
						            <div class="m-detail-main-codes" pro="myCodes" ng-show="user && numbersCount > 0">
						            	你已拥有{{numbersCount}}个夺宝号码 <a href="javascript:void(0)" ng-click="showCode()">查看号码&gt;&gt;</a>
						            </div>
						        </div>
						    </div>
		                </div>

		                <div ng-show="yungou.status === 1 || yungou.status === 2" ng-cloak>
			                <!-- 等待揭晓倒计时 -->
			                <div class="m-detail-main-countdown clearfix" pro="countdownWrap" ng-show="yungou.status === 1">
		                        <i class="ico ico-detail-main-hourglass"></i>
		                        <div class="m-detail-main-countdown-content">
		                            <div class="m-detail-main-countdown-hd">
		                                <span class="period">期号：{{yungou.term}}</span>
		                                <span class="split">|</span>
		                                <span class="title">揭晓倒计时</span>
		                            </div>
		                            <?php
		                            	date_default_timezone_set("PRC");
		                            	$timeout = strtotime($yungou['endTime']) - time();
		                            ?>

		                            <div id="countdownNum" class="m-detail-main-countdown-num" ng-show="1==intervalService['main'].time.length">
		                            	{{Math.floor(intervalService['main'].time[0][0] / 10 % 10)}}{{intervalService['main'].time[0][0] % 10}}:{{Math.floor(intervalService['main'].time[0][1] / 10 % 10)}}{{intervalService['main'].time[0][1] % 10}}:{{Math.floor(intervalService['main'].time[0][2] / 10 % 10)}}{{intervalService['main'].time[0][2] % 10}}
				                    </div>
		                        </div>
	                    	</div>
		                    <!-- 已揭晓 -->
	                    	<div class="m-detail-main-winner" ng-show="yungou.status === 2">
	                    		
		                        <div class="m-detail-main-winner-luckyCode f-clear">
		                            <div class="hd">
		                                <span class="period">期号<span class="period-num">{{yungou.term}}</span></span>
		                                <span class="title">幸运号码</span>
		                            </div>
		                            <div class="code">{{yungou.result}}</div>
		                        </div>
								<?php
	                    			$winUser = json_decode(yungouController::getWin($yungou['id']),true);
	                    			if ($winUser['code'] == 200) {
	                    				$winUser = $winUser['data'];
	                    				if (!$winUser['avatorUrl']) {
	                    					$winUser['avatorUrl'] = "http://mimg.127.net/p/yy/lib/img/avatar/90.jpeg";
	                    				}
	                    		?>
		                        <div class="m-detail-main-winner-detail f-clear">
		                            <i class="ico ico-detail-main-winner"></i>
		                            
		                            <img width="90" height="90" src="<?php echo $winUser['avatorUrl'];?>" class="user-avatar">
		                            <div class="user-info">
		                                <div class="info-item user-nickname"><span class="hd">用户昵称</span>：<span class="bd"><a href="" target="_blank" title="<?php echo $winUser['userName'];?>(<?php echo $winUser['loginArea'];?>)"><?php echo $winUser['userName'];?></a>(<?php echo $winUser['loginArea'];?>)</span></div>
		                                <div class="info-item user-id"><span class="hd">用户 I D</span>：<span class="bd"><?php echo $winUser['userId'];?>（ID为用户唯一不变标识）</span></div>
		                                <div class="info-item user-buyTimes"><span class="hd">本期参与</span>：<span class="bd"><?php echo $winUser['count'];?>人次</span></div>
		                            </div>
		                            <div class="record-info">
		                                <div class="info-item published-time"><span class="hd">揭晓时间</span>：<span class="bd"><?php echo $winUser['endTime'].'.000';?></span></div>
		                                <div class="info-item buy-time"><span class="hd">夺宝时间</span>：<span class="bd"><?php echo $winUser['buyTime'];?></span></div>
		                                <div class="info-item codes"><a id="btnWinnerCodes" href="javascript:void(0)" ng-click="showOtherCode()">查看TA的号码&gt;&gt;</a></div>
		                            </div>
	                        	</div>
	                        	<?php }?>
	                        </div>
	                    	<div class="m-detail-main-calculation clearfix">
		                        <div class="m-detail-main-calculation-formula m-detail-main-calculation-main f-clear">
		                            <div class="m-detail-main-calculation-title">如何计算？</div>
		                            <div class="m-detail-main-calculation-parameter m-detail-main-calculation-luckyCode">
		                                <span class="num">{{yungou.status==2 ? yungou.result : '?'}}</span>
		                                <span class="tip">本期幸运号码</span>
		                            </div>
		                            <div class="m-detail-main-calculation-operation m-detail-main-calculation-equal">=</div>
		                            <div class="m-detail-main-calculation-parameter m-detail-main-calculation-constant">
		                                <span class="num">10000001</span>
		                                <span class="tip">固定数值</span>
		                            </div>
		                            <div class="m-detail-main-calculation-operation m-detail-main-calculation-add">+</div>
		                            <div class="m-detail-main-calculation-parameter m-detail-main-calculation-variable">
		                                <span class="num">{{yungou.status==2 ? (yungou.result-10000001) : '?'}}</span>
		                                <span class="tip">变化数值</span>
		                            </div>
		                        </div>
		                        <div class="m-detail-main-calculation-formula m-detail-main-calculation-secondary f-clear">
		                            <div class="m-detail-main-calculation-title"><strong>变化数值</strong>是取下面公式的余数</div>
		                            <div class="m-detail-main-calculation-operation m-detail-main-calculation-leftBracket">(</div>
		                            <div class="m-detail-main-calculation-parameter m-detail-main-calculation-sum" pro="formulaSum">
		                                <span class="num">{{yungou.A}}</span>
		                                <span class="tip">50个时间求和</span>
		                                <span class="more">
							                <i class="ico ico-detail-main-calculation-tipBox"></i>
							                <span class="more-content">商品的最后一个号码分配完毕，公示该分配时间点前本站全部商品的<strong>最后50个参与时间</strong>，并求和。</span>
		                                </span>
		                            </div>
		                            <div class="m-detail-main-calculation-operation m-detail-main-calculation-add">+</div>
		                            <div class="m-detail-main-calculation-parameter m-detail-main-calculation-lottery" pro="formulaLottery">
		                                <span class="num">{{yungou.status==2 ? yungou.B : '?'}}</span>
		                                <span class="tip">“老时时彩”幸运号码</span>
		                                <span class="more">
						                	<i class="ico ico-detail-main-calculation-tipBox"></i>
						                	<span class="more-content">取最近一期“老时时彩” (第<?php echo $yungou['term'];?>期) 揭晓结果。</span>
			                            </span>
		                            </div>
		                            <div class="m-detail-main-calculation-operation m-detail-main-calculation-rightBracket">)</div>
		                            <div class="m-detail-main-calculation-operation m-detail-main-calculation-divide">÷</div>
		                            <div class="m-detail-main-calculation-parameter m-detail-main-calculation-price">
		                                <span class="num"><?php echo $product['price'];?></span>
		                                <span class="tip">总需人次</span>
		                            </div>
		                        </div>
	                    	</div>
		                    <div class="m-detail-main-codes" pro="myCodes" ng-show="!user">
		                    <a href="javascript:void(0)" id="btnCodesLogin" ng-click="login()">请登录</a>查看你的夺宝号码
		                    </div>
		                    <div class="m-detail-main-codes" pro="myCodes" ng-show="user&&numbersCount == 0">
		                    你还没有参与本期商品
		                    </div>
		                    <div class="m-detail-main-codes" pro="myCodes" ng-show="user && numbersCount > 0">
				            	你已拥有{{numbersCount}}个夺宝号码 <a href="javascript:void(0)" ng-click="showCode()">查看号码&gt;&gt;</a>
				            </div>
		                    <?php
		                    	$json = yungouController::yungouProductId($product['id']);
								$arr = json_decode($json,true);
								if ($arr['code'] == 200) {
									$data = $arr['data'];
									if (count($data) > 0) {
		                    ?>
		                    <div class="m-detail-main-newest f-clear" pro="progress">
		                        	<div class="m-detail-main-newest-title"><strong>【最新一期】</strong>正在火热进行中…</div>
		                       		<div class="m-detail-main-newest-progress">
		                            <div class="w-progressBar f-clear">
		                                <div class="w-progressBar-wrap"> <span class="w-progressBar-bar" style="width: <?php echo intval($data[0]['saleCount']/$product['price']*100).'%';?>;"></span> </div>
		                                <div class="w-progressBar-txt w-progressBar-empty">已完成<?php echo intval($data[0]['saleCount']/$product['price']*100).'%';?>，剩余<?php echo $product['price']-$data[0]['saleCount'];?></div>
		                            </div>
		                        	</div><a class="m-detail-main-newest-go" href="/detail/<?php echo $product['id'];?>.html" target="_blank">立即前往</a>
		                    </div>
		                    <?php }}?>
		                    <!-- 商品缺货 -->
					        <div class="m-detail-main-outOfStock f-clear" ng-show="product.isOn==0" style="<?php if (count($data) > 0) echo 'display: none;';?>">
					            <i class="ico m-detail-main-outOfStock-ico"></i>
					            <div class="m-detail-main-outOfStock-content">
					                <p><span>此商品暂时缺货。我们会尽快重新上架，</span><span>敬请期待！</span></p>
					                <a href="/list.html">去逛逛其它商品</a>
					            </div>
					        </div>
					        <div id="wrapExpired" class="m-detail-main-soldOut" style="display: none;">商品已下架</div>
	                    </div>
	                    	
	                </div><!-- 商品右侧 -->

	            </div>
	        </div>
	    </div>

	    <div class="w-tabs w-tabs-main m-detail-mainTab" module="detail/tabs/Tabs" id="pro-view-6" module-id="module-5" module-launched="true" ng-cloak>
		    <div class="g-wrap g-body-bd clearfix">

		    	<!-- 五个panel -->
		        <div class="w-tabs-tab">
		        	<div id="introTab" ng-show="yungou.status===0" class="w-tabs-tab-item" ng-class="{'pro-tabs-tab-item-selected':tagValue===1, 'w-tabs-tab-item-selected':tagValue===1}" ng-click="setTagValue(1)">商品详情</div>
		            <div id="resultTab" ng-show="yungou.status!==0" class="w-tabs-tab-item" ng-class="{'pro-tabs-tab-item-selected':tagValue===2, 'w-tabs-tab-item-selected':tagValue===2}" ng-click="setTagValue(2)">计算结果</div>
		            <div id="recordTab" class="w-tabs-tab-item" ng-class="{'pro-tabs-tab-item-selected':tagValue===3, 'w-tabs-tab-item-selected':tagValue===3}" ng-click="setTagValue(3); record(1)">夺宝参与记录</div>
		            <div id="shareTab" class="w-tabs-tab-item" ng-class="{'pro-tabs-tab-item-selected':tagValue===4, 'w-tabs-tab-item-selected':tagValue===4}" ng-click="setTagValue(4); show(1)">晒单</div>
		            <div id="historyTab" class="w-tabs-tab-item" ng-class="{'pro-tabs-tab-item-selected':tagValue===5, 'w-tabs-tab-item-selected':tagValue===5}" ng-click="setTagValue(5); history(1)">往期夺宝</div>
		        </div>

		        <div class="w-tabs-panel">
		        	<!-- 商品详情 -->
					<div id="introPanel" class="w-tabs-panel-item" ng-show="tagValue===1">
						<?php echo $product['content'];?>
            		</div>
		        	<!-- 计算结果 -->
		            <div id="resultPanel_FH" class="w-tabs-panel-item" ng-show="tagValue===2">
		                <div class="m-detail-mainTab-calcRule">
		                    <h4>
		                        <span class="wrap">
		                                <i class="ico ico-text"></i><span class="txt">幸运号码计算规则</span>
		                        </span>
							</h4>
							 <div class="ruleWrap">
		                        <ol class="ruleList">
		                            <li><span class="index">1</span>商品的最后一个号码分配完毕后，将公示该分配时间点前本站全部商品的最后50个参与时间；</li>
		                            <li><span class="index">2</span>将这50个时间的数值进行求和（得出数值A）（每个时间按时、分、秒、毫秒的顺序组合，如20:15:25.362则为201525362）；</li>
		                            <li><span class="index">3</span>为保证公平公正公开，系统还会等待一小段时间，取最近下一期中国福利彩票“老时时彩”的揭晓结果（一个五位数值B）；</li>
		                            <li><span class="index">4</span>（数值A+数值B）除以该商品总需人次得到的余数<i style="margin-top:-3px;" data-func="remainder" class="ico ico-questionMark"></i> + 原始数&nbsp;10000001，得到最终幸运号码，拥有该幸运号码者，直接获得该商品。</li>
		                            <li class="txt-red">注：如遇福彩中心通讯故障，无法获取上述期数的中国福利彩票“老时时彩”揭晓结果，且24小时内该期“老时时彩”揭晓结果仍未公布，则默认“老时时彩”揭晓结果为00000。</li>
		                        </ol>
		                    </div>
		                </div>
		                <table class="m-detail-mainTab-resultList" cellpadding="0" cellspacing="0" ng-show="computes&&computes.length>0">
		                    <thead>
		                        <tr>
		                            <th class="time" colspan="2">夺宝时间</th>
		                            <th>会员帐号</th>
		                            <th>商品名称</th>
		                            <th width="70">商品期号</th>
		                            <th width="70">参与人次</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                        <tr class="startRow">
		                            <td colspan="6">
		                                截止该商品最后夺宝时间【{{computes[0].buyTime}}】最后50条全站参与记录
		                            </td>
		                        </tr>
		                        <tr class="calcRow" ng-repeat="compute in computes track by $index" ng-show="$index < 50">
		                            <td class="day">{{compute.buyTime.split(' ')[0]}}</td>
		                            <td class="time">{{compute.buyTime.split(' ')[1]}}<i class="ico ico-arrow-transfer"></i><b class="txt-red">{{compute.buyTime.split(' ')[1].replace(":","").replace(":","").replace(".","")}}</b></td>
		                            <td class="user">
		                                <div class="f-txtabb"><a title="{{compute.userName}}(ID:{{compute.userId}})" href="" target="_blank">{{compute.userName}}</a></div>
		                            </td>
		                            <td class="gname"><a href="/detail/{{compute.productId}}-{{compute.term}}.html" target="_blank">{{compute.title}}</a></td>
		                            <td>{{compute.term}}</td>
		                            <td>{{compute.count}}人次</td>
		                        </tr>
		                        
		                        <tr class="resultRow" ng-show="result">
		                            <td colspan="6">
		                                <h4>计算结果<a name="calcResult"></a></h4>
		                                <ol>
		                                    <li><span class="index">1、</span>求和：{{result.A}} (上面50条参与记录的时间取值相加)</li>
		                                    
		                                    <li ng-show="yungou.status==2"><span class="index">2、</span> 第 {{result.sscTerm}} 期“老时时彩”幸运号码：<b class="ball" ng-repeat="ball in result.B.toString().split('') track by $index">{{ball}}</b>
		                                    </li>
		                                    <li ng-show="yungou.status==1"><span class="index">2、</span> 第 {{result.sscTerm}} 期“老时时彩”幸运号码：<b class="ball">?</b><b class="ball">?</b><b class="ball">?</b><b class="ball">?</b><b class="ball">?</b>
		                                    </li>
		                                    
		                                    <li ng-show="yungou.status==2"><span class="index">3、</span> 求余：({{result.A}} + <b class="ball" ng-repeat="ball in result.B.toString().split('') track by $index">{{ball}}</b> ) % {{product.price}} (商品所需人次) =
		                                       <b class="square" ng-repeat="ball in (((result.A+result.B)%product.price).toString().split('')) track by $index">{{ball}}</b> (余数) <i data-func="remainder" class="ico ico-questionMark"></i>
		                                    </li>
		                                    <li ng-show="yungou.status==1"><span class="index">3、</span> 求余：({{result.A}} + <b class="ball">?</b><b class="ball">?</b><b class="ball">?</b><b class="ball">?</b><b class="ball">?</b> ) % {{product.price}} (商品所需人次) =
		                                       <b class="square" ng-repeat="ball in product.price.toString().split('') track by $index">?</b> (余数) <i data-func="remainder" class="ico ico-questionMark"></i>
		                                    </li>
		                                    
		                                    <li ng-show="yungou.status==2"><span class="index">4、</span>
		                                        <b class="square" ng-repeat="ball in (((result.A+result.B)%product.price).toString().split('')) track by $index">{{ball}}</b> (余数) + 10000001 =
		                                        <b class="square" ng-repeat="ball in result.result.toString().split('') track by $index">{{ball}}</b>
		                                    </li>
		                                    <li ng-show="yungou.status==1"><span class="index">4、</span>
		                                        <b class="square" ng-repeat="ball in product.price.toString().split('') track by $index">?</b> (余数) + 10000001 =
		                                        <b class="square">?</b><b class="square">?</b><b class="square">?</b><b class="square">?</b><b class="square">?</b><b class="square">?</b><b class="square">?</b><b class="square">?</b>
		                                    </li>
		                                </ol>
		                                <span class="resultCode" ng-show="yungou.status==2">幸运号码：{{result.result}}</span>
		                                <span class="resultCode" ng-show="yungou.status==1">幸运号码：<span style="margin-left:10px;color:#bbb">等待揭晓...</span></span>
		                            </td>
		                        </tr>
		                        <tr ng-repeat="compute in computes track by $index" ng-show="$index >= 50">
		                            <td class="day">{{compute.buyTime.split(' ')[0]}}</td>
		                            <td class="time">{{compute.buyTime.split(' ')[1]}}</td>
		                            <td class="user">
		                                <div class="f-txtabb"><a title="{{compute.userName}}(ID:{{compute.userId}})" href="" target="_blank">{{compute.userName}}</a></div>
		                            </td>
		                            <td class="gname"><a href="/detail/{{compute.productId}}-{{compute.term}}.html" target="_blank">{{compute.title}}</a></td>
		                            <td>{{compute.term}}</td>
		                            <td>{{compute.count}}人次</td>
		                        </tr>
		                        
		                    </tbody>
		                </table>
		            </div>
		        	<!-- 夺宝记录 -->
		            <div id="recordPanel" class="w-tabs-panel-item m-detail-mainTab-record" ng-show="tagValue===3" ng-cloak>
		                <div class="content">
			                <div class="empty" ng-show="pageService.order.pageCount == 0">
		                        <p class="status-empty"><i class="littleU littleU-cry"></i>&nbsp;&nbsp;暂时还没有任何记录</p>
		                    </div>
		                    <div class="m-detail-recordList-start" ng-show="pageService.order.pageCount > 0 && pageService.order.page == 1"><i class="ico ico-clock"></i></div>
		                    <div ng-repeat="date in orders[pageService.order.page].dates track by $index">
		                        <div class="m-detail-recordList-timeSeperate">{{date}}<i class="ico ico-recordDot ico-recordDot-solid"></i></div>
		                        <ul class="m-detail-recordList">
		                            <li class="clearfix" ng-repeat="order in orders[pageService.order.page].obj[date] track by $index"> <span class="time">{{order.buyTime.split(" ")[1]}}</span> <i class="ico ico-recordDot ico-recordDot-hollow"></i>
		                                <div class="m-detail-recordList-userInfo" ng-class="{'m-detail-recordList-userInfo-detail':showRecord[order.orderId]}" ng-mouseenter="showRecordBtn(order.orderId)" ng-mouseleave="hideRecordBtn(order.orderId)">
		                                    <div class="inner">
		                                        <p>
		                                        	<span class="avatar"><img width="20" height="20" ng-src="{{order.avatorUrl}}"></span>
		                                        	<a href="/user/{{order.userId}}.html" target="_blank" title="{{order.userName}}(ID:{{order.userId}})">{{order.userName}}</a> ({{order.loginArea}} IP：{{order.ip}}) 参与了<b class="times txt-red">{{order.count}}人次</b> 
		                                        	<a ng-show="recordCodeBtn[order.orderId]" class="w-button w-button-simple btn-checkCodes" href="javascript:void(0)" ng-click="showRecordCode(order.orderId,order.numberStart,order.numberEnd,order.buyTime)">所有夺宝号码 
		                                        		<i class="ico ico-arrow-gray ico-arrow-gray-down"></i>
		                                        	</a> 
		                                        </p>
	                                        </div>
		                                </div>
		                            </li>
		                            
		                        </ul>
		                    </div>
		                    <div class="m-detail-recordList-end" ng-show="pageService.order.pageCount > 0 && pageService.order.pageCount == pageService.order.page"><i class="ico ico-clock"></i></div>
		                </div>
		                <div class="pager" ng-hide="pageService.order.pageCount == 0">
		                    <div class="w-pager">

		                        <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.order.page==1}" type="button" ng-disabled="page==1" ng-click="record(1)"><span>首页</span></button>
		                        
		                        <button class="w-button" type="button" ng-class="{'w-button-main':page==pageService.order.page}" ng-repeat="page in pageService.order.pageArr track by $index" ng-click="record(page)"><span>{{page}}</span></button>
		                        
		                        <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.order.page==pageService.order.pageCount}" ng-disabled="pageService.order.page==pageService.order.pageCount" ng-click="record(pageService.order.pageCount)"><span>末页</span></button>
		                        
		                        <span class="w-pager-ellipsis">共{{pageService.order.pageCount}}页</span>
		                    </div>
		                </div>
		            </div>

					<!-- 晒单panel -->
		            <div id="sharePanel" class="w-tabs-panel-item m-detail-mainTab-share" ng-show="tagValue===4">
		                <div pro="sharelist-wrapper" id="pro-view-78">
		                    <div class="empty" ng-show="pageService.show.pageCount==0">
		                        <p class="status-empty"><i class="littleU littleU-cry"></i>&nbsp;&nbsp;暂时还没有任何晒单</p>
		                    </div>
		                    <ul class="m-detail-shareList-list" pro="list" ng-show="pageService.show.pageCount>0">
		                        <li ng-repeat="show in shows[pageService.show.page]">
		                            <div class="m-detail-shareList-author">
		                                <a class="avatar" href="/user/{{show.userId}}.html" target="_blank">
		                                <img width="90" height="90" ng-src="{{show.avatorUrl}}"></a> <a class="nickname f-txtabb" href="/user/index.do?cid=58112809" target="_blank" title="{{show.userName}}(ID:{{show.userId}})">{{show.userName}}</a> 
		                            </div>
		                            <div class="m-detail-shareList-detail">
		                                <div class="titleWrap"> <span class="date">{{show.createdTime}}</span> <span class="title"><a href="" target="_blank"><b>{{show.title}}</b></a></span> </div>
		                                <div class="contentWrap"><a href="/showDetail?showId={{show.showId}}" target="_blank">{{show.content}}</a></div>
		                                <div class="imgWrap">
		                                    <a href="" target="_blank" ng-repeat="imgUrl in show.imgUrls track by $index">
		                                        <div style="display:block;writing-mode:tb-rl;height:140px;line-height:140px;vertical-align:middle"><img style="display: inline;vertical-align:middle;" width="140" ng-src="{{imgUrl}}"></div>
		                                    </a>
		                                </div>
		                            </div>
		                        </li>
		                    </ul>
		                    <div class="pager" pro="pager"  ng-show="pageService.show.pageCount > 0">
		                        <div class="w-pager" id="pro-view-89">
		                            
		                            <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.show.page==1}" type="button" ng-disabled="pageService.show.page==1" ng-click="show(1)"><span>首页</span></button>
			                        
			                        <button class="w-button" ng-class="{'w-button-main':pageService.show.page==page}" type="button" ng-repeat="page in pageService.show.pageArr" ng-click="show(page)"><span>{{page}}</span></button>
			                        
			                        <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':showPage==pageService.show.pageCount}" ng-disabled="pageService.show.page==pageService.show.pageCount" ng-click="show(pageService.show.pageCount)"><span>末页</span></button>
			                        
			                        <span class="w-pager-ellipsis">共{{pageService.show.pageCount}}页</span>
		                        </div>
		                    </div>
		                </div>
		            </div>

		   			

		            <!-- 往期夺宝 -->
		            <div id="historyPanel" class="w-tabs-panel-item m-detail-mainTab-history" ng-show="tagValue===5">
		                <div class="content">
		                	<div class="empty" ng-show="pageService.history.pageCount == 0">
		                        <p class="status-empty"><i class="littleU littleU-cry"></i>&nbsp;&nbsp;暂时还没有任何记录</p>
		                    </div>
		                    <ul class="m-detail-tabHistory-list f-clear" ng-show="pageService.history.pageCount>0">
		                        <div>
		                        	<li class="m-detail-tabHistory-item m-detail-tabHistory-countdown" ng-repeat="history in histories[pageService.history.page].remainItems">
								        <div class="m-detail-tabHistory-period">期号 {{history.term}}</div>
								        <div class="m-detail-tabHistory-info">
								            <div class="w-countdown">
								            	<span class="w-countdown-title">揭晓倒计时</span> <span class="w-countdown-nums" ng-show="$index < intervalService[pageService.history.page].time.length">
								            		<b>{{Math.floor(intervalService[pageService.history.page].time[$index][0] / 10 % 10)}}</b>
				                                    <b>{{intervalService[pageService.history.page].time[$index][0] % 10}}</b>:
				                                    <b>{{Math.floor(intervalService[pageService.history.page].time[$index][1] / 10 % 10)}}</b>
				                                    <b>{{intervalService[pageService.history.page].time[$index][1] % 10}}</b>:
				                                    <b>{{Math.floor(intervalService[pageService.history.page].time[$index][2] / 10 % 10)}}</b>
				                                    <b>{{intervalService[pageService.history.page].time[$index][2] % 10}}</b>
								           		</span>
								           	</div>
								        </div>
								        <div class="m-detail-tabHistory-result" ng-show="history.status==1"></div>
								        <div class="m-detail-tabHistory-operation">
								        	<a href="/detail/<?php echo $yungou['productId'];?>-{{history.term}}.html">查看详情</a>
								        </div>
								    </li>
								    <li class="m-detail-tabHistory-item m-detail-tabHistory-countdown m-detail-tabHistory-published" ng-repeat="history in histories[pageService.history.page].finishedItems">
								        <div class="m-detail-tabHistory-period">期号 {{history.term}}</div>
								        <div class="m-detail-tabHistory-info">
								        	<img ng-src="{{history.avatorUrl}}">
		                                    <div class="m-detail-tabHistory-info-txt">
		                                    	<span>恭喜 <a href="/user/{{history.userId}}.html" title="{{history.userName}}" target="_blank"><span class="m-detail-tabHistory-info-nickname">{{history.userName}}</span></a>（<span class="m-detail-tabHistory-info-address" title="{{history.loginArea}}">{{history.loginArea}}</span>）获得了本期商品</span> <span>用户ID：{{history.userId}}（ID为用户唯一不变标识）</span> <span>本期参与：<strong>{{history.count}}人次</strong></span>
		                                    </div>
		                                </div>
								        <div class="m-detail-tabHistory-result"> 
								         	<span>幸运号码：<strong>{{history.result}}</strong></span> 
								         	<span>揭晓时间：{{history.endTime}}</span> 
								         	<span>夺宝时间：{{history.buyTime}}</span>
								        </div>
								        <div class="m-detail-tabHistory-operation">
								        	<a href="/detail/<?php echo $yungou['productId'];?>-{{history.term}}.html">查看详情</a>
								        </div>
								    </li>
		                        </div>
		                    </ul>
		                </div>
		                <div class="pager" id="history" ng-show="pageService.history.pageCount > 0">
		                    <div class="w-pager">
		                        
		                        <button class="w-button w-button-aside" ng-class="{'w-button-disabled':pageService.history.page==1}" type="button" ng-disabled="pageService.history.page==1" ng-click="history(1)"><span>首页</span></button>

		                        <button class="w-button" ng-class="{'w-button-main':pageService.history.page==page}" type="button" ng-repeat="page in pageService.history.pageArr track by $index" ng-click="history(page)"><span>{{page}}</span></button>
		                        
		                        <button class="w-button w-button-aside" type="button" ng-class="{'w-button-disabled':pageService.history.page==pageService.history.pageCount}" ng-disabled="pageService.history.page==pageService.history.pageCount" ng-click="history(pageService.history.pageCount)"><span>末页</span></button>
		                        
		                        <span class="w-pager-ellipsis">共{{pageService.history.pageCount}}页</span>
		                    </div>
		                </div>
		            </div>
		        </div>
		    </div>
		</div>
	</div>

	<?php include '../components/footer.php';?>

    <script type="text/javascript" src="../../public/js/app.js"></script>

    <script type="text/javascript">
        app.controller('DetailController', function ($rootScope, $scope, $http, $interval, $timeout, ngDialog,intervalService,pageService) {
        	$scope.num_type = -1;
        	$scope.spanHide = 1;
        	$scope.span = "";
			$scope.animate = $("#spanHide");

			$scope.pageService = pageService;

        	$scope.orders = {};
        	$scope.histories = {};
        	$scope.shows = {};
        	$scope.Math = Math;
            $scope.intervalService = intervalService;
            $scope.revealListRemainItems = {};
            $scope.remainItems = new Array();
            $scope.finishedItems = new Array();

            $scope.historyStartTime = "";

        	$scope.numbersCount = -1;

        	$scope.recordCodeBtn = {};
        	$scope.showRecord = {};
        	$scope.showRecordNumbers = {};
        	
        	$scope.imgIndex = 0; // 显示第n张图片
        	$scope.leftPad = 31; // 
        	$scope.hoverIn = function (index) {
	        	$scope.imgIndex = index;
	        	$scope.leftPad = 31+86*index;
        	};

        	$scope.currentPanel = 0;
        	$scope.yungou = JSON.parse('<?php echo str_replace("'","",json_encode($yungou,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE)); ?>');
        	$scope.product = JSON.parse('<?php echo str_replace("\\\"","",str_replace("'","",json_encode($product,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE))); ?>');
        	if ($scope.yungou.status == 1) {
        		var timeout = <?php echo $timeout;?>;
        		timeout = timeout * 100;
        		$scope.revealRemainItems = [];
        		$scope.revealRemainItems.push(timeout)
        		$scope.intervalService.init('main',$scope.revealRemainItems);
        		$scope.intervalService.countdown('main',function (e,id) {
        			$scope.revealRemainItems[e] = 0;
		        	location.reload();
		        });
        	}
        	$scope.amount = $scope.product.singlePrice;

        	$scope.tagValue= $scope.yungou.status == 0 ? 1:2;

        	//显示夺宝参与记录里的查看夺宝号码按钮
        	$scope.showRecordBtn = function (id) {
        		$scope.recordCodeBtn[id] = 1;
        	};
        	$scope.hideRecordBtn = function (id) {
        		$scope.recordCodeBtn[id] = 0;
        	}

        	//显示夺宝参与记录用户的夺宝号码
        	$scope.showRecordCode = function (id, start, end, buyTime) {
        		var numbers = []
        		for(var i=start;i<=end;i++) {
        			numbers.push(i);
        		}
        		var items = [];
        		var item = {};
        		item.buyTime = buyTime;
        		item.numbers = numbers;
        		items.push(item);
        		var data = {numbersCount:(end-start+1),items:items,result:-1};
				ngDialog.open({
					template: '/public/tpls/code.html',
					className: 'ngdialog-theme-default',
					data: data,
					controller: ['$rootScope', '$scope', '$http', function ($rootScope, $scope, $http) {
					}]
				});
        	}

			//设置tag
			$scope.setTagValue = function (value) {
				$scope.tagValue = value;
			}
			//点击数量按钮
			$scope.setAmount = function (num) {
				$scope.animate.stop(true);
				$scope.num_type = num;
				if (num == 0) {
					$scope.amount = ($scope.product.price - $scope.yungou.saleCount);
				} else {
					if (num > ($scope.product.price - $scope.yungou.saleCount)) {
						$scope.amount = ($scope.product.price - $scope.yungou.saleCount);
					} else {
						$scope.amount = num;
					}
				}
				$scope.span = "获得几率"+($scope.amount/$scope.product.price*100).toFixed(3)+"%";
				$scope.spanHide = 0;
				$scope.animate.css("opacity",1).animate({
					opacity : 0
				},2000,function () {
					$scope.spanHide = 1;
				})
			}
        	// 添加参与数量
			$scope.addAmount = function () {
				$scope.amount += $scope.product.singlePrice;
				if ($scope.amount >= ($scope.product.price - $scope.yungou.saleCount)) {
					$scope.amount = $scope.product.price - $scope.yungou.saleCount;
				}
			};
			// 减少参与数量
			$scope.minusAmount = function () {
				$scope.amount -= $scope.product.singlePrice;
				if ($scope.amount <= 0) {
					$scope.amount = $scope.product.singlePrice;
				}
			};
			// 检查数量
        	$scope.checkAmount = function () {
        		if (!parseInt($scope.amount)) {
					$scope.amount = $scope.product.singlePrice;
				} else if (parseInt($scope.amount) <= 0) {
					$scope.amount = $scope.product.singlePrice;
				} else if (parseInt($scope.amount) >= ($scope.product.price - $scope.yungou.saleCount)) {
					$scope.amount = $scope.product.price - $scope.yungou.saleCount;
				} else {
					$scope.amount = Math.floor(parseInt($scope.amount)/$scope.product.singlePrice) * $scope.product.singlePrice;
				}
        	};
        	// 加入清单
			$scope.addToCart = function () {
				$rootScope.addToCart({
                    amount: $scope.amount,
                    yungou: $scope.yungou.id,
                    product: {
                        thumbnailUrl: $scope.product.thumbnailUrl,
                        title: $scope.product.title,
                        price : $scope.product.price
                    }
                });
			};
			// 立即夺宝
			$scope.addToPay = function () {
				if (!$rootScope.user) {
					$rootScope.login();
				} else {
					// TODO 去购物车结算
					location.href = "/cartIndex?yungouId="+$scope.yungou.id+"&amount="+$scope.amount;
				}
			};
			$scope.showCode = function (other) {
				var data = {numbersCount:$scope.numbersCount,items:$scope.items,result:$scope.yungou.result};
				ngDialog.open({
					template: '/public/tpls/code.html',
					className: 'ngdialog-theme-default',
					data: data,
					controller: ['$rootScope', '$scope', '$http', function ($rootScope, $scope, $http) {
					}]
				});
			};
			$scope.showOtherCode = function () {
				var data = {numbersCount:$scope.otherNumbersCount,items:$scope.otherItems,result:$scope.yungou.result};
				ngDialog.open({
					template: '/public/tpls/code.html',
					className: 'ngdialog-theme-default',
					data: data,
					controller: ['$rootScope', '$scope', '$http', function ($rootScope, $scope, $http) {
					}]
				});
			}
			$http.get('/user/isLogin').success(function (data) {
				if (data.code === 200) {
					$http.get("/order/getNumbers?id="+$scope.yungou.id+"&userId="+data.data[0].id)
					.success(function (data) {
						if (data.code == 200) {
							var objs = data.data
							$scope.numbersCount = objs.count;
							for(var index in objs.numbers) {
								objs.numbers[index].numbers = new Array();
								for (var i = objs.numbers[index].numberStart; i <= objs.numbers[index].numberEnd; i++) {
									objs.numbers[index].numbers.push(i);
								}
							}
							$scope.items = objs.numbers;
						}
					});
				}
			});
			if ($scope.yungou.status == 2) {
				$http.get("/order/getNumbers?id="+$scope.yungou.id+"&userId=<?php echo $yungou['status']==2 ? $winUser['userId']: '';?>")
				.success(function (data) {
					if (data.code == 200) {
						var objs = data.data;
						$scope.otherNumbersCount = objs.count;
						for(var index in objs.numbers) {
							objs.numbers[index].numbers = new Array();
							for (var i = objs.numbers[index].numberStart; i <= objs.numbers[index].numberEnd; i++) {
								objs.numbers[index].numbers.push(i);
							}
						}
						$scope.otherItems = objs.numbers;
					}
				})
				.error(function (data) {
					console.log(data);
				})
			}
			var second = (new Date()).valueOf()/1000;
			$http.get("/order/getOrderCount?id="+$scope.yungou.id)
			.success(function (data) {
				if (data.code == 200) {
					var orderPageCount = parseInt(data.data/50)+(data.data%50?1:0);
					$scope.pageService.init("order",orderPageCount);
					$scope.record(1);
				}
			})
			.error(function (data) {
				console.log(data);
			});
			$scope.record = function (page) {
				if (typeof $scope.pageService.order !== 'undefined' && page != $scope.orderPage && typeof $scope.orders[page] == "undefined") {
					$scope.pageService.setPage('order',page);
					$http.get("/order/orderByYungou?id="+$scope.yungou.id+"&now="+second+"&page=1")
					.success(function (data) {

						var orders = data.data;
						var obj = {};
						var dates = new Array();
						for(var index in orders) {
							orders[index].avatorUrl = orders[index].avatorUrl == "" ||
							typeof orders[index] == "undefined" ||
							!orders[index] ? "http://mimg.127.net/p/yy/lib/img/avatar/40.jpeg" : orders[index].avatorUrl;

							$scope.recordCodeBtn[orders[index].orderId] = 0;

							var date = orders[index].buyTime.split(" ")[0];
							if (typeof obj[date] == "undefined") {
								obj[date] = new Array();
								dates.push(date);
							}
							obj[date].push(orders[index]);
						}
						$scope.orders[page] = {};
						$scope.orders[page].dates = dates;
						$scope.orders[page].obj = obj;
					})
					.error(function (data) {
						console.log(data);
					});
				}
			}
			$http.get("/yungou/getHistoryCount?productId="+$scope.yungou.productId)
			.success(function (data) {
				if (data.code == 200) {
					var historyPageCount = parseInt(data.data/10)+(data.data%10?1:0);
					$scope.pageService.init("history",historyPageCount);
					$scope.history(1);
				}
			})
			.error(function (data) {
				console.log(data);
			})
			$scope.history = function (page) {
				if (typeof $scope.pageService.history !== 'undefined' && page != $scope.pageService.history.page && typeof $scope.histories[page] == "undefined") {
					$scope.pageService.setPage('history',page);
					var url = "/yungou/getHistory?productId="+$scope.yungou.productId+"&limit=10";
					if (page != 1) {
						url += "&startTime="+$scope.historyStartTime;
					}
					$http.get(url)
					.success(function (data) {
						if (data.code == 200) {
							$scope.histories[page] = {};
							$scope.histories[page].finishedItems = new Array();
							$scope.histories[page].remainItems = new Array();
							$scope.revealListRemainItems[page] = new Array();
							data = data.data;
							for(var index in data) {
								if (data[index].status == 1) {
									$scope.histories[page].remainItems.push(data[index]);
						        	$scope.revealListRemainItems[page].push(data[index].timeout*100);
								} else {
									data[index].avatorUrl = (data[index].avatorUrl==""||typeof data[index].avatorUrl=="undefined"||!data[index].avatorUrl ? "http://mimg.127.net/p/yy/lib/img/avatar/40.jpeg" : data[index].avatorUrl);
									$scope.histories[page].finishedItems.push(data[index]);
								}
								if (index == data.length-1) {
									$scope.historyStartTime = data[index].startTime;
								}
							}
							// $scope.histories[page] = data;
					        if ($scope.revealListRemainItems[page].length > 0) {
						        $scope.intervalService.init(page,$scope.revealListRemainItems[page]);
						        $scope.intervalService.countdown(page,function (e,id) {
		                            $scope.histories[id].remainItems[e].timeout = 0;
						        	$http.get("/order/winOrderDetail?yungouId="+$scope.histories[id].remainItems[e].yungouId)
			                        .success(function (data) {
			                            if (data.code == 200) {
			                                data = data.data;
			                                $scope.histories[id].remainItems[e] = $scope.histories[id].remainItems[e] = data;
			                            }
			                            $scope.histories[id].finishedItems.unshift($scope.histories[id].remainItems[e]);
			                            $scope.histories[id].remainItems.splice(e,1);
			                        })
			                        .error(function (data) {
			                            console.log(data);
			                        });
						        });
					        }
						}
					})
					.error(function (data) {
						console.log(data);
					});
				}
			}
			$http.get("/show/getCountByProduct?productId="+$scope.yungou.productId)
			.success(function (data) {
				var showPageCount = parseInt(data.data/10)+(data.data%10?1:0);
				$scope.pageService.init("show",showPageCount);
				$scope.show(1);
			})
			.error(function (data) {
				console.log(data);
			})
			$scope.show = function (page) {
				if (typeof $scope.pageService.show !== 'undefined' && page != $scope.showPage && typeof $scope.shows[page] == "undefined") {
					var url = "/show/getByProduct?productId="+$scope.yungou.productId+"&limit=10";
					if (page != 1) {
						url += "&lastId="+$scope.showLastId;
					}
					$http.get(url)
					.success(function (data) {
						if (data.code == 200) {
							data = data.data;
							for(var index in data) {
								if (index == data.length-1) {
									$scope.showLastId = data[index].showId;
								}
							}
							$scope.shows[page] = data;
						}
					})
					.error(function (data) {
						console.log(data);
					})
				}
				$scope.pageService.setPage('show',page);

			};
			
			if ($scope.tagValue == 2) {
				$http.get("/yungou/compute?id="+$scope.yungou.id)
				.success(function (data) {
					if (data.code == 200) {
						var computes = data.data;
						if (computes.length > 0) {
							$scope.computes = computes;
						}
					}
				})
				.error(function (data) {
					console.log(data);
				});
				$http.get("/yungou/getResult?id="+$scope.yungou.id)
				.success(function (data) {
					if (data.code == 200) {
						var result = data.data;
						if (result.length > 0) {
							$scope.result = result[0];
						}
					}
				})
				.error(function (data) {
					console.log(data);
				});
			}
        });
		app.filter('num', function () {
			return function (input) {
				return parseInt(input);
			}
		});
    </script>
	
</body>

</html>