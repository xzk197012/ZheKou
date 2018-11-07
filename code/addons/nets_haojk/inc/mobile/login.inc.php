<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
get_global();
$global		=	$_W['global'];
include $this->template('login');


?>