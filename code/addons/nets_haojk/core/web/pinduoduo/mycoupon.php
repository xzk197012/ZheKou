<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Mycoupon_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

        if ($_W['action']=='pinduoduo.mycoupon')
            $this->mycoupon();
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
        $global=pdo_fetch("select * from ".tablename("nets_hjk_pdd_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
        $data = array(
            'unionId' => $global["mobile"],
            'cname' => $cname,
            'page' => $page,
            'pageSize' => $pagesize,
            'keyword' => $keyword
        );
        $url=HAOJK_HOST."pdd/goodslist";
        $temp_url="pdd/goodslist".$_W['uniacid'];
        $filename=getfilename($temp_url);
        load()->func('communication');
        
        $res=ihttp_post($url,$data);
        $res=$res["content"];
        $list=json_decode($res);

        $total=$list->total;
        $list=$list->data;

        $json_string = json_encode($list);

        file_put_contents($filename, $json_string);
        $json=file_get_contents($filename);
        $list=json_decode($json, true);
        $cate=getpdd_cname();
        $pager = pagination($total, $page, $pagesize);

        include $this->template('haojingke/pinduoduo/mycoupon');
    }


    
	
}
?>