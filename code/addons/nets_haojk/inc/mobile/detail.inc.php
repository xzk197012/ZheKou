<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';


global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=1;
$_W['page']['title']='好货精选';

getUserInfo(1);
get_global();
$from_member_shop='';
if(!empty($_GPC['from_uid'])){
    //查询来自分享的会员店铺名称 取消uniacid条件 zxq.2018.05.17 detail.inc.php in 14
	$from_member_shop=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_members")." where memberid=:memberid",array(":memberid"=>$_GPC['from_uid']));
	$_W['page']['title']=$from_member_shop['nickname'].'的小店-'.'好货精选';
}
$skuId      =   $_GPC["skuId"];
$filename   =   getfilename($skuId);
$detail     =   array();
$detail["from_uid"]=$_GPC["from_uid"];
if(file_exists($filename)){
    $json   =   file_get_contents($filename);
    $detail =   json_decode($json, true);
	$detail["skuDesc"]=str_replace("\r\n","",$detail["skuDesc"]);
    $unionUrl	=	get_unionUrl($detail);
}

include $this->template('detail');

?>