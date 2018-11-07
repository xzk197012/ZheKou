<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;
if(empty($_GPC['pix']))	$_GPC['pix']=2;
$_W['page']['title']='申请盟主';

getUserInfo(1);
get_global();
//查询当前会员 原openid变更为uid zxq.2018.05.17 in topay.inc.php in 11
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

$orderno	=	date('YmdHis').rand(111,999);

$data		=	array();
$data['uniacid']	=	$_W['uniacid'];
$data['memberid']	=	$_W['fans']['uid'];
$data['type']		=	4;
$data['logno']		=	$orderno;
$data['title']		=	'申请盟主';
$data['status']		=	0;
$data['money']		=	$_W['global']['applyleader_fee'];
$data['credit1']	=	$_W['member']['credit1'];
$data['credit2']	=	$_W['member']['credit2'];
$data['remark']		=	'申请盟主';
$data['created_at']	=	time();

$res	=	pdo_insert('nets_hjk_member_logs',$data);

if($res)
{
	//applyloaderMsg($member['openid'],0,'','',"正在申请盟主");
	//sendOwnerApplayMsg($member['nickname'],0,'','',"正在申请盟主");
    $params = array(
        'tid' 		=> 	$orderno,      			//充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
        'ordersn' 	=> 	$orderno,  				//收银台中显示的订单号
        'title' 	=> 	'申请盟主',          	//收银台中显示的标题
        'fee' 		=> 	$_W['global']['applyleader_fee'],  	//收银台中显示需要支付的金额,只能大于 0
        'user' 		=> 	$_W['member']['uid'],  	//付款用户, 付款的用户名(选填项)
    );
    //var_dump($params);
    $this->pay($params);	//调用pay方法
}
else
{
    message('操作失败',$this->createMobileUrl('my'),'error');
}

?>