<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='发起提现';

getUserInfo(1);
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.17 in tixianpay.inc.php in 11
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

$money=$member['credit2'];
$mincash=$_W['global']['mincash'];

if(empty($money)){
    message('提现金额不能为空！',$this->createMobileUrl('my'),'error');
}

if($money<=0)
{
    message('余额不足无法提现',$this->createMobileUrl('my'),'error');
}
if($money<$mincash){
    message('提现金额不能小于最小可提现金额￥'.$mincash.'！',$this->createMobileUrl('my'),'error');
}
if($money>=$mincash)
{
    $rate=$_W['global']['rate'];
    $cash_money=floatval($money)-floatval($money)*floatval($rate);
    $cashtype	=	$_GPC["cashtype"];
    $remark="";
    $alipay	=	$_GPC["alipay"];
    if($cashtype==2){
        if(empty($alipay)){
            message('支付宝账号不能为空！',$this->createMobileUrl('my'),'error');
        }
        $remark="支付宝账号：".$alipay;
    }

    $errno 	=	'success';
    $message=	"操作成功";
    /*
    if($cash_money<$_W['global']['mincash'])
    {
        message("提现金额￥".$cash_money.'不能小于最小提现金额￥'.$_W['global']['mincash'],$this->createMobileUrl('my'),'error');
    }
    */

    $uid 	=	$member["memberid"];
    //保留2位小数
    $cash_money=sprintf("%.2f",$cash_money);
    $logs["uniacid"]	=	$_W["uniacid"];
    $logs["memberid"]	=	$member["memberid"];
    $logs["type"]		=	5;//1积分2佣金3补贴
    $logs["logno"]		=	0;
    $logs["title"]		=	"提现".$money;
    $logs["status"]		=	0;//0 生成 1 成功 2 失败
    $logs["money"]		=	-1*$cash_money;
    $logs["credit1"]	=	$member["credit1"];
    $logs["credit2"]	=	$member["credit2"];
    $logs["rechargetype"]=	"credit2";
    $logs["cashtype"]=	$cashtype;
    $logs["remark"]		=	$remark;
    $logs["created_at"]	=	time();
    $logs["updated_at"]	=	time();
    $logs["deleted_at"]	=	0;
    $i=pdo_insert("nets_hjk_member_logs",$logs);
    if($i>0){
        $remark	=	"申请提现";
        if($_GPC["cashtype"]==2) {
            $upalipay["alipay_no"] = $_GPC["alipay"];
            pdo_update("nets_hjk_members", $upalipay, array('memberid' => $member["memberid"]));
        }
        sendCashMsg($member["openid"],$cash_money);
        sendOwnerCashMsg($member["nickname"],$cash_money);
        $b		= 	mc_credit_update($member["memberid"], "credit2", -1*$money, $log = array(0,$remark,"nets_haojk",0,0,1));
    }else{
        $message=	"操作失败";
        $errno	=	'error';
    }
    message($message,$this->createMobileUrl('my'),$errno);
}

?>