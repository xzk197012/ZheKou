<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='账户明细';

getUserInfo(1);
get_global();

$uid 	=	$_W['user_info']["memberid"];
$page	=	$_GPC["page"];
$type=2;
if(!empty($_GPC["type"])){
	$type=$_GPC["type"];
}
if(empty($page))	$page=1;
$pagesize=	20;
$state	=	$_GPC["state"];
$where	=	"where memberid=:memberid and type=".$type;
$data[":memberid"]	=	$uid;
$list	=	pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_member_logs").$where." order by id desc LIMIT ".(($page-1)*$pagesize).",".$pagesize,$data);
//var_dump($list);
$total	=	pdo_fetchcolumn("SELECT COUNT(id) FROM ".tablename("nets_hjk_member_logs").$where,$data);

$page	=	pagination($total, $page, $pagesize);

include $this->template('log');

?>