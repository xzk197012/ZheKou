<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
class Index_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='pinduoduo.index'||$_W['action']=='pinduoduo')
            $this->index();
	}
	//全网查券
	public function index()
	{
        global $_GPC, $_W;
        //var_dump(WXAPP_DIY);
        // $uniacid=$_W['uniacid'];
         $page=1;
        if(!empty($_GPC["page"]))
            $page = $_GPC["page"];
        $pagesize=20;
        // $querydata['page']=$page;
        // $querydata['pageSize']=$pagesize;
        // $querydata['sort_type']=$_GPC["sort_type"];
        //  $querydata['with_coupon']=$_GPC['with_coupon'];
        // $querydata['keyword']=$_GPC["keyword"];
        // $querydata['cname']=$_GPC["cname"];
        // $querydata['goodstype']=0;
        // $_GPC["cname"]=$_GPC["cid"];
        $cate=getpdd_cname();
        // var_dump($_GPC["with_coupon"]);
        $goodsdata=getpdd_goodlistweb();        
        $list = $goodsdata['data'];
        //var_dump($list);
        $pager = pagination($goodsdata['total'], $page, $pagesize);
        include $this->template('haojingke/pinduoduo/index');
	}
    public function pddentry()
    {
        include $this->template('haojingke/pinduoduo/pddentry');
    }
}
?>