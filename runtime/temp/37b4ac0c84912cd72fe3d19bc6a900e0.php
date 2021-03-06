<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"D:\wamp\www\yikesong\public/../application/useradmin\view\order\change_price.html";i:1536804056;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
<!--引入meta模板-->
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link rel="Bookmark" href="/yikesong/public/favicon.ico" >
<link rel="Shortcut Icon" href="/yikesong/public/favicon.ico" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5shiv.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>

<![endif]-->
<link rel="stylesheet" type="text/css" href="/yikesong/public/static/h-ui/css/H-ui.min.css" />
<link rel="stylesheet" type="text/css" href="/yikesong/public/static/h-ui.admin/css/H-ui.admin.css" />
<link rel="stylesheet" type="text/css" href="/yikesong/public/lib/Hui-iconfont/1.0.8/iconfont.css" />
<link rel="stylesheet" type="text/css" href="/yikesong/public/static/h-ui.admin/skin/default/skin.css" id="skin" />
<link rel="stylesheet" type="text/css" href="/yikesong/public/static/h-ui.admin/css/style.css" />
<link rel="stylesheet" type="text/css" href="/yikesong/public/static/style/css/tip.css" />
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->

<!--模板继承 业务内容-->

    <title>
        修改订单价格
    </title>

    </head>
    <body>
    <div class="page-container">
        <form class="form form-horizontal" id="form-article-add" action="<?php echo url('order/change_price'); ?>" method="post">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">订单号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <?php echo $list['ordernum']; ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">用户名：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <?php echo $list['member']['realname']; ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">手机号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <?php echo $list['member']['mobile']; ?>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">价格：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['price'])?$list['price']: ''; ?>" placeholder="" id="" name="price">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">所属服务/底价：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <?php echo $list['service_name']; ?>/<?php echo $list['sprice']; ?>
                </div>
            </div>
            <?php if(($list['service_name']!=='资质转让')&& ($list['service_name']!=='园区服务')): ?>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">用户选择内容：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <?php echo $list['content']; ?>
                </div>
            </div>
            <?php endif; if($list['service_name']=='财税管理'): ?>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">收费方式：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <select name="mtc" id="sname">
                        <option value="">--请选择--</option>
                        <option value="1" <?php echo $list['mtc']==1?'selected': ''; ?>>季付</option>
                        <option value="2" <?php echo $list['mtc']==2?'selected': ''; ?>>半年付</option>
                        <option value="3" <?php echo $list['mtc']==3?'selected': ''; ?>>年付</option>
                    </select>
                </div>
            </div>
            <?php endif; ?>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input name="" type="submit" class="btn btn-primary radius Hui-iconfont" value="&#xe632;保存">
                    <input name="" type="reset" class="btn btn-default radius size-S" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                </div>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="hidden" name="uid" value="<?php echo $list['uid']; ?>"/>
            <?php echo token(); ?>
        </form>
    </div>


<!--引入footer模板-->
<script type="text/javascript" src="/yikesong/public/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/yikesong/public/lib/layer/2.4/layer.js"></script>
<script type="text/javascript" src="/yikesong/public/static/h-ui/js/H-ui.min.js"></script>
<script type="text/javascript" src="/yikesong/public/static/h-ui.admin/js/H-ui.admin.js"></script>
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

    <!--此页面业务相关的脚本-->
    <script type="text/javascript" src="/yikesong/public/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/yikesong/public/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/yikesong/public/lib/jquery.validation/1.14.0/messages_zh.js"></script>

    <script type="text/javascript">

        function article_save(){
            alert("刷新父级的时候会自动关闭弹层。")
            window.parent.location.reload();
        }

    </script>


</body>
</html>