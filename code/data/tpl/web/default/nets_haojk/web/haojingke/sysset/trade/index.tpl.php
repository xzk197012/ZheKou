<?php defined('IN_IA') or exit('Access Denied');?><!DOCTYPE html>
<html>
<?php (!empty($this) && $this instanceof WeModuleSite || 1) ? (include $this->template('haojingke/common/header', TEMPLATE_INCLUDEPATH)) : (include template('haojingke/common/header', TEMPLATE_INCLUDEPATH));?>

<body>
    <div class="x-body">
        <form action="" method="post" enctype="multipart/form-data" class="layui-form">
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
                <legend>补贴设置</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>补贴开关
                </label>
                <div class="layui-input-block">
                    <label>
                        <input title="关闭" name="isopen_subsidy" type="radio" value="2" <?php  if($r[ 'isopen_subsidy']==0) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <label>
                        <input title="开启" name="isopen_subsidy" type="radio" value="1" <?php  if($r[ 'isopen_subsidy']==1) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <span class="x-red">开启用户补贴后，用户提单可获得佣金比例的平台补贴</span>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>显示补贴
                </label>
                <div class="layui-input-block">
                    <label>
                        <input title="关闭" name="isshow_subsidy" type="radio" value="2" <?php  if($r[ 'isshow_subsidy']==0) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <label>
                        <input title="开启" name="isshow_subsidy" type="radio" value="1" <?php  if($r[ 'isshow_subsidy']==1) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <span class="x-red">平台是否显示补贴，关闭后普通会员不可见</span>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>显示代理补贴
                </label>
                <div class="layui-input-block">
                    <label>
                        <input title="关闭" name="isshow_subsidy_dl" type="radio" value="2" <?php  if($r[ 'isshow_subsidy_dl']==0) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <label>
                        <input title="开启" name="isshow_subsidy_dl" type="radio" value="1" <?php  if($r[ 'isshow_subsidy_dl']==1) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <span class="x-red">平台是否显示补贴，关闭后代理不可见</span>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>会员补贴名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="member_subsidename" lay-verify="member_subsidename" autocomplete="off" class="layui-input" value="<?php  echo $r['member_subsidename'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    普通会员补贴名称，默认 (
                    <span class="x-red">'约补'</span>)
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>盟主补贴名称
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="leader_subsidename" lay-verify="leader_subsidename" autocomplete="off" class="layui-input" value="<?php  echo $r['leader_subsidename'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    盟主补贴名称，默认(
                    <span class="x-red">'约赚' </span>)
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>补贴比例
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="subsidy" lay-verify="subsidy" autocomplete="off" class="layui-input" value="<?php  echo $r['subsidy'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">%</span> 给用户补贴比例
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>积分充值
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="credit1_to_credit2" onkeyup="this.value=this.value.replace(/\D/g,'')" onafterpaste="this.value=this.value.replace(/\D/g,'')"
                        lay-verify="credit1_to_credit2" autocomplete="off" class="layui-input" value="<?php  echo $r['credit1_to_credit2'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">积分充值 : "1元等于多少积分"</span>
                </div>
            </div>
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
                <legend>申请盟主设置</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>申请盟主
                </label>
                <div class="layui-input-block">
                    <label>
                        <input title="自动通过" name="applyleader" type="radio" value="2" <?php  if($r[ 'applyleader']==0) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <label>
                        <input title="付费通过" name="applyleader" type="radio" value="1" <?php  if($r[ 'applyleader']==1) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <span class="x-red">申请盟主:自动通过或付费</span>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>付费返佣
                </label>
                <div class="layui-input-block">
                    <label>
                        <input title="关闭" name="isopen_paycommission" type="radio" value="2" <?php  if($r[ 'isopen_paycommission']==0) { ?>
                            checked="checked" <?php  } ?>/>&nbsp; </label>
                    <label>
                        <input title="开启" name="isopen_paycommission" type="radio" value="1" <?php  if($r[ 'isopen_paycommission']==1) { ?>
                            checked="checked" <?php  } ?>/>&nbsp; </label>
                    <span class="x-red">开启后，会员申请盟主付费上级会员参与分拥</span>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>申请盟主费用
                </label>
                <div class="layui-input-inline">
                    <input type="text" oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"
                        onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"
                        onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"
                        name="applyleader_fee" lay-verify="applyleader_fee" autocomplete="off" class="layui-input" value="<?php  echo $r['applyleader_fee'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">元</span> 申请盟主费用
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>申请说明
                </label>
                <div class="layui-input-block">
                    <textarea placeholder="请输入申请说明" class="layui-textarea" name="applyleader_remark"><?php  echo $r['applyleader_remark'];?></textarea> 申请盟主描述
                </div>
            </div>
            <fieldset class="layui-elem-field layui-field-title" style="margin-top: 50px;">
                <legend>合伙人设置</legend>
            </fieldset>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>合伙人
                </label>
                <div class="layui-input-block">
                    <label>
                        <input title="关闭" name="isopen_partner" type="radio" value="2" <?php  if($r[ 'isopen_partner']==0) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <label>
                        <input title="开启" name="isopen_partner" type="radio" value="1" <?php  if($r[ 'isopen_partner']==1) { ?> checked="checked"
                            <?php  } ?>/>&nbsp; </label>
                    <span class="x-red">开启后，会员可申请合伙人</span>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>合伙人费用
                </label>
                <div class="layui-input-inline">
                    <input type="text" oninput="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"
                        onkeyup="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"
                        onafterpaste="this.value=this.value.replace(/^(\-)*(\d+)\.(\d\d).*$/,'$1$2.$3');if(isNaN(value))execCommand('undo');"
                        name="partner_fee" lay-verify="partner_fee" autocomplete="off" class="layui-input" value="<?php  echo $r['partner_fee'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">元</span> 申请合伙人费用
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                    <span class="x-red">*</span>合伙人佣金
                </label>
                <div class="layui-input-inline">
                    <input type="text" name="partner_commission" lay-verify="partner_commission" autocomplete="off" class="layui-input" value="<?php  echo $r['partner_commission'];?>">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">%</span> 给合伙人的佣金比例
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">
                </label>
                <div class="layui-input-block">
                    <input type="hidden" name="token" value="<?php  echo $_W['token'];?>" />
                    <button type="submit" class="layui-btn" lay-filter="add" lay-submit="">
                        提交
                    </button>
                </div>
            </div>
        </form>
    </div>
</body>

</html>