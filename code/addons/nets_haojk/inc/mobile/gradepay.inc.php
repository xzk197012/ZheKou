<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['level']))
    message('参数错误，没有找到要升级的盟主信息！',$this->createMobileUrl('grade'),'error');
$_W['page']['title']='升级盟主付费';

getUserInfo(1);
get_global();

$credit1_to_credit2 = intval($_W["hjk_global"]['credit1_to_credit2']);
if(intval($credit1_to_credit2)<=0)
    $credit1_to_credit2 = 100;
//升级盟主付费 查询当前会员等级信息 取消uniacid条件，原openid变更uid zxq.2018.05.17 gradepay.inc.php in 16
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2,l.name as levelname,l.recharge_get FROM ".tablename("nets_hjk_members")." AS em left join "
    .tablename("mc_members")." AS m on em.memberid=m.uid  left join "
    .tablename("nets_hjk_memberlevel")." AS l on em.level=l.name and l.type=1  and l.uniacid=:uniacid  where  em.memberid=:memberid"
    ,array(":uniacid"=>$_W["uniacid"],":memberid"=>$_W["fans"]["uid"]));

$credit1 = $member['credit1'];//用户当前积分余额
$recharge_get = 0;//升级已花费积分
if(!empty($member['levelname']))
    $recharge_get=$member['recharge_get'];
$levelname = $member['levelname'];//用户当前等级
$maxlevelrecharge_get = 0;
if(intval($member['levelname'])>=intval($_GPC['level']))
    message('无法升级到当前等级！',$this->createMobileUrl('grade'),'error');
$levelinfo=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_memberlevel")."  where type =1  and uniacid=:uniacid and name=:name order by name",array(":uniacid"=>$_W["uniacid"],":name"=>$_GPC['level']));


if(empty($levelinfo))
    message('参数错误！',$this->createMobileUrl('grade'),'error');
$nextlevelname = '白银盟主';
if($levelinfo['name']=='1')
    $nextlevelname = '白银盟主';
if($levelinfo['name']=='2')
    $nextlevelname = '黄金盟主';
if($levelinfo['name']=='3')
    $nextlevelname = '铂金盟主';
if($levelinfo['name']=='4')
    $nextlevelname = '钻石盟主';
$releader_credit1=intval($levelinfo['recharge_get'])-intval($recharge_get);

if($releader_credit1<0||$releader_credit1<=intval($credit1))
    message('参数错误,无法升级！',$this->createMobileUrl('grade'),'error');
$fee = ($releader_credit1-$credit1)/$credit1_to_credit2;
//

$orderno	=	date('YmdHis').rand(111,999);
$data		=	array();
$data['title']	=	$_GPC['level'];
$data['uniacid']	=	$uniacid;
$data['memberid']	=	$_W['fans']['uid'];
$data['type']		=	4;
$data['logno']		=	$orderno;
//$data['title']		=	'升级'.$nextlevelname.'付费';
$data['status']		=	0;
$data['money']		=	$fee;
$logs["rechargetype"]="credit2";
$data['credit1']	=	$_W['member']['credit1'];
$data['credit2']	=	$_W['member']['credit2'];
$data['remark']		=	'升级盟主付费';
$data['created_at']	=	time();
$res	=	pdo_insert('nets_hjk_member_logs',$data);

if($res)
{
    $params = array(
        'tid' 		=> 	$orderno,      			//充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
        'ordersn' 	=> 	$orderno,  				//收银台中显示的订单号
        'title' 	=> 	'升级'.$nextlevelname,          	//收银台中显示的标题
        'fee' 		=> 	$fee,  	//收银台中显示需要支付的金额,只能大于 0
        'user' 		=> 	$_W['member']['uid'],  	//付款用户, 付款的用户名(选填项)
    );
    //var_dump($params);
    $this->pay($params);	//调用pay方法
}
else
{
    message('操作失败',$this->createMobileUrl('grade'),'error');
}

?>