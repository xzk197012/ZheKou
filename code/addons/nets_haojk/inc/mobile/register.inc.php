<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';

global $_W,$_GPC;

get_global();
$global		=	$_W['global'];
if(!empty($_GPC['ajax'])){
    //注册会员
    $sql = 'SELECT `uid` FROM ' . tablename('mc_members') . ' WHERE `uniacid`=:uniacid and mobile=:mobile';
    $sql1 = 'SELECT `memberid` FROM ' . tablename('nets_hjk_members') . ' WHERE `uniacid`=:uniacid and mobile=:mobile';
    $pars = array();
    $pars[':uniacid'] = $_W['uniacid'];
    $pars[':mobile'] = $_GPC['mobile']; 
    $password=$_GPC['password'];   
    $user = pdo_fetch($sql, $pars);
    $user1 = pdo_fetch($sql1, $pars);
    if(!empty($user) || !empty($user1)) {
        itoast('该手机号已被注册.', '', '');
    }
    
        if(!check_hjk_code($_GPC['mobile'],$_GPC['code'])){
            itoast('你输入的验证码不正确, 请重新输入.', '', '');
        }
        $default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
		$data = array(
			'uniacid' => $_W['uniacid'], 
			'salt' => random(8),
			'groupid' => $default_groupid, 
			'createtime' => TIMESTAMP,
        ); 
        if (!empty($password)) {
			$data['password'] = md5($password . $data['salt'] . $_W['config']['setting']['authkey']);
		}
		$data['mobile'] = $_GPC['mobile'];
        pdo_insert('mc_members', $data);
        $uid = pdo_insertid();
        if(!empty($uid)){
            $member['uid']=$uid;
            $member['mobile']=$_GPC['mobile'];
            $member['password']=$password;
            $i=register_hjk_member($member);
            if($i>0){
                itoast('注册成功，先去登陆吧.', url('entry',array('m'=>'nets_haojk','do'=>'my')), '200');
            }
        }
        itoast('注册失败，请联系管理员解决！', '', '');
}

//验证码是否过期
function check_hjk_code($mobile,$codes){
    global $_W,$_GPC;
    $code=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_smsrecord")." where uniacid=:uniacid and  mobile=:mobile and code=:code",array(":uniacid"=>$_W["uniacid"],':mobile'=>$mobile,':code'=>$codes));
	//var_dump($code);
	$msg=array("success"=>false,'message'=>'操作失败','status'=>'-1');
	if(empty($code)){
		$msg['message']='[01]验证码已过期，请重新获取!';//.$_W["uniacid"]."_".$mobile."_".$code;
		return false;
	}
	$exprietime=time()-$code['createtime'];

	if($exprietime>360){
		$msg['message']='[02]验证码已过期，请重新获取!';
		return false;
    }
    return true;
}
//注册会员到模块表里
function register_hjk_member($member){
    global $_W,$_GPC;
    $from_uid=$_SESSION['from_uid'];
    if(empty($from_uid)){
        $from_uid=0;
    }
    $hjk_member['uniacid']=$_W['uniacid'];
    $hjk_member['openid']=$member['mobile'];
    $hjk_member['memberid']=$member['uid'];
    $hjk_member['username']=$member['mobile'];
    $hjk_member['nickname']=$member['mobile'];
    $hjk_member['mobile']=$member['mobile'];
    $hjk_member['password']=md5($member['password']);
    $hjk_member['created_at']=time();
    $hjk_member['updated_at']=time();
    $i=pdo_insert('nets_hjk_members',$hjk_member);
    return $i;
}

include $this->template('register');


?>