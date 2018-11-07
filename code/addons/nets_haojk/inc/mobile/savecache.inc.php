<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W;
global $_GPC;
$errno  =   0;
$message=   "操作成功";
$detail =   $_GPC['detail'];
$skuId  =   $detail["skuId"];
$json_string=   json_encode($detail);
$filename   =   getfilename($skuId);
file_put_contents($filename, $json_string);
echo $skuId;

?>