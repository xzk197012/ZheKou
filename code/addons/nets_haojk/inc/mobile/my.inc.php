<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/img.func.php';
global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='我的';

getUserInfo(1);
get_global();
cloud_authcheckweb();
// echo $_W["wxappauth"];
// echo $_W["wxgzhauth"];
// echo $_W["wxgwqauth"];
// echo $_W["wxpddauth"];
$qrpath="";
$membermenu=array();
if(!empty($_W['global']['membermenu'])){
    $membermenu=json_decode($_W['global']['membermenu'],true);
    $membermenu=$membermenu["list"];
}
if($_W['global']['exqrtype']=="2"){
    $qrpath=getfllowqrcode();
    //$qrpath=ATTACHMENT_ROOT.'qrcode_'.$_W['acid'].'.jpg';
    $posterSize=5;
}
$global		=	$_W['global'];
$user_info	=	$_W['user_info'];
$isapplyleader=checkapplyleader($user_info["memberid"]);
//取出最大等级
$maxlevel=pdo_fetch("select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and type=1 and isuse=1 order by name desc",array(":uniacid"=>$_W["uniacid"]));

$from_user_info	=	pdo_get('nets_hjk_members',array('memberid'=>$user_info['from_uid']));
$member=$_W['user_info'];
$poster=new Poster();
$res=$poster->getQrPoster($member["memberid"],0,$qrpath);

 if($_W['global']['memberskin']=="1"){
     include $this->template('my1');    
 }else{
     if($user_info['type']==0){
        include $this->template('my'); 
     }else{
         include $this->template('leader');
     }
 } 

?>