<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
 

global $_W,$_GPC;

if(empty($_GPC['pix']))	$_GPC['pix']=0;
$_W['page']['title']='我的订单';
getUserInfo(1);
//查询当前会员 原openid变更为uid zxq.2018.05.17 order.inc.php in 11
$member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
if(empty($member['jduniacid'])){
	
}
get_global();

$global		=	$_W['global'];

$order=pdo_fetchall("SELECT em.* FROM ".tablename("nets_hjk_freeorders")." AS em   where em.uniacid=:uniacid and em.memberid=:memberid",array(":uniacid"=>$_W["uniacid"],":memberid"=>$_W["user_info"]['memberid']));



include $this->template('order');


?>