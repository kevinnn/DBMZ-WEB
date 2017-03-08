<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8"> 
<link id=cssfile rel=stylesheet type=text/css href='/public/css/admin/skin_0.css'>
<link rel="stylesheet" href="/public/css/admin/global.css" type="text/css">
<link rel="stylesheet" href="/public/css/admin/index.css" type="text/css">
<script src="/public/js/jquery/jquery.min.js"></script>
<script src="/public/js/admin/layer.min.js"></script>
<script src="/public/js/admin/global.js"></script>
<title>后台首页</title>

<script type="text/javascript">

var ready=1;
var kj_width;
var kj_height;
var header_height=91;
var R_label;
var R_label_one = "当前位置: 系统设置 >";


function left(init){
	var left = document.getElementById("left");
	var leftlist = left.getElementsByTagName("ul");
	for (var k=0; k<leftlist.length; k++){
		leftlist[k].style.display="none";
	}
	var selected_left = document.getElementById(init);
	selected_left.style.display="block";
	selected_left.getElementsByTagName('a')[0].click();
}

function secBoard(elementID,n,init,r_lable) {
			
	var elem = document.getElementById(elementID);
	var elemlist = elem.getElementsByTagName("li");
	for (var i=0; i<elemlist.length; i++) {
		elemlist[i].className = "normal";		
	}
	elemlist[n].className = "current";
	R_label_one="当前位置: "+r_lable+" >";
	R_label.text(R_label_one);
	left(init);
}


function set_div(){
		kj_width=$(window).width();
		kj_height=$(window).height();
		if(kj_width<1000){kj_width=1000;}
		if(kj_height<500){kj_height=500;}

		$("#header").css('width',kj_width); 
		$("#header").css('height',header_height);
		$("#left").css('height',kj_height-header_height); 
	    $("#right").css('height',kj_height-header_height); 
		$("#left").css('top',header_height); 
		$("#right").css('top',header_height);
		
		$("#left").css('width',180);		
		$("#right").css('width',kj_width-182); 
		$("#right").css('left',182);
		
		$("#right_iframe").css('width',kj_width-206); 
		$("#right_iframe").css('height',kj_height-148);
		 		
		$("#iframe_src").css('width',kj_width-208); 
		$("#iframe_src").css('height',kj_height-150); 	
		
		$("#off_on").css('height',kj_height-180);
		
		var nav=$("#nav");		
				nav.css("left",(kj_width-nav.get(0).offsetWidth)/2);
		nav.css("top",61);	
}


$(document).ready(function(){	
		set_div();		
		$("#off_on").click(function(){
				if($(this).attr('val')=='open'){
					$(this).attr('val','exit');
					$("#right").css('width',kj_width);
					$("#right").css('left',1);
					$("#right_iframe").css('width',kj_width-25); 
					$("iframe").css('width',kj_width-27);
				}else{
					$(this).attr('val','open');
					$("#right").css('width',kj_width-182);
					$("#right").css('left',182);
					$("#right_iframe").css('width',kj_width-206); 
					$("iframe").css('width',kj_width-208);
				}
		});
		
		left('admin');
		$(".left_date a").click(function(){
				$(".left_date li").removeClass("set");						  
				$(this).parent().addClass("set");
				R_label.text(R_label_one+' '+$(this).text()+' >');
				$("#iframe_src").attr("src",$(this).attr("src"));
		});
		$(".left_date1 a").click(function(){
				$(".left_date li").removeClass("set");						  
				$(this).parent().addClass("set");
				R_label.text(R_label_one+' '+$(this).text()+' >');
				$("#iframe_src").attr("src",$(this).attr("src"));
		});
		$("#iframe_src").attr("src","/views/admin/tDefault.html");
		R_label=$("#R_label");
	//	$('body').bind('contextmenu',function(){return false;});
	//	$('body').bind("selectstart",function(){return false;});
				
});

