<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <input type="hidden" name="skuId" value="<?php  echo $_GPC['skuId'];?>">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>自定义商品源
            </label>
            <div class="layui-input-inline4">
                <select  name="cname" id="cname" lay-verify="cname">
                    <option value ="" <?php  if($_GPC['cname']=='') { ?>selected="selected"<?php  } ?>>全部</option>
                    <?php  if(is_array($usecate)) { foreach($usecate as $c) { ?>
                    <option value ="<?php  echo $c['id'];?>" <?php  if($_GPC['cname']==$c["id"]) { ?>selected="selected"<?php  } ?>><?php  echo $c["name"];?></option>
                    <?php  } } ?>
                </select>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*必填</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                排序
            </label>
            <div class="layui-input-inline4">
                <input type="text" name="sort" lay-verify="sort" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"
                       autocomplete="off" class="layui-input" value="<?php  echo $data['sort'];?>">
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">数字越大越靠前</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <div class="layui-input-block">
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
<script>

    layui.use(['form','layer'], function(){
        $ = layui.jquery;
        var form = layui.form
            ,layer = layui.layer;

        //自定义验证规则
        form.verify({
            cname: function(value){
                if(value==''){
                    return '请选择分类！';
                }
            }
        });
        //监听提交
        form.on('submit(add)', function(data){
            var loadii = layer.load();
            console.log(data);
            //发异步，把数据提交给php
            var ajaxurl="<?php  echo webUrl('goodssource/sourcegoods_addpost')?>";
            $.post(ajaxurl,data.field,function(res){
                var res=JSON.parse(res);
                layer.close(loadii);
                if (res.status == 1) {
                    layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
//                        parent.location.href=parent.location.href;
                    });
                } else {
                    layer.msg(res.result.message);
                }
            });
            return false;
        });
    });

</script>