<?php if (!defined('THINK_PATH')) exit(); /*a:5:{s:85:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/home/index.html";i:1536741709;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/meta.html";i:1536741707;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/header.html";i:1536741707;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/menu.html";i:1536742313;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/footer.html";i:1536741707;}*/ ?>
﻿<!--引入meta模板-->
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="/favicon.ico" >
<link rel="Shortcut Icon" href="/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5shiv.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>

<![endif]-->
<link rel="stylesheet" type="text/css" href="/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/static/h-ui.admin/css/style.css" />
<link rel="stylesheet" type="text/css" href="/static/style/css/tip.css" />
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->

<title>一棵松</title>
<meta name="keywords" content="一棵松">
<meta name="description" content="一棵松">
</head>
<body>

<!--引入header和menu模板-->
<header class="navbar-wrapper">
	<div class="navbar navbar-fixed-top">
		<div class="container-fluid cl"> <a class="logo navbar-logo f-l mr-10 hidden-xs" target="_blank" href="<?php echo url('index/index'); ?>">一棵松官网首页</a> <a class="logo navbar-logo-m f-l mr-10 visible-xs" target="_blank" href="<?php echo url('index/index'); ?>">一棵松官网首页</a> <span class="logo navbar-slogan f-l mr-10 hidden-xs">v1.0</span> <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>
			<!--<nav class="nav navbar-nav">
				<ul class="cl">
					<li class="dropDown dropDown_hover"><a href="javascript:;" class="dropDown_A"><i class="Hui-iconfont">&#xe600;</i> 新增 <i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="<?php echo url('admin/content/consult_edit','option=add'); ?>"><i class="Hui-iconfont">&#xe616;</i> 资讯</a></li>
							<li><a href="<?php echo url('admin/content/slide_edit','option=add'); ?>"><i class="Hui-iconfont">&#xe613;</i> 幻灯片</a></li>
							<li><a href="<?php echo url('admin/artwork/artwork_edit','option=add'); ?>"><i class="Hui-iconfont">&#xe620;</i> 艺术品</a></li>
						</ul>
					</li>
				</ul>
			</nav>-->
			<nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
				<ul class="cl">
					<li><?php echo \think\Session::get('admin.group_id'); ?></li>
					<li class="dropDown dropDown_hover"> <a href="#" class="dropDown_A"><?php echo \think\Session::get('admin.username'); ?><i class="Hui-iconfont">&#xe6d5;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<!--<li><a href="#">个人信息</a></li>-->
							<li><a href="<?php echo url('login/doLogout'); ?>">退出</a></li>
						</ul>
					</li>
					<!--<li id="Hui-msg"> <a href="#" title="消息"><span class="badge badge-danger">1</span><i class="Hui-iconfont" style="font-size:18px">&#xe68a;</i></a> </li>-->
					<li id="Hui-skin" class="dropDown right dropDown_hover"> <a href="javascript:;" class="dropDown_A" title="换肤"><i class="Hui-iconfont" style="font-size:18px">&#xe62a;</i></a>
						<ul class="dropDown-menu menu radius box-shadow">
							<li><a href="javascript:;" data-val="default" title="默认（黑色）">默认（黑色）</a></li>
							<li><a href="javascript:;" data-val="blue" title="蓝色">蓝色</a></li>
							<li><a href="javascript:;" data-val="green" title="绿色">绿色</a></li>
							<li><a href="javascript:;" data-val="red" title="红色">红色</a></li>
							<li><a href="javascript:;" data-val="yellow" title="黄色">黄色</a></li>
							<li><a href="javascript:;" data-val="orange" title="绿色">橙色</a></li>
						</ul>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header><aside class="Hui-aside">
	<input runat="server" id="divScrollValue" type="hidden" value="" />
	<div class="menu_dropdown bk_2">
		<?php switch($group_id): case "1": ?>
				<dl id="menu-system">
					<dt><i class="Hui-iconfont">&#xe62e;</i> 系统管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('system/parameter'); ?>" data-title="系统参数设置" href="javascript:void(0)">系统参数设置</a></li>
							<li><a data-href="<?php echo url('system/clear_cache'); ?>" data-title="清除缓存" href="javascript:void(0)">清除缓存</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-admin">
					<dt><i class="Hui-iconfont">&#xe62d;</i> 用户管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('member/member'); ?>" data-title="用户列表" href="javascript:void(0)">用户列表</a></li>
							<li><a data-href="<?php echo url('member/admin'); ?>" data-title="管理员列表" href="javascript:void(0)">管理员列表</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-picture">
					<dt><i class="Hui-iconfont">&#xe613;</i> 幻灯片管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('content/slide'); ?>" data-title="幻灯片管理" href="javascript:void(0)">幻灯片管理</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-news">
					<dt><i class="Hui-iconfont">&#xe637;</i> 服务管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('service/index'); ?>" data-title="服务管理" href="javascript:void(0)">服务管理</a></li>
							<li><a data-href="<?php echo url('service/csituation'); ?>" data-title="服务选项管理" href="javascript:void(0)">服务选项管理</a></li>
							<li><a data-href="<?php echo url('service/schedule'); ?>" data-title="服务进度管理" href="javascript:void(0)">服务进度管理</a></li>
							<li><a data-href="<?php echo url('service/integral'); ?>" data-title="服务积分管理" href="javascript:void(0)">服务积分管理</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-news">
					<dt><i class="Hui-iconfont">&#xe72d;</i> 特殊服务管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('specialservice/phone'); ?>" data-title="客服管理" href="javascript:void(0)">客服管理</a></li>
							<li><a data-href="<?php echo url('specialservice/filter'); ?>" data-title="分类筛选管理" href="javascript:void(0)">分类筛选管理</a></li>
							<li><a data-href="<?php echo url('specialservice/housecontent'); ?>" data-title="园区服务内容管理" href="javascript:void(0)">园区服务内容管理</a></li>
							<li><a data-href="<?php echo url('specialservice/companycontent'); ?>" data-title="资质转让内容管理" href="javascript:void(0)">资质转让内容管理</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-article">
					<dt><i class="Hui-iconfont">&#xe685;</i> 内容管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('content/demand'); ?>" data-title="需求管理" href="javascript:void(0)">需求管理</a></li>
							<li><a data-href="<?php echo url('content/coupon'); ?>" data-title="优惠券管理" href="javascript:void(0)">优惠券管理</a></li>
							<li><a data-href="<?php echo url('content/notice'); ?>" data-title="公告管理" href="javascript:void(0)">公告管理</a></li>
							<li><a data-href="<?php echo url('content/news'); ?>" data-title="资讯管理" href="javascript:void(0)">资讯管理</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-article">
					<dt><i class="Hui-iconfont">&#xe636;</i> 合同管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('pact/pact_option'); ?>" data-title="合同项管理" href="javascript:void(0)">合同项管理</a></li>
							<li><a data-href="<?php echo url('pact/pact_temp'); ?>" data-title="合同模板管理" href="javascript:void(0)">合同模板管理</a></li>
							<li><a data-href="<?php echo url('pact/pact'); ?>" data-title="合同管理" href="javascript:void(0)">合同管理</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-article">
					<dt><i class="Hui-iconfont">&#xe687;</i> 订单管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('order/index'); ?>" data-title="订单管理" href="javascript:void(0)">订单管理</a></li>
							<!--<li><a data-href="<?php echo url('order/fiscal'); ?>" data-title="财税管理" href="javascript:void(0)">财税管理</a></li>-->
							<li><a data-href="<?php echo url('order/integral_withdraw'); ?>" data-title="积分提现管理" href="javascript:void(0)">积分提现管理</a></li>
						</ul>
					</dd>
				</dl>

				<dl id="menu-admin">
					<dt><i class="Hui-iconfont">&#xe63c;</i> 权限管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('role/role'); ?>" data-title="角色管理" href="javascript:void(0)">角色管理</a></li>
							<!--<li><a data-href="<?php echo url('role/jurisdiction_classify'); ?>" data-title="权限分类管理" href="javascript:void(0)">权限分类管理</a></li>
							<li><a data-href="<?php echo url('role/jurisdiction'); ?>" data-title="权限管理" href="javascript:void(0)">权限管理</a></li>-->
						</ul>
					</dd>
				</dl>
			<?php break; case "客服": ?>
				<dl id="menu-article">
					<dt><i class="Hui-iconfont">&#xe616;</i> 内容管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('content/consult_classify'); ?>" data-title="栏目分类管理" href="javascript:void(0)">栏目分类管理</a></li>
							<li><a data-href="<?php echo url('content/consult'); ?>" data-title="栏目管理" href="javascript:void(0)">栏目管理</a></li>
							<li><a data-href="<?php echo url('content/notice'); ?>" data-title="公告管理" href="javascript:void(0)">公告管理</a></li>
							<li><a data-href="<?php echo url('content/cooperator'); ?>" data-title="合作单位管理" href="javascript:void(0)">合作单位管理</a></li>
							<li><a data-href="<?php echo url('content/center'); ?>" data-title="帮助中心管理" href="javascript:void(0)">帮助中心管理</a></li>
							<li class="tip_span"><a data-href="<?php echo url('content/leave_word'); ?>" data-title="留言管理" href="javascript:void(0)">留言管理</a></li>
						</ul>
					</dd>
				</dl>
				<dl id="menu-picture">
					<dt><i class="Hui-iconfont">&#xe61e;</i> 期权管理<i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i></dt>
					<dd>
						<ul>
							<li><a data-href="<?php echo url('stock/rate'); ?>" data-title="股票汇率" href="javascript:void(0)">股票汇率</a></li>
							<li><a data-href="<?php echo url('stock/stock_data'); ?>" data-title="股票数据" href="javascript:void(0)">股票数据</a></li>
							<li><a data-href="<?php echo url('stock/order'); ?>" data-title="期权持仓订单" href="javascript:void(0)">期权持仓订单</a></li>
							<li><a data-href="<?php echo url('stock/enquiry'); ?>" data-title="询价记录" href="javascript:void(0)">询价记录</a></li>
						</ul>
					</dd>
				</dl>


			<?php break; default: endswitch; ?>
	</div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>

