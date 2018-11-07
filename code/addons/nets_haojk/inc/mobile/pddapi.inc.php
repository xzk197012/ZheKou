<?php
	/**
	 * 对外开放的api,不需要验证用户身份
	 * API 地址：http://www.91fyt.com/app/index.php?i=56&c=entry&m=nets_haojk&do=pddapi 
	 * 请求方式：POST
	 * 参数：
	 * 1. oper：执行的操作，枚举可选cloud.pdd.func里的函数名
	 * 2. 其他参数参加cloud.pdd.func文件里的函数
	 */
	defined('IN_IA') or exit('Access Denied');
	require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
	require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
	global $_GPC, $_W;
	getUserInfo(0);
	$oper=$_GPC["oper"];
	$openid=$_W["openid"];
	$result=array("code"=>500,"result"=>0,"msg"=>"操作失败,不允许的操作");
	//通过传递的oper调用对应的函数,oper参数与函数名一致
	if (function_exists($oper)) {
		$result=array("code"=>200,"result"=>0,"msg"=>"操作成功");
		$result['result']=$oper();
	}
	
	exit(json_encode($result));
	
	

?>