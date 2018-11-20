<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/member/admin.html";i:1536741710;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/base.html";i:1536741707;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/meta.html";i:1536741707;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/footer.html";i:1536741707;}*/ ?>
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

    <title>管理员列表</title>
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 管理员列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <!--<form class="form form-horizontal" action="<?php echo url('user/admin'); ?>" method="post">
                创建日期范围：<input type="text" name="start_time" id="countTimestart" onfocus="selecttime(1)" value="<?php echo !empty($start_time)?$start_time: ''; ?>" size="17" class="input-text Wdate" style="width:150px;" readonly>
                -
                <input type="text" name="end_time" id="countTimeend" onfocus="selecttime(2)" value="<?php echo !empty($end_time)?$end_time: ''; ?>" size="17"  class="input-text Wdate" style="width:150px;" readonly>
                <input type="text" class="input-text" style="width:250px" placeholder="输入账号查询" id="" value="<?php echo !empty($username)?$username: ''; ?>" name="username">
                <button type="submit" class="btn btn-success" id="" name=""><i class="Hui-iconfont">&#xe665;</i> 搜管理员用户</button>
            </form>-->
        </div>
        <div class="cl pd-5 bg-1 bk-gray mt-20">
		<span class="l">
			<a href="javascript:;" onclick="admin_batch_del()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
			<a class="btn btn-primary radius" href="<?php echo url('member/admin_edit','option=add'); ?>"><i class="Hui-iconfont">&#xe600;</i> 添加管理员</a>
		</span>
        </div>
        <table class="table table-border table-bordered table-bg table-hover table-sort">
            <thead>
            <tr>
                <th scope="col" colspan="10">管理员列表</th>
            </tr>
            <tr class="text-c">
                <th width="25"><input type="checkbox" name="" value=""></th>
                <th width="80">ID</th>
                <th width="100">用户名</th>
                <th width="100">真实姓名</th>
                <th width="100">手机号</th>
                <th width="130">上次登录IP</th>
                <th width="130">上次登录时间</th>
                <th width="100">是否已启用</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <tr class="text-c">
                    <td><input type="checkbox" value="<?php echo $vo['id']; ?>" name="check"></td>
                    <td><?php echo $vo['id']; ?></td>
                    <td><?php echo $vo['user_login']; ?></td>
                    <td><?php echo $vo['realname']; ?></td>
                    <td><?php echo $vo['mobile']; ?></td>
                    <td><?php echo $vo['last_login_ip']; ?></td>
                    <td><?php echo $vo['last_login_time']; ?></td>
                    <td class="td-status">
                        <span class="label <?php echo $vo['status_text']=='启用'?'label-success' : 'label-defaunt'; ?> radius"><?php echo $vo['status_text']; ?></span>
                    </td>
                    <td class="td-manage">
                        <?php if($vo['status_text'] == '启用'): ?>
                            <a style="text-decoration:none" onClick="status_stop(this,'<?php echo $vo['id']; ?>',0)" href="javascript:;" title="禁用"><i class="Hui-iconfont" style="font-size:20px;">&#xe631;</i></a>
                            <?php else: ?>
                            <a style="text-decoration:none" onClick="status_start(this,'<?php echo $vo['id']; ?>',1)" href="javascript:;" title="启用"><i class="Hui-iconfont" style="font-size:20px;">&#xe615;</i></a>
                        <?php endif; ?>
                        <a title="编辑" href="<?php echo url('member/admin_edit',['id'=>$vo['id'],'option'=>'update']); ?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>
                        <a title="删除" href="javascript:;" onclick="admin_del(this,'<?php echo $vo['id']; ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e2;</i></a>
                    </td>
                </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
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
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/laypage/1.2/laypage.js"></script>
    <script type="text/javascript">
        /*时间插件函数*/
        function selecttime(flag){
            if(flag==1){
                var endTime = $("#countTimeend").val();
                if(endTime != ""){
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',maxDate:endTime})}else{
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
            }else{
                var startTime = $("#countTimestart").val();
                if(startTime != ""){
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm',minDate:startTime})}else{
                    WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})}
            }
        }

        /*表格插件*/
        $('.table-sort').dataTable({
            //"aaSorting": [[ 1, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,9]}// 不参与排序的列
            ]
        });

        /*管理员-删除*/
        function admin_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('member/admin_delete'); ?>",
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

        /*管理员-批量删除*/
        function admin_batch_del(){
            layer.confirm('确认要批量删除吗？',function(index){
                var check_value =[];
                $('input[name="check"]:checked').each(function(){
                    //封装被选中的checkbox值
                    check_value.push($(this).val());
                })

                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('member/admin_batch_delete'); ?>",
                    data:{"id_array":check_value},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            //$(obj).parents("tr").remove();
                            layer.msg('已批量删除!',{icon:1,time:800});
                            setTimeout('location.reload(true)',800);
                        } else {
                            layer.msg('批量删除失败!',{icon:1,time:1000});
                        }
                    },
                    error:function(data) {
                        console.log(data);
                    },
                });

            });
        }

        /*管理员-禁用*/
        function status_stop(obj,id,show){
            layer.confirm('确认要禁用吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('member/status'); ?>",
                    data:{id:id,show:show},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="status_start(this,'+id+',1)" href="javascript:;" title="启用"><i class="Hui-iconfont" style="font-size:20px;">&#xe615;</i></a>');
                            $(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">禁用</span>');
                            $(obj).remove();
                            layer.msg('已禁用!',{icon: 5,time:1000});
                        } else {
                            layer.msg('设为禁用失败!',{icon:1,time:1000});
                        }
                    },
                    error:function(data) {
                        console.log(data);
                    },
                });
            });
        }

        /*管理员-启用*/
        function status_start(obj,id,show){
            layer.confirm('确认要启用吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('member/status'); ?>",
                    data:{id:id,show:show},
                    dataType: 'json',
                    success: function(data){
                        if(data.msg == 'success') {
                            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="status_stop(this,'+id+',2)" href="javascript:;" title="禁用"><i class="Hui-iconfont" style="font-size:20px;">&#xe631;</i></a>');
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