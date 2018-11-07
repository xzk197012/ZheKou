<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';

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


$_GPC['start_time'] = $begintime;
$_GPC['end_time'] =  $endtime;
$_GPC['status'] = 1;



 // var_dump($_GPC);
 

$list['list1']	=	getpdd_Getorderrange($_GPC);		//已审订单

// var_dump($list['list1']);
// var_dump($list['list1']);
 $_GPC['status'] = 0;
// var_dump($_GPC);
$list['list2']	=	getpdd_Getorderrange($_GPC);//未审订单

$list	=	object_array($list);
if(empty($list['list1'])){
	$list['list1']=array();
}
if(empty($list['list2'])){
	$list['list2']=array();
}

include $this->template('pinduoduomyorder');

?>