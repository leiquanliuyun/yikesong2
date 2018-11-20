<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:76:"D:\wamp\www\yikesong\public/../application/useradmin\view\member\member.html";i:1541577795;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
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

    <title>所有用户列表</title>
    <!-- 放大图片插件 -->
    <link href="/yikesong/public/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css">
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户管理 <span class="c-gray en">&gt;</span> 所有用户列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <!--<div class="text-c">
            <form class="form form-horizontal" action="<?php echo url('user/all_user'); ?>" method="post">
                创建日期范围：<input type="text" name="start_time" id="countTimestart" onfocus="selecttime(1)" value="<?php echo !empty($start_time)?$start_time: ''; ?>" size="17" class="input-text Wdate" style="width:150px;" readonly>
                -
                <input type="text" name="end_time" id="countTimeend" onfocus="selecttime(2)" value="<?php echo !empty($end_time)?$end_time: ''; ?>" size="17"  class="input-text Wdate" style="width:150px;" readonly>
                <input type="text" class="input-text" style="width:250px" placeholder="输入账号查询" id="" value="<?php echo !empty($username)?$username: ''; ?>" name="username">
                <button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜用户</button>
            </form>
        </div>-->
        <div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="l">
			<a href="javascript:;" onclick="user_batch_del()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>

		</span>
        </div>
        <table class="table table-border table-bordered table-bg table-hover table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="15">所有用户列表</th>
            </tr>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="60">用户名</th>
                <th width="60">昵称</th>
                <th width="60">真实姓名</th>
                <th width="40">性别</th>
                <th width="60">手机号</th>
                <th width="100">身份证号</th>
                <th width="120">身份证照</th>
                <th width="40">可用积分</th>
                <th width="40">推荐数量</th>
                <th width="40">推荐查看</th>
                <th width="40">优惠券查看</th>
                <th width="100">添加时间</th>
                <th width="60">订单信息</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr class="text-c">
                    <td><input type="checkbox" value="<?php echo $vo['id']; ?>" name="check"></td>
                    <td><?php echo $vo['username']; ?></td>
                    <td><?php echo $vo['nickname']; ?></td>
                    <td><?php echo $vo['realname']; ?></td>
                    <td><?php echo $vo['sex']; ?></td>
                    <td><?php echo $vo['mobile']; ?></td>
                    <td><?php echo $vo['card_num']; ?></td>

                    <td>
                        <?php if(is_array($vo['id_photo_show']) || $vo['id_photo_show'] instanceof \think\Collection || $vo['id_photo_show'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['id_photo_show'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$img): $mod = ($i % 2 );++$i;?>
                            <div class="picbox"><a href="<?php echo $img; ?>" data-lightbox="gallery" data-title="<?php echo $vo['realname']; ?>" style="float:left">
                                <img width="50" class="picture-thumb" src="<?php echo $img; ?>" title="单击放大"></a></div>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </td>
                    <td><?php echo $vo['integral']; ?></td>
                    <td><?php echo $vo['recommend_count']; ?></td>
                    <td>
                        <a style="text-decoration:none" class="ml-5" href="<?php echo url('member/member_recommend',['id'=>$vo['id']]); ?>" title="推荐查看"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>
                    </td>
                    <td>
                        <a style="text-decoration:none" class="ml-5" href="<?php echo url('member/member_coupon',['id'=>$vo['id']]); ?>" title="优惠券查看"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>
                    </td>
                    <td><?php echo $vo['create_time']; ?></td>
                    <td class="td-status">
                        <a style="text-decoration:none" class="ml-5" href="<?php echo url('member/member_detail',['id'=>$vo['id']]); ?>" title="查看"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>
                    </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
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

        /*表格插件*/
        $('.table-sort').dataTable({
            "aaSorting": [[ 12, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,9]}// 不参与排序的列
            ]
        });

        /*用户-删除*/
        function user_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('member/member_delete'); ?>",
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
        /*用户-批量删除*/
        function user_batch_del(){
            layer.confirm('确认要批量删除吗？',function(index){
                var check_value =[];
                $('input[name="check"]:checked').each(function(){
                    //封装被选中的checkbox值
                    check_value.push($(this).val());
                })
                //console.log(check_value);
                if (check_value.length===0){
                    layer.msg('请先选择要删除的用户!',{icon:2,time:1000});
                }
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('member/member_batch_delete'); ?>",
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

        /*所有用户-停用*/
        function status_stop(obj,id,show){
            layer.confirm('确认要停用吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('user/status'); ?>",
                    data:{id:id,show:show},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="status_start(this,'+id+',1)" href="javascript:;" title="启用"><i class="Hui-iconfont" style="font-size:20px;">&#xe615;</i></a>');
                            $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">停用</span>');
                            $(obj).remove();
                            layer.msg('已停用!',{icon: 5,time:1000});
                        } else {
                            layer.msg('设为停用失败!',{icon:1,time:1000});
                        }
                    },
                    error:function(data) {
                        console.log(data);
                    },
                });
            });
        }

        /*所有用户-启用*/
        function status_start(obj,id,show){
            layer.confirm('确认要启用吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('user/status'); ?>",
                    data:{id:id,show:show},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="status_stop(this,'+id+',2)" href="javascript:;" title="停用"><i class="Hui-iconfont" style="font-size:20px;">&#xe631;</i></a>');
                            $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">启用</span>');
                            $(obj).remove();
                            layer.msg('已启用!',{icon: 6,time:1000});
                        } else {
                            layer.msg('启用失败!',{icon:1,time:1000});
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