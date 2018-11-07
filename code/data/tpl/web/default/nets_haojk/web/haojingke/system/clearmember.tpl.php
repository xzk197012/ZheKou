<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">
            <blockquote class="layui-elem-quote">一键清理无效会员</blockquote>
            <div class="layui-row">
                <form id="initform" action="" method="post"  enctype="multipart/form-data" class="layui-form layui-col-md12 x-so layui-form-pane">
                    <input type="hidden" name="op" value="post"/>
                    <button class="layui-btn"  onclick="return firm()" >马上清理</button>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                </form>
            </div>
            <blockquote class="layui-elem-quote">
                <br/>
                PS&nbsp;:&nbsp;一键清理无效会员，将会删除当前公众号无效的会员数据！<br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="x-red">友情提醒：操作前请备份数据库</span>,有粉丝数据而没有会员数据！！！
            </blockquote>
            
        </div>
    </body>
</html>

<script>
    function firm() {
        var layer = layui.layer;
        layer.confirm('警告：一键清理无效会员不可恢复，请在操作前备份数据库，是否确认执行此操作？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            layer.confirm('警告：清理后不可恢复,是否确认执行此操作？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $("#initform").submit();
            }, function(){
//              return false;
            });
        }, function(){
//              return false;
        });
        return false;
    }
</script>