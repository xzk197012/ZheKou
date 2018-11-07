<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Coupon_addpost_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

        if ($_W['action']=='pinduoduo.coupon_addpost')
            $this->coupon_addpost();
        elseif ($_W['action']=='pinduoduo.coupon_addpost.coupon_checkpost')
            $this-> coupon_checkpost();
	}
	
    

    
    //优惠券-添加提交
    public function coupon_addpost()
    {
        global $_W;
        global $_GPC;
        $picUrl=tomedia($_GPC['picUrl']);
        $picUrl="";
        $global=pdo_fetch("select * from ".tablename("nets_hjk_pdd_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $jduniacid = $global['jduniacid'];
        $skuId = $_GPC['skuId'];
        $couponUrl = $_GPC['couponUrl'];
        $skuName = $_GPC['skuName'];
        $skuDesc = $_GPC['skuDesc'];
        $cname = $_GPC['cname'];
        $data = array(
            'picUrl' => $picUrl,
            'unionId' => $global["mobile"],
            'skuId' => $skuId,
            'skuName' => $skuName,
            'skuDesc' => $skuDesc
        );

        $url=HAOJK_HOST."pdd/goodsadd";
        
        load()->func('communication');
        $res=ihttp_post($url,$data);
        $res=$res["content"];
        $res=json_decode($res);
        exit(json_encode($res));
    }

    //优惠券-提交校验
    public function coupon_checkpost(){
        global $_W;
        global $_GPC;
        $_GPC["goods_id_list"] = $_GPC['skuId'];
        $list=getpdd_detail();
        exit(json_encode($list));
    }
	
}
?>