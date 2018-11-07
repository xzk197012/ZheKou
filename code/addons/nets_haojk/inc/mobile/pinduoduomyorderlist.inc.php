<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='我的订单';

getUserInfo(1);
get_global();
if(!empty($_GPC["ajax"])){
	$list	=	array();
	$begintime=date('Y-m-d 00:00:00', strtotime("-90 day"));
	$begintime=strtotime($begintime);
	$endtime=date('Y-m-d 23:59:59', time());
	$endtime=strtotime($endtime);
	$_GPC['begintime'] = $begintime;
	$_GPC['endtime'] =  $endtime;
	$list['list1']	=	pdd_getorderlist();		//订单
	if(empty($list['list1'])){
		exit("");
	}
	exit(json_encode($list['list1']));
	
}
include $this->template('pinduoduomyorderlist');

?>