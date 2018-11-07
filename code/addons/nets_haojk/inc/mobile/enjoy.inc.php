<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
 

global $_W,$_GPC;

if(empty($_GPC['pix']))	$_GPC['pix']=0;
$_W['page']['title']='京东选品中心';

getUserInfo(1);
get_global();
$_W['global']["title"]="京东选品中心";
$_W['global']['remark']="设置京东联盟,一键获取商品推广到您的微信！来转发文案赚佣金。";

//查询当前会员 原openid变更为uid zxq.2018.05.17 in enjoy.inc.php in 16
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));


$level=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
$rate=0;

foreach($level AS $l){
	//当前会员类型的等级佣金比例
	if($l["type"]==$member["type"] && $l["name"]==$member["level"] ){
		if($member["type"]==0){
			$rate=$l["myteam_credit2"];
		}else{
			$rate=$l["myteam_credit2"];	
		}
	}
}
$global		=	$_W['global'];
$global		=	$_W['global'];

$banner		=	object_array(json_decode($global['banner']));
$ad_menu	=	object_array(json_decode($global['ad_menu']));

$head_menu		=	object_array(json_decode($global['head_menu']));
$picture_menu	=	object_array(json_decode($global['picture_menu']));
$picture_menu2	=	object_array(json_decode($global['picture_menu2']));

 




include $this->template('enjoy');


?>