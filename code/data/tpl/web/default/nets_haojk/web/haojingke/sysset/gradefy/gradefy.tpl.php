<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>

<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>
<body>
<div class="x-body">


    <form action="" method="post"  enctype="multipart/form-data" class="layui-form">
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>等级设置
            </label>
            <div class="layui-input-inline">
                <select  type="text" name="name" class="valid" disabled  >
                    <option value="0" <?php  if($leve['name']=='0') { ?>selected='selected'<?php  } ?>>普通</option>
                    <option value="1" <?php  if($leve['name']=='1') { ?>selected='selected'<?php  } ?>>白银</option>
                    <option value="2" <?php  if($leve['name']=='2') { ?>selected='selected'<?php  } ?>>黄金</option>
                    <option value="3" <?php  if($leve['name']=='3') { ?>selected='selected'<?php  } ?>>铂金</option>
                    <option value="4" <?php  if($leve['name']=='4') { ?>selected='selected'<?php  } ?>>钻石</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">名称描述</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="gradename"    class="layui-input"  <?php  if($leve['gradename']==null) { ?>value=""<?php  } else { ?>value="<?php  echo $leve['gradename'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>会员等级设置
            </div>
        </div>
        <div class="layui-form-item">
            <label  class="layui-form-label">
                <span class="x-red">*</span>身份选择
            </label>
            <div class="layui-input-inline">
                <select type="text" name="type"  class="valid"  disabled >
                    <option value="0" <?php  if($leve['type']=='0') { ?>selected='selected'<?php  } ?>>会员</option>
                    <option value="1" <?php  if($leve['type']=='1') { ?>selected='selected'<?php  } ?>>盟主</option>
                    <option value="2" <?php  if($leve['type']=='2') { ?>selected='selected'<?php  } ?>>合伙人</option>
                </select>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">名称描述</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="identityname"   class="layui-input"  <?php  if($leve['identityname']==null) { ?>value=""<?php  } else { ?>value="<?php  echo $leve['identityname'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>会员身份选择
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>升级积分
            </label>
            <div class="layui-input-inline">
                <input type="text" name="recharge_get"  oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  value="<?php  echo $leve['recharge_get'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">分，签到积分</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="sign_credit1"  oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  <?php  if($leve['sign_credit1']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['sign_credit1'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">分</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>订单积分
            </label>
            <div class="layui-input-inline">
                <input type="text" name="order_credit1"   oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  value="<?php  echo $leve['order_credit1'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">分，推荐(成为)盟主积分</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="releader_credit1"  oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  <?php  if($leve['releader_credit1']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['releader_credit1'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">分，推荐好友积分</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="refriend_credit1"   oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');" class="layui-input"  <?php  if($leve['refriend_credit1']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['refriend_credit1'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">分</span>
            </div>
        </div>
        <?php  if($leve['type']!='0') { ?>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>一级佣金比例
            </label>
            <div class="layui-input-inline">
                <input type="text" name="myteam_credit2"    oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');" class="layui-input"  value="<?php  echo $leve['myteam_credit2'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%,前台显示</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="show_myteam_credit2"  oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  <?php  if($leve['show_myteam_credit2']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['show_myteam_credit2'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>二级佣金比例
            </label>
            <div class="layui-input-inline">
                <input type="text" name="myleader1_credit2"   oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  value="<?php  echo $leve['myleader1_credit2'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%,前台显示</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="show_myleader1_credit2"   oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');" class="layui-input"  <?php  if($leve['show_myleader1_credit2']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['show_myleader1_credit2'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>三级佣金比例
            </label>
            <div class="layui-input-inline">
                <input type="text" name="myleader2_credit2"   oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  value="<?php  echo $leve['myleader2_credit2'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%,前台显示</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="show_myleader2_credit2"  oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  <?php  if($leve['show_myleader2_credit2']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['show_myleader2_credit2'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>助力最高佣金
            </label>
            <div class="layui-input-inline">
                <input type="text" name="max_credit2"   oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  value="<?php  echo $leve['max_credit2'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%,前台显示</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="show_max_credit2"  oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  <?php  if($leve['show_max_credit2']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['show_max_credit2'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">%</span>
            </div>
        </div>
        <?php  } ?>
        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="x-red">*</span>佣金结算
            </label>
            <div class="layui-input-inline">
                <input type="text" name="commission_formonth"   oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  value="<?php  echo $leve['commission_formonth'];?>">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">次,前台显示</span>
            </div>
            <div class="layui-input-inline">
                <input type="text" name="show_commission_formonth"  oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"   onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"  class="layui-input"  <?php  if($leve['show_commission_formonth']==null) { ?>value="0"<?php  } else { ?>value="<?php  echo $leve['show_commission_formonth'];?>"<?php  } ?>>
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="input-group-addon">次</span>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">
            </label>
            <input type="hidden" name="id" value="<?php  echo $leve['id'];?>"/>
            <input type="hidden" name="op" value="post"/>
            <input type="hidden" name="ispost" value="1"/>
            <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
            <button  class="layui-btn" lay-filter="add" lay-submit="">
                提交
            </button>
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
            var ajaxurl="<?php  echo webUrl('sysset/gradefy/post').'&id='.$_GPC['id']?>";
            $.post(ajaxurl,data.field,function(res){
                var res=JSON.parse(res);
                layer.close(loadii);
                if(res.status==1){
                    layer.alert("操作成功", {icon: 6,closeBtn: 0},function () {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                        parent.location.href=parent.location.href;
                    });
                }else{
                    layer.msg("操作失败");
                }
            });
            return false;
        });
    });
</script>