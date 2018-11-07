<?php
	/**
	 * 对外开放的api,不需要验证用户身份
	 * API 地址：http://www.91fyt.com/app/index.php?i=56&c=entry&m=nets_haojk&do=api 
	 * 请求方式：POST
	 * 参数：
	 * 1. oper：执行的操作，枚举可选下面对应的函数名
	 */
	defined('IN_IA') or exit('Access Denied');
	require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
	
	global $_GPC, $_W;
	getUserInfo(1);
	//var_dump($_W['user_info']);
	$oper=$_GPC["oper"];
	$openid=$_W["openid"];
	$result=array("code"=>500,"result"=>0,"msg"=>"操作失败,不允许的操作");
	//通过传递的oper调用对应的函数,oper参数与函数名一致
	if (function_exists($oper)) {
		$result=$oper();
	}
	
	exit(json_encode($result));
	//exit(json_encode($result));
	
	function testBindMobile(){
		$mobile="13913904762";
		$isbind=true;
		$iswxapp=true;
		$res=bindMobile($mobile,"123123","123456",$isbind,$iswxapp);
		var_dump($res);
		exit;
	}
	/*
	* 发送短信验证码2分钟有效期
	* 参数1：mobile，手机号
	*/
	function AjaxSms(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$mobile=$_GPC['mobile'];
		if(empty($_GPC['mobile'])){
			$message="手机号码不能为空";
			return array('code'=>0,'message'=>$message,'status'=>-1);
		}
		if(empty($_W['openid'])){
			$message="非法访问";
			//return array('code'=>0,'message'=>$message,'status'=>-1);
			//return $this->result($errno, $message, 0);
		}
		$res=sendsms($mobile);
		
		return $res;
	}
	/*
	* 发送短信验证码2分钟有效期
	* 参数1：mobile，手机号
	* 参数2：code，收到的验证码
	* 参数3：isbind，默认false，在绑定提示手机号码已被其他人绑定后，用户确认再次绑定的时候isbind=true
	*/
	function AjaxBindMobile(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$mobile=$_GPC['mobile'];
		$code=$_GPC['code'];
		$isbind=$_GPC['isbind'];
        $password=$_GPC['password'];
		if(empty($_GPC['mobile'])){
			$message="手机号码不能为空";
			return array('code'=>0,'message'=>$message,'status'=>-1);
		}
		if(empty($_GPC['code'])){
			$message="验证码不能为空";
			return array('code'=>0,'message'=>$message,'status'=>-1);
		}
        if(empty($_GPC['password'])){
            $message="密码不能为空";
            return array('code'=>0,'message'=>$message,'status'=>-1);
        }
		if(empty($_W['openid'])){
			$message="非法访问";
			//return array('code'=>0,'message'=>$message,'status'=>-1);
		}
		
		$res=bindMobile($mobile,$code,$password,$isbind,false);
		if(!empty($res)){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $res;
	}

	/*
	* 设置首页模板
	* 参数1：skinid，默认0老版的首页，1新版的首页
	*/
	function AjaxSetShopSkin(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skinid=$_GPC['skinid'];

		//查询当前会员 原openid变更为uid zxq.2018.05.17 api.inc.php in 101
		$member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$i=pdo_update('nets_hjk_members',array('homeskinid'=>$skinid),array('id'=>$member['id']));
		if($i>0){
			$_SESSION['member']['homeskinid']=$skinid;
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $i;
	}
	/*
	* 设置店铺名称
	* 参数1：shopname 店铺名称
	* 参数2：shopdesc 店铺描述
	* 参数3：shoplogo 店铺logo
	*/
	function AjaxSetShopName(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$shopname=$_GPC['shopname'];
		$shopdesc=$_GPC['shopdesc'];
		$shoplogo=$_GPC['shoplogo'];
		$jduniacid=$_GPC['jduniacid'];
		//查询当前会员 原openid变更为uid zxq.2018.05.17 api.inc.php in 126
		$member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$i=pdo_update('nets_hjk_members',array('jduniacid'=>$jduniacid,'shopname'=>$shopname,'shopdesc'=>$shopdesc,'shoplogo'=>$shoplogo),array('id'=>$member['id']));
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $i;
	}

	/**
	 * 查询订单统计
	 */
	function AjaxGetOrdersIncomeStatistics(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersIncomeStatistics();
		//var_dump($res);
		
		return $res;
	}
	/**
	 * 查询今日订单统计
	 */
	function AjaxordersIncomeStatistics_today(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersIncomeStatistics_today();
		//var_dump($res);
		
		return $res;
	}
		/**
	 * 查询昨天订单统计
	 */
	function AjaxordersIncomeStatistics_yester(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersIncomeStatistics_yester();
		//var_dump($res);
		
		return $res;
	}
		/**
	 * 查询当月订单统计
	 */
	function AjaxordersIncomestatistics_thismonth(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersIncomestatistics_thismonth();
		//var_dump($res);
		
		return $res;
	}
	/**
	 * 查询上月订单统计
	 */
	function AjaxordersIncomestatistics_yestermonth(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersIncomestatistics_yestermonth();
		//var_dump($res);
		return $res;
	}
	/**
	 * 登陆
	 */
	function AjaxLogin(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$mobile=$_GPC['mobile'];
		$password=$_GPC['password'];
		$password=md5($password);
		//客户端登录 取消uniacid查询条件 zxq.2018.05.17 api.inc.php in 207
		$member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.password=:password and em.mobile=:mobile",array(":password"=>$password,":mobile"=>$mobile));
		
		if(!empty($member)){
			//客户端登录成功后重新获取会员完整信息，原openid变更为uid zxq.2018.05.17 api.inc.php in 211
			$member=getmemberinfo($member['memberid']);
			$_SESSION['member']=$member;
			$message="登陆成功";
			return array('code'=>0,'message'=>$message,'status'=>200);
			
		}else{
			$message="操作失败";
		}
		return array('code'=>0,'message'=>$message,'status'=>-1);
	}
	/**
	 * 登陆
	 */
	function AjaxGoodsDetail(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuid=$_GPC['skuid'];
		
		if(!empty($skuid)){
			$res=getGoodsDetail($skuid);
			return array('code'=>0,'message'=>$message,'res'=>$res);
			
		}else{
			$message="操作失败";
		}
		return array('code'=>0,'message'=>$message,'res'=>'');
	}
	/**
	 * 登陆
	 */
	function AjaxGoodsDetailJD(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuid=$_GPC['skuid'];
		
		if(!empty($skuid)){
			$res=getGoodsDetailJD($skuid);
			return array('code'=>0,'message'=>$message,'res'=>$res);
			
		}else{
			$message="操作失败";
		}
		return array('code'=>0,'message'=>$message,'res'=>'');
	}

	//上传图片，返回图片URL
	function upload(){
		global $_W;
		global $_GPC;
		if (isset($_POST['upload'])) {
		  $fiename='/images/'.time().'.jpg';
		  move_uploaded_file($_FILES['upfile']['tmp_name'], ATTACHMENT_ROOT.$fiename);
		  exit($_W['siteroot']."attachment".$fiename);
		}
	}

	//获取关键词列表
	function AjaxGetsearchkeyword(){
		global $_GPC, $_W;
		$errno = 0;
		$message="success";
		
		$res=getsearchkeyword();
		return array('code'=>0,'message'=>$message,'res'=>$res);
		
	}
	/*
	* 首页订单弹幕接口虚拟数据列表，弹幕信息从列表里随机抽取一个
	*/
	function AjaxGetOrderTipInfo(){
		global $_GPC, $_W;
		$errno = 0;
		$message="success";
		
		$ordertip=pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_ordertip")." where uniacid=:uniacid order by id desc limit 0,50 ",array(":uniacid"=>$_W["uniacid"]));
		if($ordertip){
			$message="success";
		}else{
			$message="error";
		}
		return array('code'=>0,'message'=>$message,'res'=>$ordertip);
		
	}

	/**
	 * 发送客服消息
	 * 参数1：skuId  ，商品id
	 * 参数2：skuDesc，商品描述
	 * 参数3：unionUrl，购买链接
	 * 参数4：wlPrice_after，券后价
	 * 参数5：wlPrice，商品价格
	 * 参数6：skuName，商品名称
	 * 参数7：yuebu，约补金额
	 * 参数8: haibaourl，海报url
	 */
	function AjaxSendServerNotice(){
		global $_GPC, $_W;
		$searchurl=$_W['siteroot']."app/index.php?i=".$_W['uniacid']."&c=entry&m=nets_haojk&do=enjoy";
		
		$openid=$_W['openid'];
		$skuId=$_GPC['skuId'];
		$skuDesc=$_GPC['skuDesc'];
		$unionUrl=$_GPC['unionUrl'];
		$wlPrice_after=$_GPC['wlPrice_after'];
		$wlPrice=$_GPC['wlPrice'];
		$skuName=$_GPC['skuName'];
		$yuebu_str="
	 ".$_GPC['yuebu'];
	 $yuebu_str="";
		if($_W['account']['level'] >= ACCOUNT_SUBSCRIPTION_VERIFY) { 
			$info = "【{$_W['account']['name']}】充值通知\n";
			$info .= "您在进行会员余额充值，充值金额【9】元，充值后余额9】元。\n";
			$detailurl=url('entry',array('m'=>'nets_haojk','do'=>'searchdetail','skuId'=>$skuId));
			$detailurl=$_W['siteroot'].substr($detailurl,2,strlen($detailurl));
			$message_str="【JD】".$skuName."
————————
京东价：￥".$wlPrice."
内购价：￥".$wlPrice_after.$yuebu_str."
领券优惠购买：".$unionUrl."
————————
【推荐理由】".$skuDesc."
京东商城 正品保证";

			$message = array(
				'msgtype' => 'text',
				'text' => array('content' => urlencode($message_str)),
				'touser' => $openid,
			);
			$account_api = WeAccount::create();
			$status = $account_api->sendCustomNotice($message);
			if (is_error($status)) {
				$tip= '发送失败，原因为' . $status['message'];
				return array('code'=>-1,'message'=>$tip,'res'=>'');
			}

			//任意指定一个文件上传
			$result = $account_api->uploadMedia(IA_ROOT . '/addons/nets_haojk/cache/'.$skuId.'_exqrcode.jpg', 'image');
			if(!empty($result['media_id'])){
				$message = array(
					'msgtype' => 'image',
					'image' => array('media_id' => $result['media_id']),
					'touser' => $openid,
				);
				$status = $account_api->sendCustomNotice($message);	
				if (is_error($status)) {
					$tip= '发送失败，原因为' . $status['message'];
					return array('code'=>-1,'message'=>$tip,'res'=>'');
				}
			}
			
			//发送成功
			return array('code'=>1,'message'=>'发送成功','res'=>'');
		}
		return array('code'=>-1,'message'=>'没有发送权限','res'=>'');
	}

	/*
	* 设置京东联盟id
	* 参数1：jduniacid，
	*/
	function AjaxSaveJduniacid(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$jduniacid=$_GPC['jduniacid'];
		//合伙人设置京东联盟id，查询当前会员原openid查询变更为uid zxq.2018.05.17  api.inc.php in 383
		$member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em   where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$i=pdo_update('nets_hjk_members',array('jduniacid'=>$jduniacid),array('id'=>$member['id']));
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $i;
	}

	function ApiTest(){
		global $_W;
		global $_GPC;
		$res=ordersIncomeStatistics_today();
		var_dump($res);
		exit;
	}

	//查询签到记录
	function doPageSignLog(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		//查询签到记录 当前会员查询原openid变更为uid zxq.2018.05.17 api.inc.php in 408
		$member=pdo_fetch("SELECT *,date_format(now(), '%m.%d') FROM ".tablename("nets_hjk_members")."  where memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$uid = $member["memberid"];
		$type=2;
		$page=$_GPC["page"];
		$pagesize=10;
		$state=$_GPC["state"];
		$where="where memberid=:memberid and remark='签到'";
		$data[":memberid"]=$uid;
		$sql="SELECT *,date_format(from_unixtime(created_at), '%m.%d') AS 'today'  FROM ".tablename("nets_hjk_member_logs").$where." order by id desc LIMIT ".(($page-1)*$pagesize).",".$pagesize;
		$list=pdo_fetchall($sql,$data);
		//var_dump($list);
		return array('code'=>1,'message'=>'成功','res'=>$list);
	}
	//查询签到记录
	function doPageGetcnamelist(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		$clist=getcnamelist("cname",0,0,0,0,0,0,0,0,0);
		//var_dump($list);
		return array('code'=>1,'message'=>'成功','res'=>$clist);
	}

?>