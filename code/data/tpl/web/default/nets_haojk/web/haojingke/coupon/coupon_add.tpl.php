<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<link href="./resource/css/bootstrap.min.css?v=20180110" rel="stylesheet">
<link href="./resource/css/common.css?v=20170719" rel="stylesheet">
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<script>var require = { urlArgs: 'v=201180110' };</script>
<script type="text/javascript" src="./resource/js/lib/jquery-1.11.1.min.js"></script>
<script type="text/javascript" src="./resource/js/lib/bootstrap.min.js"></script>
<script type="text/javascript" src="./resource/js/app/util.js?v=20180110"></script>
<script type="text/javascript" src="./resource/js/app/common.min.js?v=20170719"></script>
<script type="text/javascript" src="./resource/js/require.js?v=20180110"></script>
<script type="text/javascript" src="<?php echo NETS_HAOJIK_WEB_STYLE;?>js/jquery.nestable.js"></script>
<body>
<div class="x-body">
    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商品skuId
            </label>
            <div class="layui-input-inline4">
                <input type="text" name="skuId" lay-verify="skuId"
                       autocomplete="off" class="layui-input" value="<?php  echo $data['skuId'];?>">
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*必填，复制商品链接或手动输入商品skuId</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>商品分类
            </label>
            <div class="layui-input-inline4">
                <select name="cname" id="cname" lay-verify="cname">
                    <option value ="" <?php  if($_GPC['cname']=='') { ?>selected="selected"<?php  } ?>>请选择分类</option>
                    <?php  if(is_array($cate)) { foreach($cate as $c) { ?>
                    <option value ="<?php  echo $c['name'];?>" <?php  if($_GPC['cname']==$c["name"]) { ?>selected="selected"<?php  } ?>><?php  echo $c["name"];?></option>
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
                <span class="x-red">*</span>优惠券地址
            </label>
            <div class="layui-input-block">
                <input type="text" name="couponUrl" id="couponUrl" lay-verify="couponUrl"
                       autocomplete="off" class="layui-input" value="<?php  echo $data['couponUrl'];?>">
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*必填，格式必须为：http://coupon.m.jd.com/coupons/show.action?key=49d287d6a46b400c8c02c6358aa0409e&roleId=10088564&to=songqiao.jd.com</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                商品名称
            </label>
            <div class="layui-input-inline4">
                <input type="text" name="skuName" id="skuName"
                       autocomplete="off" class="layui-input layui-disabled" value="<?php  echo $r['skuName'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                请填写商品名称，如为空则自动获取京东商品标题
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                导购信息
            </label>
            <div class="layui-input-block">
                <textarea placeholder="请输入导购信息" id="skuDesc" class="layui-textarea" name="skuDesc"><?php  echo $data['skuDesc'];?></textarea>
            </div>
            <label class="layui-form-label">
            </label>
            <div class="layui-form-mid layui-word-aux">
                请填写商品名称，如为空则自动获取京东商品标题设置成导购信息
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                商品图片
            </label>
            <div class="layui-input-inline4" id="picUrl">
                <?php  echo tpl_form_field_image('picUrl')?>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>设置商品图片,建议800*800，如不上传则使用默认图片
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <div class="layui-input-block">
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <button  type="button" class="layui-btn layui-btn-normal" lay-filter="checkadd" lay-submit="">
                    校验优惠券
                </button>
                <button  type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                    发布
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
            skuId: function(value){
                if(value.length <=0){
                    return '请输入商品ID！';
                }
            },
            cname: function(value){
                if(value.length <=0){
                    return '请选择分类！';
                }
            },
            couponUrl: function(value){
                if(value.length <=0){
                    return '请输入优惠券地址！';
                }
            }
        });
        //监听提交
        form.on('submit(add)', function(data){
            var loadii = layer.load();
            console.log(data);
            //发异步，把数据提交给php
            var ajaxurl="<?php  echo webUrl('coupon/coupon_addpost')?>";
            $.post(ajaxurl,data.field,function(res){
                var res=JSON.parse(res);
                layer.close(loadii);
                if (res.status_code == 200) {
                    layer.alert(res.message+"请等待审核！", {icon: 6,closeBtn: 0},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                        parent.location.href=parent.location.href;
                    });
                } else {
                    layer.msg(res.message);
                }
            });
            return false;
        });
        //监听效验
        form.on('submit(checkadd)', function(data){
            var loadii = layer.load();
            console.log(data);
            //发异步，把数据提交给php
            var ajaxurl="<?php  echo webUrl('coupon/coupon_checkpost')?>";
            $.post(ajaxurl,data.field,function(res){
                var res=JSON.parse(res);
                layer.close(loadii);
                console.log(res);
                if (res.status_code == 200) {
                    var goods=res.goodsinfo;

                    var goodcoupon=res.couponinfo;
                    if(goods!=""){
                        console.log("商品名称"+goods.skuName);
                        $("#skuId").val(goods.skuId);
                        $("#couponUrl").val(goodcoupon.link);
                        $("#skuName").val(goods.skuName);
                        $("#skuDesc").val(goods.skuDesc);
                        $("#picUrl").find("input").eq(0).val(goods.skuDesc);
                        $("#picUrl").find("img").eq(0).attr("src",goods.picUrl);
                    }
                } else {
                    //关闭当前frame
                    parent.layer.close(index);
                    parent.location.href=parent.location.href;
                    layer.msg(res.message);
                }
            });
            return false;
        });
    });

</script>