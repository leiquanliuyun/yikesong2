<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/login/index.html";i:1536741710;}*/ ?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<!--[if lt IE 9]>
<script type="text/javascript" src="lib/html5shiv.js"></script>
<script type="text/javascript" src="lib/respond.min.js"></script>
<![endif]-->
<link href="/static/h-ui/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/static/h-ui.admin/css/H-ui.login.css" rel="stylesheet" type="text/css" />
<link href="/static/h-ui.admin/css/style.css" rel="stylesheet" type="text/css" />
<link href="/lib/Hui-iconfont/1.0.8/iconfont.css" rel="stylesheet" type="text/css" />
<!--[if IE 6]>
<script type="text/javascript" src="lib/DD_belatedPNG_0.0.8a-min.js" ></script>
<script>DD_belatedPNG.fix('*');</script>
<![endif]-->
<title>后台登录 - <?php echo $list['seo_title']; ?></title>
<meta name="keywords" content="<?php echo $list['seo_keyword']; ?>">
<meta name="description" content="<?php echo $list['seo_describe']; ?>">
</head>
<body>
<div class="header"></div>
<div class="loginWraper">
  <div id="loginform" class="loginBox">
    <form class="form form-horizontal" action="<?php echo url('login/login'); ?>" method="post">
      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
        <div class="formControls col-xs-8">
          <input id="" name="user_login" type="text" placeholder="账户" class="input-text size-L">
        </div>
      </div>

      <div class="row cl">
        <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
        <div class="formControls col-xs-8">
          <input id="" name="password" type="password" placeholder="密码" class="input-text size-L">
        </div>
      </div>

      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
            <input class="input-text size-L" type="text" name="captcha" placeholder="验证码" onblur="if(this.value==''){this.value='验证码:'}" onclick="if(this.value=='验证码:'){this.value='';}" value="验证码:" style="width:150px;">
            <img src="<?php echo captcha_src(); ?>" onclick="this.src='<?php echo captcha_src(); ?>'" alt="验证码" title="点击更换">
          </div>
      </div>
      <!--<div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <label for="online">
            <input type="checkbox" name="online" id="online" value="">
            使我保持登录状态</label>
        </div>
      </div>-->
      <div class="row cl">
        <div class="formControls col-xs-8 col-xs-offset-3">
          <input name="" type="submit" class="btn btn-success radius size-L" value="&nbsp;登&nbsp;&nbsp;&nbsp;&nbsp;录&nbsp;">
          <input name="" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
        </div>
      </div>
      <input type="hidden" name="option" value="login"/>
      <?php echo token(); ?>

    </form>
  </div>
</div>

<div class="footer">Copyright2018 <?php echo $list['site_name']; ?> 版权所有 <?php echo $list['archival_information']; ?> 技术支持：风步信息</div>
<script type="text/javascript" src="/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/static/h-ui/js/H-ui.min.js"></script>

</body>
</html>