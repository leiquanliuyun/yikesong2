<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:82:"D:\wamp\www\yikesong\public/../application/useradmin\view\content\coupon_edit.html";i:1536804057;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
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
        <?php if($option == 'add'): ?>添加<?php else: ?>编辑<?php endif; ?>优惠券
    </title>
    <link href="/yikesong/public/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
    <div class="page-container">
        <form class="form form-horizontal" id="form-article-add" action="<?php echo url('content/coupon_edit'); ?>" method="post">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">所属服务：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box">
                    <select name="service_name" id="service_name" class="select">
                        <option value="">--请选择--</option>
                        <option value="公司注册">公司注册</option>
                        <option value="验资审计">验资审计</option>
                        <option value="政策规划">政策规划</option>
                        <option value="财税管理">财税管理</option>
                        <option value="APP制作">APP制作</option>
                        <option value="知识产权">知识产权</option>
                        <option value="园区服务">园区服务</option>
                        <option value="pos机">pos机</option>
                        <option value="资质转让">资质转让</option>
                    </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">优惠券标题：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['name'])?$list['name']: ''; ?>" placeholder="" id="" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">抵扣价格：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['price'])?$list['price']: ''; ?>" placeholder="" id="" name="price">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">满价可用：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['fullprice'])?$list['fullprice']: ''; ?>" placeholder="" id="" name="fullprice">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">使用期限：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" name="start_time" id="countTimestart" onfocus="selecttime(1)" value="<?php echo !empty($list['start_time'])?$list['start_time']: ''; ?>" size="17" class="input-text Wdate" style="width:150px;" readonly>
                    -
                    <input type="text" name="end_time" id="countTimeend" onfocus="selecttime(2)" value="<?php echo !empty($list['end_time'])?$list['end_time']: ''; ?>" size="17"  class="input-text Wdate" style="width:150px;" readonly>
                </div>
            </div>


            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">是否显示：</label>
                <div class="formControls col-xs-8 col-sm-9">
				<span class="select-box">
				<select name="status" class="select">
					<option value="1" <?php echo $list['status_text']=='发布'?'selected': ''; ?>>发布</option>
                    <option value="2" <?php echo $list['status_text']=='下架'?'selected' : ''; ?>>下架</option>
				</select>
				</span>
                </div>
            </div>

            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">是否指定：</label>
                <div class="formControls col-xs-8 col-sm-9 skin-minimal">
                    <div class="radio-box">
                        <input name="is_assign" value="1" type="radio" id="is_assign-1" <?php echo $list['is_assign']==1?'checked': ''; ?>>
                        <label for="is_assign-1">是</label>
                    </div>
                    <div class="radio-box">
                        <input type="radio" value="0" id="is_assign-2" name="is_assign" <?php echo $list['is_assign']==0?'checked': ''; ?>>
                        <label for="is_assign-2">否</label>
                    </div>
                </div>
            </div>
            <div class="row cl" id="coupon_num" <?php echo $list['is_assign']==0?"": "style='display: none;'"; ?>>
                <label class="form-label col-xs-4 col-sm-2">数量：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['num'])?$list['num']: ''; ?>" placeholder="" id="" name="num">
                </div>
            </div>
            <div class="row cl" id="assign_obj" <?php echo $list['is_assign']==1?"": "style='display: none;'"; ?> >
                <label class="form-label col-xs-4 col-sm-2">指定用户：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <div class="dowebok">
                        <select name="assign_obj[]" id="user_select" multiple="multiple">
                            <option value="0">--请选择用户--</option>
                            <?php if(empty($list['coupon_id'])): if(is_array($member_list) || $member_list instanceof \think\Collection || $member_list instanceof \think\Paginator): $i = 0; $__LIST__ = $member_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['nickname']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; else: if(is_array($member_list) || $member_list instanceof \think\Collection || $member_list instanceof \think\Paginator): $i = 0; $__LIST__ = $member_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                    <option value="<?php echo $vo['id']; ?>" {in_array($vo['id'],$list['assign_obj']) ? 'selected' : ''}><?php echo $vo['nickname']; ?></option>
                                <?php endforeach; endif; else: echo "" ;endif; endif; ?>
                        </select>
                    </div>
                </div>
            </div>
            <!--<div class="row cl" id="frequency" <?php echo $list['is_assign']==0?"": "style='display: none;'"; ?>>
                <label class="form-label col-xs-4 col-sm-2">可领取次数：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['frequency'])?$list['frequency']: ''; ?>" placeholder="0表示不限制" id="" name="frequency">
                </div>
            </div>-->
            <!--<div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">缩略图：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="hidden" name="picture" id="picture" value="<?php echo !empty($list['picture'])?$list['picture'] : ''; ?>">
                    <?php if(empty($list['picture'])): ?>
                        <img src="/yikesong/public/static/h-ui.admin/images/default-thumbnail.png" id="thumb-preview" width="200" style="cursor: hand"/>
                        <?php else: ?>
                        <img src="<?php echo $list['picture_show']; ?>" id="thumb-preview" width="200" style="cursor: hand"/>
                    <?php endif; ?>
                    <input type="button" class="btn btn-small" onclick="$('#thumb-preview').attr('src','/yikesong/public/static/h-ui.images/default-thumbnail.png');$('#picture').val('');return false;" value="取消图片">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"></label>
                <div class="formControls col-xs-8 col-sm-9">
                    <div class="uploader-thum-container">
                        <div id="fileList" class="uploader-list"></div>
                        <div id="filePicker">上传图片</div>
                    </div>
                </div>
            </div>-->

            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input name="" type="submit" class="btn btn-primary radius Hui-iconfont" value="&#xe632;保存">
                    <input name="" type="reset" class="btn btn-default radius size-S" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                </div>
            </div>
            <input type="hidden" name="option" value="<?php echo $option; ?>"/>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
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
<script type="text/javascript" src="/yikesong/public/lib/webuploader/0.1.5/webuploader.min.js"></script>
<script type="text/javascript" src="/yikesong/public/lib/My97DatePicker/4.8/WdatePicker.js"></script>
<script type="text/javascript">
    /*时间插件函数*/
    function selecttime(flag){
        if(flag==1){
            var endTime = $("#countTimeend").val();
            if(endTime != ""){
                WdatePicker({dateFmt:'yyyy-MM-dd HH',maxDate:endTime})}else{
                WdatePicker({dateFmt:'yyyy-MM-dd HH'})}
        }else{
            var startTime = $("#countTimestart").val();
            if(startTime != ""){
                WdatePicker({dateFmt:'yyyy-MM-dd HH',minDate:startTime})}else{
                WdatePicker({dateFmt:'yyyy-MM-dd HH'})}
        }
    }
    $(function(){
        var ser = "<?php echo $list['service_name']; ?>";
        $("#service_name option[value="+ser+"]").attr("selected",true);
    });
    //点击按钮显隐审核理由框
    $('#is_assign-1').on('click',function() {
        $('#assign_obj').css('display','');
        $('#coupon_num').css('display','none');
    })
    $('#is_assign-2').on('click',function() {
        $('#assign_obj').css('display','none');
        $('#coupon_num').css('display','');
    })
</script>




</body>
</html>