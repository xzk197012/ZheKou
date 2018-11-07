<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
global $_W,$_GPC;

$detail		=	$_GPC['detail'];

$detail     =   object_array($detail);
$unionUrl	=	get_unionUrl($detail);

if(!empty($unionUrl))
{
    echo json_encode(array('status'=>1,'info'=>'','unionurl'=>$unionUrl));
}
else
{
    echo json_encode(array('status'=>0,'info'=>'短连接生成失败','unionurl'=>''));
}

?>