<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:84:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/role/role.html";i:1536741711;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/base.html";i:1536741707;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/meta.html";i:1536741707;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/footer.html";i:1536741707;}*/ ?>
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

<title>角色管理</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 管理员管理 <span class="c-gray en">&gt;</span> 角色管理 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="page-container">
	<div class="cl pd-5 bg-1 bk-gray">
	 	<span class="l">
	 		<a class="btn btn-primary radius" href="<?php echo url('role/role_edit','option=add'); ?>"><i class="Hui-iconfont">&#xe600;</i> 添加角色</a> 
	 	</span> 
	 </div>
	<table class="table table-border table-bordered table-hover table-bg table-sort">
		<thead>
			<tr>
				<th scope="col" colspan="6">角色管理</th>
			</tr>
			<tr class="text-c">
				<th width="40">ID</th>
				<th width="100">角色名</th>
				<th width="200">描述</th>
				<th width="60">角色状态</th>
				<th width="70">操作</th>
			</tr>
		</thead>
		<tbody>
			<?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<tr class="text-c">
					<td><?php echo $vo['id']; ?></td>
					<td><?php echo $vo['name']; ?></td>
					<td><?php echo $vo['remark']; ?></td>
					<td class="td-status">
						<span class="label <?php echo $vo['status_text']=='正常'?'label-success' : 'label-defaunt'; ?> radius"><?php echo $vo['status_text']; ?></span>
					</td>
					<td class="f-14 td-manage">
						<?php if($vo['id'] == 1): if($vo['status_text'] == '正常'): ?>
							    <font color="#cccccc"><i class="Hui-iconfont" style="font-size:20px;">&#xe631;</i></font>|
							<?php else: ?>
								<font color="#cccccc"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e1;</i></font>|
							<?php endif; ?>
							<font color="#cccccc"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></font> |
							<font color="#cccccc"><i class="Hui-iconfont" style="font-size:20px;">&#xe61d;</i></font> |
							<font color="#cccccc"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e2;</i></font>
						<?php else: if($vo['status_text'] == '正常'): ?>
								<a style="text-decoration:none" onClick="admin_role_stop(this,'<?php echo $vo['id']; ?>',2)" href="javascript:;" title="禁用"><i class="Hui-iconfont" style="font-size:20px;">&#xe631;</i></a>
							<?php else: ?>
								<a style="text-decoration:none" onClick="admin_role_start(this,'<?php echo $vo['id']; ?>',1)" href="javascript:;" title="正常"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e1;</i></a>
							<?php endif; ?>
							<a title="编辑" href="<?php echo url('role/role_edit',['id'=>$vo['id'],'option'=>'update']); ?>" style="text-decoration:none"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>
							<a title="权限设置" href="<?php echo url('role/role_edit',['id'=>$vo['id'],'option'=>'update']); ?>" style="text-decoration:none"><i class="Hui-iconfont" style="font-size:20px;">&#xe61d;</i></a>
							<a style="text-decoration:none" class="ml-5" onClick="admin_role_del(this,'<?php echo $vo['id']; ?>')" href="javascript:;" title="删除"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e2;</i></a>
						<?php endif; ?>
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
<script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript">

$('.table-sort').dataTable({
	//"aaSorting": [[ 0, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  //{"orderable":true,"aTargets":[0,4]}// 制定列不参与排序
	]
});

/*管理员-角色-禁用*/
function admin_role_stop(obj,id,show){
	layer.confirm('确认要禁用吗？',function(index){
		$.ajax({
			type: 'POST',
			url: "<?php echo url('role/role_show'); ?>",
			data:{id:id,show:show},
			dataType: 'json',
			success: function(data){
				if(data.msg == 'success') {
					$(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="admin_role_start(this,'+id+',1)" href="javascript:;" title="正常"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e1;</i></a>');
					$(obj).parents("tr").find(".td-status").html('<span class="label label-defaunt radius">禁用</span>');
					$(obj).remove();
					layer.msg('已禁用!',{icon: 5,time:1000});
				} else {
					layer.msg('禁用失败!',{icon:1,time:1000});
				}
			},
			error:function(data) {
				console.log(data);
			},
		});
	});
}

/*管理员-角色-正常*/
function admin_role_start(obj,id,show){
	layer.confirm('确认要正常吗？',function(index){
		$.ajax({
			type: 'POST',
			url: "<?php echo url('role/role_show'); ?>",
			data:{id:id,show:show},
			dataType: 'json',
			success: function(data){
				if(data.msg == 'success') {
					$(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="admin_role_stop(this,'+id+',2)" href="javascript:;" title="禁用"><i class="Hui-iconfont" style="font-size:20px;">&#xe631;</i></a>');
						$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">正常</span>');
						$(obj).remove();
						layer.msg('已正常!',{icon: 6,time:1000});
				} else {
					layer.msg('修改失败!',{icon:1,time:1000});
				}
			},
			error:function(data) {
				console.log(data);
			},
		});
	});
}

/*管理员-角色-删除*/
/*幻灯片-删除*/
function admin_role_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		$.ajax({
			type: 'POST',
			url: "<?php echo url('role/role_delete'); ?>",
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
</script>


</body>
</html>