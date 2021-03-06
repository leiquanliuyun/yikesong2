<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:107:"/data/wwwroot/app.yikesong66.com/public/../application/useradmin/view/specialservice/housecontent_edit.html";i:1536741711;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/base.html";i:1536741707;s:76:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/meta.html";i:1536741707;s:78:"/data/wwwroot/app.yikesong66.com/application/useradmin/view/common/footer.html";i:1536741707;}*/ ?>
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

    <title>
        <?php if($option == 'add'): ?>添加<?php else: ?>编辑<?php endif; ?>服务内容
    </title>
    <link href="/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/lib/ueditor/1.4.3/ueditor.config.js"></script>
    <script type="text/javascript" src="/lib/ueditor/1.4.3/ueditor.all.min.js"> </script>
    <script type="text/javascript" src="/lib/ueditor/1.4.3/lang/zh-cn/zh-cn.js"></script>
    </head>
    <body>
    <div class="page-container">
        <form class="form form-horizontal" id="form-article-add" action="<?php echo url('specialservice/housecontent_edit'); ?>" method="post">
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">房屋类型：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box">
                    <select name="housetype" id="housetype" class="select">
                        <option value="">--请选择--</option>
                        <option value="办公楼">办公楼</option>
                        <option value="住宅楼">住宅楼</option>
                    </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">所属分类(筛选条件)：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <span class="select-box">
                    <select name="filter_id[]" id="filter_id" class="select" multiple="multiple">
                        <option value="">--请选择--</option>
                        <?php if(is_array($filter_list) || $filter_list instanceof \think\Collection || $filter_list instanceof \think\Paginator): $i = 0; $__LIST__ = $filter_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                            <option value="<?php echo $vo['id']; ?>"><?php echo $vo['condition']; ?></option>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </select>
                    </span>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">房屋名称：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['name'])?$list['name']: ''; ?>" placeholder="" id="" name="name">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">户型：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['type'])?$list['type']: ''; ?>" placeholder="" id="" name="type">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">面积：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['size'])?$list['size']: ''; ?>" placeholder="" id="" name="size">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">朝向：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['orientation'])?$list['orientation']: ''; ?>" placeholder="" id="" name="orientation">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">楼层：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['floor'])?$list['floor']: ''; ?>" placeholder="" id="" name="floor">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">装修方式：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['fixture'])?$list['fixture']: ''; ?>" placeholder="" id="" name="fixture">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">租售方式：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['rental_method'])?$list['rental_method']: ''; ?>" placeholder="" id="" name="rental_method">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">价格：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <input type="text" class="input-text" value="<?php echo !empty($list['price'])?$list['price']: ''; ?>" placeholder="" id="" name="price">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2">房屋轮播图：</label>
                <div class="formControls col-xs-8 col-sm-9" id="div_picture">
                    <input type="hidden" name="picture" id="picture" value="<?php echo !empty($list['picture'])?$list['picture'] : ''; ?>">
                    <?php if(empty($list['picture'])): ?>
                        <img src="/static/h-ui.admin/images/default-thumbnail.png" id="thumb-preview" width="50" style="cursor: hand"/>
                        <?php else: if(is_array($list['picture_show']) || $list['picture_show'] instanceof \think\Collection || $list['picture_show'] instanceof \think\Paginator): if( count($list['picture_show'])==0 ) : echo "" ;else: foreach($list['picture_show'] as $k=>$vo): ?>
                            <img src="<?php echo $vo; ?>" width="100" style="cursor: hand"/>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                        <input type="button" class="btn btn-small" onclick="$('#div_picture > img').remove();return false;" value="取消多图">
                    <?php endif; ?>
                    <div class="uploader-list-container">
                        <div class="queueList">
                            <div id="dndArea" class="placeholder">
                                <div id="filePicker-2"></div>
                                <p>或将照片拖到这里，单次可选多张照片</p>
                            </div>
                        </div>
                        <div class="statusBar" style="display:none;">
                            <div class="progress"> <span class="text">0%</span> <span class="percentage"></span> </div>
                            <div class="info"></div>
                            <div class="btns">
                                <div id="filePicker2"></div>
                                <div class="uploadBtn">开始上传</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>信息介绍：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <script id="introduce_mess" name="introduce_mess" type="text/plain" style="width:99.6%; height:300px"><?php echo $list['introduce_mess']; ?></script>
                    <script type="text/javascript">
                    var ue = UE.getEditor('introduce_mess');
                    </script>
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-4 col-sm-2"><span class="c-red">*</span>交易方式：</label>
                <div class="formControls col-xs-8 col-sm-9">
                    <script id="trade_type" name="trade_type" type="text/plain" style="width:99.6%; height:300px"><?php echo $list['trade_type']; ?></script>
                    <script type="text/javascript">
                    var ue = UE.getEditor('trade_type');
                    </script>
                </div>
            </div>
            <div class="row cl">
                <div class="col-xs-8 col-sm-9 col-xs-offset-4 col-sm-offset-2">
                    <input name="" type="submit" class="btn btn-primary radius Hui-iconfont" value="&#xe632;保存">
                    <input name="" type="reset" class="btn btn-default radius size-S" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                </div>
            </div>
            <input type="hidden" name="option" value="<?php echo $option; ?>"/>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <?php echo token(); ?>
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

    <!--此页面业务相关的脚本-->
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/jquery.validate.js"></script>
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/validate-methods.js"></script>
    <script type="text/javascript" src="/lib/jquery.validation/1.14.0/messages_zh.js"></script>
    <script type="text/javascript" src="/lib/webuploader/0.1.5/webuploader.min.js"></script>

    <script type="text/javascript">
        function article_save(){
            alert("刷新父级的时候会自动关闭弹层。")
            window.parent.location.reload();
        }
        $(function(){
            var ser = "<?php echo $list['housetype']; ?>";
            $("#housetype option[value="+ser+"]").attr("selected",true);
            var ser = "<?php echo $list['filter_id']; ?>";
            $("#filter_id option[value="+ser+"]").attr("selected",true);
        });
        $(function(){

            $('.skin-minimal input').iCheck({
                checkboxClass: 'icheckbox-blue',
                radioClass: 'iradio-blue',
                increaseArea: '20%'
            });
            //百度编辑器
            /* var ue = UE.getEditor('introduce_mess');
             var ue2 = UE.getEditor('trade_type');*/

        });
        /*多图上传*/
        (function( $ ){
            // 当domReady的时候开始初始化
            $(function() {
                var $wrap = $('.uploader-list-container'),

                    // 图片容器
                    $queue = $( '<ul class="filelist"></ul>' )
                        .appendTo( $wrap.find( '.queueList' ) ),

                    // 状态栏，包括进度和控制按钮
                    $statusBar = $wrap.find( '.statusBar' ),

                    // 文件总体选择信息。
                    $info = $statusBar.find( '.info' ),

                    // 上传按钮
                    $upload = $wrap.find( '.uploadBtn' ),

                    // 没选择文件之前的内容。
                    $placeHolder = $wrap.find( '.placeholder' ),

                    $progress = $statusBar.find( '.progress' ).hide(),

                    // 添加的文件数量
                    fileCount = 0,

                    // 添加的文件总大小
                    fileSize = 0,

                    //多图input地址
                    picture = '',

                    // 优化retina, 在retina下这个值是2
                    ratio = window.devicePixelRatio || 1,

                    // 缩略图大小
                    thumbnailWidth = 110 * ratio,
                    thumbnailHeight = 110 * ratio,

                    // 可能有pedding, ready, uploading, confirm, done.
                    state = 'pedding',

                    // 所有文件的进度信息，key为file id
                    percentages = {},
                    // 判断浏览器是否支持图片的base64
                    isSupportBase64 = ( function() {
                        var data = new Image();
                        var support = true;
                        data.onload = data.onerror = function() {
                            if( this.width != 1 || this.height != 1 ) {
                                support = false;
                            }
                        }
                        data.src = "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==";
                        return support;
                    } )(),

                    // 检测是否已经安装flash，检测flash的版本
                    flashVersion = ( function() {
                        var version;

                        try {
                            version = navigator.plugins[ 'Shockwave Flash' ];
                            version = version.description;
                        } catch ( ex ) {
                            try {
                                version = new ActiveXObject('ShockwaveFlash.ShockwaveFlash')
                                    .GetVariable('$version');
                            } catch ( ex2 ) {
                                version = '0.0';
                            }
                        }
                        version = version.match( /\d+/g );
                        return parseFloat( version[ 0 ] + '.' + version[ 1 ], 10 );
                    } )(),

                    supportTransition = (function(){
                        var s = document.createElement('p').style,
                            r = 'transition' in s ||
                                'WebkitTransition' in s ||
                                'MozTransition' in s ||
                                'msTransition' in s ||
                                'OTransition' in s;
                        s = null;
                        return r;
                    })(),

                    // WebUploader实例
                    uploader;

                if ( !WebUploader.Uploader.support('flash') && WebUploader.browser.ie ) {

                    // flash 安装了但是版本过低。
                    if (flashVersion) {
                        (function(container) {
                            window['expressinstallcallback'] = function( state ) {
                                switch(state) {
                                    case 'Download.Cancelled':
                                        alert('您取消了更新！')
                                        break;

                                    case 'Download.Failed':
                                        alert('安装失败')
                                        break;

                                    default:
                                        alert('安装已成功，请刷新！');
                                        break;
                                }
                                delete window['expressinstallcallback'];
                            };

                            var swf = 'expressInstall.swf';
                            // insert flash object
                            var html = '<object type="application/' +
                                'x-shockwave-flash" data="' +  swf + '" ';

                            if (WebUploader.browser.ie) {
                                html += 'classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" ';
                            }

                            html += 'width="100%" height="100%" style="outline:0">'  +
                                '<param name="movie" value="' + swf + '" />' +
                                '<param name="wmode" value="transparent" />' +
                                '<param name="allowscriptaccess" value="always" />' +
                                '</object>';

                            container.html(html);

                        })($wrap);

                        // 压根就没有安转。
                    } else {
                        $wrap.html('<a href="http://www.adobe.com/go/getflashplayer" target="_blank" border="0"><img alt="get flash player" src="http://www.adobe.com/macromedia/style_guide/images/160x41_Get_Flash_Player.jpg" /></a>');
                    }

                    return;
                } else if (!WebUploader.Uploader.support()) {
                    alert( 'Web Uploader 不支持您的浏览器！');
                    return;
                }

                // 实例化
                uploader = WebUploader.create({
                    pick: {
                        id: '#filePicker-2',
                        label: '点击选择图片'
                    },
                    formData: {
                        uid: 123
                    },
                    dnd: '#dndArea',
                    paste: '#uploader',
                    swf: '/lib/webuploader/0.1.5/Uploader.swf',
                    chunked: false,
                    chunkSize: 512 * 1024,
                    server: "<?php echo url('upload/upload_image'); ?>",
                    // runtimeOrder: 'flash',

                    // accept: {
                    //     title: 'Images',
                    //     extensions: 'gif,jpg,jpeg,bmp,png',
                    //     mimeTypes: 'image/*'
                    // },

                    // 禁掉全局的拖拽功能。这样不会出现图片拖进页面的时候，把图片打开。
                    disableGlobalDnd: true,
                    fileNumLimit: 300,
                    fileSizeLimit: 200 * 1024 * 1024,    // 200 M
                    fileSingleSizeLimit: 50 * 1024 * 1024    // 50 M
                });

                // 拖拽时不接受 js, txt 文件。
                uploader.on( 'dndAccept', function( items ) {
                    var denied = false,
                        len = items.length,
                        i = 0,
                        // 修改js类型
                        unAllowed = 'text/plain;application/javascript ';

                    for ( ; i < len; i++ ) {
                        // 如果在列表里面
                        if ( ~unAllowed.indexOf( items[ i ].type ) ) {
                            denied = true;
                            break;
                        }
                    }

                    return !denied;
                });

                uploader.on('dialogOpen', function() {
                    console.log('here');
                });

                // uploader.on('filesQueued', function() {
                //     uploader.sort(function( a, b ) {
                //         if ( a.name < b.name )
                //           return -1;
                //         if ( a.name > b.name )
                //           return 1;
                //         return 0;
                //     });
                // });

                // 添加“添加文件”的按钮，
                uploader.addButton({
                    id: '#filePicker2',
                    label: '继续添加'
                });

                uploader.on('ready', function() {
                    window.uploader = uploader;
                });

                // 当有文件添加进来时执行，负责view的创建
                function addFile( file ) {
                    var $li = $( '<li id="' + file.id + '">' +
                        '<p class="title">' + file.name + '</p>' +
                        '<p class="imgWrap"></p>'+
                        '<p class="progress"><span></span></p>' +
                        '</li>' ),

                        $btns = $('<div class="file-panel">' +
                            '<span class="cancel">删除</span>' +
                            '<span class="rotateRight">向右旋转</span>' +
                            '<span class="rotateLeft">向左旋转</span></div>').appendTo( $li ),
                        $prgress = $li.find('p.progress span'),
                        $wrap = $li.find( 'p.imgWrap' ),
                        $info = $('<p class="error"></p>'),

                        showError = function( code ) {
                            switch( code ) {
                                case 'exceed_size':
                                    text = '文件大小超出';
                                    break;

                                case 'interrupt':
                                    text = '上传暂停';
                                    break;

                                default:
                                    text = '上传失败，请重试';
                                    break;
                            }

                            $info.text( text ).appendTo( $li );
                        };

                    if ( file.getStatus() === 'invalid' ) {
                        showError( file.statusText );
                    } else {
                        // @todo lazyload
                        $wrap.text( '预览中' );
                        uploader.makeThumb( file, function( error, src ) {
                            var img;

                            if ( error ) {
                                $wrap.text( '不能预览' );
                                return;
                            }

                            if( isSupportBase64 ) {
                                img = $('<img src="'+src+'">');
                                $wrap.empty().append( img );
                            } else {
                                $.ajax('/lib/webuploader/0.1.5/server/preview.php', {
                                    method: 'POST',
                                    data: src,
                                    dataType:'json'
                                }).done(function( response ) {
                                    if (response.result) {
                                        img = $('<img src="'+response.result+'">');
                                        $wrap.empty().append( img );
                                    } else {
                                        $wrap.text("预览出错");
                                    }
                                });
                            }
                        }, thumbnailWidth, thumbnailHeight );

                        percentages[ file.id ] = [ file.size, 0 ];
                        file.rotation = 0;
                    }

                    file.on('statuschange', function( cur, prev ) {
                        if ( prev === 'progress' ) {
                            $prgress.hide().width(0);
                        } else if ( prev === 'queued' ) {
                            $li.off( 'mouseenter mouseleave' );
                            $btns.remove();
                        }

                        // 成功
                        if ( cur === 'error' || cur === 'invalid' ) {
                            console.log( file.statusText );
                            showError( file.statusText );
                            percentages[ file.id ][ 1 ] = 1;
                        } else if ( cur === 'interrupt' ) {
                            showError( 'interrupt' );
                        } else if ( cur === 'queued' ) {
                            percentages[ file.id ][ 1 ] = 0;
                        } else if ( cur === 'progress' ) {
                            $info.remove();
                            $prgress.css('display', 'block');
                        } else if ( cur === 'complete' ) {
                            $li.append( '<span class="success"></span>' );
                        }

                        $li.removeClass( 'state-' + prev ).addClass( 'state-' + cur );
                    });

                    $li.on( 'mouseenter', function() {
                        $btns.stop().animate({height: 30});
                    });

                    $li.on( 'mouseleave', function() {
                        $btns.stop().animate({height: 0});
                    });

                    $btns.on( 'click', 'span', function() {
                        var index = $(this).index(),
                            deg;

                        switch ( index ) {
                            case 0:
                                uploader.removeFile( file );
                                return;

                            case 1:
                                file.rotation += 90;
                                break;

                            case 2:
                                file.rotation -= 90;
                                break;
                        }

                        if ( supportTransition ) {
                            deg = 'rotate(' + file.rotation + 'deg)';
                            $wrap.css({
                                '-webkit-transform': deg,
                                '-mos-transform': deg,
                                '-o-transform': deg,
                                'transform': deg
                            });
                        } else {
                            $wrap.css( 'filter', 'progid:DXImageTransform.Microsoft.BasicImage(rotation='+ (~~((file.rotation/90)%4 + 4)%4) +')');

                        }


                    });

                    $li.appendTo( $queue );
                }

                // 负责view的销毁
                function removeFile( file ) {
                    var $li = $('#'+file.id);

                    delete percentages[ file.id ];
                    updateTotalProgress();
                    $li.off().find('.file-panel').off().end().remove();
                }

                function updateTotalProgress() {
                    var loaded = 0,
                        total = 0,
                        spans = $progress.children(),
                        percent;

                    $.each( percentages, function( k, v ) {
                        total += v[ 0 ];
                        loaded += v[ 0 ] * v[ 1 ];
                    } );

                    percent = total ? loaded / total : 0;


                    spans.eq( 0 ).text( Math.round( percent * 100 ) + '%' );
                    spans.eq( 1 ).css( 'width', Math.round( percent * 100 ) + '%' );
                    updateStatus();
                }

                function updateStatus() {
                    var text = '', stats;

                    if ( state === 'ready' ) {
                        text = '选中' + fileCount + '张图片，共' +
                            WebUploader.formatSize( fileSize ) + '。';
                    } else if ( state === 'confirm' ) {
                        stats = uploader.getStats();
                        if ( stats.uploadFailNum ) {
                            text = '已成功上传' + stats.successNum+ '张照片至相册，'+
                                stats.uploadFailNum + '张照片上传失败，<a class="retry" href="#">重新上传</a>失败图片或<a class="ignore" href="#">忽略</a>'
                        }

                    } else {
                        stats = uploader.getStats();
                        text = '共' + fileCount + '张（' +
                            WebUploader.formatSize( fileSize )  +
                            '），已上传' + stats.successNum + '张';

                        if ( stats.uploadFailNum ) {
                            text += '，失败' + stats.uploadFailNum + '张';
                        }
                    }

                    $info.html( text );
                }

                function setState( val ) {
                    var file, stats;

                    if ( val === state ) {
                        return;
                    }

                    $upload.removeClass( 'state-' + state );
                    $upload.addClass( 'state-' + val );
                    state = val;

                    switch ( state ) {
                        case 'pedding':
                            $placeHolder.removeClass( 'element-invisible' );
                            $queue.hide();
                            $statusBar.addClass( 'element-invisible' );
                            uploader.refresh();
                            break;

                        case 'ready':
                            $placeHolder.addClass( 'element-invisible' );
                            $( '#filePicker2' ).removeClass( 'element-invisible');
                            $queue.show();
                            $statusBar.removeClass('element-invisible');
                            uploader.refresh();
                            break;

                        case 'uploading':
                            $( '#filePicker2' ).addClass( 'element-invisible' );
                            $progress.show();
                            $upload.text( '暂停上传' );
                            break;

                        case 'paused':
                            $progress.show();
                            $upload.text( '继续上传' );
                            break;

                        case 'confirm':
                            $progress.hide();
                            $( '#filePicker2' ).removeClass( 'element-invisible' );
                            $upload.text( '开始上传' );

                            stats = uploader.getStats();
                            if ( stats.successNum && !stats.uploadFailNum ) {
                                setState( 'finish' );
                                return;
                            }
                            break;
                        case 'finish':
                            stats = uploader.getStats();

                            if ( stats.successNum ) {
                                //alert( '上传成功' );
                            } else {
                                // 没有成功的图片，重设
                                state = 'done';
                                location.reload();
                            }
                            break;
                    }

                    updateStatus();
                }

                uploader.onUploadProgress = function( file, percentage ) {
                    var $li = $('#'+file.id),
                        $percent = $li.find('.progress span');

                    $percent.css( 'width', percentage * 100 + '%' );
                    percentages[ file.id ][ 1 ] = percentage;
                    updateTotalProgress();
                };

                uploader.onFileQueued = function( file ) {
                    fileCount++;
                    fileSize += file.size;

                    if ( fileCount === 1 ) {
                        $placeHolder.addClass( 'element-invisible' );
                        $statusBar.show();
                    }

                    addFile( file );
                    setState( 'ready' );
                    updateTotalProgress();
                };

                uploader.onFileDequeued = function( file ) {
                    fileCount--;
                    fileSize -= file.size;

                    if ( !fileCount ) {
                        setState( 'pedding' );
                    }

                    removeFile( file );
                    updateTotalProgress();

                };

                uploader.on( 'all', function( type ) {
                    var stats;
                    switch( type ) {
                        case 'uploadFinished':
                            setState( 'confirm' );
                            break;

                        case 'startUpload':
                            setState( 'uploading' );
                            break;

                        case 'stopUpload':
                            setState( 'paused' );
                            break;

                    }
                });

                uploader.on( 'uploadSuccess', function( file,response) {
                    console.log(response);
                    //表单图片地址
                    picture = $('#picture').val();
                    if(picture == '') {
                        $('#picture').val(response._raw);
                    } else {
                        picture = picture+'##'+response._raw;
                        $('#picture').val(picture);
                    }
                    //显示缩略图
                    $('#div_picture').prepend("<img src='/uploads/images/"+response._raw+"' width='100' style='cursor: hand' />");

                });

                uploader.onError = function( code ) {
                    alert( 'Eroor: ' + code );
                };

                $upload.on('click', function() {
                    if ( $(this).hasClass( 'disabled' ) ) {
                        return false;
                    }

                    if ( state === 'ready' ) {
                        uploader.upload();
                    } else if ( state === 'paused' ) {
                        uploader.upload();
                    } else if ( state === 'uploading' ) {
                        uploader.stop();
                    }
                });

                $info.on( 'click', '.retry', function() {
                    uploader.retry();
                } );

                $info.on( 'click', '.ignore', function() {
                    alert( 'todo' );
                } );

                $upload.addClass( 'state-' + state );
                updateTotalProgress();
            });

        })( jQuery );
    </script>


</body>
</html>