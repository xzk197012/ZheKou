<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
                        <legend>领券方式设置</legend>
                    </fieldset>    
            <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
                <div class="layui-form-item">
                    <label for="L_jduniacid" class="layui-form-label">
                        小程序AppID
                    </label>
                    <div class="layui-input-inline">
                        <input type="text"  name="hjkappid" 
                               autocomplete="off" class="layui-input" value="<?php  echo $r['hjkappid'];?>">
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>好京客小程序appid,请向服务商索取！</a>
                    </div>
                </div>
                
                <div class="layui-form-item">
                    <label for="L_jdpid" class="layui-form-label">
                        <span class="x-red">*</span>领劵方式
                    </label>
                   <div class="layui-input-inline">
                        <select name="couptype" id="couptype">
                            <option value ="0" <?php  if($r['couptype']=='0') { ?>selected="selected"<?php  } ?>>默认客服消息</option>
                            <option value ="1" <?php  if($r['couptype']=='1') { ?>selected="selected"<?php  } ?>>直接跳转</option>
                        </select>
                    </div>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>领劵方式,直接跳转京东领券页请先在公众号内关联好京客小程序。<br/>
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