<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='预估收入';
$result		=	array();

$result['list1']['orders']		=	getorders('orders',strtotime(date('Y-m-d')),time());
$result['list1']['importorders']=	getorders('importorders',strtotime(date('Y-m-d')),time());

$result['list2']['orders']		=	getorders('orders',strtotime(date('Y-m-d',time()-3600*24)),strtotime(date('Y-m-d',time())));
$result['list2']['importorders']=	getorders('importorders',strtotime(date('Y-m-d',time()-3600*24)),strtotime(date('Y-m-d',time())));
$result['list3']['orders']		=	getorders('orders',strtotime(date('Y-m-d',time()-3600*24*7)),time());
$result['list3']['importorders']=	getorders('importorders',strtotime(date('Y-m-d',time()-3600*24*7)),time());
$result['list4']['orders']		=	getorders('orders',strtotime(date('Y-m-d',time()-3600*24*30)),time());
$result['list4']['importorders']=	getorders('importorders',strtotime(date('Y-m-d',time()-3600*24*30)),time());
$result		=	object_array($result);
//var_dump($result);
$srData		=	array();
foreach($result as $key=>$list)
{
	
    $lo 	=	$list['orders'];
    $li		=	$list['importorders'];
	
	//echo count($lo)."<br/>";
	//echo count($li)."<br/>";
	$yg_i=0;
	$js_i=0;
	if(!empty($li)){
		$yg_i=count($li);
	}
	if(!empty($lo)){
		$js_i=count($lo);
	}
	
    $srData[$key]['yg']	=	$yg_i;	//预估笔数
    $srData[$key]['js']	=	$js_i;	//结算笔数
    $srData[$key]['bs']	=	$js_i+$yg_i;	//预估+结算 笔数
    $srData[$key]['sr']	=	0;	//总收入
    foreach((array)$lo as $loo)
    {
		if(empty($loo['my_commission'])){
			$loo['my_commission']=0;
		}
        $srData[$key]['sr']+=$loo['my_commission'];
    }
	foreach((array)$li as $loo)
    {
		if(empty($loo['my_commission'])){
			$loo['my_commission']=0;
		}
        $srData[$key]['sr']+=floatval($loo['my_commission']);
    }
}
//var_dump($srData);
//var_dump($result['list1']);

include $this->template('estimated');

?>