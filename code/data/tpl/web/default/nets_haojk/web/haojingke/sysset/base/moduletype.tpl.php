<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                <legend>功能模块设置</legend>
            </fieldset>    
            <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
                <div class="layui-form-item">
                        <label class="layui-form-label">京东</label>
                        <div class="layui-input-block">
                                <input type="checkbox" name="jdmodule_status" title="启用" <?php  if($r["jdmodule_status"]==1) { ?> checked="" <?php  } ?>><div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin=""><span>阅读</span><i class="layui-icon"></i></div>
                        </div>
                </div>
                <div class="layui-form-item">
                        <label class="layui-form-label">拼多多</label>
                        
                        <div class="layui-input-block">
                                <input type="checkbox" name="pddmodule_status" title="启用" <?php  if($r["pddmodule_status"]==1) { ?> checked="" <?php  } ?>><div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin=""><span>阅读</span><i class="layui-icon"></i></div>
                        </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">蘑菇街</label>

                    <div class="layui-input-block">
                        <input type="checkbox" name="mgjmodule_status" title="启用" <?php  if($r["mgjmodule_status"]==1) { ?> checked="" <?php  } ?>><div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin=""><span>阅读</span><i class="layui-icon"></i></div>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <input type="hidden" name="op" value="post"/>
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <label for="L_jdpid" class="layui-form-label">
                    </label>
                    <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                        提交
                    </button>
                </div>
            </form>
            
        </div>
    </body>
</html>