<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<!--<link href="./resource/affordable/css/bootstrap.min.css?v=20170915" rel="stylesheet">-->
<!--<link href="./resource/affordable/css/commona.css?v=20170915" rel="stylesheet">	-->
<link href="./resource/css/bootstrap.min.css?v=20170719" rel="stylesheet">
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
    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>客服二维码
            </label>
            <div class="layui-input-inline4">
                <?php  echo tpl_form_field_image('kefuqr',$r['kefuqr'])?>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>设置客服二维码
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>logo
            </label>
            <div class="layui-input-inline4">
                <?php  echo tpl_form_field_image('logo',$r['logo'])?>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>设置logo图片
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>分享标题
            </label>
            <div class="layui-input-inline4">
                <input type="text" name="title" lay-verify="title"
                       autocomplete="off" class="layui-input" value="<?php  echo $data['title'];?>">
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>分享标题
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>分享描述
            </label>
            <div class="layui-input-inline4">
                <textarea placeholder="请输入分享描述" class="layui-textarea" name="remark"><?php  echo $data['remark'];?></textarea>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>分享描述
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>推广海报
            </label>
            <div class="layui-input-inline4">
                <?php  echo tpl_form_field_image('haibao',$haibao)?>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>设置会员推广的海报背景图,背景图请上传1242*2208大尺寸图片
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商品海报
            </label>
            <div class="layui-input-inline12">
                <label style="float:left;">
                    <img  src="<?php echo MODULE_URL;?>skin/goodposter1.jpg" style="width:200px;height:300px;"/><br/>
                    <input  title="&nbsp;风格1"  name="goodposter" type="radio" value="1" <?php  if($r['goodposter']==1 || $r['goodposter']==0) { ?>checked="checked"<?php  } ?>/> 
                    
                </label>
                &emsp;&emsp;
                <label  style="float:left;">
                    <img  src="<?php echo MODULE_URL;?>skin/goodposter2.jpg" style="width:200px;height:300px;"/><br/>
                    <input  title="&nbsp;风格2 "  name="goodposter" type="radio" value="2" <?php  if($r['goodposter']==2) { ?>checked="checked"<?php  } ?>/>
                    
                </label>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>选择商品海报背景
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>引导关注
            </label>
            <div class="layui-input-inline4">
                <input type="text" name="subscribeurl"
                       autocomplete="off" class="layui-input layui-disabled" value="<?php  echo $r['subscribeurl'];?>">
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>推广二维码链接
            </label>
            <div class="layui-input-block">
                <label ><input title="&nbsp;首页"  name="exqrtype" type="radio" value="1" <?php  if($r['exqrtype']==1 || $r['exqrtype']==0) { ?>checked="checked"<?php  } ?>/> </label>
                &emsp;&emsp;<label ><input title="&nbsp;关注(小程序端无效) "  name="exqrtype" type="radio" value="2" <?php  if($r['exqrtype']==2) { ?>checked="checked"<?php  } ?>/></label>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商品二维码链接
            </label>
            <div class="layui-input-block">
                <label ><input title="&nbsp;京东页 "  name="goodsqrtype" type="radio" value="1" <?php  if($r['goodsqrtype']==1 || $r['goodsqrtype']==0) { ?>checked="checked"<?php  } ?>/></label>
                &emsp;<label ><input title="&nbsp;商品页"   name="goodsqrtype" type="radio" value="2" <?php  if($r['goodsqrtype']==2) { ?>checked="checked"<?php  } ?>/></label>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>是否展示商品详情
            </label>
            <div class="layui-input-block">
                <label ><input title="&nbsp;开启 "  name="isshow_detail" type="radio" value="1" <?php  if($r['isshow_detail']==1) { ?>checked="checked"<?php  } ?>/></label>
                &emsp;&emsp;<label ><input title="&nbsp;关闭&nbsp;&nbsp;(关闭后用户点击商品则直接去领券，小程序端无效)"  name="isshow_detail" type="radio" value="2" <?php  if($r['isshow_detail']==0) { ?>checked="checked"<?php  } ?>/></label>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div class="layui-form-item" style="display:none;">
                <label class="layui-form-label">会员终身制 :</label>
                    <div class="layui-input-block">
                        <td class="text-center" >
                             <div class="input-group " >
                                 <select type="text" name="isuse_parent">
                                    <option value="2" <?php  if($r['isuse_parent'] == 0 ) { ?> selected="selected" <?php  } ?>>开启</option>
                                    <option value="1"  <?php  if($r['isuse_parent'] == 1 ) { ?> selected="selected" <?php  } ?>>关闭</option> 
                                 </select> 
                            </div>
                        </td>
                        <div class="help-block">默认使用推荐人推广位，如果关闭则推广仅锁定上下级关系，下单依据推广位判定订单归属</div>
                    </div>
            </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <div class="layui-input-block">
                <input type="hidden" name="menu_name" value="分享设置"/>
                <input type="hidden" name="op" value="post"/>
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                    提交
                </button>
            </div>
        </div>
    </form>
</div>
</body>
</html>
<!--<script>-->
    <!--if(typeof $.fn.tooltip != 'function' || typeof $.fn.tab != 'function' || typeof $.fn.modal != 'function' || typeof $.fn.dropdown != 'function') {-->
        <!--require(['bootstrap']);-->
    <!--}-->
<!--</script>-->