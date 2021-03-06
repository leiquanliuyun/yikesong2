<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:77:"D:\wamp\www\yikesong\public/../application/useradmin\view\pact\pact_edit.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
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
        <?php if($option == 'add'): ?>添加<?php else: ?>编辑<?php endif; ?>合同
    </title>
    <!-- <link href="/yikesong/public/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />-->
    <script type="text/javascript" src="/yikesong/public/lib/ueditor/1.4.3/ueditor.config.js"></script>
    <script type="text/javascript" src="/yikesong/public/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
    <script type="text/javascript" src="/yikesong/public/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
    </head>
    <body>
    <div class="page-container">
        <form class="form form-horizontal" id="form-article-add" action="<?php echo url('pact/pact_edit'); ?>" method="post">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">订单号：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo $list['order']['ordernum']; ?>" placeholder="" id="" name="ordernum">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">合同名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['name'])?$list['name']: ''; ?>" placeholder="" id="" name="name">
                </div>
            </div>
            <?php if(is_array($alloptions) || $alloptions instanceof \think\Collection || $alloptions instanceof \think\Paginator): $i = 0; $__LIST__ = $alloptions;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$o_info): $mod = ($i % 2 );++$i;?>

                <div class="row cl pact_option <?php echo $o_info['option_id']; ?>" id="pact_option" name="pact_option" data_id="<?php echo $o_info['option_id']; ?>" style="display:none;">
                    <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span><?php echo $o_info['name']; ?>：</label>
                    <div class="formControls col-xs-8 col-sm-9">
                        <script id="<?php echo $o_info['name']; ?>" name="<?php echo $o_info['name']; ?>" type="text/plain" style="width:99.6%; height:300px"><?php echo !empty($pact[$o_info['name']])?$pact[$o_info['name']]:''; ?></script>
                        <script type="text/javascript">
                        var ue = UE.getEditor('<?php echo $o_info['name']; ?>');
                        </script>
                    </div>
                </div>

            <?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">雇主名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['master'])?$list['master']: ''; ?>" placeholder="" id="" name="master">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">合同负责人：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['liable'])?$list['liable']: ''; ?>" placeholder="" id="" name="liable">
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input name="" type="submit" class="btn btn-primary radius Hui-iconfont" value="&#xe632;保存">
                    <input name="" type="reset" class="btn btn-default radius size-S" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                </div>
            </div>
            <input type="hidden" name="option" value="<?php echo $option; ?>"/>
            <input type="hidden" name="order_id" value="<?php echo $id; ?>"/>
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
   <!-- <script type="text/javascript" src="/yikesong/public/lib/webuploader/0.1.5/webuploader.min.js"></script>-->

    <script type="text/javascript">
        function article_save(){
            alert("刷新父级的时候会自动关闭弹层。")
            window.parent.location.reload();
        }

        $(function(){
            //先隐藏所有合同项，如果某合同项在选中的模板里，将其显示
            var options='<?php echo $options; ?>';
            var stringoptions='<?php echo $stringoptions; ?>';
            console.log(options);
            stringoptions = JSON.parse(stringoptions)
            console.log(stringoptions);
            $.each(stringoptions,function(key,val){
                for(var i=0; i<options.length;i++){
                    if (val.option_id==options[i]){
                        $('[data_id='+val.option_id+']').show();
                    }
                }
            })

            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
        });

    </script>


</body>
</html>