<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
global $_W,$_GPC;
$_W['page']['title']='首页';
getUserInfo(0);
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.17 in pinduoduoindex.inc.php in 9
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


$global = $_W['global'];

$pinduoduoglobal=pdo_fetch("select * from ".tablename("nets_hjk_pdd_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
$url=$this->createMobileUrl('pinduoduoclasslist');




$banner=tolistglobal($pinduoduoglobal['banner'],$url);
$banner2=tolistglobal($pinduoduoglobal['banner2'],$url);
$banner3=tolistglobal($pinduoduoglobal['banner3'],$url);
$ad_menu=tolistglobal($pinduoduoglobal['ad_menu'],$url);
$ad_menu2=tolistglobal($pinduoduoglobal['ad_menu2'],$url);
$ad_menu3=tolistglobal($pinduoduoglobal['ad_menu3'],$url);
$head_menu=tolistglobal($pinduoduoglobal['head_menu'],$url);
$head_menu2=tolistglobal($pinduoduoglobal['head_menu2'],$url);
$head_menu3=tolistglobal($pinduoduoglobal['head_menu3'],$url);
$head_menu4=tolistglobal($pinduoduoglobal['head_menu4'],$url);
$picture_menu=tolistglobal($pinduoduoglobal['picture_menu'],$url);
$picture_menu2=tolistglobal($pinduoduoglobal['picture_menu2'],$url);
$picture_menu3=tolistglobal($pinduoduoglobal['picture_menu3'],$url);
$picture_menu4=tolistglobal($pinduoduoglobal['picture_menu4'],$url);
$picture_menu5=tolistglobal($pinduoduoglobal['picture_menu5'],$url);
$picture_menu6=tolistglobal($pinduoduoglobal['picture_menu6'],$url);
$picture_menu7=tolistglobal($pinduoduoglobal['picture_menu7'],$url);
$picture_menu8=tolistglobal($pinduoduoglobal['picture_menu8'],$url);
$itemlist=tolistglobal($pinduoduoglobal['homepage_itemjson']);
$pagetitle=$itemlist["title"];
if(!empty($itemlist)){
    $itemlist=object_array(json_decode($itemlist["itemjson"]));
}
if(!empty($pagetitle)){
    $_W['page']['title']=$pagetitle;
}
if( !empty($_W['user_info']['from_nickname']) && $_W['user_info']['from_nickname']!='无' && $_W['user_info']['from_membertype']!=0){
	$_W['page']['title']=$_W['user_info']['from_nickname'].'的小店';
}
if( !empty($_W['user_info']['from_shopname']) && $_W['user_info']['from_shopname']!='无' && $_W['user_info']['from_membertype']!=0){
	$_W['page']['title']=$_W['user_info']['from_shopname'];
}
if($_W['user_info']['type']!=0 && !empty($_W['user_info']['nickname'] )){
	$_W['page']['title']=$_W['user_info']['nickname'].'的小店';
}
if($_W['user_info']['type']!=0 && !empty($_W['user_info']['shopname'] )){
	$_W['page']['title']=$_W['user_info']['shopname'];
}
function tolistglobal($pinduoduoglobal,$url){
    global $_W,$_GPC;
    if(empty($pinduoduoglobal)){
        return array();
    }
    $list=object_array(json_decode($pinduoduoglobal));
    $newlist=array();
    foreach($list["list"] as $item){
        $hmcnarr	=	explode('=',$item['url']);
        $tourl= $url.'&cname='.$hmcnarr['1'].'&cnametype='.$hmcnarr['2'].'&sourceid='.$hmcnarr['1'];
        if(!empty($item['outer_url'])){
            $tourl=$item['outer_url'];
        }
        $item["cname"]=$hmcnarr['1'];
        $item["tourl"]=$tourl;
        $newlist[]=$item;
    }
    $list["list"]=$newlist;
    return $list;
}
include $this->template('pinduoduoindex');


?>