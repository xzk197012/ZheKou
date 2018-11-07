<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
 

global $_W,$_GPC;

if(empty($_GPC['pix']))	$_GPC['pix']=0;
$_W['page']['title']='精选推广';

getUserInfo(0);
get_global();

$global		=	$_W['global'];
if(!empty($_GPC['cateid'])){
    $source=pdo_fetch("select * from ".tablename("nets_hjk_menu")." where id=:id",array(":id"=>$_GPC['cateid']));
    $_W['page']['title']=$source['name'];
}
include $this->template('tuiguang');


?>