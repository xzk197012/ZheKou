<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">
            <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>关联公众号uniacid
                    </label>
                    <div class="layui-input-block">
                        <input type="text" name="wxapp_uniacid" lay-verify="wxapp_uniacid"
                               autocomplete="off" class="layui-input" value="<?php  echo $r['wxapp_uniacid'];?>">
                        <div class="layui-form-mid layui-word-aux">
                            <span class="x-red">*</span>关联公众号在系统中的uniacid，将公众号和小程序(可多个)加入微信开放平台，可实现用户数据统一。
                        </div>
                    </div>
                    <!--<label class="layui-form-label">-->
                    <!--</label>-->
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>小程序任务处理通知
                    </label>
                    <div class="layui-input-block">
                        <input type="text" name="notice_tplno_app" lay-verify="notice_tplno_app"
                               autocomplete="off" class="layui-input" value="<?php  echo $r['notice_tplno_app'];?>">
                    </div>
                    <!--<label class="layui-form-label">-->
                    <!--</label>-->
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>小程序平台模板消息编号AT0280,搜索“任务完成通知”
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>小程序消息URL
                    </label>
                    <div class="layui-input-block">
                        <input type="text"  id="wxurl"
                               autocomplete="off" class="layui-input layui-disabled" value="<?php echo $_W['siteroot']."app/index.php?i=".$_W["uniacid"]."&c=entry&a=wxapp&do=Servers&m=nets_haojk"?>">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>客服消息配置(请不要使用we7生成的通信URL)，填写到小程序：设置->开发设置->消息推送->URL(服务器地址)
                    </div>
                </div>
                <div class="layui-form-item" style="display: none;">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>默认客服消息
                    </label>
                    <div class="layui-input-block">
                        <textarea name="service_msg" lay-verify="service_msg" rows="8" style="height:150px"
                               autocomplete="off" class="layui-input"><?php  echo $r['service_msg'];?></textarea>
                    </div>
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>默认客服消息
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-input-block">
                        <input type="hidden" name="menu_name" value="小程序设置"/>
                        <input type="hidden" name="op" value="post"/>
                        <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                        <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                            提交
                        </button>
                        <button  type="button" onclick="copyurl()" class="layui-btn" >
                            复制小程序消息URL
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>
<script>

    var layer;
    layui.use(['laydate','form','layer'], function(){
        var laydate = layui.laydate,
            form = layui.form
            ,layer = layui.layer;
    });
    function copyurl() {
        var d = document.getElementById("wxurl");
        d.select();
        document.execCommand("Copy");
        layer.msg("复制成功！");
    }

</script>