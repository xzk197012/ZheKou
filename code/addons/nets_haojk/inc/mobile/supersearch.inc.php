<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
global $_W,$_GPC;

if(empty($_GPC['pix']))	$_GPC['pix']=0;
$_W['page']['title']='超级搜索';

getUserInfo(0);
get_global();

$global		=	$_W['global'];
//查询当前会员 原openid变更为uid zxq.2018.05.17 in supersearch.inc.php in 26
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

$banner		=	object_array(json_decode($global['banner']));
$ad_menu	=	object_array(json_decode($global['ad_menu']));

$head_menu		=	object_array(json_decode($global['head_menu']));
$picture_menu	=	object_array(json_decode($global['picture_menu']));
$picture_menu2	=	object_array(json_decode($global['picture_menu2']));

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
		$errno = 0;
		$message="success";
		
		$keword=getsearchkeyword();


include $this->template('supersearch');


?>