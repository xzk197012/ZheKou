<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=1;
$_W['page']['title']='好券精选';
$cname	=	trim($_GPC['cname']);
$cnametype	=	trim($_GPC['cnametype']);
$cnameid	=	trim($_GPC['cnameid']);
if($cnametype==3){
    $url=$this->createMobileUrl('zixuan',array("cateid"=>$cnameid,"cname"=>$cname));  
    Header("Location: $url");  
}
if($cnametype==1){
    $url=$this->createMobileUrl('tuiguang',array("cateid"=>$cnameid,"cname"=>$cname));  
    Header("Location: $url");  
}
if(!empty($cname)){
    if($cname=="自选推广"){
        $url=$this->createMobileUrl('tuiguang');  
        Header("Location: $url");  
    }
    if($cname=="好货情报局"){
        $url=$this->createMobileUrl('tuiguang');  
        Header("Location: $url");  
    }
    if($cname=="砍价"){
        $url=$this->createMobileUrl('bargain');  
        Header("Location: $url");  
    }
    if($cname=="榜单"){
        $url=$this->createMobileUrl('crunchies');  
        Header("Location: $url");  
    }
    if($cname=="2小时跑单"){
        $url=$this->createMobileUrl('crunlist');  
        Header("Location: $url");  
    }
    if($cname=="全天销售榜"){
        $url=$this->createMobileUrl('crunlist2');  
        Header("Location: $url");  
    }
    if($cname=="实时排行榜"){
        $url=$this->createMobileUrl('crunlist3');  
        Header("Location: $url");  
    }
    if($cname=="拼购"){
        $url=$this->createMobileUrl('pingou');  
        Header("Location: $url");  
    }
    if($cname=="index"){
        $url=$this->createMobileUrl('index');  
        Header("Location: $url");  
    }
    if($cname=="pddindex"){
        $url=$this->createMobileUrl('pinduoduoindex');  
        Header("Location: $url");  
    }
    if($cname=="pddsearch"){
        $url=$this->createMobileUrl('searchlist');  
        Header("Location: $url");  
    }
    if($cname=="pddcatelist"){
        $url=$this->createMobileUrl('pinduoduoclasslist');  
        Header("Location: $url");  
    }
    if($cname=="choiceness"){
        $url=$this->createMobileUrl('choiceness');  
        Header("Location: $url");  
    }
    if($cname=="bigsearch"){
        $url=$this->createMobileUrl('searchlist');  
        Header("Location: $url");  
    }
    if($cname=="my"){
        $url=$this->createMobileUrl('my');  
        Header("Location: $url");  
    }
    if($cname=="mogujieclasslist"){
        $url=$this->createMobileUrl('mogujieclasslist');
        Header("Location: $url");
    }
    if($cname=="bigsearch"){
        $url=$this->createMobileUrl('searchlist');
        Header("Location: $url");
    }
}
getUserInfo(0);
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.17 choiceness.inc.php in 75
$member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
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

$head_menu	=	object_array(json_decode($global['head_menu']));

$page		=	1;
$size		=	20;

//$page		=	$size*($page-1);

if(!empty($_GPC['cname']))
{
    $cname	=	trim($_GPC['cname']);
    $good_list_0	=	get_good_list_cname($page,$size,0,$cname);
    $good_list_1	=	get_good_list_cname($page,$size,1,$cname);
    $good_list_2	=	get_good_list_cname($page,$size,2,$cname);
    $good_list_3	=	get_good_list_cname($page,$size,3,$cname);
}
elseif(!empty($_GPC['keyword']))
{
    $keyword		=	trim($_GPC['keyword']);
    
    $good_list_0	=	get_good_list_search($page,$size,0,$keyword);
    $good_list_1	=	get_good_list_search($page,$size,1,$keyword);
    $good_list_2	=	get_good_list_search($page,$size,2,$keyword);
    $good_list_3	=	get_good_list_search($page,$size,3,$keyword);
}
else
{
    $good_list_0	=	get_good_list($page,$size,0,$keyword);
    $good_list_1	=	get_good_list($page,$size,1,$keyword);
    $good_list_2	=	get_good_list($page,$size,2,$keyword);
    $good_list_3	=	get_good_list($page,$size,3,$keyword);
}

/*
var_dump($good_list_0);

var_dump($good_list_1);
var_dump($good_list_2);
var_dump($good_list_3);
*/

include $this->template('choiceness');

?>