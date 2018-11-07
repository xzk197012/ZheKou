<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='结算中心';

getUserInfo(1);
get_global();

$uid 	=	$_W['user_info']["memberid"];
$page	=	$_GPC["page"];
if(empty($page))	$page=1;
$pagesize=	100;
$state	=	$_GPC["state"];
$where	=	"where memberid=:memberid and type=5";
$data[":memberid"]	=	$uid;
$list	=	pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_member_logs").$where." order by id desc LIMIT ".(($page-1)*$pagesize).",".$pagesize,$data);
$total	=	pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename("nets_hjk_member_logs").$where,$data);

$page	=	pagination($total, $page, $pagesize);

//查询当前会员 原openid变更为uid zxq.2018.05.17 in settlement.inc.php in 24
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

include $this->template('settlement');

?>