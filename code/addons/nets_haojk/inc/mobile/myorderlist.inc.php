<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.func.php';
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
	$_GPC['begintime']=$begintime;
	$_GPC['endtime']=$endtime;
	$page	=	$_GPC["page"];
	$pagesize=	20;
	$uri=$_GPC["uri"];
	$list['list1']	=	jd_getorderlist();		//订单
	
	if(empty($list['list1'])){
		exit("");
	}
    haojk_log("获取订单列表:".json_encode($list['list1']));
	exit(json_encode($list['list1']));
}
include $this->template('myorderlist');

?>