<section class="Hui-article-box">
	<div id="Hui-tabNav" class="Hui-tabNav hidden-xs">
		<div class="Hui-tabNav-wp">
			<ul id="min_title_list" class="acrossTab cl">
				<li class="active"><span title="我的桌面" data-href="<?php echo url('home/welcome'); ?>">我的桌面</span><em></em></li>
			</ul>
		</div>
		<div class="Hui-tabNav-more btn-group"><a id="js-tabNav-prev" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d4;</i></a><a id="js-tabNav-next" class="btn radius btn-default size-S" href="javascript:;"><i class="Hui-iconfont">&#xe6d7;</i></a></div>
	</div>
	<div id="iframe_box" class="Hui-article">
		<div class="show_iframe">
			<div style="display:none" class="loading"></div>
			<iframe scrolling="yes" frameborder="0" src="<?php echo url('home/welcome'); ?>"></iframe>
		</div>
	</div>
</section>

<div class="contextMenu" id="Huiadminmenu">
	<ul>
		<li id="closethis">关闭当前 </li>
		<li id="closeall">关闭全部 </li>
	</ul>
</div>
<audio id="music" controls="controls" preload="auto" >
  <source src="/static/style/js/tixing.mp3" type="audio/mpeg" />
	您的浏览器不支持音乐播放。
