<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='预估收入';
$result		=	array();



//今日

$begintime1=strtotime(date('Y-m-d'));

$endtime1=time();

$_GPC['start_time'] = $begintime1;
$_GPC['end_time'] =  $endtime1;
$_GPC['status'] = 0;

$result['list1']['orders']		=getpdd_Getorderrange();
$_GPC['status'] = 1;
$result['list1']['importorders']=getpdd_Getorderrange();

//昨日
$begintime2=strtotime(date('Y-m-d',time()-3600*24));
$endtime2=strtotime(date('Y-m-d',time()));

$_GPC['start_time'] = $begintime2;
$_GPC['end_time'] =  $endtime2;
$_GPC['status'] = 0;

$result['list2']['orders']		=	getpdd_Getorderrange();
$_GPC['status'] = 1;

$result['list2']['importorders']=	getpdd_Getorderrange();



//近七日
$begintime3=strtotime(date('Y-m-d',time()-3600*24*7));
$endtime3=time();

$_GPC['start_time'] = $begintime3;
$_GPC['end_time'] =  $endtime3;
$_GPC['status'] = 0;
$result['list3']['orders']		=	getpdd_Getorderrange();

$_GPC['status'] = 1;
$result['list3']['importorders']=	getpdd_Getorderrange();




//近一个月
$begintime4=strtotime(date('Y-m-d',time()-3600*24*30));
$endtime4=time();

$_GPC['start_time'] = $begintime4;
$_GPC['end_time'] =  $endtime4;
$_GPC['status'] = 1;
$result['list4']['orders']		=	getpdd_Getorderrange();
$_GPC['status'] = 0;

$result['list4']['importorders']=	getpdd_Getorderrange();



// $result		=	object_array($result);
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

include $this->template('pinduoduoestimated');

?>