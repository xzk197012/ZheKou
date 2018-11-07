<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Index_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='coupon.index'||$_W['action']=='coupon')
            $this->index();
        elseif ($_W['action']=='coupon.mycoupon')
            $this->mycoupon();
        elseif ($_W['action']=='coupon.coupon_add')
            $this->coupon_add();
        elseif ($_W['action']=='coupon.coupon_addpost')
            $this->coupon_addpost();
        elseif ($_W['action']=='coupon.coupon_checkpost')
            $this->coupon_addpost();
	}
	//全网查券
	public function index()
	{
        global $_GPC, $_W;
        //var_dump(WXAPP_DIY);
        $uniacid=$_W['uniacid'];

        $page=1;
        if(!empty($_GPC["page"]))
            $page = $_GPC["page"];
        $pagesize=20;
        $querydata['page']=$page;
        $querydata['pageSize']=$pagesize;
        $querydata['sort']=$_GPC["sort"];
        $querydata['minprice']=$_GPC["minprice"];
        $querydata['maxprice']=$_GPC["maxprice"];
        $querydata['mincommission']=$_GPC["mincommission"];
        $querydata['maxcommission']=$_GPC["maxcommission"];
        $querydata['mincommissionpirce']=$_GPC["mincommissionpirce"];
        $querydata['maxcommissionpirce']=$_GPC["maxcommissionpirce"];
        $querydata['keyword']=$_GPC["keyword"];
        $querydata['cname']=$_GPC["cname"];
        $querydata['goodstype']=0;
        $cate=pdo_fetchall("select * from ".tablename('nets_hjk_menu')." where uniacid=:uniacid and type!=3",array(":uniacid"=>$_W['uniacid']));
        $goodsdata=getgoodlistweb($querydata);
        $list = $goodsdata['data'];

        $pager = pagination($goodsdata['total'], $page, $pagesize);
        include $this->template('haojingke/coupon/index');
	}


    //我发放的券-列表
    public function mycoupon()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $page=empty($_GPC['page'])?"1":$_GPC['page'];
        $pagesize=20;
        $cname=empty($_GPC['cname'])?"":$_GPC['cname'];
        $keyword=empty($_GPC['keyword'])?"":$_GPC['keyword'];
        $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $data = array(
            'unionId' => $global['jduniacid'],
            'cname' => $cname,
            'page' => $page,
            'pageSize' => $pagesize,
            'keyword' => $keyword
        );
        $url=HAOJK_HOST."index/goodslist";
        $temp_url="index/goodslist".$_W['uniacid'];
        $filename=getfilename($temp_url);
        load()->func('communication');
        //var_dump($data);
        $res=ihttp_post($url,$data);
        $res=$res["content"];
        $list=json_decode($res);
        $total=$list->total;
        $list=$list->data;
        $json_string = json_encode($list);

        file_put_contents($filename, $json_string);
        $json=file_get_contents($filename);
        $list=json_decode($json, true);
        $cate=pdo_fetchall("select * from ".tablename('nets_hjk_menu')." where type=2 and uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $pager = pagination($total, $page, $pagesize);

        include $this->template('haojingke/coupon/mycoupon');
    }


    //优惠券-添加
    public function coupon_add()
    {
        global $_W;
        global $_GPC;
        $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $cate=pdo_fetchall("select * from ".tablename('nets_hjk_menu')." where type=2 and uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));

        include $this->template('haojingke/coupon/coupon_add');
    }
    //优惠券-添加提交
    public function coupon_addpost()
    {
        global $_W;
        global $_GPC;
        $picUrl=tomedia($_GPC['picUrl']);
        $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $jduniacid = $global['jduniacid'];
        $skuId = $_GPC['skuId'];
        $couponUrl = $_GPC['couponUrl'];
        $skuName = $_GPC['skuName'];
        $skuDesc = $_GPC['skuDesc'];
        $cname = $_GPC['cname'];
        $data = array(
            'picUrl' => $picUrl,
            'unionId' => $jduniacid,
            'skuId' => $skuId,
            'couponUrl' => $couponUrl,
            'skuName' => $skuName,
            'skuDesc' => $skuDesc,
            'cname' => $cname
        );
//        var_dump($data);
        $url=HAOJK_HOST."index/goodsadd";
        load()->func('communication');
        //var_dump($data);
        $res=ihttp_post($url,$data);
        //var_dump($res);
        $res=$res["content"];
        $res=json_decode($res);
        exit(json_encode($res));
    }

    //优惠券-效验提交
    public function coupon_checkpost()
    {
        global $_W;
        global $_GPC;
        $picUrl=tomedia($_GPC['picUrl']);
        $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $jduniacid = $global['jduniacid'];
        $skuId = $_GPC['skuId'];
        $couponUrl = $_GPC['couponUrl'];
        $skuName = $_GPC['skuName'];
        $skuDesc = $_GPC['skuDesc'];
        $cname = $_GPC['cname'];
        $data = array(
            'unionId' => $jduniacid,
            'skuId' => $skuId,
            'couponUrl' => $couponUrl,
        );
//        var_dump($data);
        $url=HAOJK_HOST."index/goodscheck";
        load()->func('communication');
        //var_dump($data);
        $res=ihttp_post($url,$data);
        //var_dump($res);
        $res=$res["content"];
        $res=json_decode($res);
        exit(json_encode($res));
    }
	
}
?>