<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/freeorder.func.php';
global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=1;
$_W['page']['title']='砍价免费拿';
$cuttingmsg=array('花钱买，不如砍价免费拿','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀');

getUserInfo(1);
get_global();
$memberid=$_W['user_info']['memberid'];
$from_member_shop='';
if(!empty($_GPC['from_uid'])){
	$memberid=$_GPC['from_uid'];
	//砍价查询来自哪个会员取消 uniacid查询 zxq.2018.05.17 bargainyou.inc.php in 16
	$from_member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where  em.memberid=:memberid",array(":uniacid"=>$_W["uniacid"],":memberid"=>$_GPC['from_uid']));
	//var_dump($from_member);
}
$skuId      =   $_GPC["skuId"];
$goods=pdo_fetch("select * from ".tablename('nets_hjk_freegoods').' where skuId='.$skuId);
//echo $goods['goodjson'];
$filename   =   getfilename($skuId);
file_put_contents($filename, $goods['goodjson']);
$detail     =   array();
$detail["from_uid"]=$_GPC["from_uid"];
if(file_exists($filename)){
    $json   =   file_get_contents($filename);
    $detail =   json_decode($json, true);
	$detail["skuDesc"]=str_replace("\r\n","",$detail["skuDesc"]);
    $unionUrl	=	get_unionUrl($detail);
}
if(empty($_GPC['from_uid'])){
	setFreeGoodsDetail($skuId,$memberid);
}	
$cutting=getMyCuttingDetail($skuId,$memberid);
$iscutting=pdo_fetch("select * from ".tablename('nets_hjk_membercutting_rec')." where uniacid=:uniacid and cuttingid=:cuttingid and memberid=:memberid"
,array(":uniacid"=>$_W['uniacid'],":cuttingid"=>$cutting['id'],':memberid'=>$_W['user_info']['memberid']));

if(empty($iscutting)){
	$iscutting=true;
	if(empty($_GPC['from_uid'])){
		helpCutting($cutting['id'],$_W['user_info']['memberid']);
	}
}else{
	
	$iscutting=false;
}
if($_GPC['from_uid']==$_W['user_info']['memberid']){
	$iscutting=true;
}
$cutting=getMyCuttingDetail($skuId,$memberid);
//var_dump($cutting);
//echo $cutting['createtime']."_".date('Y-m-d H:i:s',$cutting['createtime'])."<br/>";
$daojishi=$cutting['createtime']+(1*24*60*60);
//echo $daojishi."_".date('Y-m-d H:i:s',$daojishi)."<br/>";
//echo time()."_".date('Y-m-d H:i:s',time())."<br/>";
$daojishi=$daojishi-time();

$haicha=$cutting['goods']['wlPrice_after']-$cutting['cuttingprice'];
if($haicha<0){
	$haicha=0;
	$cutting['cuttingprice']=$cutting['goods']['wlPrice_after'];
}
if(count($cutting['cuttinglist'])==$cutting['goods']['cuttingnum']){
	$haicha=0;
	$cutting['cuttingprice']=$cutting['goods']['wlPrice_after'];
}
//echo $_W["siteurl"]."<br/>";
$senstr="skuId=".$_GPC['skuId']."&from_uid=".$_GPC['from_uid'];
$qrpath=getfllowqrcodeByfreegoods($senstr);
$qrpath=$_W['attachurl']."freeqrcode_user_".$_W['user_info']['memberid'].".jpg?t=".time();
//echo $qrpath;
include $this->template('bargainyou');
	
?>