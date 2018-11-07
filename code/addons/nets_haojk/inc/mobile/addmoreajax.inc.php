<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
		
getUserInfo(0);
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.17 addmoreajax.inc.php in 9
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
$size	=	20;

$page	=	intval($_GPC['page'])+1;
$sort	=	intval($_GPC['sorts']);

//$page	=	$size*($page-1);

if(!empty($_GPC['cname']))
{
    $cname	=	trim($_GPC['cname']);
    $result	=	get_good_list_cname($page,$size,$sort,$cname);
}
elseif(!empty($_GPC['keyword']))
{
    $keyword=	trim($_GPC['keyword']);
    $result	=	get_good_list_search($page,$size,$sort,$keyword);
}
else
{
    $result	=	get_good_list($page,$size,$sort,$keyword);
}

if(!empty($result))
{
    $status	=	1;
    $info	=	'';
    $str	=	'';
    $list	=	$result;
    foreach($list as $good)
    {
		$subsidy=number_format(number_format($good['wlPrice_after'],2)*number_format($good['wlCommissionShare'],2)/100*number_format($rate)/100,2);
		if($member["type"]==0){
			$subsidy=number_format($good['wlPrice_after']*$good['wlCommissionShare']/100*$_W["hjk_global"]["subsidy"]/100,2);
		}
		$subsidy_str='<div class="v_price">'.$_W['user_info']['subsidyname']." " . $subsidy . '</div>';
		if(empty($_W["hjk_global"]["isopen_subsidy"]) && $member["type"]==0){
			$subsidy_str="";
        }
        if(empty($_W["hjk_global"]["isshow_subsidy"])){
            $subsidy_str="";
        }
        $shengjizhuan=($good['wlPrice_after'] * $good['wlCommissionShare']/100 * 0.9 * $_W['user_info']['memberlevel']['max_credit2']/100);
		$shengjizhuan=number_format($shengjizhuan,2); 
		$voucher= intval ($good['discount']);
		
		$jd_ico="jd.png";
		$cls="jd-icons";
		if($good['goodslx']=="1"){
			$jd_ico="jd-icon.png";	
			$cls="jd-icon";
		}
        $str.=
        '<a class="good-item J_link" href="javascript:;">' .
		'<input type="hidden" class="J_link_value" value=\''.json_encode($good).'\'>' .
        '<div class="left" style="width:35%; float: left">
		<div class="img" style="width:100%; height: auto">
		<img  style="width:2.5rem; height:2.5rem" src="' . $good['picUrl'] . '" alt="">
		</div>
		</div>' .
        '<div class="right"  style="width:62%; float: right">' .
        '<div class="title" style="color: #404040; font-size: 14px;">
		<div class="img">
		<img src="../addons/nets_haojk/template/mobile/img/'.$jd_ico.'" class="'.$cls.'" alt="">
		</div>' . $good['skuName'] . '</div>' .
        '<div class="infor1" style="margin-bottom: 10px;">' .
		'<div class="quan-price">' .
        '<span class="text styel-fonts">券后价</span>' .
		'<je class="price_js">¥</je>'.
        '<span class="num">' . $good['wlPrice_after'] . '</span>' .
	    '<present class="present">现价:'. $good['wlPrice'] . '</present>' .
        '</div>' .
        '</div>' .
        '<div class="infor2">' .
        '<div class="left">' .
        '<div class="quan-box">' .
        '<div class="j">券</div>' .
        '<div class="nums">' .$voucher. '</div>' .
        '</div>' .
        '</div>' .
        '<div class="right_1">' .
         $subsidy_str.
        '</div>' .
		'<label class="supplement" onclick="toleveup_zhuan()">升级赚￥'.$shengjizhuan.'</label>'.
        '</div>' .
        '</div>' .
        '</a>';
		
    }
    $next	=	count($list)<$size?0:$page;
}
else
{
    $status	=	0;
    $info	=	'没有更多了';
    $list	=	array();
    $next	=	0;
}


echo json_encode(array('status'=>$status,'info'=>$info,'list'=>$str,'next'=>$next));


?>