<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:79:"D:\wamp\www\yikesong\public/../application/useradmin\view\pact\pact_option.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
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

    <title>合同项列表</title>
    <!-- 放大图片插件 -->
    <!--<link href="/yikesong/public/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css">-->
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 合同项管理 <span class="c-gray en">&gt;</span> 合同项列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <!--<div class="text-c">
            <form class="form form-horizontal" action="<?php echo url('content/service'); ?>" method="post">
                日期范围：<input type="text" name="start_time" id="countTimestart" onfocus="selecttime(1)" value="<?php echo !empty($start_time)?$start_time: ''; ?>" size="17" class="input-text Wdate" style="width:150px;" readonly>
                -
                <input type="text" name="end_time" id="countTimeend" onfocus="selecttime(2)" value="<?php echo !empty($end_time)?$end_time: ''; ?>" size="17"  class="input-text Wdate" style="width:150px;" readonly>
                <input type="text" name="title" value="<?php echo !empty($title)?$title: ''; ?>" id="" placeholder=" 幻灯片标题" style="width:250px" class="input-text">
                <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜幻灯片</button>
            </form>
        </div>-->

        <form class="form form-horizontal" action="<?php echo url('pact/pactoption_sort'); ?>" method="post">

            <div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="l">
			<a href="javascript:;" onclick="picture_batch_del()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
			<a class="btn btn-primary radius" href="<?php echo url('pact/pactoption_edit','option=add'); ?>"><i class="Hui-iconfont">&#xe600;</i> 添加合同项</a>
            <!--<button type="submit" class="btn btn-success radius">排序</button>-->
		</span>
            </div>
            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="20"><input name="" type="checkbox" value=""></th>
                        <th width="60">合同项名称</th>
                        <th width="60">合同项字段名</th>
                        <th width="160">合同项说明</th>
                        <th width="50">添加时间</th>
                        <th width="80">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr class="text-c">
                            <td><input name="check" type="checkbox" value="<?php echo $vo['option_id']; ?>"></td>
                            <td><?php echo $vo['name']; ?></td>
                            <td><?php echo $vo['fieldname']; ?></td>
                            <td><?php echo $vo['description']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td class="td-manage">
                                <a style="text-decoration:none" class="ml-5" href="<?php echo url('pact/pactoption_edit',['id'=>$vo['option_id'],'option'=>'update']); ?>" title="编辑"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>
                                <a style="text-decoration:none" class="ml-5" onClick="picture_del(this,'<?php echo $vo['option_id']; ?>')" href="javascript:;" title="删除"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e2;</i></a>
                            </td>
                            <input type="hidden" name="id_array[]" value="<?php echo $vo['option_id']; ?>"/>
                        </tr>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                    </tbody>
                </table>
            </div>
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

    <!--请在下方写此页面业务相关的脚本-->
    <!--<script type="text/javascript" src="/yikesong/public/lib/My97DatePicker/4.8/WdatePicker.js"></script>-->
    <script type="text/javascript" src="/yikesong/public/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/yikesong/public/lib/laypage/1.2/laypage.js"></script>
    <!--<script type="text/javascript" src="/yikesong/public/lib/lightbox2/lightbox-2.6.min.js"></script>-->
    <script type="text/javascript">
        $('.table-sort').dataTable({
            "aaSorting": [[ 1, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,2,3,5,6]}// 制定列不参与排序
            ]
        });

        /*合同项-下架*/
        function picture_stop(obj,id,show){
            layer.confirm('确认要下架吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('pact/pactoption_show'); ?>",
                    data:{id:id,show:show},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="picture_start(this,'+id+',1)" href="javascript:;" title="发布"><i class="Hui-iconfont" style="font-size:20px;">&#xe603;</i></a>');
                            $(obj).parents("tr").find(".td-status").html('<span class="label label-defaunt radius">已下架</span>');
                            $(obj).remove();
                            layer.msg('已下架!',{icon: 5,time:1000});
                        } else {
                            layer.msg('下架失败!',{icon:1,time:1000});
                        }
                    },
                    error:function(data) {
                        console.log(data);
                    },
                });
            });
        }

        /*合同项-发布*/
        function picture_start(obj,id,show){
            layer.confirm('确认要发布吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('pact/pactoption_show'); ?>",
                    data:{id:id,show:show},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="picture_stop(this,'+id+',2)" href="javascript:;" title="下架"><i class="Hui-iconfont" style="font-size:20px;">&#xe6de;</i></a>');
                            $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
                            $(obj).remove();
                            layer.msg('已发布!',{icon: 6,time:1000});
                        } else {
                            layer.msg('发布失败!',{icon:1,time:1000});
                        }
                    },
                    error:function(data) {
                        console.log(data);
                    }
                });
            });
        }

        /*合同项-删除*/
        function picture_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('pact/pactoption_delete'); ?>",
                    data:{id:id},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            //$(obj).parents("tr").remove();
                            layer.msg('已删除!',{icon:1,time:800});
                            setTimeout('location.reload(true)',800);
                        } else {
                            layer.msg('删除失败!',{icon:1,time:1000});
                        }
                    },
                    error:function(data) {
                        console.log(data);
                    },
                });
            });
        }


        /*合同项-批量删除*/
        function picture_batch_del(){
            layer.confirm('确认要批量删除吗？',function(index){
                var check_value =[];
                $('input[name="check"]:checked').each(function(){
                    //封装被选中的checkbox值
                    check_value.push($(this).val());
                })
                //console.log(check_value);
                if (check_value.length===0){
                    layer.msg('请先选择要删除的合同项!',{icon:2,time:1000});
                }
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('pact/pactoption_batch_delete'); ?>",
                    data:{"id_array":check_value},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            //$(obj).parents("tr").remove();
                            layer.msg('已批量删除!',{icon:1,time:800});
                            setTimeout('location.reload(true)',800);
                        } else {
                            layer.msg('批量删除失败!',{icon:2,time:1000});
                        }
                    },
                    error:function(data) {
                        console.log(data);
                    },
                });

            });
        }
    </script>


</body>
</html>