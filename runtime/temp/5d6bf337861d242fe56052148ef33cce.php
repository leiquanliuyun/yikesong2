<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/order/fiscal.html";i:1536720771;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/base.html";i:1536720770;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/meta.html";i:1536720770;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/footer.html";i:1536720770;}*/ ?>
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



    <title>订单列表</title>
    <!-- 放大图片插件 -->
    <!--<link href="/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css">-->
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 财税管理
        <span class="c-gray en">&gt;</span> 财税订单列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
            <i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form class="form form-horizontal" action="<?php echo url('order/fiscal'); ?>" method="post">
                日期范围：<input type="text" name="start_time" id="countTimestart" onfocus="selecttime(1)" value="<?php echo !empty($start_time)?$start_time: ''; ?>" size="17" class="input-text Wdate" style="width:150px;" readonly>
                -
                <input type="text" name="end_time" id="countTimeend" onfocus="selecttime(2)" value="<?php echo !empty($end_time)?$end_time: ''; ?>" size="17"  class="input-text Wdate" style="width:150px;" readonly>
                <input type="text" name="liable" value="<?php echo !empty($liable)?$liable: ''; ?>" id="" placeholder="订单负责人" style="width:250px" class="input-text">

                订单状态：
                <select name="status" id="sta">
                    <option value="">--请选择--</option>
                    <option value="1">等待输入价格</option>
                    <option value="2">等待支付</option>
                    <option value="3">已支付,处理中</option>
                    <option value="4">已完成</option>
                    <option value="6">非正常</option>
                </select>
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
                        <th width="60">公司名称</th>
                        <th width="60">法人</th>
                        <th width="100">联系方式</th>
                        <th width="100">收费方式</th>
                        <th width="100">价格</th>
                        <th width="60">所属服务/底价</th>
                        <th width="100">用户选择内容</th>
                        <th width="100">订单状态</th>
                        <th width="100">合同管理</th>
                        <th width="100">添加时间</th>
                        <th width="100">备注</th>
                        <th width="80">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr class="text-c">
                            <td><input name="check" type="checkbox" value="<?php echo $vo['id']; ?>"></td>
                            <td><?php echo $vo['ordernum']; ?></td>
                            <td>
                                <?php if(empty($vo['liable']) || (($vo['liable'] instanceof \think\Collection || $vo['liable'] instanceof \think\Paginator ) && $vo['liable']->isEmpty())): ?>
                                    <input type="text" name="addl" id="addl" style="width:100px;text-align:center;"/>
                                    <a href="javascript:;" onClick="addLiable(<?php echo $vo['id']; ?>,this)" style="cursor:pointer;">添加</a>
                                    <?php else: ?>
                                    <input type="text" name="editl" id="editl" style="width:100px;text-align:center;" value="<?php echo $vo['liable']; ?>" />
                                    <a onClick="editLiable(<?php echo $vo['id']; ?>,this)" style="cursor:pointer;">修改</a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['company_name']; ?></td>
                            <td><?php echo $vo['corporation']; ?></td>
                            <td><?php echo $vo['member']['mobile']; ?></td>
                            <td><?php echo $vo['mtc_text']; ?></td>
                            <td><?php echo $vo['price']; ?></td>
                            <!--<td>
                                <?php if(empty($vo['price']) || (($vo['price'] instanceof \think\Collection || $vo['price'] instanceof \think\Paginator ) && $vo['price']->isEmpty())): ?>
                                    <input type="text" name="addl" id="addl" style="width:100px;text-align:center;"/>
                                    <a href="javascript:;" onClick="addPrice(<?php echo $vo['id']; ?>,this)" style="cursor:pointer;">添加</a>
                                    <?php else: ?>
                                    <?php echo $vo['price']; endif; ?>
                            </td>-->
                            <td><?php echo $vo['service_name']; ?>/<?php echo $vo['sprice']; ?></td>
                            <td><?php echo $vo['content']; ?></td>
                            <td><?php echo !empty($vo['audit'])?$vo['status_text']:'非正常'; ?></td>
                            <td>
                                <?php if($vo['pact'] == 1): ?>
                                    <a href="<?php echo url('pact/pact_add',['order_id'=>$vo['id'],'option'=>'add']); ?>" style="cursor:pointer;">添加</a>
                                    <?php else: ?>
                                    <a href="<?php echo url('pact/pact_edit',['order_id'=>$vo['id'],'option'=>'update']); ?>" style="cursor:pointer;color:red;">修改</a>
                                <?php endif; ?>
                            </td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td><?php echo $vo['remark']; ?></td>
                            <td class="td-manage">
                                <?php if($vo['febt'] == 1): ?><a href="javascript:debt(<?php echo $vo['id']; ?>,<?php echo $vo['price']; ?>)" title="催缴"><i class="Hui-iconfont" style="font-size:20px;">&#xe63c;</i></a><?php endif; if($vo['status'] == 1): ?><a style="text-decoration:none" class="ml-5" href="<?php echo url('order/change_price',['id'=>$vo['id']]); ?>" title="修改价格"><i class="Hui-iconfont" style="font-size:20px;">&#xe691;</i></a><?php endif; ?>
                                <a style="text-decoration:none" class="ml-5" href="<?php echo url('order/audit',['id'=>$vo['id'],'option'=>'fiscal']); ?>" title="审核"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e0;</i></a>
                                <a style="text-decoration:none" class="ml-5" onClick="picture_del(this,'<?php echo $vo['id']; ?>')" href="javascript:;" title="删除"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e2;</i></a>
                            </td>
                            <input type="hidden" name="id_array[]" value="<?php echo $vo['id']; ?>"/>
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
    <script type="text/javascript" src="/lib/My97DatePicker/4.8/WdatePicker.js"></script>
    <script type="text/javascript" src="/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="/lib/laypage/1.2/laypage.js"></script>
    <!--<script type="text/javascript" src="/lib/lightbox2/lightbox-2.6.min.js"></script>-->
    <script type="text/javascript">
        $(function(){
            var status = "<?php echo $status; ?>";
            $("#sta option[value="+status+"]").attr("selected",true);
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
            "aaSorting": [[ 11, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,2,3,5,6]}// 制定列不参与排序
            ]
        });
        function debt(id,price){
            if(confirm("确认进行催账吗？")){
                $.ajax({
                    type:"POST",
                    url:"<?php echo url('debt'); ?>",
                    data:{"id":id,"price":price},
                    dataType:"JSON",
                    success:function(data){
                        switch(data.status){
                            case "success":
                                layer.msg('催单成功!',{icon:1,time:800});
                                setTimeout('location.reload(true)',800);
                                break;
                            default:
                                alert(data.msg);
                                break;
                        }
                    },
                    error:function(){
                        alert("服务器错误,请重试");
                    }
                });
            }
        }
        /*添加订单负责人*/
        function addLiable(id,th){
            $_liable = $(th).parent().find("#addl").val();

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
                    switch(data.msg){
                        case "success":
                            alert('添加成功');
                            $_html += '<input type="text" name="editl" id="editl" value="'+$_liable+'" style="width:100px;text-align:center;"/><a href="javascript:;" onClick="editLiable('+id+',this)" style="width:100px;cursor:pointer;">修改</a>';
                            $(th).parent().html($_html);
                            break;
                        default:

                            alert(data.msg);

                            break;
                    }
                }
            })
        }
        /*function debt(id){
            if(confirm("确认进行催账吗？")){
                $.ajax({
                    type:"POST",
                    url:"<?php echo url('checkDebt'); ?>",
                    data:{"id":id},
                    dataType:"JSON",
                    success:function(data){
                        switch(data.status){
                            case "success":
                                window.location.href="/useradmin/order/debt/id/"+id;
                                break;
                            default:
                                alert(data.msg);
                                break;
                        }
                    },
                    error:function(){
                        alert("服务器错误,请重试");
                    }
                });
            }
        }*/
        function editLiable(id,th){
            $_liable = $(th).parent().find("#editl").val();
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
                    switch(data.msg){
                        case "success":
                            $(th).parent().find("#editl").val($_liable);
                            alert('修改成功');
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
                    switch(data.msg){
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