<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
class Set_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;
		if ($_W['action']=='pinduoduo.set')
            $this->index();
        if($_W['action']=='pinduoduo.set.buildset'){
            $this->buildpid();
        }
        if($_W['action']=='pinduoduo.set.position_switch'){
            $this->position_switch();
        }
	}
	//京盟设置
	public function index()
	{
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $data['mobile'] = $r['mobile'];
        $data['couptype'] = $r['couptype'];
        $data['default_pid'] = $r['default_pid'];

        
        $r1 = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        
        if ($_W['ispost']==1) {
            $set['mobile']  = $_GPC['mobile'];
            $set['couptype']  = $_GPC['couptype'];
            $set['default_pid']  = $_GPC['default_pid'];
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_pdd_global",$set);
            }else{
                $res = pdo_update("nets_hjk_pdd_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            $set1['hjkappid']  = $_GPC['hjkappid'];
            if (empty($r1)) {
                $set1['uniacid'] = $_W['uniacid'];
                $set1['created_at'] = time();
                $res1 = pdo_insert("nets_hjk_global",$set1);
            }else{
                $res1 = pdo_update("nets_hjk_global",$set1,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('pinduoduo/set'), 'success');
//            show_message('操作成功！',webUrl('system/index'), 'success');
        }
        include $this->template('haojingke/pinduoduo/set');
    }
    public function buildpid(){
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        if(empty($r['mobile'])){
            show_message('请先保存联盟手机号',webUrl('pinduoduo/set'), 'error');
        }
        $set['default_pid'] = getpdd_Pidcreate();
        if(empty($set['default_pid'])){
            show_message('请先保存正确的联盟手机号后在生成',webUrl('pinduoduo/set'), 'error');
        }
        if (empty($r)) {
            $set['created_at'] = time();
            $res = pdo_insert("nets_hjk_pdd_global",$set);
        }else{
            $res = pdo_update("nets_hjk_pdd_global",$set,array('uniacid'=>$_W['uniacid']));
        }
        if($res)
            show_message('生成成功',webUrl('pinduoduo/set'), 'success');
        
        include $this->template('haojingke/pinduoduo/set');
    }

    public function position_switch()
    {
        global $_W;
        global $_GPC;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        if(empty($r['mobile'])){
            show_message('请先保存联盟手机号',webUrl('pinduoduo/set'), 'error');
        }
        $set['default_pid'] = getpdd_Pidcreate();
        $res = pdo_update("nets_hjk_pdd_global",$set,array('uniacid'=>$_W['uniacid']));

        $members = pdo_fetchall("select * from ".tablename("nets_hjk_members")." where (type=1 or type=2) and uniacid=:uniacid", array('uniacid'=>$_W['uniacid']));
        foreach($members AS $m) {
            $setm["pdd_bitno"] = getpdd_Pidcreate();
            $setm["updated_at"] = time();
            if (!empty($jduniacid)) {
                $p1["jduniacid"] = $jduniacid;
            }
            $res = pdo_update("nets_hjk_members",$setm,array('id'=>$m["id"]));
        }
        show_json(1,"操作成功");
    }
}
?>