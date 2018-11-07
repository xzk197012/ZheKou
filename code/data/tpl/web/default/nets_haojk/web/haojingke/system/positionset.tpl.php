<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">

    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <div class="layui-form-item">
            <label for="L_jd_key" class="layui-form-label">
                <span class="x-red">*</span>京东授权KEY
            </label>
            <div class="layui-input-inline">
                <input type="text" id="L_jd_key" name="jd_key" lay-verify="jd_key"
                       autocomplete="off" class="layui-input" value="<?php  echo $data['jd_key'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">注意这个Key只有7天有效期</span>京东联盟后台-CPS联盟-推广管理-API权限管理-领取授权
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_position_prefix" class="layui-form-label">
                <span class="x-red">*</span>推广位名称前缀
            </label>
            <div class="layui-input-inline">
                <input type="text" id="L_position_prefix" name="position_prefix" lay-verify="position_prefix"
                       autocomplete="off" class="layui-input" value="<?php  echo $data['position_prefix'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*每次新建必须换前缀</span>
            </div>
        </div>

        <div class="layui-form-item">
            <input type="hidden" name="op" value="post"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <label for="L_jd_key" class="layui-form-label">
            </label>
            <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                提交
            </button>

        </div>
    </form>
    <div class="layui-form-item">
        <?php  if(empty($r['wxapp_uniacid'])) { ?>
        <button class="layui-btn " onclick="position_new()"> 新建50个推广位</button>
        <?php  } ?>
        <?php  if($_W['acctype']=="小程序") { ?>
        <button class="layui-btn " onclick="position_sync()"> 从公众号同步</button>
        <?php  } ?>
    </div>
    <img src="http://www.haojingke.com/images/jdkey.png"/>
</div>
</body>
</html>

<script>
    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //自定义验证规则
        form.verify({
            haojk_key: function(value){
                if(value.length <=0){
                    return '请输入好京客APIKEY！';
                }
            },
            jd_key: function(value){
                if(value.length <=0){
                    return '请输入京东授权KEY！';
                }
            },
            position_prefix: function(value){
                if(value.length <=0){
                    return '请输入推广位名称前缀！';
                }
            }
        });
    });
    /*新建50个推广位*/
    function position_new(){
        layer.confirm('请确保提交京东新建前修改不同推广位前缀，你可以刷新此页面终止或者重试,现在开始新建吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('system/position_new')?>";
            $.post(ajaxurl,function(res){
                var res=JSON.parse(res);
                layer.close(loadii);
                if(res.status==1){
                    layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
                        location.href=location.href;
                    });
                }else{
                    layer.msg("操作失败");
                }
            });
        });
    }
    /*公众号同步推广位到小程序*/
    function position_sync(){
        layer.confirm('请确认开启了统一会员，此操作会将推广位同步至小程序,现在同步吗？',function(index){
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('system/position_sync')?>";
            $.post(ajaxurl,function(res){
                var res=JSON.parse(res);
                layer.close(loadii);
                if(res.status==1){
                    layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
                        location.href=location.href;
                    });
                }else{
                    layer.msg("操作失败");
                }
            });
        });
    }
</script>