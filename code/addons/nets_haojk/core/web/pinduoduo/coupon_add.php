<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Coupon_add_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;
      
        if ($_W['action']=='pinduoduo.coupon_add')
            $this->coupon_add();
	}
	
    

    //优惠券-添加
    public function coupon_add()
    {
        global $_W;
        global $_GPC;
        $global=pdo_fetch("select * from ".tablename("nets_hjk_pdd_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $cate=pdo_fetchall("select * from ".tablename('nets_hjk_pdd_menu')." where type=2 and uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        
        include $this->template('haojingke/pinduoduo/coupon_add');
    }
    
	
}
?>