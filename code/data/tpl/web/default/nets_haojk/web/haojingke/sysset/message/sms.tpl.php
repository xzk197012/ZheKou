<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
    <body>
        <div class="x-body">
            <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
                <div class="layui-form-item">
                    <label class="layui-form-label">是否强制绑定手机号</label>
                    <div class="layui-input-block">
                        <input type="checkbox" name="isforce_mobile" title="启用" <?php  if($r["isforce_mobile"]==1) { ?> checked="" <?php  } ?>><div class="layui-unselect layui-form-checkbox layui-form-checked" lay-skin=""><i class="layui-icon"></i></div>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>服务商
                    </label>
                    <div class="layui-input-block">
                        <label ><input title="阿里大于" name="sms_type" type="radio" value="1" <?php  if($r['sms_type']==1) { ?> checked="checked"<?php  } ?>/>&nbsp; </label>
                        <span><a href="https://dayu.aliyun.com/" target="_blank">&emsp;<font style="color: red; font-size: 8px;">没有？立即申请</font></a></span>
                        <br>
                        <label ><input title="极速数据" name="sms_type" type="radio" value="2" <?php  if($r['sms_type']==2) { ?> checked="checked"<?php  } ?>/>&nbsp; </label>
                        <span><a href="http://www.jisuapi.com/" target="_blank">&emsp;<font style="color: red; font-size: 8px;">没有？立即申请</font></a></span>
                        <br>
                        <label ><input title="阿里短信" name="sms_type" type="radio" value="3" <?php  if($r['sms_type']==3) { ?> checked="checked"<?php  } ?>/>&nbsp; </label>
                        <span><a href="https://www.aliyun.com/" target="_blank">&emsp;<font style="color: red; font-size: 8px;">没有？立即申请</font></a></span>
                        <br>
                        <label ><input name="sms_type" title="关闭短信服务" type="radio" value="-1" <?php  if($r['sms_type']==-1) { ?> checked="checked"<?php  } ?>/>&nbsp; </label>
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>appid
                    </label>
                    <div class="layui-input-block">
                        <input type="text" name="dayu_appid" lay-verify="dayu_appid"
                               autocomplete="off" class="layui-input" value="<?php  echo $r['dayu_appid'];?>">
                    </div>
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-form-mid layui-word-aux">
                        请填写短信服务商提供的appid (<span class="x-red">极速短信请填写appkey</span>)
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>appkey
                    </label>
                    <div class="layui-input-block">
                        <input type="text" name="dayu_appkey" lay-verify="dayu_appkey"
                               autocomplete="off" class="layui-input" value="<?php  echo $r['dayu_appkey'];?>">
                    </div>
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-form-mid layui-word-aux">
                        请填写短信服务商提供的appkey (<span class="x-red">极速短信请填写appsecret</span>)
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>模板ID
                    </label>
                    <div class="layui-input-block">
                        <input type="text" name="dayu_smstplid" lay-verify="dayu_smstplid"
                               autocomplete="off" class="layui-input" value="<?php  echo $r['dayu_smstplid'];?>">
                    </div>
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>服务商提供的模板ID,例: 1234 (短信服务商提供的模板ID,没有则不填)
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>短信签名
                    </label>
                    <div class="layui-input-block">
                        <input type="text" name="dayu_smssign" lay-verify="dayu_smssign"
                               autocomplete="off" class="layui-input" value="<?php  echo $r['dayu_smssign'];?>">
                    </div>
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>请填写短信签名 (服务商审核成功的签名)
                    </div>
                </div>
                <div class="layui-form-item">
                    <label class="layui-form-label">
                        <span class="x-red">*</span>短信模板
                    </label>
                    <div class="layui-input-block">
                        <input type="text" name="sms_tpl" lay-verify="sms_tpl"
                               autocomplete="off" class="layui-input" value="<?php  echo $r['sms_tpl'];?>">
                    </div>
                    <label class="layui-form-label">
                    </label>
                    <div class="layui-form-mid layui-word-aux">
                        <span class="x-red">*</span>极速短信接口请填入申请时的短信模板内容 (如：您的手机验证码是@。本条信息无需回复)
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
                    </div>
                </div>
            </form>
        </div>
    </body>
</html>