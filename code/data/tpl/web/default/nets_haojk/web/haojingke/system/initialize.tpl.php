<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">
            <blockquote class="layui-elem-quote">初始化系统配置</blockquote>
            <div class="layui-row">
                <form id="initform" action="" method="post"  enctype="multipart/form-data" class="layui-form layui-col-md12 x-so layui-form-pane">
                    <input type="hidden" name="op" value="post"/>
                    <button class="layui-btn"  onclick="return firm()" >一键初始化</button>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                </form>
            </div>
            <blockquote class="layui-elem-quote">
                <br/>
                PS&nbsp;:&nbsp;初始化系统配置，将会使用系统默认的菜单、图片、会员等级等初始配置，方便新用户参考设置！<br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="x-red">友情提醒：操作前请备份数据库,已设置过的请谨慎使用，将会代替原有配置并不可恢复！</span>
            </blockquote>
            
        </div>
    </body>
</html>

<script>
    function firm() {
        var layer = layui.layer;
        layer.confirm('警告：初始化后将会还原系统初始配置，是否确认执行此操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $("#initform").submit();
        }, function(){
//              return false;
        });
        return false;
    }
</script>