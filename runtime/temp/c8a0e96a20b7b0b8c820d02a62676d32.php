<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:98:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/member/member_recommend.html";i:1536647332;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/base.html";i:1536647331;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/meta.html";i:1536647331;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/footer.html";i:1536647331;}*/ ?>
<!--引入meta模板-->
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

<!--模板继承 业务内容-->

    <title>用户推荐列表</title>
    <!-- 放大图片插件 -->
    <link href="/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户管理 <span class="c-gray en">&gt;</span> 用户推荐列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <!--<div class="text-c">
            <form class="form form-horizontal" action="<?php echo url('content/content'); ?>" method="post">
                日期范围：<input type="text" name="start_time" id="countTimestart" onfocus="selecttime(1)" value="<?php echo !empty($start_time)?$start_time: ''; ?>" size="17" class="input-text Wdate" style="width:150px;" readonly>
                -
                <input type="text" name="end_time" id="countTimeend" onfocus="selecttime(2)" value="<?php echo !empty($end_time)?$end_time: ''; ?>" size="17"  class="input-text Wdate" style="width:150px;" readonly>
                <input type="text" name="title" value="<?php echo !empty($title)?$title: ''; ?>" id="" placeholder=" 幻灯片标题" style="width:250px" class="input-text">
                <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜幻灯片</button>
            </form>
        </div>-->

        <form class="form form-horizontal" action="" method="post">
            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="20">序号</th>
                        <th width="60">昵称</th>
                        <th width="100">真实姓名</th>
                        <th width="50">性别</th>
                        <th width="100">手机号</th>
                        <th width="100">订单数量</th>
                        <th width="100">订单总金额</th>
                        <th width="100">添加时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                        <tr class="text-c">
                            <td><?php echo $k; ?></td>
                            <td><?php echo $vo['nickname']; ?></td>
                            <td><?php echo $vo['realname']; ?></td>
                            <td><?php echo $vo['sex']; ?></td>
                            <td><?php echo $vo['mobile']; ?></td>
                            <td><?php echo $vo['order_count']; ?></td>
                            <td><?php echo $vo['total_money']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
    </div>


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

<!--模板继承 业务相关的脚本-->

    <!--请在下方写此页面业务相关的脚本-->
    <!-- <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>-->
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/laypage/1.2/laypage.js"></script>
    <script type="text/javascript" src="/lib/lightbox2/lightbox-2.6.min.js"></script>

    <script type="text/javascript">
        $('.table-sort').dataTable({
            "aaSorting": [[ 7, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,2,3,5,6]}// 制定列不参与排序
            ]
        });
    </script>


</body>
</html>