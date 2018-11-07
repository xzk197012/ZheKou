<?php
	/**
	 * 独立活动相关api，这里只放独立不相关业务的api，直接跳转或者ajax请求
	 * API 地址：http://www.91fyt.com/app/index.php?i=56&c=entry&m=nets_haojk&do=activityapi&oper=jd_activity_redbag 
	 * 请求方式：POST
	 * 参数：
	 * 1. oper：执行的操作，枚举可选下面对应的函数名
	 */
	defined('IN_IA') or exit('Access Denied');
	require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
	
	global $_GPC, $_W;
	getUserInfo(0);
	get_global();
	$oper=$_GPC["oper"];
	$openid=$_W["openid"];
	$result=array("code"=>500,"result"=>0,"msg"=>"操作失败,不允许的操作");
	//通过传递的oper调用对应的函数,oper参数与函数名一致
	if (function_exists($oper)) {
		$result=$oper();
	}
	//京喜红包
	function jd_activity_redbag(){
		global $_GPC, $_W;
		$jduniacid=$_W["hjk_global"]['jduniacid'];
		$pid=$_W["hjk_global"]['jdpid'];
		if($_W["user_info"]["type"]==1){
			$probit=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_probit")." where id=:id",array(":id"=>$_W["user_info"]["pid"]));
			if(!empty($probit)){
				$pid=$probit["bitno"];
			}
		}
		$data["unionId"]=$jduniacid;
		$data["positionId"]=$pid;
		$data["materialIds"]="https://h5.m.jd.com/dev/PnCvvueTH3G6kqX61zV8F5RN3Ub/index.html";
		$data["couponUrl"]=16;
		$url=HAOJK_HOST."index/getunionurl";
		$res=ihttp_post($url,$data);
		$res=$res["content"];	
		$res=json_decode($res,true);
		$redirct=$res["data"];
		header("location:".$redirct );//直接跳转走
	}
?>