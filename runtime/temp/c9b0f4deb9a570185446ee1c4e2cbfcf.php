<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:98:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/order/integral_withdraw.html";i:1536741710;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/base.html";i:1536741707;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/meta.html";i:1536741707;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/footer.html";i:1536741707;}*/ ?>
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



    <title>积分提现订单列表</title>
    <!-- 放大图片插件 -->
    <!--<link href="/lib/lightbox2/2.8.1/css/lightbox.css" rel="stylesheet" type="text/css">-->
    </head>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 积分提现管理
        <span class="c-gray en">&gt;</span> 积分提现订单列表 <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" >
            <i class="Hui-iconfont">&#xe68f;</i></a></nav>
    <div class="page-container">
        <div class="text-c">
            <form class="form form-horizontal" action="<?php echo url('order/integral_withdraw'); ?>" method="post">

                订单状态：
                <span class="select-box" style="width:30%">
                <select name="status" id="sta" class="select">
                    <option value="">--请选择--</option>
                    <option value="1">待处理</option>
                    <option value="2">已处理</option>
                    <option value="3">已退回</option>
                </select>
                </span>
                <button name="" id="" class="btn btn-success" type="submit"><i class="Hui-iconfont">&#xe665;</i> 搜索</button>
            </form>
        </div>

        <form class="form form-horizontal" action="" method="post">

            <div class="mt-20">
                <table class="table table-border table-bordered table-bg table-hover table-sort">
                    <thead>
                    <tr class="text-c">
                        <th width="100">提现金额</th>
                        <th width="100">支付宝</th>
                        <th width="100">支付宝绑定姓名</th>
                        <th width="100">联系方式</th>
                        <th width="100">订单状态</th>
                        <th width="100">申请时间</th>
                        <th width="180">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <tr class="text-c">
                            <td><?php echo $vo['money']; ?></td>
                            <td><?php echo $vo['account']; ?></td>
                            <td><?php echo $vo['realname']; ?></td>
                            <td><?php echo $vo['member']['mobile']; ?></td>
                            <td id="order_status"><?php echo $vo['status_text']; ?></td>
                            <td><?php echo $vo['create_time']; ?></td>
                            <td class="td-manage">
                                <?php if($vo['status'] == 1): ?>
                                    <a href="javascript:void(0)" onclick="confirmWith(<?php echo $vo['id']; ?>,this)" title="审核通过"><i class="Hui-iconfont" style="font-size:20px;">&#xe6a7;</i></a>
                                    <a href="javascript:void(0)" onclick="backWith(<?php echo $vo['id']; ?>,this)" title="审核退回"><i class="Hui-iconfont" style="font-size:20px;">&#xe6a6;</i></a>
                                    <?php else: ?>
                                    已处理
                                <?php endif; ?>
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

    <script type="text/javascript">
        $(function(){
            var status = "<?php echo $status; ?>";
            $("#sta option[value="+status+"]").attr("selected",true);
        });

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

        function confirmWith(id,th){
            if(confirm("确认通过？")){
                $.ajax({
                    type:"POST",
                    url:"<?php echo url('order/confirm_withdraw'); ?>",
                    data:{"id":id},
                    dataType:"JSON",
                    success:function(data){
                        switch(data.status){
                            case "success":
                                console.log($(th))
                                $(th).parents("tr").find('.order_status').text('已通过');
                                $(th).parents('.td-manage').html('已处理');
                                break;
                            default:
                                alert(data.msg);
                                break;
                        }
                    },
                    error:function(){
                        alert.msg("服务器错误，请重试");
                    }
                })
            }
        }
        function backWith(id,th){
            if(confirm("确认退回？")){
                $.ajax({
                    type:"POST",
                    url:"<?php echo url('order/back_withdraw'); ?>",
                    data:{"id":id},
                    dataType:"JSON",
                    success:function(data){
                        switch(data.status){
                            case "success":
                                alert('退回成功')
                                $(th).parents("tr").find('.order_status').text('已退回');
                                $(th).parent('.td-manage').html('已处理');
                                break;
                            default:
                                alert(data.msg);
                                break;
                        }
                    },
                    error:function(){
                        alert.msg("服务器错误，请重试");
                    }
                })
            }
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