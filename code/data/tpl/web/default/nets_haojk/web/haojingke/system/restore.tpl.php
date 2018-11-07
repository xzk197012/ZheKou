<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">

            <blockquote class="layui-elem-quote">一键修复数据库</blockquote>
            <div class="layui-row">
                <form id="restoreform" action="" method="post"  enctype="multipart/form-data" class="layui-form layui-col-md12 x-so layui-form-pane">
                    <input type="hidden" name="op" value="post"/>
                    <button class="layui-btn"  onclick="return firm()" >一键修复数据库</button>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                </form>
            </div>
            <blockquote class="layui-elem-quote">
                <br/>
                PS&nbsp;:&nbsp;一键修复数据库，<span class="x-red">友情提醒：同步数据库到当前版本。</span>
            </blockquote>
            
        </div>
    </body>
</html>

<script>
    function firm() {
        var layer = layui.layer;
        layer.confirm('一键修复数据库，是否确认执行此操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $("#restoreform").submit();
        }, function(){
//              return false;
        });
        return false;
    }
</script>