</audio>

<!--引入footer模板-->
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/static/h-ui.admin/js/H-ui.admin.js"></script>
<script type="text/javascript">
$(function(){
	$('#enquiry_li').on('click',function() {
	    $('#enquiry_i').removeClass('tip');
	})
	$('#leave_word_li').on('click',function() {
	    $('#leave_word_i').removeClass('tip');
	})
	$('#register_li').on('click',function() {
	    $('#register_i').removeClass('tip');
	})
	$('#port_apply_li').on('click',function() {
	    $('#port_apply_i').removeClass('tip');
	})
	$('#port_renew_li').on('click',function() {
	    $('#port_renew_i').removeClass('tip');
	})
});
</script>

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript" src="/lib/jquery.contextmenu/jquery.contextmenu.r2.js"></script>
<script type="text/javascript">
$(function(){
	music();//1000为1秒钟
	function music() {
    	var timer=setInterval(function(){
    		$.ajax({
			type: 'POST',
			url: "<?php echo url('home/subscribe'); ?>",
			data:{},
			dataType: 'json',
			success: function(data){
				switch(data.msg) {
				case 1:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#enquiry_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的询价信息，请注意查看');
							break;
					}
				  break;
				case 2:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#register_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('新的经纪注册信息，请打开【用户管理】-【经纪注册列表】查看。');
							break;
					}
				  break;
				case 12:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#enquiry_i').addClass('tip');
					$('#register_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的询价和经纪人注册信息，请注意查看');
							break;
					}
				  break;
				case 3:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#leave_word_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的在线留言提交信息，请注意查看');
							break;
					}
				  break;
				case 13:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#enquiry_i').addClass('tip');
					$('#leave_word_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的询价和在线留言提交信息，请注意查看');
							break;
					}
				  break;
				case 23:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#leave_word_i').addClass('tip');
					$('#register_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的经纪人注册和在线留言提交信息，请注意查看');
							break;
					}
				  break;
				case 123:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#enquiry_i').addClass('tip');
					$('#leave_word_i').addClass('tip');
					$('#register_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的询价、经纪人注册和在线留言提交信息，请注意查看');
							break;
					}
				  break;
				default:
				 return false;
				}
			},
			error:function(data) {
				console.log(data);
			},
		});
    	},10000);
    }

    port();//1000为1秒钟
	function port() {
    	var timer=setInterval(function(){
    		$.ajax({
			type: 'POST',
			url: "<?php echo url('home/port_subscribe'); ?>",
			data:{},
			dataType: 'json',
			success: function(data){
				switch(data.msg) {
				case 1:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#port_apply_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的数据接口申请信息，请注意查看');
							break;
					}
				  break;
				case 2:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#port_renew_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有一个待处理【接口延期】任务请到接口管理中查看。');
							break;
					}
				  break;
				case 12:
				  	var myVideo=document.getElementById("music");
					myVideo.play();
					$('#port_apply_i').addClass('tip');
					$('#port_renew_i').addClass('tip');
					switch(data.remind_status) {
						case 1 :
							alert('您有新的数据接口申请和接口延期信息，请注意查看');
							break;
					}
				  break;
				
				default:
				 return false;
				}
			},
			error:function(data) {
				console.log(data);
			},
		});
    	},8000);
    }
});
</script> 

</body>
</html>