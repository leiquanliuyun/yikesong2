<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:74:"D:\wamp\www\yikesong\public/../application/useradmin\view\order\index.html";i:1541580581;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
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


<title>订单列表</title>
    <!-- 放大图片插件 -->
    <!--<link href="/yikesong/public/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css">-->
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 订单管理
        <span class="c-gray en">&gt;</span> 订单列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
            <i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form class="form form-horizontal" action="<?php echo url('order/index'); ?>" method="post">
                日期范围：<input type="text" name="start_time" id="countTimestart" onfocus="selecttime(1)" value="<?php echo !empty($start_time)?$start_time: ''; ?>" size="17" class="input-text Wdate" style="width:150px;" readonly>
                -
                <input type="text" name="end_time" id="countTimeend" onfocus="selecttime(2)" value="<?php echo !empty($end_time)?$end_time: ''; ?>" size="17"  class="input-text Wdate" style="width:150px;" readonly>

                订单负责人
                <span class="select-box" style="width:15%">
                <select name="liable" id="liable" class="select">
                    <option value="">--请选择--</option>
                    <?php if(is_array($user_info) || $user_info instanceof \think\Collection || $user_info instanceof \think\Paginator): $i = 0; $__LIST__ = $user_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                        <option value="<?php echo $v['id']; ?>" <?php echo $liable==$v['realname']?'selected' : ''; ?>><?php echo $v['realname']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
                </select>
                </span>
                服务:
                <span class="select-box" style="width:15%">
                <select name="service_name" id="service_name" class="select">
                    <option value="">--请选择所属服务--</option>
                    <option value="资质转让">资质转让</option>
                    <option value="公司注册">公司注册</option>
                    <option value="工商变更">工商变更</option>
                    <option value="验资审计">验资审计</option>
                    <option value="政策规划">政策规划</option>
                    <option value="财税管理">财税管理</option>
                    <option value="APP制作">APP制作</option>
                    <option value="知识产权">知识产权</option>
                    <option value="园区服务">园区服务</option>
                    <option value="pos机">pos机</option>
                </select>
                </span>
                订单状态：
                <span class="select-box" style="width:15%">
                <select name="assign" id="sta" class="select">
                    <option value="">--请选择--</option>
                    <option value="1">待分配</option>
                    <option value="2">待接单</option>
                </select>
                </span>
                <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
            </form>
        </div>

        <form class="form form-horizontal" action="" method="post">

            <div class="cl pd-5 bg-1 bk-gray mt-20">
                <span class="l">
                    <a href="javascript:;" onclick="picture_batch_del()" class="btn btn-danger radius"><i class="Hui-iconfont">&#xe6e2;</i> 批量删除</a>
                    <!--<a class="btn btn-primary radius" href="<?php echo url('pact/pact_add',['order_id'=>81,'option'=>'add']); ?>"><i class="Hui-iconfont">&#xe600;</i> 添加订单</a>-->
                    <!--<button type="submit" class="btn btn-success radius">排序</button>-->
                </span>
            </div>
            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="20"><input name="" type="checkbox" value=""></th>
                        <th width="60">订单号</th>
                        <th width="100">订单负责人</th>
                        <th width="60">用户姓名</th>
                        <th width="60">手机号</th>
                        <!--<th width="100">价格</th>-->
                        <th width="60">所属服务/底价</th>
                        <th width="100">用户选择内容</th>
                        <th width="100">订单状态</th>
                        <!--<th width="100">合同管理</th>
                        <th width="100">订单进度管理</th>-->
                        <th width="100">添加时间</th>
                        <!--<th width="100">备注</th>
                        <th width="80">操作</th>-->
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr class="text-c">
                            <td><input name="check" type="checkbox" value="<?php echo $vo['id']; ?>"></td>
                            <td><?php echo $vo['ordernum']; ?></td>
                            <td>
                                <?php if(empty($vo['liable']) || (($vo['liable'] instanceof \think\Collection || $vo['liable'] instanceof \think\Paginator ) && $vo['liable']->isEmpty())): ?>
                                    <select name="add1" id="" class="select">
                                        <option value="">--请选择--</option>
                                        <?php if(is_array($user_info) || $user_info instanceof \think\Collection || $user_info instanceof \think\Paginator): $i = 0; $__LIST__ = $user_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $v['id']; ?>" <?php echo $vo['liable']==$v['realname']?'selected' : ''; ?>><?php echo $v['realname']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <a href="javascript:void(0);" onClick="addLiable(<?php echo $vo['id']; ?>,this)" style="cursor:pointer;">派单</a>
                                <?php else: ?>
                                    <select name="edit1" id="" class="select">
                                        <option value="">--请选择--</option>
                                        <?php if(is_array($user_info) || $user_info instanceof \think\Collection || $user_info instanceof \think\Paginator): $i = 0; $__LIST__ = $user_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                            <option value="<?php echo $v['id']; ?>" <?php echo $vo['liable']==$v['realname']?'selected' : ''; ?>><?php echo $v['realname']; ?></option>
                                        <?php endforeach; endif; else: echo "" ;endif; ?>
                                    </select>
                                    <a onClick="addLiable(<?php echo $vo['id']; ?>,this)" style="cursor:pointer;">派单</a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['member']['realname']; ?></td>
                            <td><?php echo $vo['member']['mobile']; ?></td>
                            <td><?php echo $vo['service_name']; ?>/<?php echo $vo['sprice']; ?></td>
                            <td><?php echo $vo['content']; ?></td>
                            <td class="assign_text"><?php echo $vo['assign_text']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <input type="hidden" name="id_array[]" value="<?php echo $vo['id']; ?>"/>
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
    <!--<script type="text/javascript" src="/yikesong/public/lib/lightbox2/lightbox-2.6.min.js"></script>-->
    <script type="text/javascript">
        $(function(){
            var assign = "<?php echo $assign; ?>";
            $("#sta option[value="+assign+"]").attr("selected",true);
            var ser = "<?php echo $service_name; ?>";
            $("#service_name option[value="+ser+"]").attr("selected",true);
            var liable = "<?php echo $liable; ?>";
            $("#liable option[value="+liable+"]").attr("selected",true);
        });
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
        $('.table-sort').dataTable({
            "aaSorting": [[ 8, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,2,3,5,6]}// 制定列不参与排序
            ]
        });
        /*添加订单负责人*/
        function addLiable(id,th){
            $_liable = $(th).parent().find(".select").find("option:selected").text();
            if($_liable==""){
                alert('请填写订单所属负责人');
                return false;
            }
            $_html='';
            $.ajax({
                type:"POST",
                url:"<?php echo url('changeLiable'); ?>",
                data:{"id":id,"liable":$_liable},
                dataType:"JSON",
                success:function(data){
                    console.log(data);
                    switch(data.status){
                        case "success":
                            //alert('添加成功');
                            liable=$(th).parents("tr").find(".assign_text").html('待接单')//
                            console.log(liable);
                            layer.msg('派单成功!',{icon: 5,time:1000});
                            break;
                        default:
                            alert(data.msg);
                            break;
                    }
                }
            })
        }
        function editLiable(id,th){
            $_liable = $(th).parent().find(".select").find("option:selected").text();

            if($_liable==""){
                alert('请填写订单所属负责人');
                return false;
            }
            $.ajax({
                type:"POST",
                url:"<?php echo url('changeLiable'); ?>",
                data:{"id":id,"liable":$_liable},
                dataType:"JSON",
                success:function(data){
                    console.log(data);
                    switch(data.status){
                        case "success":
                            layer.msg('派单成功!',{icon: 5,time:1000});
                            break;
                        default:
                            alert(data.msg);
                            break;
                    }
                }
            })
        }
        /*添加订单价格*/
        function addPrice(id,th){
            $_price = $(th).parent().find("#addl").val();

            if($_price==""){
                alert('请填写订单价格');
                return false;
            }
            $_html='';
            $.ajax({
                type:"POST",
                url:"<?php echo url('changePrice'); ?>",
                data:{"id":id,"price":$_price},
                dataType:"JSON",
                success:function(data){
                    switch(data.status){
                        case "success":
                            alert('添加成功');
                            $(th).parent().html($_price);
                            break;
                        default:
                            alert(data.msg);
                            break;
                    }
                }
            })
        }
        /*订单-删除*/
        function picture_del(obj,id){
            layer.confirm('确认要删除吗？',function(index){
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('order/order_delete'); ?>",
                    data:{id:id},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 'success') {
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


        /*订单-批量删除*/
        function picture_batch_del(){
            layer.confirm('确认要批量删除吗？',function(index){
                var check_value =[];
                $('input[name="check"]:checked').each(function(){
                    //封装被选中的checkbox值
                    check_value.push($(this).val());
                })
                //console.log(check_value);
                if (check_value.length===0){
                    layer.msg('请先选择要删除的订单!',{icon:2,time:1000});
                }
                $.ajax({
                    type: 'POST',
                    url: "<?php echo url('order/order_batch_delete'); ?>",
                    data:{"id_array":check_value},
                    dataType: 'json',
                    success: function(data){
                        if(data.status == 'success') {
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