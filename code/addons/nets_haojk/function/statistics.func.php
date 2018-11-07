<?php

//统计预估收入的订单数据
/*
* $begintime 时间戳
* $endtime 时间戳
* 如 今日 
* $begintime=mktime(0,0,0,date('m'),date('d'),date('Y'));
* $endtime=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
*/
function orderstatistics($begintime,$endtime){
	
	//7天内起止时间戳
	//$begintime=mktime(0,0,0,date('m'),date('d')-7,date('Y'));
	//$endtime=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
	//订单列表
	$orders=getorders_byadmin('importorders',0,10000,$begintime,$endtime,'0');
	$sum_commission=0;
	$sum_price=0;
	if(empty($orders)){
		return '';
	}
	
	$orders=$orders['data'];
	
	foreach($orders AS $o){
		$sum_commission=floatval($sum_commission)+floatval($o['commission']);
		$sum_price=floatval($sum_price)+floatval($o['cosPrice']);
	}
	$result["beginyear"]=date("Y",$begintime)."年";
	$result["beginm"]=date("m",$begintime)."月";
	$result["begind"]=date("d",$begintime)."日";
	$result["endyear"]=date("Y",$begintime);
	$result["endm"]=date("m",$begintime);
	$result["endd"]=date("d",$begintime);
	$result["list"]=$orders;
	if(empty($orders)){
		$result["count"]=0;
	}else{
		$result["count"]=count($orders);
	}
	$result['sum_price']=$sum_price;
	$result['sum_commission']=$sum_commission;
	
	return $result;
}

function getTodayorder(){
	//今日预估		
	$beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
	return orderstatistics($beginToday,$endToday);
}
function getYesterdayorder(){
	//昨日预估
	$beginYesterday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
	$endYesterday=mktime(0,0,0,date('m'),date('d'),date('Y'))-2;
	return orderstatistics($beginYesterday,$endYesterday);
}
function getYestday7order(){
	//近7日
	$beginYestday7=mktime(0,0,0,date('m'),date('d')-7,date('Y'));
	$endYestday7=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
	return orderstatistics($beginYestday7,$endYestday7);
}
function getYestday30order(){
	//近30日
	$beginYestday30=mktime(0,0,0,date('m'),date('d')-30,date('Y'));
	$endYestday30=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
	return orderstatistics($beginYestday30,$endYestday30);
}

function getWeekorder(){
	//一周内每天订单	
	//前 第七天
	$beginToday=mktime(0,0,0,date('m'),date('d')-7,date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')-6,date('Y'))-1;
	//echo $beginToday."_".$endToday;
	$week['week7']=orderstatistics($beginToday,$endToday);
	//前 第六天
	$beginToday=mktime(0,0,0,date('m'),date('d')-6,date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')-5,date('Y'))-1;
	$week['week6']=orderstatistics($beginToday,$endToday);
	//前 第五天
	$beginToday=mktime(0,0,0,date('m'),date('d')-5,date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')-4,date('Y'))-1;
	$week['week5']=orderstatistics($beginToday,$endToday);
	//前 第四天
	$beginToday=mktime(0,0,0,date('m'),date('d')-4,date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')-3,date('Y'))-1;
	$week['week4']=orderstatistics($beginToday,$endToday);
	//前 第三天
	$beginToday=mktime(0,0,0,date('m'),date('d')-3,date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')-2,date('Y'))-1;
	$week['week3']=orderstatistics($beginToday,$endToday);
	//前 第二天
	$beginToday=mktime(0,0,0,date('m'),date('d')-2,date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d')-1,date('Y'))-1;
	$week['week2']=orderstatistics($beginToday,$endToday);
	//前 一天
	$beginToday=mktime(0,0,0,date('m'),date('d')-1,date('Y'));
	$endToday=mktime(0,0,0,date('m'),date('d'),date('Y'))-1;
	$week['week1']=orderstatistics($beginToday,$endToday);
	//var_dump($week);
	return $week;
}
//获取12个月内的订单数据
function getMonthOrder($year=''){
	if(empty($year)){
		$year=date('Y');
	}
	$monthorder=array();
	for($i=0;$i<12;$i++){
		$time=getShiJianChuo($year,$i+1);
		$beginThismonth=$time['begin'];
		$endThismonth=$time['end'];
		//echo $beginThismonth."(".date("Y-m-d H:i",$beginThismonth) .")_".$endThismonth."(".date("Y-m-d H:i",$endThismonth) .")"."<br/>";
		$monthorder["month".($i+1)]=orderstatistics($beginThismonth,$endThismonth);
		
	}
	//var_dump($monthorder);
	return $monthorder;
}
function getShiJianChuo($nian=0,$yue=0){
	if(empty($nian) || empty($yue)){
		$now = time();
		$nian = date("Y",$now);
		$yue =  date("m",$now);
	}
	$time['begin'] = mktime(0,0,0,$yue,1,$nian);
	$time['end'] = mktime(23,59,59,($yue+1),0,$nian);
	return $time;
}

//获取会员的统计数据
//统计预估收入的订单数据
/*
* $begin 时间戳
* $end 时间戳
* 如 今日 
* $begintime=mktime(0,0,0,date('m'),date('d'),date('Y'));
* $endtime=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
*/
function getMemberStatis($begin,$end){
    global $_GPC, $_W;
	//$begin='1504230045';
	//$end='1512092445';
	$sql="select t.created_at AS date,count(*) as num from
	(select from_unixtime(m.created_at,'%Y-%m-%d') as created_at from ".tablename("nets_hjk_members")." AS m
	where uniacid=".$_W['uniacid']." and created_at between ".$begin." and ".$end.") t group by t.created_at order by t.created_at";
	$res=pdo_fetchall($sql);
	return $res;
}

?>