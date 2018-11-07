<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;

getUserInfo(1);
get_global();

$titleArr	=	array('help'=>'新手指引','savemoney_help'=>'省钱帮助','makemoney_help'=>'赚钱帮助');
$_W['page']['title']=$titleArr[$_GPC['help']];

$helpKey	=	'help';
if(!empty($_GPC['help']))
$helpKey	=	trim($_GPC['help']);
$result		=	$_W['global'][$helpKey];
$result		=	htmlspecialchars_decode(json_decode($result));

include $this->template('shopset');


?>