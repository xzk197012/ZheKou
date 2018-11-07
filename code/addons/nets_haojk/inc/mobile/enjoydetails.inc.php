<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/img.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=1;
$_W['page']['title']='好货精选';
//微信端走授权登陆获取用户身份，其他客户端直接显示详情，且不会建立会员身份
if($_W['container']=="wechat"){
	getUserInfo(1);
}
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.17 in enjoydetails.inc.php in 14
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

$level=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
$rate=0;

foreach($level AS $l){
	//当前会员类型的等级佣金比例
	if($l["type"]==$member["type"] && $l["name"]==$member["level"] ){
		if($member["type"]==0){
			$rate=$l["myteam_credit2"];
		}else{
			$rate=$l["myteam_credit2"];	
		}
	}
}
$global		=	$_W['global'];
$skuId      =   $_GPC["skuId"];
$filename   =   getfilename($skuId);
$detail     =   "";
if(true){
    $json   =   file_get_contents($filename);
	$detail =   json_decode($json, true);
	$detailurl=getGoodsDetailJD($detail['skuId']);
	//var_dump($detail);
	$detail["keyword"]=$detail['skuName'];
	$detail["from_uid"]=$_GPC["from_uid"];
	//echo $detail["keyword"];
	$strreplace=array(" ","　","\t","\n","\r"); 
	$detail["skuDesc"]=str_replace($strreplace,"",$detail["skuDesc"]);
	//echo "123::".$member['jduniacid'];
	$unionUrl	=	get_unionUrlByuniacid($detail,$member['jduniacid']);
	$detail["unionUrl"]=$unionUrl;
	//echo $unionUrl;
	$tempunionUrl=$unionUrl;
	if(empty($_W['global']['isshow_detail'])){
		//header("Location: $unionUrl"); exit;
	}
	$posterSize=7;
	if($_W['global']['goodsqrtype']=="2" && false){
		//$tempunionUrl=$_W["siteurl"]."&is_quan=1&from_uid=".$_W["user_info"]["memberid"];
		//$tempunionUrl=str_replace("&ajax=1","",$tempunionUrl);
		$posterSize=5;
	}
	if(!empty($_GPC["ajax"])){
		//var_dump($detail);
		//echo $detail["picUrl"];exit;
		$poster=new Poster();
		$skuId=$detail["skuId"];
		$skuName=$detail["skuName"];
		$skuDesc=$detail["skuDesc"];
		$materiaUrl=$detail["materiaUrl"];
		$picUrl=$detail["picUrl"];
		$wlPrice=$detail["wlPrice"];
		$wlPrice_after=$detail["wlPrice_after"];
		$discount=$detail["discount"];
		
		$res=$poster->getGoodPoster($skuId,$skuName,$skuDesc,$tempunionUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,$posterSize);
		if(!empty($_GPC['sendnotic'])){
			$result=AjaxSendServerNotice($detail);
			$res['notice']=$result;
		}

		exit(json_encode($res));
	}
}

/**
	 * 发送客服消息
	 * 参数1：skuId  ，商品id
	 * 参数2：skuDesc，商品描述
	 * 参数3：unionUrl，购买链接
	 * 参数4：wlPrice_after，券后价
	 * 参数5：wlPrice，商品价格
	 * 参数6：skuName，商品名称
	 * 参数7：yuebu，约补金额
	 * 参数8: haibaourl，海报url
	 */
	function AjaxSendServerNotice($g){
		global $_GPC, $_W;
		$searchurl=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=nets_haojk&do=enjoy";
		
		$openid=$_W['openid'];
		$skuId=$g['skuId'];
		$skuDesc=$g['skuDesc'];
		$unionUrl=$g['unionUrl'];
		$wlPrice_after=$g['wlPrice_after'];
		$wlPrice=$g['wlPrice'];
		$skuName=$g['skuName'];
		$yuebu_str="
	 ".$g['yuebu'];
	 $yuebu_str="";
		if($_W['account']['level'] >= ACCOUNT_SUBSCRIPTION_VERIFY) { 
			$info = "【{$_W['account']['name']}】充值通知\n";
			$info .= "您在进行会员余额充值，充值金额【9】元，充值后余额9】元。\n";
			$detailurl=url('entry',array('m'=>'nets_haojk','do'=>'searchdetail','skuId'=>$skuId));
			$detailurl=$_W['siteroot'].substr($detailurl,2,strlen($detailurl));
			$message_str="【JD】".$skuName."
————————
京东价：￥".$wlPrice."
内购价：￥".$wlPrice_after.$yuebu_str."
领券优惠购买：".$unionUrl."
————————
【推荐理由】".$skuDesc."
京东商城 正品保证";

			$message = array(
				'msgtype' => 'text',
				'text' => array('content' => urlencode($message_str)),
				'touser' => $openid,
			);
			$account_api = WeAccount::create();
			$status = $account_api->sendCustomNotice($message);
			if (is_error($status)) {
				$tip= '发送失败，原因为' . $status['message'];
				return array('code'=>-1,'message'=>$tip,'res'=>'');
			}

			//任意指定一个文件上传
			$result = $account_api->uploadMedia(IA_ROOT . '/addons/nets_haojk/cache/'.$skuId.'_exqrcode.jpg', 'image');
			if(!empty($result['media_id'])){
				$message = array(
					'msgtype' => 'image',
					'image' => array('media_id' => $result['media_id']),
					'touser' => $openid,
				);
				$status = $account_api->sendCustomNotice($message);	
				if (is_error($status)) {
					$tip= '发送失败，原因为' . $status['message'];
					return array('code'=>-1,'message'=>$tip,'res'=>'');
				}
			}
			
			//发送成功
			return array('code'=>1,'message'=>'发送成功','res'=>'');
		}
		return array('code'=>-1,'message'=>'没有发送权限','res'=>'');
	}

include $this->template('enjoydetails');


?>