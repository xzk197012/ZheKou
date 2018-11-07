<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">

            <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
                <div class="layui-form-item">
                    <label for="L_jduniacid" class="layui-form-label">
                        <span class="x-red">*</span>京东联盟ID
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_jduniacid" name="jduniacid" lay-verify="jduniacid"
                               autocomplete="off" class="layui-input" value="<?php  echo $data['jduniacid'];?>">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>京东联盟ID
                    </div>
                </div>
                <div class="layui-form-item">
                    <label for="L_jdpid" class="layui-form-label">
                        <span class="x-red">*</span>默认推广位
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_jdpid" name="jdpid" lay-verify="jdpid"
                               autocomplete="off" class="layui-input" value="<?php  echo $data['jdpid'];?>">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>京东默认推广位id
                    </div>
                </div>

              <!--   <div class="layui-form-item">
                    <label for="L_jdpid" class="layui-form-label">
                        <span class="x-red">*</span>京东联盟key
                    </label>
                    <div class="layui-input-inline">
                        <input type="text" id="L_jduniackey" name="jduniackey" lay-verify="jduniackey"
                               autocomplete="off" class="layui-input" value="<?php  echo $data['jduniackey'];?>">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>京东联盟key
                    </div>
                </div> -->
                <div class="layui-form-item">
                    <input type="hidden" name="op" value="post"/>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <label for="L_jdpid" class="layui-form-label">
                    </label>
                    <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                        提交
                    </button>
                </div>
                <!-- <div class="layui-form-item">
                    <button  type="button" id="switch1"  class="layui-btn" lay-filter="switch1" lay-submit="" onclick="switchcloud(1)">
                            切换1号接口
                    </button>
                    <button  type="button" id="switch1"  class="layui-btn" lay-filter="switch1" lay-submit="" onclick="switchcloud(2)">
                            切换2号接口
                    </button>
                    <button  type="button" id="switch1"  class="layui-btn" lay-filter="switch1" lay-submit="" onclick="switchcloud(3)">
                            切换3号接口
                    </button>
                </div> -->
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
            jduniacid: function(value){
                if(value.length <=0){
                    return '请输入京东联盟ID！';
                }
            },
            jdpid: function(value){
                if(value.length <=0){
                    return '京东默认推广位id！';
                }
            },
            // jduniackey:function(value){
            //     if(value.length <=0){
            //         return '京东联盟key！';
            //     }
            // }
        });
        
    });
    function switchcloud(id){
            var ajaxurl="<?php  echo webUrl('system/switchcloud',array('id'=>'_id_'))?>";
            ajaxurl=ajaxurl.replace("_id_",id);
            $.post(ajaxurl,function(res){
                var res=JSON.parse(res);
                if(res.status==1){
                    layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
                        location.href=location.href;
                    });
                }else{
                    layer.msg("操作失败");
                }
            });
        
        };
</script>