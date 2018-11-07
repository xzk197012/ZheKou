<?php
if (!(defined('IN_IA'))) 
{
    exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.mgj.func.php';
class Set_NetsHaojkPage extends WebPage
{
    public function main() 
    {
        global $_GPC;
        global $_W;
        if ($_W['action']=='mogujie.set')           
            $this->index();
    }
 
	//好京客API key 
	public function index()
	{
        
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " . tablename('nets_hjk_global') . " WHERE uniacid =:uniacid", array(':uniacid' => $_W['uniacid']));
        
        $data['jduniackey'] = $r['jduniackey'];
        
        if ($_W['ispost'] == 1) {
             $set['jduniackey'] = $_GPC['jduniackey'];
             $set['updated_at'] = time();
             $set['uniacid'] = $_W['uniacid'];
        
            if (empty($r)) {
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global", $set);
            } else {
                $res = pdo_update("nets_hjk_global", $set, array('uniacid' => $_W['uniacid']));
            }
            if ($res)
                show_message('操作成功！', webUrl('mogujie/set'), 'success');
//              show_message('操作成功！',webUrl('system/index'), 'success');
        }
        include $this->template('haojingke/mogujie/set');
    }   
}
?>