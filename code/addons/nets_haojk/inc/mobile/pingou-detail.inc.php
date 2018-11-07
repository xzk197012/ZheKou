<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/img.func.php';

global $_W,$_GPC;
getUserInfo(0);
if(empty($_GPC['pix']))	$_GPC['pix']=1;
$_W['page']['title']='好货精选';
//微信端走授权登陆获取用户身份，其他客户端直接显示详情，且不会建立会员身份
if($_W['container']=="wechat"){
	getUserInfo(1);
}
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.17 in pingou-detail.inc.php in 15
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
$global		=	$_W['global'];
$skuId      =   $_GPC["skuId"];
$filename   =   getfilename($skuId);
$detail     =   "";
if(true){
    $json   =   file_get_contents($filename);
	$detail =   json_decode($json, true);
	$detailurl=getGoodsDetailJD($detail['skuId']);
	//var_dump($detail);
	$detail["keyword"]=$detail['skuName'];
	$detail["from_uid"]=$_GPC["from_uid"];
	//echo $detail["keyword"];
	$strreplace=array(" ","　","\t","\n","\r"); 
	$detail["skuDesc"]=str_replace($strreplace,"",$detail["skuDesc"]);
	$unionUrl	=	get_unionUrl($detail);
	//echo $unionUrl;
	$tempunionUrl=$unionUrl;
	if(empty($_W['global']['isshow_detail'])){
		header("Location: $unionUrl"); exit;
	}
	$posterSize=7;
	if($_W['global']['goodsqrtype']=="2"){
		$tempunionUrl=$_W["siteurl"]."&is_quan=1&from_uid=".$_W["user_info"]["memberid"];
		$tempunionUrl=str_replace("&ajax=1","",$tempunionUrl);
		$posterSize=5;
	}
	if(!empty($_GPC["ajax"])){
		//var_dump($detail);
		//echo $detail["picUrl"];exit;
		$poster=new Poster();
		$skuId=$detail["skuId"];
		$skuName=$detail["skuName"];
		$skuDesc=$detail["skuDesc"];
		$materiaUrl=$detail["materiaUrl"];
		$picUrl=$detail["picUrl"];
		$wlPrice=$detail["wlPrice"];
		$wlPrice_after=$detail["wlPrice_after"];
		$discount=$detail["discount"];
		
		$res=$poster->getGoodPoster($skuId,$skuName,$skuDesc,$tempunionUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,$posterSize);
		exit(json_encode($res));
	}
}

include $this->template('pingou-detail');


?>