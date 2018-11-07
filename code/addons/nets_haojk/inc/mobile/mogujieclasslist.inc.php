<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
$_W["pagetitle"]="蘑菇街";
getUserInfo(0);
get_global();
$cname=$_GPC["sourceid"];
$goodsource=pdo_fetch("SELECT * from ".tablename("nets_hjk_menu")." where id=:id and uniacid=:uniacid",array(":id"=>$cname,":uniacid"=>$_W["uniacid"]));
	if(!empty($goodsource)){
		//自定义商品原的跳转到专题页面
		$_W["pagetitle"]=$goodsource["name"];
	}
//查询当前会员 原openid变更为uid 
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


function check_url($url){
		if(!preg_match('/https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
			return false;
		}
		return true;	
}
// function tolistglobal($pinduoduoglobal,$url){
//     global $_W,$_GPC;
//     if(empty($pinduoduoglobal)){
//         return array();
//     }
//     $list=object_array(json_decode($pinduoduoglobal));
//     $newlist=array();
//     foreach($list["list"] as $item){
//         $hmcnarr	=	explode('=',$item['url']);
//         $tourl= $url.'&cname='.$hmcnarr['1'].'&cnametype='.$hmcnarr['2'].'&cnameid='.$hmcnarr['3'];
//         if(!empty($item['outer_url'])){
//             $tourl=$item['outer_url'];
//         }
//         $item["cname"]=$hmcnarr['1'];
//         $item["tourl"]=$tourl;
//         $newlist[]=$item;
//     }
//     $list["list"]=$newlist;
//     return $list;
// }
include $this->template('mogujieclasslist');


?>