<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.func.php';

global $_W,$_GPC;
		
getUserInfo(0);
get_global();
$credit1 = sign_credit1();

echo json_encode(array('status'=>intval($credit1),'msg'=>''));


?>