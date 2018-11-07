<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;

if(empty($_GPC['pix']))	$_GPC['pix']=0;
$_W['page']['title']='首页';

getUserInfo(0);
//var_dump($_W['user_info']);
get_global();

$global		=	$_W['global'];
//echo "123::".$global["homepage_status"];exit;
if(!empty($global["homepage_status"])){
	$homepage	=	explode('=',$global["homepage_status"]);
	
	$cname=$homepage[1];
	
	
	if($cname=="index"){
        $url=$this->createMobileUrl('index');  
        //Header("Location: $url");  
    }
    if($cname=="pddindex"  && empty($_GPC["rt"])){
        $url=$this->createMobileUrl('pinduoduoindex');  
		Header("Location: $url");  
		exit;
	}
	
    if($cname=="pddsearch"){
        $url=$this->createMobileUrl('pinduoduosearchlist');  
		Header("Location: $url");  
		exit;
    }
    if($cname=="choiceness"){
        $url=$this->createMobileUrl('choiceness');  
		Header("Location: $url");  
		exit;
    }
    if($cname=="bigsearch"){
        $url=$this->createMobileUrl('supersearch');  
		Header("Location: $url"); 
		exit; 
    }
    if($cname=="my"){
        $url=$this->createMobileUrl('my');  
		Header("Location: $url");  
		exit;
    }

}
//验证是否启用diy页面了
if($global['isopen_diypage']==1){
	//跳转到diy页面
	$url=$this->createMobileUrl('index4');  
	Header("Location: $url");  
	exit;
}
if($_W['user_info']['shopname']){
	$global["title"]=$_W['user_info']['shopname'];
}
if($_W['user_info']['shoplogo']){
	$global["logo"]=$_W['user_info']['shoplogo'];
}
if($_W['user_info']['shopdesc']){
	$global["remark"]=$_W['user_info']['shopdesc'];
}
$banner		=	object_array(json_decode($global['banner']));
$ad_menu	=	object_array(json_decode($global['ad_menu']));

$head_menu		=	object_array(json_decode($global['head_menu']));
$picture_menu	=	object_array(json_decode($global['picture_menu']));
$picture_menu2	=	object_array(json_decode($global['picture_menu2']));


//查询当前会员 原openid变更为uid zxq.2018.05.17 in index.inc.php in 78
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

if($member['type']==1){
	$global['homeskinid']=$member['homeskinid'];
}
$from_member_shop='';
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
//echo $_GPC["keyword"];
if(check_url($_GPC["keyword"])){
	//echo $_GPC["keyword"];
	$strarr=explode("/",$_GPC["keyword"]);
	$_GPC["keyword"]=$strarr[count($strarr)-1];
	$_GPC["keyword"]=str_replace(".html","",$_GPC["keyword"]);
}
if(!empty($_GPC["ajax"])){
	/*
	* 商品列表
	* uri 参数可选[list,listbysearch,listbycname,cname],默认传list
	* page 当前页数 默认1
	* pagesize 每页记录数 默认20
	* sort 排序 默认0，券后价1，优惠幅度2，佣金比例3，销量4(暂时无用)
	* keyword 搜索关键字，默认为空，仅uri参数等于listbysearch时生效
	* minprice 最小金额，默认为空，仅uri参数等于listbysearch时生效
	* maxprice 最大金额，默认为空，仅uri参数等于listbysearch时生效
	* mincommission 最小佣金，默认为空，仅uri参数等于listbysearch时生效
	* maxcommission 最大佣金，默认为空，仅uri参数等于listbysearch时生效
	* cname 分类名称，仅uri参数等于listbycname时生效
	*/
	//echo $_GPC["page"]."<br/>";
	$uri=$_GPC["uri"];
	$page=$_GPC["page"];
	$pagesize=$_GPC["pagesize"];
	$sort=$_GPC["sort"];
	
	$keyword=$_GPC["keyword"];
	$minprice=$_GPC["minprice"];
	$maxprice=$_GPC["maxprice"];
	$mincommission=$_GPC["mincommission"];
	$maxcommission=$_GPC["maxcommission"];
	$cname=$_GPC["cname"];
	$errno = 0;
	$message="操作成功";
	$list=getgoodlist($uri,$page,$pagesize,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname);
	if(empty($list)){
		exit("");
	}
	exit(json_encode($list));
}

function check_url($url){
		if(!preg_match('/https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$url)){
			return false;
		}
		return true;	
}
if(empty($global['homeskinid'])){
	include $this->template('index');
	
}else if($global['homeskinid']=="1"){
	include $this->template('index2');
	
}else{
	include $this->template('index3');
	
}


?>