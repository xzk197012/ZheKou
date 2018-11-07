<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Switchcloud_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='system.switchcloud')
            $this->index();
        
	}
	
	public function index()
	{
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $result = '';
        $old_defines=MODULE_ROOT."/defines.php";
        $new_defines1=MODULE_ROOT."/defines4.php";
        $new_defines2=MODULE_ROOT."/defines5.php";
        $new_defines3=MODULE_ROOT."/defines6.php";
        unlink ( $old_defines  );
        //替换原文件
        if($_GPC['id']==1){
            copy($new_defines1, $old_defines);
        }else if($_GPC['id']==2){
            copy($new_defines2, $old_defines);
        }else if($_GPC['id']==3){
            copy($new_defines3, $old_defines);
        }
        show_json(1,"切换成功");
	}
    
}
?>