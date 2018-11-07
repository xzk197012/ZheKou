<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">
    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <input type="hidden" name="id" value="<?php  echo $_GPC['id'];?>">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>会员
            </label>
            <div class="layui-input-inline">
                <?php  if(!empty($edit_level['avatar'])) { ?>
                <img src='<?php  echo tomedia($edit_level['avatar'])?>' style='width:30px;height:30px;padding:1px;border:1px solid #ccc' />
                <?php  } ?>
                <span style="font-size:11px">
                        <?php  echo $edit_level['nickname'];?>
                        [推荐人:<?php  echo get_fromnickname($edit_level['from_uid'])?>]</span>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>身份选择
            </label>
            <div class="layui-input-inline">
                <select type="text" name="type">
                    <option value="0" <?php  if($edit_level['type']=='0') { ?>selected='selected'<?php  } ?>>会员</option>
                    <option value="1" <?php  if($edit_level['type']=='1') { ?>selected='selected'<?php  } ?>>盟主</option>
                    <option value="2" <?php  if($edit_level['type']=='2') { ?>selected='selected'<?php  } ?>>合伙人</option>
                </select>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>等级选择
            </label>
            <div class="layui-input-inline">
                <select  type="text" name="level">
                    <option value="0" <?php  if($edit_level['level']=='0') { ?>selected='selected'<?php  } ?>>普通</option>
                    <option value="1" <?php  if($edit_level['level']=='1') { ?>selected='selected'<?php  } ?>>白银</option>
                    <option value="2" <?php  if($edit_level['level']=='2') { ?>selected='selected'<?php  } ?>>黄金</option>
                    <option value="3" <?php  if($edit_level['level']=='3') { ?>selected='selected'<?php  } ?>>铂金</option>
                    <option value="4" <?php  if($edit_level['level']=='4') { ?>selected='selected'<?php  } ?>>钻石</option>
                </select>
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>手动修改邀请人
            </label>
            <div class="layui-input-inline">
                <input type="text" name="invite" lay-verify="invite"
                       autocomplete="off" class="layui-input" value="<?php  echo $edit_level['from_uid'];?>">
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                会员ID/会员openid
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>手动修改合伙人
            </label>
            <div class="layui-input-inline">
                <input type="text" name="partner_uid" lay-verify="partner_uid"
                       autocomplete="off" class="layui-input" value="<?php  echo $edit_level['from_partner_uid'];?>">
            </div>
            <!--<label class="layui-form-label">-->
            <!--</label>-->
            <div class="layui-form-mid layui-word-aux">
                会员ID/会员openid
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <div class="layui-input-block">
                <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                <input type="hidden" name="op" value="post"/>
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


            //监听提交
            form.on('submit(add)', function(data){
                var loadii = layer.load();
                console.log(data);
                //发异步，把数据提交给php
                var ajaxurl="<?php  echo webUrl('member/member_editpost')?>";
                $.post(ajaxurl,data.field,function(res){
                    var res=JSON.parse(res);
                    layer.close(loadii);
                    if(res.status==1){
                        layer.alert(res.result.message, {icon: 6,closeBtn: 0},function () {
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
        });

</script>