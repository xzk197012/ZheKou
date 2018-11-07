<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20180110" rel="stylesheet">
<link href="./resource/css/common.css?v=20170719" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<script type="text/javascript">
    if(navigator.appName == 'Microsoft Internet Explorer'){
        if(navigator.userAgent.indexOf("MSIE 5.0")>0 || navigator.userAgent.indexOf("MSIE 6.0")>0 || navigator.userAgent.indexOf("MSIE 7.0")>0) {
            alert('您使用的 IE 浏览器版本过低, 推荐使用 Chrome 浏览器或 IE8 及以上版本浏览器.');
        }
    }
    window.sysinfo = {
    <?php  if(!empty($_W['uniacid'])) { ?>'uniacid': '<?php  echo $_W['uniacid'];?>',<?php  } ?>
    <?php  if(!empty($_W['acid'])) { ?>'acid': '<?php  echo $_W['acid'];?>',<?php  } ?>
    <?php  if(!empty($_W['openid'])) { ?>'openid': '<?php  echo $_W['openid'];?>',<?php  } ?>
    <?php  if(!empty($_W['uid'])) { ?>'uid': '<?php  echo $_W['uid'];?>',<?php  } ?>
    'isfounder': <?php  if(!empty($_W['isfounder'])) { ?>1<?php  } else { ?>0<?php  } ?>,
    'siteroot': '<?php  echo $_W['siteroot'];?>',
        'siteurl': '<?php  echo $_W['siteurl'];?>',
        'attachurl': '<?php  echo $_W['attachurl'];?>',
        'attachurl_local': '<?php  echo $_W['attachurl_local'];?>',
        'attachurl_remote': '<?php  echo $_W['attachurl_remote'];?>',
        'module' : {'url' : '<?php  if(defined('MODULE_URL')) { ?><?php echo MODULE_URL;?><?php  } ?>', 'name' : '<?php  if(defined('IN_MODULE')) { ?><?php echo IN_MODULE;?><?php  } ?>'},
    'cookie' : {'pre': '<?php  echo $_W['config']['cookie']['pre'];?>'},
    'account' : <?php  echo json_encode($_W['account'])?>,
    };
</script>
<script>var require = { urlArgs: 'v=20170915' };</script>
<script type="text/javascript" src="./resource/js/lib/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="./resource/js/lib/bootstrap.min.js"></script>
<script type="text/javascript" src="./resource/js/app/util.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/app/common.min.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/require.js?v=20170719"></script>
    <body>
        <div class="x-body">

            <form id="initform" action="" method="post"  enctype="multipart/form-data" class="layui-form ">
                <blockquote class="layui-elem-quote">帮助</blockquote>
                <div class="layui-container">
                <div class="layui-row  layui-col-space5">
                    <?php  echo tpl_ueditor('help', $data['help']);?>
                </div>
                </div>
                <blockquote class="layui-elem-quote">省钱帮助</blockquote>
                <div class="layui-container">
                    <div class="layui-row  layui-col-space5">
                        <?php  echo tpl_ueditor('savemoney_help', $data['savemoney_help']);?>
                    </div>
                </div>
                <blockquote class="layui-elem-quote">赚钱帮助</blockquote>
                <div class="layui-container">
                    <div class="layui-row  layui-col-space5">
                        <?php  echo tpl_ueditor('makemoney_help', $data['makemoney_help']);?>
                    </div>
                    <xblock>
                        <input type="hidden" name="help_name" value="帮助"/>
                        <input type="hidden" name="op" value="help"/>
                        <input type="submit" name="submit" value="提交" class="layui-btn " />
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    </xblock>
                </div>
            </form>
        </div>
    </body>
</html>
