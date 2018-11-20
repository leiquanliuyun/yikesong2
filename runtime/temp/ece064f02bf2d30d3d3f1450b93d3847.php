<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:81:"D:\wamp\www\yikesong\public/../application/useradmin\view\service\csituation.html";i:1536804055;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
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

    <title>服务选项列表</title>
    <!-- 放大图片插件 -->
    <link href="/yikesong/public/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 服务选项管理 <span class="c-gray en">&gt;</span> 服务选项列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">

        <form class="form form-horizontal" action="<?php echo url('service/csituation'); ?>" method="post">
            <div class="cl pd-5 bg-1 bk-gray mt-20">
                <span class="l">
                    <a href="javascript:;" onclick="picture_batch_del()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                    <a class="btn btn-primary radius" href="<?php echo url('service/csituation_add','option=add'); ?>"><i class="Hui-iconfont">&#xe600;</i> 添加服务选项</a>
                </span>
            </div>
            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="20"><input name="" type="checkbox" value=""></th>
                        <th width="15">所属服务</th>
                        <th width="60">所属分类</th>
                        <th width="60">名称</th>
                        <th width="100">添加时间</th>
                        <th width="80">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr class="text-c">
                            <td><input name="check" type="checkbox" value="<?php echo $vo['cs_id']; ?>"></td>
                            <td><?php echo $vo['service_name']; ?></td>
                            <td><?php echo !empty($vo['pname'])?$vo['pname']:$vo['name']; ?></td>
                            <td><?php echo $vo['name']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td class="td-manage">
                                <!--<a style="text-decoration:none" class="ml-5" href="<?php echo url('service/csituation_edit',['id'=>$vo['cs_id'],'option'=>'update']); ?>" title="编辑"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>-->
                                <a style="text-decoration:none" class="ml-5" onClick="picture_del(this,'<?php echo $vo['cs_id']; ?>')" href="javascript:;" title="删除"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e2;</i></a>
                            </td>
                            <input type="hidden" name="id_array[]" value="<?php echo $vo['cs_id']; ?>"/>
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
    <script type="text/javascript" src="/yikesong/public/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/yikesong/public/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/yikesong/public/lib/laypage/1.2/laypage.js"></script>
    <script type="text/javascript" src="/yikesong/public/lib/lightbox2/lightbox-2.6.min.js"></script>

    <script type="text/javascript">



        $('.table-sort').dataTable({
            "aaSorting": [[ 1, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,2,3,5,6]}// 制定列不参与排序
            ]
        });

        /*服务选项-删除*/
        function picture_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('service/csituation_delete'); ?>",
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


        /*服务选项-批量删除*/
        function picture_batch_del(){
            layer.confirm('确认要批量删除吗？',function(index){
                var check_value =[];
                $('input[name="check"]:checked').each(function(){
                    //封装被选中的checkbox值
                    check_value.push($(this).val());
                })
                //console.log(check_value);
                if (check_value.length===0){
                    layer.msg('请先选择要删除的服务选项!',{icon:2,time:1000});
                }
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('service/csituation_batch_delete'); ?>",
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