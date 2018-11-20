<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:83:"D:\wamp\www\yikesong\public/../application/useradmin\view\member\member_detail.html";i:1541729751;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\base.html";i:1536804058;s:64:"D:\wamp\www\yikesong\application\useradmin\view\common\meta.html";i:1536804058;s:66:"D:\wamp\www\yikesong\application\useradmin\view\common\footer.html";i:1536804057;}*/ ?>
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

    <title>基本设置</title>
    </head>
    <style>
        .user-info-cont{
            display: flex;
            padding: 20px;
        }
        .user-info-lt{
            flex-shrink: 0;
            width: 150px;
            margin-right: 20px;
        }
        .user-pic img{
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .user-info-rt{
            flex: 1;
        }
        .user-info-call{
            display: flex;
            flex-wrap: wrap;
        }
        .user-info-call p{
            width: 40%;
            margin-bottom: 25px;
        }
    </style>
    <body>
    <nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页
        <span class="c-gray en">&gt;</span>
        用户管理
        <span class="c-gray en">&gt;</span>
        用户详情
        <a class="btn btn-success radius r" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
    </nav>
    <div class="page-container">
            <div class="user-info-cont">
                <div class="user-info-lt">
                    <div class="user-pic">
                        <img src="<?php echo $member_info['picture']; ?>">
                    </div>
                </div>
                <div class="user-info-rt">
                    <div class="user-info-call">
                        <p>用户名：<?php echo $member_info['nickname']; ?></p>
                        <p>姓名：<?php echo $member_info['realname']; ?></p>
                        <p>地址：</p>
                        <p>联系方式：<?php echo $member_info['mobile']; ?></p>
                        <p>公司名称：</p>
                        <p>记账：</p>
                    </div>
                </div>
            </div>
            <div id="tab-system" class="HuiTab">
                <div class="tabBar cl">
                    <span>积分提现</span>
                    <span>办理业务</span>
                    <span>知识产权查询</span>
                    <span>合同</span>
                    <span>记账情况</span>
                    <span>财务相关</span>
                </div>
                <div class="tabCon">
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
                            <?php if(is_array($withdraw_list) || $withdraw_list instanceof \think\Collection || $withdraw_list instanceof \think\Paginator): $i = 0; $__LIST__ = $withdraw_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <tr class="text-c">
                                    <td><?php echo $vo['money']; ?></td>
                                    <td><?php echo $vo['account']; ?></td>
                                    <td><?php echo $vo['realname']; ?></td>
                                    <td><?php echo $vo['member']['mobile']; ?></td>
                                    <td class="order_status"><?php echo $vo['status_text']; ?></td>
                                    <td><?php echo $vo['create_time']; ?></td>
                                    <td class="td-manage">
                                        <?php if($vo['status'] == 1): ?>
                                            <a href="javascript:void(0)" onclick="confirmWith(<?php echo $vo['id']; ?>,this)" title="审核通过"><i class="Hui-iconfont" style="font-size:20px;">&#xe6a7;</i></a>
                                            <a href="javascript:void(0)" onclick="backWith(<?php echo $vo['id']; ?>,this)" title="审核退回"><i class="Hui-iconfont" style="font-size:20px;">&#xe6a6;</i></a>
                                            <?php else: ?>
                                            已处理
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tabCon">
                    <div class="mt-20">
                        <table class="table table-border table-bordered table-bg table-hover table-sort">
                            <thead>
                            <tr class="text-c">
                                <th width="20">序号</th>
                                <th width="20">订单号</th>
                                <th width="60">所属服务</th>
                                <th width="60">下单时间</th>
                                <th width="60">订单状态</th>
                                <th width="60">费用</th>
                                <th width="60">订单进度</th>
                                <th width="60">合同管理</th>
                                <th width="60">合同状态</th>
                                <th width="60">是否正常</th>
                                <th width="100">负责人</th>
                                <th width="50">备注</th>
                                <th width="50">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($order_list) || $order_list instanceof \think\Collection || $order_list instanceof \think\Paginator): $k = 0; $__LIST__ = $order_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <tr class="text-c">
                                    <td><?php echo $k; ?></td>
                                    <td><?php echo $vo['ordernum']; ?></td>
                                    <td><?php echo $vo['service_name']; ?></td>
                                    <td><?php echo $vo['create_time']; ?></td>
                                    <td><?php echo $vo['order_status_text']; ?></td>
                                    <td>
                                        <?php if($vo['order_status'] == 1): ?>
                                            <a style="text-decoration:none" class="ml-5" href="<?php echo url('order/change_price',['id'=>$vo['id']]); ?>" title="修改价格">修改价格</a>
                                            <?php else: ?>
                                            <?php echo $vo['price']; endif; ?>
                                    </td>
                                    <td>
                                        <?php if($vo['order_status'] == 3 && $vo['service_name']!=='财税管理'): ?>
                                            <a href="<?php echo url('order/schedule',array('id'=>$vo['id'],'status'=>$vo['order_status'])); ?>" style="cursor:pointer;">管理</a>
                                            <?php elseif($vo['order_status'] == 4 && $vo['service_name']!=='财税管理'): ?>
                                            <a href="<?php echo url('order/schedule',array('id'=>$vo['id'],'status'=>$vo['order_status'])); ?>" style="cursor:pointer;color:red;">查看</a>
                                            <?php else: ?>
                                            暂无进度
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($vo['pact'] == 1): ?>
                                            <a href="<?php echo url('pact/pact_add',['order_id'=>$vo['id'],'option'=>'add']); ?>" style="cursor:pointer;">添加</a>
                                            <?php else: ?>
                                            <a href="<?php echo url('pact/pact_edit',['order_id'=>$vo['id'],'option'=>'update']); ?>" style="cursor:pointer;color:red;">修改</a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $vo['pact_text']; ?></td>
                                    <td><?php echo $vo['audit_text']; ?></td>
                                    <td>
                                        <select name="edit1" id="" class="select">
                                            <option value="">--请选择--</option>
                                            <?php if(is_array($user_info) || $user_info instanceof \think\Collection || $user_info instanceof \think\Paginator): $i = 0; $__LIST__ = $user_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>
                                                <option value="<?php echo $v['id']; ?>" <?php echo $vo['liable']==$v['realname']?'selected' : ''; ?>><?php echo $v['realname']; ?></option>
                                            <?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <a onClick="editLiable(<?php echo $vo['id']; ?>,this)" style="cursor:pointer;">派单</a>
                                    </td>
                                    <td><?php echo $vo['remark']; ?></td>
                                    <td class="td-manage">
                                        <a style="text-decoration:none" class="ml-5" href="<?php echo url('member/order_audit',['id'=>$vo['id'],'uid'=>$id]); ?>" title="审核"><i class="Hui-iconfont" style="font-size:20px;">&#xe6e0;</i></a>
                                        <?php if($vo['febt'] == 1): ?><a href="javascript:debt(<?php echo $vo['id']; ?>,<?php echo $vo['price']; ?>)" title="催缴"><i class="Hui-iconfont" style="font-size:20px;">&#xe63c;</i></a><?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tabCon">
                    <div class="mt-20">
                        <table class="table table-border table-bordered table-bg table-hover table-sort">
                            <thead>
                            <tr class="text-c">
                                <th width="100">查询品牌</th>
                                <th width="100">用户姓名</th>
                                <th width="100">手机号</th>
                                <th width="100">订单状态</th>
                                <th width="100">申请时间</th>
                                <th width="180">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($intell_list) || $intell_list instanceof \think\Collection || $intell_list instanceof \think\Paginator): $i = 0; $__LIST__ = $intell_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                                <tr class="text-c">
                                    <td><?php echo $vo['brand']; ?></td>
                                    <td><?php echo $vo['member']['realname']; ?></td>
                                    <td><?php echo $vo['mobile']; ?></td>
                                    <td class="order_status"><?php echo $vo['status_text']; ?></td>
                                    <td><?php echo $vo['create_time']; ?></td>
                                    <td class="td-manage">
                                        <?php if($vo['status'] == 0): ?>
                                            <a href="javascript:void(0)" onclick="intell_confirmWith(<?php echo $vo['id']; ?>,this)" title="已处理"><i class="Hui-iconfont" style="font-size:20px;">&#xe6a7;</i></a>
                                        <?php else: ?>
                                            已处理
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tabCon">
                    <div class="mt-20">
                        <table class="table table-border table-bordered table-bg table-hover table-sort">
                            <thead>
                            <tr class="text-c">
                                <th width="60">合同名称</th>
                                <th width="60">合同状态</th>
                                <th width="60">签订日期</th>
                                <th width="60">费用</th>
                                <th width="100">合同</th>
                                <th width="100">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(is_array($order_list) || $order_list instanceof \think\Collection || $order_list instanceof \think\Paginator): $k = 0; $__LIST__ = $order_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?>
                                <tr class="text-c">
                                    <td><?php echo $vo['name']; ?></td>
                                    <td><?php echo $vo['pact_status']; ?></td>
                                    <td><?php echo $vo['master_sign_time']; ?></td>
                                    <td>
                                        <?php if($vo['order_status'] == 1): ?>
                                            <a style="text-decoration:none" class="ml-5" href="<?php echo url('order/change_price',['id'=>$vo['id']]); ?>" title="修改价格"><i class="Hui-iconfont" style="font-size:20px;">&#xe6df;</i></a>
                                        <?php else: ?>
                                            <?php echo $vo['price']; endif; ?>
                                    </td>
                                    <td>
                                        <?php if($vo['pact'] == 1): ?>
                                            <a href="<?php echo url('pact/pact_add',['order_id'=>$vo['id'],'option'=>'add']); ?>" style="cursor:pointer;">添加</a>
                                            <?php else: ?>
                                            <a href="<?php echo url('pact/pact_edit',['order_id'=>$vo['id'],'option'=>'update']); ?>" style="cursor:pointer;color:red;">修改</a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($vo['febt'] == 1): ?><a href="javascript:debt(<?php echo $vo['id']; ?>)" title="催缴"><i class="Hui-iconfont" style="font-size:20px;">&#xe606;</i></a><?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; endif; else: echo "" ;endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tabCon">

                </div>
                <div class="tabCon">
                    <div class="mt-20">
                        <form class="form form-horizontal" id="form-article-add" action="<?php echo url('member/finance'); ?>" method="post">
                            <div class="row cl">
                                <table class="table table-border table-bordered table-bg table-hover">
                                    <thead>
                                    <tr class="text-c">
                                        <th width="20">名称</th>
                                        <th width="15">是否开通</th>
                                        <th width="15">是否委托</th>
                                        <!--<th width="50">备注</th>-->
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="text-c">
                                        <td>自助开票功能</td>
                                        <td>
                                            <select name="is_open_billing" class="select" id="is_open_billing">
                                                <option value="1" <?php echo $finance_info['is_open_billing']==1?'selected': ''; ?>>是</option>
                                                <option value="2" <?php echo $finance_info['is_open_billing']==2?'selected' : ''; ?>>否</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="is_open_billing" <?php echo $finance_info['is_open_billing']==1?"": "style='display: none;' disabled=true"; ?>>
                                                <select name="is_consign_billing" class="select">
                                                    <option value="1" <?php echo $finance_info['is_consign_billing']==1?'selected': ''; ?>>是</option>
                                                    <option value="2" <?php echo $finance_info['is_consign_billing']==2?'selected' : ''; ?>>否</option>
                                                </select>
                                            </div>
                                            <div class="no_open_billing" <?php echo $finance_info['is_open_billing']==2?"": "style='display: none;'"; ?>>
                                                --
                                                <input type="hidden" name="is_consign_billing" value="3" <?php echo $finance_info['is_open_billing']==2?"": "disabled='disabled'"; ?>/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="text-c">
                                        <td>社保</td>
                                        <td>
                                            <select name="is_open_shebao" class="select" id="is_open_shebao">
                                                <option value="1" <?php echo $finance_info['is_open_shebao']==1?'selected': ''; ?>>是</option>
                                                <option value="2" <?php echo $finance_info['is_open_shebao']==2?'selected' : ''; ?>>否</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="is_open_shebao" <?php echo $finance_info['is_open_shebao']==1?"": "style='display: none;' disabled=true"; ?>>
                                                <select name="is_consign_shebao" class="select">
                                                    <option value="1" <?php echo $finance_info['is_consign_shebao']==1?'selected': ''; ?>>是</option>
                                                    <option value="2" <?php echo $finance_info['is_consign_shebao']==2?'selected' : ''; ?>>否</option>
                                                </select>
                                            </div>
                                            <div class="no_open_shebao" <?php echo $finance_info['is_open_shebao']==2?"": "style='display: none;'"; ?>>
                                                --
                                                <input type="hidden" name="is_consign_shebao" value="3" <?php echo $finance_info['is_open_shebao']==2?"": "disabled='disabled'"; ?>/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="text-c">
                                        <td>公积金</td>
                                        <td>
                                            <select name="is_open_pfund" class="select" id="is_open_pfund">
                                                <option value="1" <?php echo $finance_info['is_open_pfund']==1?'selected': ''; ?>>是</option>
                                                <option value="2" <?php echo $finance_info['is_open_pfund']==2?'selected' : ''; ?>>否</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="is_open_pfund" <?php echo $finance_info['is_open_pfund']==1?"": "style='display: none;' disabled=true"; ?>>
                                                <select name="is_consign_pfund" class="select">
                                                    <option value="1" <?php echo $finance_info['is_consign_pfund']==1?'selected': ''; ?>>是</option>
                                                    <option value="2" <?php echo $finance_info['is_consign_pfund']==2?'selected' : ''; ?>>否</option>
                                                </select>
                                            </div>
                                                <div class="no_open_pfund" <?php echo $finance_info['is_open_pfund']==2?"": "style='display: none;'"; ?>>
                                                --
                                                <input type="hidden" name="is_consign_pfund" value="3" <?php echo $finance_info['is_open_pfund']==2?"": "disabled='disabled'"; ?>/>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="text-c">
                                        <td>银行回单</td>
                                        <td>
                                            <select name="is_open_receipt" class="select" id="is_open_receipt">
                                                <option value="1" <?php echo $finance_info['is_open_receipt']==1?'selected': ''; ?>>是</option>
                                                <option value="2" <?php echo $finance_info['is_open_receipt']==2?'selected' : ''; ?>>否</option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="is_open_receipt" <?php echo $finance_info['is_open_receipt']==1?"": "style='display: none;' disabled=true"; ?>>
                                                <select name="is_consign_receipt" class="select">
                                                    <option value="1" <?php echo $finance_info['is_consign_receipt']==1?'selected': ''; ?>>是</option>
                                                    <option value="2" <?php echo $finance_info['is_consign_receipt']==2?'selected' : ''; ?>>否</option>
                                                </select>
                                            </div>
                                            <div class="no_open_receipt" <?php echo $finance_info['is_open_receipt']==2?"": "style='display: none;'"; ?>>
                                                --
                                                <input type="hidden" name="is_consign_receipt" value="3" <?php echo $finance_info['is_open_receipt']==2?"": "disabled='disabled'"; ?>/>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="row cl">
                                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                                    <input name="" type="submit" class="btn btn-primary radius Hui-iconfont" value="&#xe632;保存">
                                    <input name="" type="reset" class="btn btn-default radius size-S" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                                </div>
                            </div>
                            <input type="hidden" name="uid" value="<?php echo $id; ?>"/>
                            <?php echo token(); ?>
                        </form>
                    </div>
                </div>
            </div>
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
<script type="text/javascript" src="/yikesong/public/lib/ueditor/1.4.3/ueditor.config.js"></script>
<script type="text/javascript" src="/yikesong/public/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
<script type="text/javascript" src="/yikesong/public/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
<script type="text/javascript" src="/yikesong/public/lib/datatables/1.10.0/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(function(){
        //百度编辑器
        var ue = UE.getEditor('editor');

        $('.skin-minimal input').iCheck({
            checkboxClass: 'icheckbox-blue',
            radioClass: 'iradio-blue',
            increaseArea: '20%'
        });
        var index="<?php echo $index?>"
        $("#tab-system").Huitab({
            index:index //控制显示哪个选项卡，从0开始
        });
        /*表格插件*/
        $('.table-sort').dataTable({
            //"aaSorting": [[ 1, "desc" ]],//默认第几个排序
            "bStateSave": true,//状态保存
            "aoColumnDefs": [
                //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
                //{"orderable":false,"aTargets":[0,9]}// 不参与排序的列
            ]
        });
    });
    function editLiable(id,th){
        //console.log($(th).parent().find(".select").find("option:selected")); return false;
        $_liable = $(th).parent().find(".select").find("option:selected").text();
        if($_liable==""){
            alert('请填写订单所属负责人');
            return false;
        }
        $.ajax({
            type:"POST",
            url:"<?php echo url('order/changeLiable'); ?>",
            data:{"id":id,"liable":$_liable},
            dataType:"JSON",
            success:function(data){
                switch(data.status){
                    case "success":
                        layer.msg('修改成功!',{icon: 1,time:1000});
                        break;
                    default:
                        alert(data.msg);
                        break;
                }
            }
        })
    }
    function debt(id){
        layer.prompt({title: '费用催收', formType: 3}, function(val, index){
            if(val==="" || val ===null || isNaN(val) ||val<=0){
                alert('请先正确填写费用')
                return false;
            }

            $.ajax({
                type:"POST",
                url:"<?php echo url('order/debt'); ?>",
                data:{"id":id,"price":val},
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
        });
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
                            console.log($(th).parents("tr").find('.order_status'))
                            $(th).parents("tr").find('.order_status').text('已通过');//必须为$(th)
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
    function intell_confirmWith(id,th){
        if(confirm("确认处理？")){
            $.ajax({
                type:"POST",
                url:"<?php echo url('order/confirm_intell'); ?>",
                data:{"id":id},
                dataType:"JSON",
                success:function(data){
                    switch(data.status){
                        case "success":
                            console.log($(th).parents("tr").find('.order_status'))
                            $(th).parents("tr").find('.order_status').text('已处理');//必须为$(th)
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
</script>
<script type="text/javascript">
    $('#is_open_billing').change(function() {
        var opt=$("#is_open_billing").val();
        if (opt==1){
            $(".is_open_billing").css('display','');
            $(".no_open_billing").css('display','none');
            $(".no_open_billing").find(":input").attr('disabled',true);
            $(".is_open_billing").find(".select").attr('disabled',false);
        } else{
            $(".no_open_billing").css('display','');
            $(".is_open_billing").css('display','none');
            $(".is_open_billing").find(".select").attr('disabled',true);
            $(".no_open_billing").find(":input").attr('disabled',false);
        }
    })
    $('#is_open_shebao').change(function() {
        var opt=$("#is_open_shebao").val();
        if (opt==1){
            $(".is_open_shebao").css('display','');
            $(".no_open_shebao").css('display','none');
            $(".no_open_shebao").find(":input").attr('disabled',true);
            $(".is_open_shebao").find(".select").attr('disabled',false);
        } else{
            $(".no_open_shebao").css('display','');
            $(".is_open_shebao").css('display','none');
            $(".is_open_shebao").find(".select").attr('disabled',true);
            $(".no_open_shebao").find(":input").attr('disabled',false);
        }
    })
    $('#is_open_pfund').change(function() {
        var opt=$("#is_open_pfund").val();
        if (opt==1){
            $(".is_open_pfund").css('display','');
            $(".no_open_pfund").css('display','none');
            $(".no_open_pfund").find(":input").attr('disabled',true);
            $(".is_open_pfund").find(".select").attr('disabled',false);
        } else{
            $(".no_open_pfund").css('display','');
            $(".is_open_pfund").css('display','none');
            $(".is_open_pfund").find(".select").attr('disabled',true);
            $(".no_open_pfund").find(":input").attr('disabled',false);
        }
    })
    $('#is_open_receipt').change(function() {
        var opt=$("#is_open_receipt").val();
        if (opt==1){
            $(".is_open_receipt").css('display','');
            $(".no_open_receipt").css('display','none');
            $(".no_open_receipt").find(":input").attr('disabled',true);
            $(".is_open_receipt").find(".select").attr('disabled',false);
        } else{
            $(".no_open_receipt").css('display','');
            $(".is_open_receipt").css('display','none');
            $(".is_open_receipt").find(".select").attr('disabled',true);
            $(".no_open_receipt").find(":input").attr('disabled',false);
        }
    })
</script>
<!--/请在上方写此页面业务相关的脚本-->


</body>
</html>