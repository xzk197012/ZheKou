<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;

$_W['page']['title']='申请合伙人';
getUserInfo(1);
get_global();
if(!empty($_GPC["pay"])){
    if(!empty($_GPC['name']) && !empty($_GPC['mobile']) && !empty($_GPC['weixin'])){
        //修改会员信息
        $data['realname']=$_GPC['name'];
        $data['mobile']=$_GPC['mobile'];
        $data['weixin']=$_GPC['weixin'];
        pdo_update('nets_hjk_members',$data,array('memberid'=>$_W['fans']["uid"]));
    }else{
        message("合伙人资料不能为空",$this->createMobileUrl('partnerapply_v1'),'error');
    }
    $orderno	=	date('YmdHis').rand(111,999);
    $data		=	array();
    $fee=$_W['hjk_global']['partner_fee'];
    $data['title']	=	"申请合伙人付费";
    $data['uniacid']	=	$uniacid;
    $data['memberid']	=	$_W['fans']['uid'];
    $data['type']		=	4;
    $data['logno']		=	$orderno;
    $data['status']		=	0;
    $data['money']		=	$fee;
    $logs["rechargetype"]="credit2";
    $data['credit1']	=	$_W['member']['credit1'];
    $data['credit2']	=	$_W['member']['credit2'];
    $data['remark']		=	'申请合伙人付费';
    $data['created_at']	=	time();
    $res	=	pdo_insert('nets_hjk_member_logs',$data);
    if($res){
        $params = array(
            'tid' 		=> 	$orderno,      			//充值模块中的订单号，此号码用于业务模块中区分订单，交易的识别码
            'ordersn' 	=> 	$orderno,  				//收银台中显示的订单号
            'title' 	=> 	'申请合伙人付费',          	//收银台中显示的标题
            'fee' 		=> 	$fee,  	//收银台中显示需要支付的金额,只能大于 0
            'user' 		=> 	$_W['member']['uid'],  	//付款用户, 付款的用户名(选填项)
        );
        $this->pay($params);	//调用pay方法
    }
    exit;
}
include $this->template('partnerapply_v1');


?>