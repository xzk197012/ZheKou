<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>最小提现
            </label>
            <div class="layui-input-inline">
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" name="mincash" lay-verify="mincash"
                       autocomplete="off" class="layui-input" value="<?php  echo $mincash;?>">
            </div>
            <div class="layui-form-mid layui-word-aux"><span class="x-red">元</span>  设置最小提现额度
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>提现手续费
            </label>
            <div class="layui-input-inline">
                <input type="text" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')" name="rate" lay-verify="rate"
                       autocomplete="off" class="layui-input" value="<?php  echo $rate;?>">
            </div>
            <div class="layui-form-mid layui-word-aux"><span class="x-red">%</span>  设置提现手续费
            </div>
        </div>
        <!--<div class="layui-form-item">-->
            <!--<label class="layui-form-label">-->
                <!--<span class="x-red">*</span>AppID-->
            <!--</label>-->
            <!--<div class="layui-input-block">-->
                <!--<input type="text" name="wxappid" lay-verify="wxappid"-->
                       <!--autocomplete="off" class="layui-input" value="<?php  echo $r['wxappid'];?>">-->
            <!--</div>-->
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <!--<div class="layui-form-mid layui-word-aux">-->
                <!--<span class="x-red">*AppID</span>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="layui-form-item">-->
            <!--<label class="layui-form-label">-->
                <!--<span class="x-red">*</span>AppKey-->
            <!--</label>-->
            <!--<div class="layui-input-block">-->
                <!--<input type="text" name="wxkey" lay-verify="wxkey"-->
                       <!--autocomplete="off" class="layui-input" value="<?php  echo $r['wxkey'];?>">-->
            <!--</div>-->
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <!--<div class="layui-form-mid layui-word-aux">-->
                <!--<span class="x-red">*Key密钥</span>-->
            <!--</div>-->
        <!--</div>-->
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>提现方式
            </label>
            <div class="layui-input-inline">
                <select type="text" name="cashtype">
                    <option value="5" <?php  if($r['cashtype'] == 5 ) { ?> selected="selected" <?php  } ?>> 全部</option>
                    <option value="1" <?php  if($r['cashtype'] == 1 ) { ?> selected="selected" <?php  } ?>> 微信</option>
                    <option value="2"  <?php  if($r['cashtype'] == 2 ) { ?> selected="selected" <?php  } ?>> 支付宝</option>
                </select>
                <span class="x-red">设置提现方式</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商户ID
            </label>
            <div class="layui-input-inline">
                <input type="text"  name="mchid" lay-verify="mchid"
                       autocomplete="off" class="layui-input" value="<?php  echo $r['mchid'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">微信支付的商户mchid ，用来给会员提现打款用</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商户支付证书
            </label>
            <div class="layui-input-block">
                <textarea placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入" class="layui-textarea" name="cert"></textarea>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
                从商户平台上下载支付证书, 解压并取得其中的 <span class="x-red">apiclient_cert.pem</span> 用记事本打开并复制文件内容, 填至此处
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>支付证书私钥
            </label>
            <div class="layui-input-block">
                <textarea placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入" class="layui-textarea" name="key"></textarea>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
                从商户平台上下载支付证书, 解压并取得其中的 <span class="x-red">apiclient_key.pem</span> 用记事本打开并复制文件内容, 填至此处
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>支付根证书
            </label>
            <div class="layui-input-block">
                <textarea placeholder="为保证安全性, 不显示证书内容. 若要修改, 请直接输入" class="layui-textarea" name="ca"></textarea>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
                从商户平台上下载支付证书, 解压并取得其中的 <span class="x-red">rootca.pem</span> 用记事本打开并复制文件内容, 填至此处
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>支付宝商家转账到支付宝账户APPID
            </label>
            <div class="layui-input-inline">
                <input type="text"  name="alipay_appid" lay-verify="alipay_appid"
                       autocomplete="off" class="layui-input" value="<?php  echo $r['alipay_appid'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">转账到支付宝账户，用来给会员支付宝提现打款用</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>支付宝开放平台RSA(SHA256)私钥
            </label>
            <div class="layui-input-block">
                <textarea placeholder="为保证安全性, 不显示密钥内容. 若要修改, 请直接输入" class="layui-textarea" name="alipay_privatekey"></textarea>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
                请使用<span class="x-red">支付宝密钥生成工具</span>生成后用记事本打开并复制私钥内容填至此处
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
                <button  type="button" class="layui-btn layui-btn-normal" onclick="payment_test()"  >
                    微信提现测试
                </button>
                <button  type="button" class="layui-btn layui-btn-normal" onclick="alipaypayment_test()"  >
                    支付宝提现测试
                </button>
            </div>
        </div>
    </form>
</div>
</body>
</html>

<script>
    /*测试提现*/
    function payment_test(){
        //prompt层
        layer.prompt({title: '请输入用户的openid！', formType: 0}, function(openid, index){
            if(openid==''){
                layer.msg("请输入用户的openid");
                return false;
            }
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/trade/payment_test').'&openid='?>"+openid;
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
            layer.close(index);
        });
    }
    /*支付宝测试提现*/
    function alipaypayment_test(){
        //prompt层
        layer.prompt({title: '请输入用户的支付宝账号！', formType: 0}, function(openid, index){
            if(openid==''){
                layer.msg("请输入用户的支付宝账号");
                return false;
            }
            var loadii = layer.load();
            var ajaxurl="<?php  echo webUrl('sysset/trade/alipaypayment_test').'&openid='?>"+openid;
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
            layer.close(index);
        });
    }

</script>