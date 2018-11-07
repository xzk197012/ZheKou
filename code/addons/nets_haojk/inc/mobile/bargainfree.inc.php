<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
getUserInfo(0);
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.18 bargainfree.inc.php in 8
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

$level=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
$rate=0;
foreach($level AS $l){
	//当前会员类型的等级佣金比例
	if($l["type"]==$member["type"] && $l["name"]==$member["level"] ){
		if($member["type"]==0){
			$rate=$l["myteam_credit2"];
		}else{
			$rate=$l["myteam_credit2"];	
		}
	}
}
//echo $_GPC["keyword"];
if(check_url($_GPC["keyword"])){
	//echo $_GPC["keyword"];
	$jdurl=$_GPC["keyword"];
	if(strpos($jdurl,'.html') !== false){
		$jdurl_arr=explode('?',$jdurl);
		$jdurl=$jdurl_arr[0];
		$strarr=explode("/",$jdurl);
		$_GPC["keyword"]=$strarr[count($strarr)-1];
		$_GPC["keyword"]=str_replace(".html","",$_GPC["keyword"]);
	}
	if(strpos($jdurl,'sku=') !== false){
		$jdurl_arr=explode('sku=',$jdurl);
		$jdurl=$jdurl_arr[1];
		$strarr=explode("&",$jdurl);
		$_GPC["keyword"]=$strarr[0];
	}
}
if(!empty($_GPC["ajax"])){
	/*
	* 商品列表
	* uri 参数可选[list,listbysearch,listbycname,cname],默认传list
	* page 当前页数 默认1
	* pagesize 每页记录数 默认20
	* sort 排序 默认0，券后价1，优惠幅度2，佣金比例3，销量4(暂时无用)
	* keyword 搜索关键字，默认为空，仅uri参数等于listbysearch时生效
	* minprice 最小金额，默认为空，仅uri参数等于listbysearch时生效
	* maxprice 最大金额，默认为空，仅uri参数等于listbysearch时生效
	* mincommission 最小佣金，默认为空，仅uri参数等于listbysearch时生效
	* maxcommission 最大佣金，默认为空，仅uri参数等于listbysearch时生效
	* cname 分类名称，仅uri参数等于listbycname时生效
	*/
	//echo $_GPC["page"]."<br/>";
	$uri=$_GPC["uri"];
	$page=$_GPC["page"];
	$pagesize=$_GPC["pagesize"];
	$sort=$_GPC["sort"];
	
	$keyword=$_GPC["keyword"];
	$minprice=$_GPC["minprice"];
	$maxprice=$_GPC["maxprice"];
	$mincommission=$_GPC["mincommission"];
	$maxcommission=$_GPC["maxcommission"];
	$cname=$_GPC["cname"];
	$goodstype=$_GPC["goodstype"];
	$goodslx=$_GPC["goodslx"];
	$sortby=$_GPC["sortby"];
	$limittime=$_GPC["limittime"];
	$realtime=$_GPC["realtime"];
	$errno = 0;
	$message="操作成功";
	if($_GPC["op"]=="byskuid"){
		$res=getunionurlbysku($keyword);
		exit(json_encode($res));
	}
	$list=getgoodlist($uri,$page,$pagesize,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname,$goodstype,$goodslx,$sortby,$limittime,$realtime);
	if(empty($list)){
		exit("");
	}
	exit(json_encode($list));
}

function check_url($url){
		if(!preg_match('/https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
			return false;
		}
		return true;	
}

include $this->template('bargainfree');


?>