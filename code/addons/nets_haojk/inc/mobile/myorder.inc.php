<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='我的订单';

getUserInfo(1);
get_global();

$list	=	array();
$begintime=date('Y-m-d 00:00:00', strtotime("-90 day"));
$begintime=strtotime($begintime);
$endtime=date('Y-m-d 23:59:59', time());
$endtime=strtotime($endtime);
$list['list1']	=	getorders('orders',$begintime,$endtime);		//已审订单

$list['list2']	=	getorders('importorders',$begintime,$endtime);//未审订单
$list	=	object_array($list);
if(empty($list['list1'])){
	$list['list1']=array();
}
if(empty($list['list2'])){
	$list['list2']=array();
}

include $this->template('myorder');

?>