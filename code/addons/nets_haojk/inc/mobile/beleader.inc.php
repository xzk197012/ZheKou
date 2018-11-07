<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;


getUserInfo(1);
get_global();
$_W['page']['title']='申请'.$_W['global']['applygradename'];
//查询当前会员 原openid变更为uid zxq.2018.05.17 beleader.inc.php in 11
$member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
/*
$probit=pdo_fetch("select * from ".tablename("nets_hjk_probit")." where  uniacid=:uniacid and state=0",array(":uniacid"=>$_W["uniacid"]));
if(empty($probit)){
    message('暂时没有推广位，请等待！',$this->createMobileUrl('my'),'error');
}
*/

$uniacid		=	$_W['uniaccount']['uniacid'];

$applyleader	=	$_W['global']['applyleader'];
$applyleader_fee=	$_W['global']['applyleader_fee'];
$res=checkapplyleader($member["memberid"]);
if($res['code']!="200"){
    message($res['msg'],$this->createMobileUrl('my'),'error');
}
if($applyleader==0 || $applyleader_fee==0)
{
    //申请盟主自动通过查询前后会员信息，原openid变更为uid zxq.2018.05.17  beleader.inc.php in 30
    $member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
    $res	=	applyleader($member["memberid"]);
    $member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"])); 
	if($res['code']==200)
    {
        applyloaderMsg($_W['openid'],0,'','',$res['msg']);
        sendOwnerApplayMsg($member['nickname'],0,'','',$res['msg']);
        message('申请成功',$this->createMobileUrl('my'),'success');
    }
    else
    {
        message($res['msg'],$this->createMobileUrl('my'),'error');
    }
}

include $this->template('beleader');

?>