function api_off_on_open(key){
	if(key=='open'){
				$("#off_on").attr('val','exit');
				$("#right").css('width',kj_width);
				$("#right").css('left',1);
				$("#right_iframe").css('width',kj_width-25); 
				$("iframe").css('width',kj_width-27);
	}else{
					$("#off_on").attr('val','open');
					$("#right").css('width',kj_width-182);
					$("#right").css('left',182);
					$("#right_iframe").css('width',kj_width-206); 
					$("iframe").css('width',kj_width-208);
	}
}
</script>

<style>
.header_case{  position:absolute; right:10px; top:10px; color:#fff}
.header_case a{ padding-left:5px}
.header_case a:hover{ color:#fff; }

.left_date a{background: url("../../public/img/admin/left.png") repeat-y scroll left top rgba(0, 0, 0, 0);}
.left_date{overflow:hidden;}
.left_date ul{ margin:0px; padding:0px;}
.left_date li{line-height:25px; height:25px; margin:0px 10px; margin-left:15px; overflow:hidden;}
.left_date li a{ display:block;text-indent:5px;  overflow:hidden}
.left_date li a:hover{ background-color:#d3e8f2;text-decoration:none;border-radius:3px;}
.left_date .set a{background-color:#d3e8f2;border-radius:3px; font-weight:bold}
.head{ border-bottom:1px solid #032E43; color:#032E43; font-weight:bold; margin-bottom:10px;}

.left_date1 {overflow:hidden;}
</style>

</head>

<link id="cssfile" rel=stylesheet type=text/css href="/public/css/admin/skin_0.css">




<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries --><!--[if lt IE 9]>
<SCRIPT src="/data/plugin/style/images/html5shiv.js"></SCRIPT>

<SCRIPT src="/data/plugin/style/images/respond.min.js"></SCRIPT>
<![endif]-->


<script>
//
$(document).ready(function () {
    $('span.bar-btn').click(function () {
	$('ul.bar-list').toggle('fast');
    });
});

$(document).ready(function(){
	var pagestyle = function() {
		var iframe = $("#workspace");
		var h = $(window).height() - iframe.offset().top;
		var w = $(window).width() - iframe.offset().left;
		if(h < 300) h = 300;
		if(w < 973) w = 973;
		iframe.height(h);
		iframe.width(w);
	}
	pagestyle();
	$(window).resize(pagestyle);
	//turn location
	if($.cookie('now_location_act') != null){
		openItem($.cookie('now_location_op')+','+$.cookie('now_location_act')+','+$.cookie('now_location_nav'));
	}else{
		$('#mainMenu>ul').first().css('display','block');
		//第一次进入后台时，默认定到欢迎界面
		$('#item_welcome').addClass('selected');
		$('#workspace').attr('src',"/views/admin/tDefault.html");
	}
	$('#iframe_refresh').click(function(){
		var fr = document.frames ? document.frames("workspace") : document.getElementById("workspace").contentWindow;;
		fr.location.reload();
	});

});
//收藏夹
function addBookmark(url, label) {
    if (document.all)
    {
        window.external.addFavorite(url, label);
    }
    else if (window.sidebar)
    {
        window.sidebar.addPanel(label, url, '');
    }
}


function openItem(args){
    closeBg();
	//cookie

	if($.cookie('F81E_sys_key') === null){
		location.href = 'index.php?act=login&op=login';
		return false;
	}

	spl = args.split(',');
	op  = spl[0];
	try {
		act = spl[1];
		nav = spl[2];
	}
	catch(ex){}
	if (typeof(act)=='undefined'){var nav = args;}
	$('.actived').removeClass('actived');
	$('#nav_'+nav).addClass('actived');

	$('.selected').removeClass('selected');

	//show
	$('#mainMenu ul').css('display','none');
	$('#sort_'+nav).css('display','block');

	if (typeof(act)=='undefined'){
		//顶部菜单事件
		html = $('#sort_'+nav+'>li>dl>dd>ol>li').first().html();
		str = html.match(/openItem\('(.*)'\)/ig);
		arg = str[0].split("'");
		spl = arg[1].split(',');
		op  = spl[0];
		act = spl[1];
		nav = spl[2];
		first_obj = $('#sort_'+nav+'>li>dl>dd>ol>li').first().children('a');
		$(first_obj).addClass('selected');
		//crumbs
		$('#crumbs').html('<span>'+$('#nav_'+nav+' > span').html()+'</span><span class="arrow">&nbsp;</span><span>'+$(first_obj).text()+'</span>');
	}else{
		//左侧菜单事件
		//location
		$.cookie('now_location_nav',nav);
		$.cookie('now_location_act',act);
		$.cookie('now_location_op',op);
		$("a[name='item_"+op+act+"']").addClass('selected');
		//crumbs
		$('#crumbs').html('<span>'+$('#nav_'+nav+' > span').html()+'</span><span class="arrow">&nbsp;</span><span>'+$('#item_'+op+act).html()+'</span>');
	}
	src = 'index.php?act='+act+'&op='+op;
	$('#workspace').attr('src',src);

}

$(function(){
		bindAdminMenu();
		})
		function bindAdminMenu(){

		$("[nc_type='parentli']").click(function(){
			var key = $(this).attr('dataparam');
			if($(this).find("dd").css("display")=="none"){
				$("[nc_type='"+key+"']").slideDown("fast");
				$(this).find('dt').css("background-position","-322px -170px");
				$(this).find("dd").show();
			}else{
				$("[nc_type='"+key+"']").slideUp("fast");
				$(this).find('dt').css("background-position","-483px -170px");
				$(this).find("dd").hide();
			}
		});
	}
</script>

<script type=text/javascript>
//显示灰色JS遮罩层
function showBg(ct,content){
var bH=$("body").height();
var bW=$("body").width();
var objWH=getObjWh(ct);
$("#pagemask").css({width:bW,height:bH,display:"none"});
var tbT=objWH.split("|")[0]+"px";
var tbL=objWH.split("|")[1]+"px";
$("#"+ct).css({top:tbT,left:tbL,display:"block"});
$(window).scroll(function(){resetBg()});
$(window).resize(function(){resetBg()});
}
function getObjWh(obj){
var st=document.documentElement.scrollTop;//滚动条距顶部的距离
var sl=document.documentElement.scrollLeft;//滚动条距左边的距离
var ch=document.documentElement.clientHeight;//屏幕的高度
var cw=document.documentElement.clientWidth;//屏幕的宽度
var objH=$("#"+obj).height();//浮动对象的高度
var objW=$("#"+obj).width();//浮动对象的宽度
var objT=Number(st)+(Number(ch)-Number(objH))/2;
var objL=Number(sl)+(Number(cw)-Number(objW))/2;
return objT+"|"+objL;
}
function resetBg(){
var fullbg=$("#pagemask").css("display");
if(fullbg=="block"){
var bH2=$("body").height();
var bW2=$("body").width();
$("#pagemask").css({width:bW2,height:bH2});
var objV=getObjWh("dialog");
var tbT=objV.split("|")[0]+"px";
var tbL=objV.split("|")[1]+"px";
$("#dialog").css({top:tbT,left:tbL});
}
}

//关闭灰色JS遮罩层和操作窗口
function closeBg(){
$("#pagemask").css("display","none");
$("#dialog").css("display","none");
}
</script>

<script type=text/javascript>
$(function(){
    var $li =$("#skin li");
		$li.click(function(){
		$("#"+this.id).addClass("selected").siblings().removeClass("selected");
		$("#cssfile").attr("href","http://fwy-zsb.yygcms.com/index.php/admin/templates/default/css/"+ (this.id) +".css");
        $.cookie( "MyCssSkin" ,  this.id , { path: '/', expires: 10 });

        $('iframe').contents().find('#cssfile2').attr("href","http://fwy-zsb.yygcms.com/index.php/admin/templates/default/css/"+ (this.id) +".css");
    });

    var cookie_skin = $.cookie( "MyCssSkin");
    if (cookie_skin) {
		$("#"+cookie_skin).addClass("selected").siblings().removeClass("selected");
		$("#cssfile").attr("href","http://fwy-zsb.yygcms.com/index.php/admin/templates/default/css/"+ cookie_skin +".css");
		$.cookie( "MyCssSkin" ,  cookie_skin  , { path: '/', expires: 10 });
    }
});
function addFavorite(url, title) {
	try {
		window.external.addFavorite(url, title);
	} catch (e){
		try {
			window.sidebar.addPanel(title, url, '');
        	} catch (e) {
			showDialog("请按 Ctrl+D 键添加到收藏夹", 'notice');
		}
	}
}
</script>


      <div id="topnav" class="top-nav">
	  <div class="top-navleft">欢迎您进入管理后台！</div>
      <ul>
        <li class="adminid" title="admin">您好&nbsp;:&nbsp;<STRONG id="admin_username"></STRONG></li>
        <li><a title="安全退出" href="#" onclick="logout()"><span>安全退出</span></a></li>
        <li><a title="网站首页" href="/views/admin/index.php" target="_blank"><span>网站首页</span></a></li>
		</ul>
	</div><!-- End of Top navigation --><!-- Main navigation -->
	 
	<div id="navbox" class="navbox">
	<div class="navleft"></div>
	<div class="navright">
	<NAV id="nav" class="main-nav">
      <ul>
              <li class="current"><a href="#" src="/views/admin/tDefault.html" onClick="secBoard('nav',1,'admin','后台首页');">后台首页</a></li>
	        <!--<li class="normal"><a href="#" onClick="secBoard('nav',1,'setting','系统设置');">系统设置</a></li>-->
            <li class="normal"><a href="#" onClick="secBoard('nav',1,'admin','管理员管理');">管理员管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',2,'content','文章管理');">文章管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',3,'shop','商品管理');">商品管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',4,'jf_shop','云购管理');">云购管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',5,'order','订单管理');">订单管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',6,'show','晒单管理');">晒单管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',7,'user','用户管理');">用户管理</a></li>
            <li class="normal"><a href="#" onClick="secBoard('nav',8,'template','界面管理');">界面管理</a></li>
            <!--<li class="normal"><a href="#" onClick="secBoard('nav',10,'yunapp','插件管理');">插件管理</a></li>-->
		</ul></NAV>
		</div>
	</div>

<!--header end-->
<div id="left">
    <ul class="left_date" id="setting">   
    	<li class="head">系统管理</li>
        <li class="head">后台首页</li>
        <li><a href="javascript:void(0);" src="/views/admin/tDefault.html">后台首页</a></li>
		<li class="head">其他</li>
    </ul>
  
    <ul class="left_date" id="admin">
       <li class="head">管理员管理</li>
		<li><a href="javascript:void(0);" src="/views/admin/adminList.html">管理员列表</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/adminAdd.html">添加管理员</a></li>
        <li><a id="updatePass" href="javascript:void(0);">修改密码</a></li>
    </ul>
   
    <ul class="left_date" id="shop">
		<li class="head">商品管理</li>
        <li><a href="javascript:void(0);" src="/views/admin/productAdd.html">添加新商品</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/productList.html">商品列表</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/category.html">商品分类</a></li> 
        <li><a href="javascript:void(0);" src="/views/admin/brand.html">品牌管理</a></li>    	
	</ul>	
         
         
	<ul class="left_date" id="jf_shop">
        <li class="head">云购管理</li>
        <li><a href="javascript:void(0);" src="/views/admin/yungouList.html">云购列表</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/yungouList.html?status=0">未结束</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/yungouList.html?status=1">揭晓中</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/yungouList.html?status=2">已结束</a></li>
    </ul>

    <ul class="left_date" id="order">
        <li class="head">订单管理</li>
        <li><a href="javascript:void(0);" src="/views/admin/orderList.html">订单列表</a></li>
		<li><a href="javascript:void(0);" src="/views/admin/orderList.html?isPay=1">已支付订单</a></li> 
        <li><a href="javascript:void(0);" src="/views/admin/winList.html">中奖订单</a></li> 
        <li><a href="javascript:void(0);" src="/views/admin/winList.html?logisticsStatus=1">待发货订单</a></li>
		<li><a href="javascript:void(0);" src="/views/admin/winList.html?logisticsStatus=2">已发货订单</a></li>
		<li><a href="javascript:void(0);" src="/views/admin/winList.html?logisticsStatus=3">已收货订单</a></li>
    </ul>

    <ul class="left_date" id="show">
        <li class="head">晒单管理</li>
        <li><a href="javascript:void(0);" src="/views/admin/showList.html">晒单列表</a></li>
    </ul>

     <ul class="left_date" id="user">
     	<li class="head">用户管理</li>
        <li><a href="javascript:void(0);" src="/views/admin/userList.html">会员列表</a></li>
		 <li><a href="javascript:void(0);" src="/views/admin/userSelect.html">查找会员</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/userAdd.html">添加会员</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/depositList.html?page=1&type=1">充值记录</a></li>
		<li><a href="javascript:void(0);" src="/views/admin/delegateList.html">代理列表</a></li>
		<li><a href="javascript:void(0);" src="/views/admin/withdrawList.html?page=1">提现记录</a></li>
		<li><a href="javascript:void(0);" src="/views/admin/balanceList.html">余额变动记录</a></li>
    </ul>
         
         
    <ul class="left_date" id="content">
     	<li class="head">文章管理</li>
        <li><a href="javascript:void(0);" src="/views/admin/articleList.html">文章列表</a></li>
        <li><a href="javascript:void(0);" src="/views/admin/articleAdd.html">添加文章</a></li>
    </ul>	
        
    <ul class="left_date" id="template">
     	<li class="head">界面管理</li>
        <li><a href="javascript:void(0);" src="/views/admin/bannerList.html">幻灯管理</a></li>
		<li><a href="javascript:void(0);" src="/views/admin/bannerAdd.html">添加幻灯</a></li>
    </ul>	


       
	 <div style="padding:30px 10px; color:#ccc">
     	<p>
        	 © 2014 YYGCMS<br>
			All Rights Reserved.
        </p>
     </div>

</div><!--left end-->
<div id="right">
	<div class="right_top">
    	<ul class="R_label" id="R_label">
        	当前位置: 系统设置 >  后台主页 >
        </ul>
    	<ul class="R_btn">
    	<a href="javascript:;" onClick="btn_iframef5();" class="system_button"><span>刷新框架</span></a>
        </ul>
    </div>
    <div class="right_left">
    	<a href="#" val="open" title="全屏" id="off_on">全屏</a>
    </div>
    <div id="right_iframe">
        
         <iframe id="iframe_src" name="iframe" class="iframe"
         frameborder="no" border="1" marginwidth="0" marginheight="0" 
         src="" 
         scrolling="auto" allowtransparency="yes" style="width:100%; height:100%">
         </iframe>
        
    </div>
</div><!--right end-->

<script type="text/javascript">
	$.ajax({
		'url': '/admin/admin/isLogin',
		'type': 'GET',
		'async': false,
		success: function (data) {
			if (data.code == 200) {
				data = data.data;
				$("#admin_username").text(data.username);
				document.getElementById("updatePass").setAttribute("src","/views/admin/adminEdit.html?id="+data.id+"&username="+data.username);
			} else {
				window.location.href = '/admin/login.html';
			}
		}
	})
	function logout () {
		$.ajax({
			'url': '/admin/admin/logout',
			'type': 'GET',
			success: function (data) {
				if (data.code == 200) {
					window.location.href = '/admin/login.html';
				}
			}
		})
	}
</script>

</body>
</html>