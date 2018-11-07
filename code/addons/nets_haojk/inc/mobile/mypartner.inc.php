<?php

defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='我的伙伴';

getUserInfo(1);
get_global();

$uid 	=	$_W['user_info']["memberid"];
$levelname = pdo_fetchcolumn("select identityname FROM ".tablename('nets_hjk_memberlevel')." where  type=1 and uniacid=:uniacid ",array(":uniacid"=>$_W["uniacid"]));
if(empty($levelname)){
    $levelname=	"盟主";
}
$team0_count=pdo_fetchcolumn("select count(0) FROM ".tablename('nets_hjk_members')." where  (from_uid=:memberid or from_partner_uid=:memberid) and uniacid=:uniacid ",array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
$team1_count=pdo_fetchcolumn("select count(0) FROM ".tablename('nets_hjk_members')." where type=1 and from_uid=:memberid and uniacid=:uniacid ",array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
$team2_count=pdo_fetchcolumn("select count(0) FROM ".tablename('nets_hjk_members')." where type=1 and from_uid2=:memberid and uniacid=:uniacid ",array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
if(!empty($_GPC["ajax"])){
	$page=empty($_GPC["page"])?1:$_GPC['page'];
	$pagesize=20;
	//下级会员和盟主
	//直接下级会员数
// 	$team_data1 = pdo_fetchall("SELECT ex.*,  FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1',
// 	(select sum(money) from ".tablename('nets_hjk_member_logs')." where type=2 and memberid=ex.memberid) AS 'commission',
// 	(select sum(money) from ".tablename('nets_hjk_member_logs')." where type=3 and memberid=ex.memberid) AS 'subsidy',
// 	(select count(0) from ".tablename('nets_hjk_orders')." where memberid=ex.memberid) AS 'ordercount' 
// 	FROM ".tablename('nets_hjk_members')." AS ex 
// WHERE ex.from_uid=:memberid order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":memberid"=>$uid));
	//下下级会员和盟主
	// $mids='';
	// 		foreach($team_data1 AS $m){
	// 			$mids.=$m['memberid'].',';
	// 		}
	// 		if(!empty($mids)){
	// 			$mids=substr($mids,0,strlen($mids)-1);
	// 		}
	// 		if(empty($mids)){
	// 			$mids=-1;
	// 		}

	// 		//下下级会员数
	// 		$team_data2=pdo_fetchall("SELECT ex.*,  FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1',
	// 		(select sum(money) from ".tablename('nets_hjk_member_logs')." where type=2 and memberid=ex.memberid) AS 'commission',
	// 		(select sum(money) from ".tablename('nets_hjk_member_logs')." where type=3 and memberid=ex.memberid) AS 'subsidy',
	// 		(select count(0) from ".tablename('nets_hjk_orders')." where memberid=ex.memberid) AS 'ordercount' 
	// 		FROM ".tablename('nets_hjk_members')." AS ex 
	// 		where type=1 and from_uid in(".$mids.")  order by id desc",array(":memberid"=>$uid));
			// $result["team0"]=array();
			// $result["team1"]=array();
			// $result["team2"]=array();
	// foreach((array)$team_data1 as $tm)
	// {
	// 	if(empty($tm['ordercount'])){
	// 		$tm['ordercount']=0;
	// 	}
	// 	if(empty($tm['commission'])){
	// 		$tm['commission']=0;
	// 	}
	// 	if(empty($tm['subsidy'])){
	// 		$tm['subsidy']=0;
	// 	}
	// 	if($tm['type']==0)
	// 	{
			
	// 		$result["team0"][]	=	$tm;
	// 	}
	// 	if($tm['type']==1)
	// 	{
	// 		$result["team1"][]	=	$tm;
	// 	}
	// }

	// foreach((array)$team_data2 as $tm)
	// {
	// 	if(empty($tm['ordercount'])){
	// 		$tm['ordercount']=0;
	// 	}
	// 	if(empty($tm['commission'])){
	// 		$tm['commission']=0;
	// 	}
	// 	if(empty($tm['subsidy'])){
	// 		$tm['subsidy']=0;
	// 	}
	// 	if($tm['type']==0)
	// 	{
	// 		$result["team0"][]	=	$tm;
	// 	}
	// 	if($tm['type']==1)
	// 	{
	// 		$result["team2"][]	=	$tm;
	// 	}
	// }
	$result["team0"]=array();
	$result["team1"]=array();
	$result["team2"]=array();
	//一级会员
	$result["team0"]=pdo_fetchall("select id,memberid,nickname,avatar,username,0 AS 'commission',0 AS 'ordercount',0 AS 'subsidy', FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_members')." where  (from_uid=:memberid or from_partner_uid=:memberid) and uniacid=:uniacid order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
	//一级盟主
	$result["team1"]=pdo_fetchall("select id,memberid,nickname,avatar,username,0 AS 'commission',0 AS 'ordercount',0 AS 'subsidy', FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_members')." where type=1 and from_uid=:memberid and uniacid=:uniacid order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
	//二级盟主
	$result["team2"]=pdo_fetchall("select id,memberid,nickname,avatar,username,0 AS 'commission',0 AS 'ordercount',0 AS 'subsidy', FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_members')." where type=1 and from_uid2=:memberid and uniacid=:uniacid order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
	exit(json_encode($result));
	//var_dump($result);
}
include $this->template('mypartner');

?>