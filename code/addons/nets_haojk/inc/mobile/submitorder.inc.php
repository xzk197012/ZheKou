<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='提交订单';

getUserInfo(1);
get_global();

if(!empty($_GPC["ajax"])){
	$errno = 0;
	$message="操作成功";
	$orderno=$_GPC["orderno"];
	$o["otherinfo"]=$_GPC['otherinfo'];
	if(empty($orderno)){
		$message="订单号不能为空";
		exit(json_encode(array("errno"=>$errno,"message"=>$message,"data"=>"0")));
	}
	if($o["otherinfo"]!=3){
		$checkorder=checkorders_byorderno($orderno);
		//var_dump($checkorder);
		if(empty($checkorder["data"])){
			$message="云服务暂未查到该订单，请稍后再来提交订单";
			exit(json_encode(array("errno"=>$errno,"message"=>$message,"data"=>"0")));
		}
	}else{
		$_GPC["orderids"]=$orderno;
		$orderlist=mgj_getorderlist();
		if(empty($orderlist["data"])){
			$message="云服务暂未查到该订单，请稍后再来提交订单";
			exit(json_encode(array("errno"=>$errno,"message"=>$message,"data"=>"0")));
		}
	}
	
	//查询当前会员 原openid变更为uid zxq.2018.05.17 in submitorder.inc.php in 26
	$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

	if($member["type"]!=0){
		$message="盟主不可以提单";
		exit(json_encode(array("errno"=>$errno,"message"=>$message,"data"=>"0")));
	}
	$order=pdo_fetch("select * from ".tablename("nets_hjk_orders")." where orderno=:orderno",array(":orderno"=>$orderno));
	if(!empty($order)){
		$message="该订单号已提交过，不能重复提单！";
		exit(json_encode(array("errno"=>$errno,"message"=>$message,"data"=>"0")));
	}
	$o["orderno"]=$orderno;
	$o["uniacid"]=$_W["uniacid"];
	$o["memberid"]=$member["memberid"];
	$o["otherinfo"]=$_GPC['otherinfo'];
	$o["created_at"]=time();
	$o["state"]=0;
	$i=pdo_insert("nets_hjk_orders",$o);
	if($i>0){
		$message="提单成功，请等待审核";
	}else{
		$message="操作失败";
	}
	exit(json_encode(array("errno"=>$errno,"message"=>$message,"data"=>$i)));
}
include $this->template('submitorder');

?>