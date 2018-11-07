<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;

getUserInfo(1);
get_global();

$_W['page']['title']='分店管理';
$helpKey	=	'help';
if(!empty($_GPC['help']))
$helpKey	=	trim($_GPC['help']);
$result		=	$_W['global'][$helpKey];
$result		=	htmlspecialchars_decode(json_decode($result));
if($_W["user_info"]['type']==0){
	$url=$this->createMobileUrl('my');  
    Header("Location: $url"); 
}
include $this->template('shop_v1');


?>