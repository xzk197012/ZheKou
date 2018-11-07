<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
 

global $_W,$_GPC;

if(empty($_GPC['pix']))	$_GPC['pix']=0;
$_W['page']['title']='首页';

//getUserInfo(1);
get_global();

$global		=	$_W['global'];

$banner		=	object_array(json_decode($global['banner']));
$ad_menu	=	object_array(json_decode($global['ad_menu']));

$head_menu		=	object_array(json_decode($global['head_menu']));
$picture_menu	=	object_array(json_decode($global['picture_menu']));
$picture_menu2	=	object_array(json_decode($global['picture_menu2']));

 




include $this->template('index3');


?>