<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Message_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

        if ($_W['action']=='sysset.message.index'||$_W['action']=='sysset.message')
            $this->index();
        elseif ($_W['action']=='sysset.message.sms')
            $this->sms_set();
	}
	public function index()
	{
		global $_W;
		global $_GPC;

        $uniacid=$_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['notice_tplno'] = $_GPC['notice_tplno'];
            $set['notice_newlevel'] = $_GPC['notice_newlevel'];
            $set['notice_applycash'] = $_GPC['notice_applycash'];
            $set['notice_auditingcash'] = $_GPC['notice_auditingcash'];
            $set['owner_openid'] = $_GPC['owner_openid'];
            $set['owner_openid_app'] = $_GPC['owner_openid_app'];
            $set['fllow_msg'] = $_GPC['fllow_msg'];
            $set['created_at'] = time();
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/message/index'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/message/index'), 'warning');

        }
        include $this->template('haojingke/sysset/message/index');
	}

    public function sms_set()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['isforce_mobile']  = $_GPC['isforce_mobile']=="on"?1:0;
            $set['sms_type']  = $_GPC['sms_type'];
            $set['sms_tpl']  = $_GPC['sms_tpl'];
            $set['dayu_appid']  = $_GPC['dayu_appid'];
            $set['dayu_appkey']  = $_GPC['dayu_appkey'];
            $set['dayu_smstplid']  = $_GPC['dayu_smstplid'];
            $set['dayu_smssign']  = $_GPC['dayu_smssign'];
            $set['created_at'] = time();
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/message/sms'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/message/sms'), 'warning');
        }

        include $this->template('haojingke/sysset/message/sms');
    }
}
?>