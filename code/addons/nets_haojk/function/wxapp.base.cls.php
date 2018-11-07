<?php
/**
 * 好京客模块小程序接口定义,原小程序接口类
 *
 * @author zhang
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.base.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/freeorder.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/img.func.php';
include_once IA_ROOT . '/addons/nets_haojk/function/wxBizMsgCrypt.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.feed.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.ordersv2.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.helpset.func.php';
require_once IA_ROOT . '/addons/nets_haojk/defines.php';
class WxappBase extends WeModuleWxapp {
    
    public function doPageTest(){
		global $_GPC, $_W;
		ordertest();
		goodtest();
		$errno = 0;
		$message = '返回消息';
		$data = array("list"=>"接口消息返回的数据");
		$isapp="1";
		return $this->result($errno, $message, $data);
	}
	
	/*
	* 获取小程序全局配置接口
	*/
	public function doPageGlobal(){
		global $_GPC, $_W;
		$errno = 0;
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		$level=pdo_fetchall("select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and isuse=1 ",array(":uniacid"=>$_W["uniacid"]));
		for($i=0;$i<count($level);$i++){
			if(empty($l["gradename"])){
				$levelarr=array('普通','白银','黄金','铂金','钻石');
				if($level[$i]["name"]==0){
					$level[$i]["gradename"]="普通";
				}
				if($level[$i]["name"]==1){
					$level[$i]["gradename"]="白银";
				}
				if($level[$i]["name"]==2){
					$level[$i]["gradename"]="黄金";
				}
				if($level[$i]["name"]==3){
					$level[$i]["gradename"]="铂金";
				}
				if($level[$i]["name"]==4){
					$level[$i]["gradename"]="钻石";
				}
				
			}
			if(empty($level[$i]["identityname"])){
				if($level[$i]["type"]==0){
					$level[$i]["identityname"]="会员";
				}else{
					$level[$i]["identityname"]="盟主";
				}
			}
		}
		$global["memberlevel"]=$level;
		$member=getmemberinfo('');
		$global["applygradename"]="盟主";
		if($member["type"]==1){
			//如果是盟主，则是否显示补贴使用盟主的控制字段
			$global["isshow_subsidy"]=$global["isshow_subsidy_dl"];
			//普通会员申请的等级名称展示
			$level0=pdo_fetch("select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and name=0 and type=1",array(":uniacid"=>$_W["uniacid"]));
			if(empty($level0["identityname"])){
				if($level0["type"]==0){
					$level0["identityname"]="会员";
				}else{
					$level0["identityname"]="盟主";
				}
			}
			$global["applygradename"]=$level0["identityname"];
		}else{
			//普通会员申请的等级名称展示
			$level0=pdo_fetch("select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and name=0 and type=1",array(":uniacid"=>$_W["uniacid"]));
			if(empty($level0["identityname"])){
				if($level0["type"]==0){
					$level0["identityname"]="会员";
				}else{
					$level0["identityname"]="盟主";
				}
			}
			$global["applygradename"]=$level0["identityname"];
		}
		if($member["type"]==1 || $member["type"]==2){
			//如果是盟主，则是否显示补贴使用盟主的控制字段
			$global["isshow_subsidy"]=$global["isshow_subsidy_dl"];
		}
		//合伙人的等级查询出来计算升级赚使用
		$partnerlevel=pdo_fetch("select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and name=0 and type=2",array(":uniacid"=>$_W["uniacid"]));
		if(!empty($partnerlevel)){
			$hjk_global["partnerlevel"]=$partnerlevel;
		}	
		//$global['member']=$member;
		$fans=$_W["fans"];
		$uid=$fans["uid"];
		//$global["uid"]=$uid;
		$global['JD_APPID']	=JD_APPID;
		$global['JD_CUSTOMERINFO']	=JD_CUSTOMERINFO;
		$message = '请求成功';
		return $this->result($errno, $message, $global);
	}
	
	/*
	* 添加会员信息,并验证是否有推荐人
	* from_uid 推荐人id，默认传0
	*/
	public function doPageAddMember(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$uid=0;
		//取消多余的 再次查询fans，直接用$_W['fans']里的 zxq.2018.05.19 wxapp.base.cls.php in 108
		//$fans=pdo_fetch("SELECT * FROM ".tablename("mc_mapping_fans")."  where uniacid=:uniacid and openid=:openid",array(":uniacid"=>$_W["uniacid"],":openid"=>$_W["openid"]));
		$fans=$_W["fans"];
		$uid=$fans["uid"];
		$member=getmemberinfo('');
		if(empty($member)){
			$member["from_uid"]=$_GPC["from_uid"];
			if($_W['member']['uid']==$_GPC["from_uid"]){
				$member["from_uid"]=0;
			}
			if(!empty($member['from_uid'])){
				//验证推荐人是否是合伙人的会员
				$from_member=pdo_fetch("select * from ".tablename("nets_hjk_members")." where memberid=:memberid",array(":memberid"=>$member['from_uid']));
				if(!empty($from_member)){
					//继承上级用户的jduniacid
					$member['from_jduniacid']=$from_member['jduniacid'];
					$member['from_uid2']=$from_member['from_uid'];
					//继承推荐人的合伙人id
                    if($from_member['type']==2)
                        $member['from_partner_uid']=$from_member['memberid'];
                    else{
					    $member['from_partner_uid']=$from_member['from_partner_uid'];
                    }
				}
			}
			if(empty($uid)){
				return $this->result($errno, "会员信息错误", false);
			}
			$member["uniacid"]=$_W["uniacid"];
			$member["memberid"]=$uid;
			$member["openid"]=$_W["openid"];
			$member["sex"]=$_W['fans']["gender"]=="1"?"男":"女";
			$member["province"]=$_W['member']["province"];
			$member["city"]=$_W['member']["city"];
			$member["avatar"]=$_W['fans']["avatar"];
			$member["username"]=$_W['fans']["nickname"];
			$member["nickname"]=$_W['fans']["nickname"];
			$member["pid"]=0;
			$member["type"]=0;
			$member["level"]=0;
			$member["created_at"]=time();
			$member["updated_at"]=time();
			$i=pdo_insert("nets_hjk_members",$member);
			if($i>0){
				newmember_credit1($_GPC["from_uid"]);
				$isapp="1";
				sendNewLevelMsg($_W["openid"],$isapp);
			}
			$member=getmemberinfo('');
			
			
		}else{
			
		}
		xcx_syncmember();
		$member=getmemberinfo('');
		$member["fans"]=$fans;
		//如果头像与昵称有个为空或者不一样的则修改
		$hjkmember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where (nickname is null or avatar is null) and memberid=:memberid",array(":memberid"=>$member['memberid']));
		if(empty($hjkmember['avatar']) || empty($hjkmember['nickname'])){
		//if(true){
			$m["avatar"]=$_W['fans']["avatar"];
			$m["username"]=$_W['fans']["nickname"];
			$m["nickname"]=$_W['fans']["nickname"];
			$m["updated_at"]=time();
			$i=pdo_update("nets_hjk_members",$m,array("memberid"=>$member['memberid']));
		}
		//$member['wm']=$_W['fans'];
		return $this->result($errno, $message, $member);
	}
	/*
	* 修改会员信息
	* from_uid 推荐人id，默认传0
	*/
	public function doPageUpdateMember(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$uid=0;
		//取消openid查询会员改用uid zxq.2018.05.19 wxapp.base.cls.php in 176
		$hjkmember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where memberid=:memberid",array(":memberid"=>$_W['fans']['uid']));
		$m["avatar"]=$_GPC["avatar"];
		$m["username"]=$_GPC["nickname"];
		$m["nickname"]=$_GPC["nickname"];
		$m["sex"]=$_GPC['gender']==1?"男":"女";
		$m["updated_at"]=time();
		$i=pdo_update("nets_hjk_members",$m,array("memberid"=>$member['memberid']));
		$hjkmember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where memberid=:memberid",array(":memberid"=>$_W['fans']['uid']));
		
		return $this->result($errno, $message, $hjkmember);
	}
	
	/*
	* 获取我的团队列表，一级盟主、二级盟主
	*/
	public function doPageMyteam(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		//取消openid查询会员改用uid zxq.2018.05.19 wxapp.base.cls.php in 176
		$member=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_members")."  where memberid=:memberid",array(":memberid"=>$_W['fans']['uid']));
		
		$uid = $member["memberid"];
		$page=empty($_GPC["page"])?1:$_GPC['page'];
		$pagesize=20;
		if(empty($uid)){
			$uid=0;
		 }
		 //删掉没用的代码，zxq.2018.05.18 wxapp.base.cls.php in 202
		//一级会员
		$result["team0"]=pdo_fetchall("select id,memberid,nickname,avatar,username,0 AS 'commission',0 AS 'ordercount',0 AS 'subsidy', FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_members')." where (from_uid=:memberid or from_partner_uid=:memberid) and uniacid=:uniacid order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
		$result["team0_count"]=pdo_fetchcolumn("select count(0) FROM ".tablename('nets_hjk_members')." where  (from_uid=:memberid or from_partner_uid=:memberid) and uniacid=:uniacid ",array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
		//一级盟主
		$result["team1"]=pdo_fetchall("select id,memberid,nickname,avatar,username,0 AS 'commission',0 AS 'ordercount',0 AS 'subsidy', FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_members')." where type=1 and from_uid=:memberid and uniacid=:uniacid order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
		$result["team1_count"]=pdo_fetchcolumn("select count(0) FROM ".tablename('nets_hjk_members')." where type=1 and from_uid=:memberid and uniacid=:uniacid ",array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
		//二级盟主
		$result["team2"]=pdo_fetchall("select id,memberid,nickname,avatar,username,0 AS 'commission',0 AS 'ordercount',0 AS 'subsidy', FROM_UNIXTIME(created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_members')." where type=1 and from_uid2=:memberid and uniacid=:uniacid order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
		$result["team2_count"]=pdo_fetchcolumn("select count(0) FROM ".tablename('nets_hjk_members')." where type=1 and from_uid2=:memberid and uniacid=:uniacid ",array(":memberid"=>$uid,":uniacid"=>$_W["uniacid"]));
		
		return $this->result($errno, $message, $result);
	}
	
	/*
	* 我的提现记录
	*/
	public function doPageMyCommissionlog(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		//取消openid查询会员改用uid zxq.2018.05.19 wxapp.base.cls.php in 228
		$member=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_members")."  where memberid=:memberid",array(":memberid"=>$_W['fans']['uid']));
		
		$uid = $member["memberid"];
		
		$page=$_GPC["page"];
		$pagesize=1000;
		$state=$_GPC["state"];
		$where="where memberid=:memberid and type=5";
		$data[":memberid"]=$uid;
		$list=pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_member_logs").$where."  order by id desc LIMIT ".(($page-1)*$pagesize).",".$pagesize,$data);
		return $this->result($errno, $message, $list);	
	}
	/*
	* 申请提现
	* money 申请提现金额
	* cashtype '提现类型 1支付宝2微信'，默认微信
	* remark 申请提现备注，默认为空，支付宝提现时传支付宝账号
	*/
	public function doPageApplyCommission(){
		global $_W;
		global $_GPC;
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		haojk_log("提现表单formId：".$_GPC["formId"]);
	
		$money=$_GPC["money"];
		$formid=$_GPC['formId'];
		if($_GPC["cashtype"]==2){
			$remark="支付宝账号:".$_GPC["remark"];
		}
		$rate=$global['rate'];
		$cash_money=floatval($money)-floatval($money)*floatval($rate);
		$errno = 0;
		$message="操作成功";
		//取消openid查询会员改用uid zxq.2018.05.19 wxapp.base.cls.php in 262
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));

		if(!empty($member["from_jduniacid"])){
			$partnermember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where jduniacid=:jduniacid",array(":jduniacid"=>$member["from_jduniacid"]));
			$logs["partnermemberid"]=$partnermember["memberid"];
		}else{
			$logs["partnermemberid"]=0;
		}
		
		$uid = $member["memberid"];
		
		$logs["uniacid"]=$_W["uniacid"];
		$logs["memberid"]=$member["memberid"];
		$logs["type"]=5;//1积分2佣金3补贴
		$logs["logno"]=0;
		$logs["title"]="提现".$money;
		$logs["status"]=0;//0 生成 1 成功 2 失败
		$logs["money"]=-1*$cash_money;
		//保留2位小数
		$logs["money"]=sprintf("%.2f",$logs["money"]);
		$logs["credit1"]=$member["credit1"];
		$logs["credit2"]=$member["credit2"];
		$logs["rechargetype"]="credit2";
		$logs["cashtype"]=$_GPC["cashtype"];
		$logs["remark"]=$remark;
		$logs["created_at"]=time();
		$logs["updated_at"]=time();
		$logs["deleted_at"]=0;
		$i=pdo_insert("nets_hjk_member_logs",$logs);
		$msg_res=array();
		if($i>0){
			$remark="申请提现";
			$isapp="1";
            if($_GPC["cashtype"]==2) {
                $alipay["alipay_no"] = $_GPC["remark"];
                pdo_update("nets_hjk_members", $alipay, array('memberid' => $member["memberid"]));
            }
			$msg_res=sendCashMsg($_W["openid"],$money,$isapp,$formid);
			$b= mc_credit_update($member["memberid"], "credit2", -1*$money, $log = array(0,$remark,"nets_haojk",0,0,1));
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $i);	
	}
	
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
	public function doPageGoods(){
		global $_W;
		global $_GPC;
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
		$goodstype=$_GPC["goodstype"];
		$goodslx=$_GPC["goodslx"];
		$sortby=$_GPC["sortby"];
		$limittime=$_GPC["limittime"];
		$realtime=$_GPC["realtime"];
		$errno = 0;
		$message="操作成功";
		$list=getgoodlist($uri,$page,$pagesize,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname,$goodstype,$goodslx,$sortby,$limittime,$realtime);
		return $this->result($errno, $message, $list);	
	}
	//获取拼购商品分类
	public function doPagePgcname(){
		global $_W;
		global $_GPC;
		
		$errno = 0;
		$message="操作成功";
		$pgcname=getpgcname();
		return $this->result($errno, $message, $pgcname);	
	}
	/*
	* 拼购商品列表
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
	public function doPagePgGoods(){
		global $_W;
		global $_GPC;
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
		$goodstype=$_GPC["goodstype"];
		$goodslx=$_GPC["goodslx"];
		$sortby=$_GPC["sortby"];
		$limittime=$_GPC["limittime"];
		$realtime=$_GPC["realtime"];
		$errno = 0;
		$message="操作成功";
		$list=getPgGoodlist($uri,$page,$pagesize,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname,$goodstype,$goodslx,$sortby,$limittime,$realtime);
		return $this->result($errno, $message, $list);	
	}
	
	/*
	* 订单列表，我的订单，预估收入接口
	* uri 参数可选[orders：业绩收入,importorders：预估收入],默认传orders
	* begintime 开始时间
	* endtime 结束时间
	*/
	public function doPageOrders(){
		global $_W;
		global $_GPC;
		
		$uri=$_GPC["uri"];
		$starttime=$_GPC["begintime"];
		$endtime=$_GPC["endtime"];
		if(empty($starttime)){
			$starttime=date('Y-m-d 00:00:00', strtotime("-90 day"));
			$starttime=strtotime($starttime);
			$endtime=date('Y-m-d 23:59:59', time());
			$endtime=strtotime($endtime);
		}
		$cname=$_GPC["cname"];
		$errno = 0;
		$message="操作成功";
		$list=getorders($uri,$starttime,$endtime);
		return $this->result($errno, $message, $list);	
	}
	/*
	* 分页查询订单列表，我的订单，预估收入接口
	* uri 参数可选[orders：业绩收入,importorders：预估收入],默认传orders
	* begintime 开始时间
	* endtime 结束时间
	*/
	public function doPageOrdersPage(){
		global $_W;
		global $_GPC;
		
		$uri=$_GPC["uri"];
		$starttime=$_GPC["begintime"];
		$endtime=$_GPC["endtime"];
		$page = max(1, intval($_GPC['page']));
        $pagesize = max(20, intval($_GPC['pagesize']));;
		if(empty($starttime)){
			$starttime=date('Y-m-d 00:00:00', strtotime("-90 day"));
			$starttime=strtotime($starttime);
			$endtime=date('Y-m-d 23:59:59', time());
			$endtime=strtotime($endtime);
		}
		$cname=$_GPC["cname"];
		$errno = 0;
		$message="操作成功";
		$list=getorders_bypage($uri,$starttime,$endtime,$page,$pagesize);
		return $this->result($errno, $message, $list);	
	}
	
	/*
	* 订单列表，我的订单，预估收入接口
	* uri 参数可选[orders：业绩收入,importorders：预估收入],默认传orders
	* begintime 开始时间
	* endtime 结束时间
	*/
	public function doPageCmsmember(){
		global $_W;
		global $_GPC;
		$uri=$_GPC["uri"];
		$begintime=$_GPC["begintime"];
		$endtime=$_GPC["endtime"];
		$cname=$_GPC["cname"];
		$errno = 0;
		$message="操作成功";
		$list=getorders($uri,$begintime,$endtime);
		return $this->result($errno, $message, $list);	
	}
	
	/*
	* 通过此接口获取二合一短连接
	* couponUrl 从商品的属性中获取
	* skuid 从商品的属性中获取
	*/
	public function doPageUnionurl(){
		global $_W;
		global $_GPC;
		$couponUrl=$_GPC["couponUrl"];
		if(empty($couponUrl) || $couponUrl=="null" || $couponUrl=="NULL"){
			$couponUrl="";
		}
		$skuid=$_GPC["skuid"];
		$from_uid=$_GPC["from_uid"];
		if(empty($skuid)){
			return $this->result($errno, "error", "");	
		}
		$errno = 0;
		$message="操作成功";
		$list=getunionurl($couponUrl,$skuid,$from_uid);
		return $this->result($errno, $message, $list);	
	}

	/*
	* 通过此接口获取二合一短连接
	* couponUrl 从商品的属性中获取
	* skuid 从商品的属性中获取
	*/
	public function doPageUnionurlbysku(){
		global $_W;
		global $_GPC;
		$skuid=$_GPC["keyword"];
		
		$errno = 0;
		$message="操作成功";
		$res=getunionurlbysku($skuid);
		return $this->result($errno, $message, $res);	
	}


    //保存商品属性到缓存
    public function doPageSavecache(){
        global $_W;
        global $_GPC;
        $errno = 0;
        $message="操作成功";
        $skuId=$_GPC["skuId"];
        unset($_GPC["i"]);
        unset($_GPC["state"]);
        $json_string=json_encode($_GPC);
        $filename=getfilename($skuId);
        file_put_contents($filename, $json_string);
        return $this->result($errno, $message, $filename);
    }
    //保存商品属性到缓存
    public function doPageGetcache(){
        global $_W;
        global $_GPC;
        $errno = 0;
        $message="操作成功";
        $skuId=$_GPC["skuId"];
        $json_string=json_encode($_GPC);
        $filename=getfilename($skuId);
        $goodlist="";
        if(file_exists($filename)){
            $json=file_get_contents($filename);
            $goodlist=json_decode($json, true);
        }
        return $this->result($errno, $message, $goodlist);
    }


    /***
     * 商品缓存 add by tzs  开始
     ***/
    //保存商品属性到缓存
    public function doPageSavecachenew(){
        global $_W;
        global $_GPC;
        $errno = 0;
        $message="操作成功";
        $skuId=$_GPC["skuId"];
        $memberid=$_GPC["memberid"];
        if(empty($memberid))
            $memberid='0';
        $from_uid=$_GPC["from_uid"];
        if(empty($from_uid))
            $from_uid='0';
        $json_string=json_encode($_GPC);
        $filename=getfilename($skuId.'_'.$memberid.'_'.$from_uid);
        haojk_log("读取缓存：".$skuId.'_'.$memberid.'_'.$from_uid);
        haojk_log("读取缓存：".$filename);
        file_put_contents($filename, $json_string);
        return $this->result($errno, $message, $filename);
    }
    //保存商品属性到缓存
    public function doPageGetcachenew(){
        global $_W;
        global $_GPC;
        $errno = 0;
        $message="操作成功";
        $skuId=$_GPC["skuId"];
        $memberid=$_GPC["memberid"];
        if(empty($memberid))
            $memberid='0';
        $from_uid=$_GPC["from_uid"];
        if(empty($from_uid))
            $from_uid='0';
        $json_string=json_encode($_GPC);
        $filename=getfilename($skuId.'_'.$memberid.'_'.$from_uid);
        haojk_log("写入缓存：".$skuId.'_'.$memberid.'_'.$from_uid);
        haojk_log("写入缓存：".$filename);
        $goodlist="";
        if(file_exists($filename)){
            $json=file_get_contents($filename);
            $goodlist=json_decode($json, true);
        }
        return $this->result($errno, $message, $goodlist);
    }

    /***
     * 商品缓存 add by tzs  结束
     ***/

	/*
	* 获取小程序消息接口
	*/
	public function doPageServers(){
		global $_GPC, $_W;
		$token=$_W['uniaccount']["token"];
		$encodingaeskey=$_W['uniaccount']["encodingaeskey"];
		$key=$_W['uniaccount']["key"];
		$secret=$_W['uniaccount']["secret"];
		$encodingAesKey =$encodingaeskey;
		$token = $token;
		$timeStamp = time();
		$nonce = "xxxxxx";
		$appId = $key;
		$pc = new WXBizMsgCrypt($token, $encodingAesKey, $appId);
		$signature = $_GPC["signature"];
		$timestamp = $_GPC["timestamp"];
		$nonce = $_GPC["nonce"];
		$res=$pc->checkSignature($signature,$timestamp,$nonce);
		if (isset($_GET['echostr'])) {
			haojk_log("0.1首次api验证对接！");
			$echoStr = $_GPC["echostr"];
			if ($res) {
				 echo $echoStr;
				 exit;
			}
		}else{
			//发送客服消息
			$this->responseMsg();
		}
		exit;
	}
	
	private function responseMsg()
    {
		global $_GPC, $_W;
		//haojk_log("0.1发送客服消息！".json_encode($_GPC));
        $postStr = $_GPC["__input"];
		//haojk_log("0.2发送客服消息！".json_encode($postStr));
		$access_token=$this->get_accessToken();
		$msg["touser"]=$postStr["FromUserName"];
		//中文先urlencode
		$msgcontent["content"]=urlencode("亲，请稍后！\r\n 正在给你发优惠券…………");
		$goods="";
        $skuId='';
        $uniacid='';
        $from_uid='';
		$goodtype="jd";
		$multi_group=false;
		$uniacid = $_W["uniacid"];
		haojk_log($postStr["SessionFrom"]);
		if(!empty($postStr["SessionFrom"]) && $postStr["SessionFrom"]!="kefu" && $postStr["SessionFrom"]!="send"){
            $SessionFrom=explode('_',$postStr["SessionFrom"]);

            for ($i=0;$i<count($SessionFrom);$i++){
                if($i==0)
                    $skuId = $SessionFrom[$i];
                if($i==1)
                    $uniacid = $SessionFrom[$i];
                if($i==2)
					$from_uid = $SessionFrom[$i];
				if($i==3)
					$goodtype = $SessionFrom[$i];
				if($i==4){
					$multi_group=true;
				}
            }
			$goods=getgoodjson($skuId);
		}
		
		if(empty($goods)){
			$content_ex=explode(':',$postStr["Content"]);

			for ($i=0;$i<count($content_ex);$i++){
				if($i==1)
					$skuId = $content_ex[$i];
				if($i==0)
					$goodtype = $content_ex[$i];
			}
			$goods=getgoodjson($skuId);
		}

		//触发客服消息的重新查询下fans zxq.2018.05.19 wxapp.base.cls.php in 663
		$fans=pdo_fetch("select * from .".tablename("mc_mapping_fans")." where openid=:openid",array(":openid"=>$postStr["FromUserName"]));
        //$fans=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_members")."  where openid=:openid",array(":openid"=>$postStr["FromUserName"]));
		haojk_log("小程序用户openid：".$postStr["FromUserName"]);
		$_W["openid"]=$postStr["FromUserName"];//触发客服消息的保存下openid
		//触发客服消息的 取fans对应的uid  zxq.2018.05.19 wxapp.base.cls.php in 668
		$uid=$fans["uid"];
		$_W['fans']['uid']=$uid;//这里赋值全局fans的uid 里面函数直接使用
		//$uid=$fans["memberid"];
		if($goodtype=="jd" || $goodtype=="pinduoduo")
        {
            if(!empty($goods)){
                haojk_log("发送客服消息 优惠券：".$goods->couponList);
                haojk_log("发送客服消息 skuId：".$goods->skuId);
                haojk_log("发送客服消息 uid：".$uid);
                haojk_log("发送客服消息 uniacid：".$uniacid);
                haojk_log("发送客服消息 from_uid：".$from_uid);
                haojk_log("发送客服消息 goodtype".$goodtype);
                if($goodtype=="jd"){
                    $unionres=getunionurlbymsg($goods->couponList,$goods->skuId,$uid,$uniacid,$from_uid);
                }
                if($goodtype=="pinduoduo"){
                    $_GPC["goods_id_list"]=$goods->skuId;
                    $_GPC["from_uid"]=$goods->from_uid;
					$_GPC["memberid"]=$uid;
					$_GPC["multi_group"]=$multi_group==1?'true':'false';
                    $unionres=getpdd_Unionurl($uid,$goods->from_uid,$goods->skuId);
                    haojk_log("拼多多转链: ".$unionres["data"]);
                }
                //发送领券链接
                //如果领券链接为空,提示券已被领完
                if(empty($unionres->data) && empty($unionres["data"])){
                    $text="亲，您好！券已被抢完下次早点来哦";
                    $msg["msgtype"]="text";
                    $msgcontent["content"]=urlencode($text);
                    $msg["text"]=$msgcontent;
                    $this->send_msg($msg,$access_token);
                    return;
                }
                $title=$goods->discount."元优惠券";
                $url=$unionres->data;
                if($goodtype=="pinduoduo"){
                    $url=$unionres["data"];
                }
                $thumb_url=$goods->picUrl;
                $text="给您找到了这张[".$goods->discount."元优惠券]点击领取后在购买吧！更多优惠别忘记找我哦！";

                if(empty($goods->discount) || $goods->discount=="NULL" || $goods->discount=="null"){
                    $title=$goods->skuName;
                    $text=$goods->skuName;
                }
                //文字消息
                //$msg["msgtype"]="text";
                //$msgcontent["content"]=urlencode($text);
                //$msg["text"]=$msgcontent;
                //图文消息
                $msg["msgtype"]="link";
                $msgcontent["title"]=urlencode($title);
                $msgcontent["description"]=urlencode($text);
                $msgcontent["url"]=$url;
                $msgcontent["thumb_url"]=$thumb_url;
                $msg["link"]=$msgcontent;

                $this->send_msg($msg,$access_token);
            }
        }
		else{
			$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
			
			//文字消息
			$text="亲，你好，很高兴为您服务，有什么可以为您效劳的呢？";
			if(!empty($global['service_msg'])){
				$text=$global['service_msg'];
// 				$text.="

// [抱拳]常用帮助命令如下
// 【帮助】查看使用说明
// 【省钱】教你如何省钱
// 【赚钱】教你怎么赚钱";
				$content=$postStr["Content"];
				$searchkey = pdo_fetch("SELECT id,keyword,picture,title,remark FROM " .tablename('nets_hjk_keyword'). " WHERE uniacid =:uniacid and state=1 and keyword=:keyword",array(':uniacid'=>$_W['uniacid'],':keyword'=>$content));
        
				if(!empty($searchkey['keyword'])){
					$helptype="help";
					$thumb_url=$searchkey['picture'];
					$msgcontent["title"]=urlencode($searchkey['title']);
					$msgcontent["description"]=urlencode($searchkey['remark']);
					// if($content=="省钱"){
					// 	$helptype="savemoney_help";
					// 	$thumb_url=MODULE_URL."skin/shengqian_icon.jpg";
					// 	$msgcontent["title"]=urlencode("【省钱攻略】点击查看");
					// 	$msgcontent["description"]=urlencode("一毛也是钱，能省一毛是一毛！");
					// }
					// if($content=="赚钱"){
					// 	$helptype="makemoney_help"; 
					// 	$thumb_url=MODULE_URL."skin/zhuanqian_icon.jpg";
					// 	$msgcontent["title"]=urlencode("【赚钱攻略】点击查看");
					// 	$msgcontent["description"]=urlencode("先定个小目标，赚他一个亿！");
					// }
					 $helpurl=$_W['siteroot']."app/index.php?i=".$_W["uniacid"]."&c=entry&m=nets_haojk&do=helpmsg&k=".$searchkey['id'];
					 $msg["msgtype"]="link";
					 $msgcontent["url"]=$helpurl;
					 $msgcontent["thumb_url"]=$thumb_url;
					 $msg["link"]=$msgcontent;
					
					$this->send_msg($msg,$access_token);
					return;
				}
			}
			if(!empty($postStr["Content"])){
				$content=$postStr["Content"];
				$goods=$this->get_goods($content,$postStr["FromUserName"]);

				//解析用户发送的关键词
				//01.发推广文案的提取二合一链接
				preg_match_all('/(https|http)?:\/\/union-click[\w-.%#?\/\\\=]+/i',$content,$urllist); 
				$unionurl_old=$urllist[0][0];
				//02.提取短链接文案内容
				//preg_match_all('/http?:\/\/url.cn[\w-.%#?\/\\\=]+/i',$content,$urllist2); 
				preg_match_all('/(https|http)?:\/\/[\w-.%#?\/\\\=]+/i',$content,$urllist2);
				$unionurl_old2=$urllist2[0][0];
				//03.内容中含有skuId
				preg_match_all('/\d{5,20}/i',$content,$skuidnumber); 
				$skuid=$skuidnumber[0][0];
				if(!strpos($content,'yangkeduo.com') && empty($goods) && !empty($unionurl_old)){
					$openid=$this->message['from'];
					$keyword=$unionurl_old;
					$res=getunionurl($unionurl_old,$unionurl_old);
					$content=str_replace($unionurl_old,$res->data,$content);
					$text=$content;
				}else if(!strpos($content,'yangkeduo.com') && empty($goods) && !empty($unionurl_old2) && !empty($skuid)){
					$text=$unionurl_old2;
					$text.="  ".$skuid;
					preg_match_all('/P\d{2}/',$content,$tempstrlist);
					$tempstr=$tempstrlist[0][0];
					$text.="  ".$tempstr;
					$res=getunionurl($unionurl_old2,$skuid);
					$content=str_replace($unionurl_old2,$res->data,$content);
					$pattern = '/(.*)'.$skuid."(.*)/";
					preg_match_all($pattern,$content,$removelist);
					$removestr=$removelist[0];
					//$content=str_replace($skuid,"",$content);
					//$content=str_replace($tempstr,"",$content);
					$content=str_replace($removestr,"",$content);
					$text=$content;
				}else{
					
					if(!empty($goods)){
						$text="找到".count($goods)."个商品";
						if(!empty($goods) && strpos($content,'京东') !== false){
							$text=$this->buildgoodsText($msg["touser"],$goods);
						}else if(!empty($goods) && strpos($content,'拼多多') !== false  || strpos($content,'yangkeduo.com') !== false){
							$text=$this->buildPddgoodsText($msg["touser"],$goods);
						}else{
							$text=$this->buildgoodsText($msg["touser"],$goods);
						}
					}
				}	
			}
			if(preg_match('/(https|http):\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$postStr["SessionFrom"])){
				$text="为您找到个商品 ，<a href='".$postStr["SessionFrom"]."'>立即去看看</a>  ";
			}
			$msg["msgtype"]="text";
			$msgcontent["content"]=urlencode($text);
			$msg["text"]=$msgcontent;
			if(!empty($goods)){
				$postpath='/addons/nets_haojk/cache/'.$goods[0]['skuId'].'_exqrcode.jpg';
				$res = $this->uploadImage($postpath);  
				//$text=$res['code'].$res['value'];
				if($res['code']==200){
					$media_id=$res['value'];
					$imgmsg["touser"]=$postStr["FromUserName"];
					$imgmsg["msgtype"]="image";
					$imgmsg_image["media_id"]=$media_id;
					$imgmsg["image"]=$imgmsg_image;
					$this->send_msg($imgmsg,$access_token);
				}
			}
			$this->send_msg($msg,$access_token);
		}
		
		
	}
	//生成推广文本
	private function buildgoodsText($openid,$goods){
		global $_GPC, $_W;
		//haojk_log("01.buildgoodsText:".$openid);
		$message_str="";
			$fans=pdo_fetch("select * from ".tablename('mc_mapping_fans')." where openid=:openid",array(":openid"=>$openid));
			$glbal=pdo_fetch("select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid",array(":uniacid"=>$fans['uniacid']));
			//取消查询会员的uniacid查询 zxq.2018.05.19 wxapp.base.cls.php in 852
			$member=pdo_get('nets_hjk_members',array('memberid'=>$fans['uid']));
			$level=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid",array(":uniacid"=>$fans['uniacid']));
			$rate=$glbal["subsidy"];
			//haojk_log("02.buildgoodsText:".$rate);
			foreach($level AS $l){
				//当前会员类型的等级佣金比例
				if($l["type"]==$member["type"] && $l["name"]==$member["level"] ){
					if($member["type"]==0){
						$rate=$glbal["subsidy"];
					}else{
						$rate=$l["myteam_credit2"];	
					}
				}
			}
			//haojk_log("03.buildgoodsText:".$rate);
			foreach($goods AS $g){
				
				$detail =   $g['detail'];
				$skuId  =   $g["skuId"];
				$json_string=   json_encode($g);
				//haojk_log("031.buildgoodsText:".$json_string);
				$filename   =   getfilename($skuId);
				file_put_contents($filename, $json_string);
				//haojk_log("04.buildgoodsText:".$filename);
				$yuebu=number_format($g['wlPrice_after']*$g['wlCommissionShare']/100*$rate/100*0.9,2);
				$yuebu_str="
约    补：￥".$yuebu;
				if(empty($glbal['isopen_subsidy']) || empty($glbal['isshow_subsidy'])){
					$yuebu_str="";	
				}
				if(!empty($g['uninonUrl'])){
					$unionUrl=$g['uninonUrl'];
				}else{
				 $unionUrl	=	getunionurl($g['couponList'],$g['skuId'],'');
				 $unionUrl		=	$unionUrl->data;
				}
				//haojk_log("05.buildgoodsText:".$unionUrl);
				 $searchurl=url('entry',array('m'=>'nets_haojk','do'=>'searchlist','keyword'=>$keyword));
				 $searchurl=$_W['siteroot']."app/index.php?i=".$fans['uniacid']."&c=entry&m=nets_haojk&do=searchlist&keyword=".$keyword;
				 
				$array=array(
					'title' => $g['skuName'].'  券后价￥'.$g['wlPrice_after'].''.$member['nickname'],
					'description' => $g['skuDesc'],
					'picurl' => $g['picUrl'],
					'url' => $this->createMobileUrl('searchdetail', array('skuId' => $g['skuId'])), 
					'tagname'=>'item'
				);
				//生成海报
				$posterSize=7;
				if($glbal['goodsqrtype']=="2"){
					$posterSize=7;
				}
				$poster=new Poster();
				$skuId=$g["skuId"];
				$skuName=$g["skuName"];
				$skuDesc=$g["skuDesc"];
				$materiaUrl=$g["materiaUrl"];
				$picUrl=$g["picUrl"];
				$wlPrice=$g["wlPrice"];
				$wlPrice_after=$g["wlPrice_after"];
				$discount=$g["discount"];
				
				$res=$poster->getGoodPoster2($skuId,$skuName,$skuDesc,$unionUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,$posterSize);
				//haojk_log("06.buildgoodsText:".json_encode($res));
				$detailurl=url('entry',array('m'=>'nets_haojk','do'=>'searchdetail','skuId'=>$g['skuId']));
				$detailurl=$_W['siteroot'].substr($detailurl,2,strlen($detailurl));
				//haojk_log("07.buildgoodsText:".json_encode($g));
$message_str="【JD】".$g['skuName']."
————————
京东价：￥".$g['wlPrice']."
内购价：￥".$g['wlPrice_after'].$yuebu_str."
领券优惠购买：".$unionUrl."
————————
【推荐理由】".$g['skuDesc']."
京东商城 正品保证";
				
				// $postpath='/addons/nets_haojk/cache/'.$g['skuId'].'_exqrcode.jpg';
				
				// $res = $this->uploadImage($postpath);  
				// if($res['code']==200){
				// 	$media_id=$res['value'];
				// 	return $this->respImage($media_id);
				// }else{
				// 	return $this->respText(json_encode($res));
				// } 	
				break;
				//$response[]=$array;
			}
			return $message_str;
	}
	//生成拼多多推广文本
	private function buildPddgoodsText($openid,$goods){
		global $_GPC, $_W;
		//haojk_log("01.buildgoodsText:".$openid);
		$message_str="";
			$fans=pdo_fetch("select * from ".tablename('mc_mapping_fans')." where openid=:openid",array(":openid"=>$openid));
			$glbal=pdo_fetch("select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid",array(":uniacid"=>$fans['uniacid']));
			//取消查询会员的uniacid条件 zxq.2018.05.19 wxapp.base.cls.php in 952
			$member=pdo_get('nets_hjk_members',array('memberid'=>$fans['uid']));
			$level=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid",array(":uniacid"=>$fans['uniacid']));
			$rate=$glbal["subsidy"];
			//haojk_log("02.buildgoodsText:".$rate);
			foreach($level AS $l){
				//当前会员类型的等级佣金比例
				if($l["type"]==$member["type"] && $l["name"]==$member["level"] ){
					if($member["type"]==0){
						$rate=$glbal["subsidy"];
					}else{
						$rate=$l["myteam_credit2"];	
					}
				}
			}
			//haojk_log("03.buildgoodsText:".$rate);
			foreach($goods AS $g){
				
				$detail =   $g['detail'];
				$skuId  =   $g["skuId"];
				$json_string=   json_encode($g);
				//haojk_log("031.buildgoodsText:".$json_string);
				$filename   =   getfilename($skuId);
				file_put_contents($filename, $json_string);
				//haojk_log("04.buildgoodsText:".$filename);
				$yuebu=number_format($g['min_group_price']*$g['wlCommissionShare']/100*$rate/100,2);
				$yuebu_str="
约    补：￥".$yuebu;
				if(empty($glbal['isopen_subsidy']) || empty($glbal['isshow_subsidy'])){
					$yuebu_str="";	
				}
				if(!empty($g['uninonUrl'])){
					$unionUrl=$g['uninonUrl'];
				}else{
				 $unionUrl	=	getpdd_Unionurl($member["memberid"],'',$g['skuId']);
				 $unionUrl		=	$unionUrl['data'];
				}
				haojk_log("05.buildgoodsText:".$g['skuId']);
				 $searchurl=url('entry',array('m'=>'nets_haojk','do'=>'searchlist','keyword'=>$keyword));
				 $searchurl=$_W['siteroot']."app/index.php?i=".$fans['uniacid']."&c=entry&m=nets_haojk&do=searchlist&keyword=".$keyword;
				 
				$array=array(
					'title' => $g['skuName'].'  券后价￥'.$g['wlPrice_after'].''.$member['nickname'],
					'description' => $g['skuDesc'],
					'picurl' => $g['picUrl'],
					'url' => $this->createMobileUrl('searchdetail', array('skuId' => $g['skuId'])), 
					'tagname'=>'item'
				);
				//生成海报
				$posterSize=7;
				if($glbal['goodsqrtype']=="2"){
					$posterSize=7;
				}
				$poster=new Poster();
				$skuId=$g["skuId"];
				$skuName=$g["skuName"];
				$skuDesc=$g["skuDesc"];
				$materiaUrl=$g["materiaUrl"];
				$picUrl=$g["picUrl"];
				$wlPrice=$g["min_group_price"];
				$wlPrice_after=$g["wlPrice_after"];
				$discount=$g["discount"];
				
				$res=$poster->getPddGoodPoster2($skuId,$skuName,$skuDesc,$unionUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,$posterSize);
				//haojk_log("06.buildgoodsText:".json_encode($res));
				$detailurl=url('entry',array('m'=>'nets_haojk','do'=>'searchdetail','skuId'=>$g['skuId']));
				$detailurl=$_W['siteroot'].substr($detailurl,2,strlen($detailurl));
				//haojk_log("07.buildgoodsText:".json_encode($g));
$message_str="【拼多多】".$g['skuName']."
————————
单购价：￥".$g['wlPrice']."
拼购价：￥".$g['min_group_price'].$yuebu_str."
领券优惠购买：".$unionUrl."
————————

【推荐理由】".$g['skuDesc']."

拼多多 正品保证";
				
				// $postpath='/addons/nets_haojk/cache/'.$g['skuId'].'_exqrcode.jpg';
				
				// $res = $this->uploadImage($postpath);  
				// if($res['code']==200){
				// 	$media_id=$res['value'];
				// 	return $this->respImage($media_id);
				// }else{
				// 	return $this->respText(json_encode($res));
				// } 	
				break;
				//$response[]=$array;
			}
			return $message_str;
	}
	private function uploadImage($img) {
		//return $img;
		//$img='/addons/nets_haojk/cache/1_bg.png';
		$file_info=array(
			'filename'=>$img,  //图片相对于网站根目录的路径
			'content-type'=>'image/png',  //文件类型
			'filelength'=>'90011'         //图文大小
		);
		$token=$this->get_accessToken();
		$url="https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$token}&type=image";
		$curl = curl_init ();
		$real_path=$_SERVER['DOCUMENT_ROOT'].$file_info['filename'];
		
		if(!file_exists($real_path)){
			return  array('code'=>404,'value'=>'文件['.$real_path.']不存在');
		}
		$data= array("media"=>new CURLFile(realpath($real_path)));
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		curl_setopt($curl, CURLOPT_SAFE_UPLOAD, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
		$result = curl_exec ( $curl );
		
		curl_close ( $curl );
		$res=json_decode($result,true);
		if(!empty($res['media_id'])){
			return array('code'=>200,'value'=>$res['media_id']);
		}
		return  array('code'=>500,'value'=>$res['errmsg']);
	}
	
	//从内容中解析关键词查询商品，返回商品数组
	private function get_goods($content,$openid){
		global $_GPC, $_W;
		$goods= array();
		//02.发商品链接 只京东
		if(!strpos($content,'yangkeduo.com') && preg_match('/(https|http):\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$content)){
			$op="";
			$jdurl=$content;
			$keyword="";//skuid
			if(strpos($jdurl,'.html') !== false){
				$jdurl_arr=explode('?',$jdurl);
				$jdurl=$jdurl_arr[0];	
				$strarr=explode("/",$jdurl);
				$keyword=$strarr[count($strarr)-1];
				$keyword=str_replace(".html","",$keyword);
				$op="byskuid";
			}
			if(strpos($jdurl,'sku=') !== false){
				$jdurl_arr=explode('sku=',$jdurl);
				$jdurl=$jdurl_arr[1];
				$strarr=explode("&",$jdurl);
				$keyword=$strarr[0];
				$op="byskuid";
			}
			
			
			
			if($op=="byskuid"){
				$res=getunionurlbysku($keyword,$openid);
				if(!empty($res->data) && !empty($res->info)){
					$g=array(
						'detail'=>$res->info->detail
						,'skuName'=>$res->info->skuName
						,'wlPrice'=>$res->info->wlPrice
						,'discount'=>$res->info->discount
						,'wlCommissionShare'=>$res->info->wlCommissionShare
					,'skuId'=>$res->info->skuId
					,'wlPrice_after'=>$res->info->wlPrice_after
					,'skuDesc'=>$res->info->skuDesc
					,'uninonUrl'=>$res->data
					,'picUrl'=>$res->info->picUrl);
					$goods[]=$g;
				}
			}
		}else{
			if(strpos($content,'京东') !== false){
				$content=str_replace("京东","",$content);
				$content=str_replace(" ","",$content);
				//包含京东关键词
				$page=1;
				$pagesize=20;
				$sort=0;//最高佣金排序
				$minprice="";
				$maxprice="";
				$mincommission="";
				$maxcommission="";
				$cname="";
				$goodstype="";
				$sortby="";
				$goods=getgoodlist("listall",$page,$pagesize,$sort,$content,$minprice,$maxprice,$mincommission,$maxcommission,$cname,$goodstype,$goodslx,$sortby);
				//haojk_log("查询到的商品：".json_encode($goods));
			}
			if(strpos($content,'拼多多') !== false  || strpos($content,'yangkeduo.com') !== false ){
				$content=str_replace("拼多多","",$content);
				$content=str_replace(" ","",$content);
				//包含拼多多关键词
				$_GPC['page']=1;
				$_GPC['pageSize']=1;
				$_GPC["keyword"]=$content;
				$goods=getpdd_goodlist();
				//haojk_log("查询到的商品：".json_encode($goods));
			}
			
		}
		return $goods;
	}
	//从内容中解析短链接查询商品，返回商品数组
	private function get_goodsbyshorturl($content,$openid=""){
		global $_GPC, $_W;
		$goods= array();
		//02.发商品链接
		if(preg_match('/https:\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$content)){
			$op="";
			$jdurl=$content;
			$keyword="";//skuid
			if(strpos($jdurl,'.html') !== false){
				$jdurl_arr=explode('?',$jdurl);
				$jdurl=$jdurl_arr[0];	
				$strarr=explode("/",$jdurl);
				$keyword=$strarr[count($strarr)-1];
				$keyword=str_replace(".html","",$keyword);
				$op="byskuid";
			}
			if(strpos($jdurl,'sku=') !== false){
				$jdurl_arr=explode('sku=',$jdurl);
				$jdurl=$jdurl_arr[1];
				$strarr=explode("&",$jdurl);
				$keyword=$strarr[0];
				$op="byskuid";
			}
			
			
			
			if($op=="byskuid"){
				$res=getunionurlbysku($keyword,$openid);
				if(!empty($res->data) && !empty($res->info)){
					$g=array(
						'detail'=>$res->info->detail
						,'skuName'=>$res->info->skuName
						,'wlPrice'=>$res->info->wlPrice
						,'discount'=>$res->info->discount
						,'wlCommissionShare'=>$res->info->wlCommissionShare
					,'skuId'=>$res->info->skuId
					,'wlPrice_after'=>$res->info->wlPrice_after
					,'skuDesc'=>$res->info->skuDesc
					,'uninonUrl'=>$res->data
					,'picUrl'=>$res->info->picUrl);
					$goods[]=$g;
				}
			}
		}else{
			$page=1;
			$pagesize=20;
			$sort=0;//最高佣金排序
			$minprice="";
			$maxprice="";
			$mincommission="";
			$maxcommission="";
			$cname="";
			$goodstype="";
			$sortby="";
			$goods=getgoodlist("listall",$page,$pagesize,$sort,$content,$minprice,$maxprice,$mincommission,$maxcommission,$cname,$goodstype,$goodslx,$sortby);
			//haojk_log("查询到的商品：".json_encode($goods));
		}
		return $goods;
	}
    /* 调用微信api，获取access_token，有效期7200s */
    private function get_accessToken(){
		global $_GPC, $_W;
		$token=$_W['uniaccount']["token"];
		$encodingaeskey=$_W['uniaccount']["encodingaeskey"];
		$key=$_W['uniaccount']["key"];
		$secret=$_W['uniaccount']["secret"];
		load()->func('communication');
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$key.'&secret='.$secret;
        $result = ihttp_get($url);
		//haojk_log("0.3消息内容：".$result["content"]);
		$res=$result["content"];
		if(empty($res)){
			return "500";//'api return error';
		}
        if(!empty($res)){
			$res=json_decode($res);
			//haojk_log("0.4消息token：".$res->access_token);
            return $res->access_token;
        }else{
            return "500";//'api return error';
        }
    }
	private function send_msg($msg,$token){
		load()->func('communication');
		$msg=json_encode($msg);
        $url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$token;
		//中文urldecode
		$msg=urldecode($msg);
        $result = ihttp_post($url,$msg);
		if(empty($result)){
			return "500";
		}
        $res = json_decode($result,true);
        if($res){
            return $res;
        }else{
            return "500";//'api return error';
        }
	}
	//生成小程序二维码
	public function doPageGetwxacodeunlimit(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		//生成小程序码  查询当前会员原openid变更为uid zxq.2018.05.19 wxapp.base.cls.php in 1264 
		$member=pdo_fetch("SELECT m.*,mm.credit1,mm.credit2 from ".tablename("nets_hjk_members")." AS m
		left join ".tablename("mc_members")." AS mm on m.memberid=mm.uid   where memberid=:uid",array(":uid"=>$_W["fans"]["uid"]));
		$global=pdo_fetch("select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
		$wxapppage="haojk/pages/index/index";
		if($global["homepage_status"]=="../choiceness/index?name=index"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=choiceness"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=bigsearch"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=好货情报局"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=砍价"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=拼购"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=榜单"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=2小时跑单"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=全天销售榜"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=实时排行榜"){$wxapppage="haojk/pages/index/index";}
		if($global["homepage_status"]=="../choiceness/index?name=pddindex"){$wxapppage="page/pinduoduo/pages/index4/index";}
		if($global["homepage_status"]=="../choiceness/index?name=pddsearch"){$wxapppage="page/pinduoduo/pages/index4/index";}
		if($global["homepage_status"]=="../choiceness/index?name=my"){$wxapppage="page/pinduoduo/pages/index4/index";}
		haojk_log("生成小程序推广海报页面：".$global["homepage_status"].";wxapppage:".$wxapppage);
		load()->func('communication');
		$token=$this->get_accessToken();
        $url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$token;
		//中文urldecode
		$par["scene"]=$member["memberid"];
		$par["page"]=$wxapppage;
		$par["width"]=430;
		$par["auto_color"]=true;
		$c["r"]=0;
		$c["g"]=0;
		$c["b"]=0;
		$par["line_color"]=$c;
		$qrpath=IA_ROOT.'/addons/nets_haojk/cache/qr_'.$member["memberid"].'.jpg';
		$qrurl=$_W['siteroot'].'/addons/nets_haojk/cache/qr_'.$member["memberid"].'.jpg';
		if(true){
			$result = ihttp_post($url,json_encode($par));
			file_put_contents($qrpath, $result["content"]);
		}
		$poster=new Poster();
		$qrres=$poster->getQrPoster($member["memberid"],1,'');
		$res=array();
		$res["path"]=$qrres["res"];
		if(empty($qrres["res"])){
			$res["path"]=$qrurl;
		}
		return $this->result($errno, $message, $res);	
	}
	
	//申请盟主
	public function doPageApplyleader(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="申请成功";
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		if($global["applyleader"]==0){
			//查询当前会员 原openid变更为uid zxq.2018.05.19 in wxapp.base.cls.php in 1319
			$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
			$res=applyleader($member["memberid"]);
			//查询当前会员 原openid变更为uid zxq.2018.05.19 in wxapp.base.cls.php in 1323
			$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
			if($res["code"]==200){
				return $this->result(0, $message, $member);
			}else{
				return $this->result($errno, $res["msg"], "");
			}
		}else{
			//返回申请盟主的信息
			$res=array("code"=>300,"msg"=>"申请盟主需要付费","applyleader_fee"=>$global["applyleader_fee"]);
			return $this->result($errno, $message, $res);	
		}
	}
	//申请盟主验证
	public function doPageCheckApplyleader(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="验证成功";
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		//查询当前会员 原openid变更为uid zxq.2018.05.19 in wxapp.base.cls.php in 1342
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$res=checkapplyleader($member["memberid"]);
		return $this->result($errno, $message, $res);	
		
	}
	//申请合伙人参数
	//type =partner 合伙人申请付费才传
	//name  姓名 必填
	//mobile 手机号 必填
	//weixin 微信号  必填
	public function doPagePay() {
        global $_W;
		global $_GPC;
        $openid = $_W["openid"];//粉丝在小程序下的openID
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		$tid=date('YmdHis');
		$remark="申请盟主付费";
		$title="申请盟主";
		$fee=floatval($global['applyleader_fee']);
		if($_GPC['type']=="partner"){
			if(!empty($_GPC['name']) && !empty($_GPC['mobile']) && !empty($_GPC['weixin'])){
				//修改会员信息
				$data['realname']=$_GPC['name'];
				$data['mobile']=$_GPC['mobile'];
				$data['weixin']=$_GPC['weixin'];
				pdo_update('nets_hjk_members',$data,array('memberid'=>$_W['fans']["uid"]));
			}
			$remark="申请合伙人付费";
			$title="申请合伙人付费";
			$fee=floatval($global['partner_fee']);
		}
        $order = array(
                'tid' => $tid,
                'user' => $openid,
                'fee' => $fee,
                'title' => $title,
		);
		
        $pay_params = $this->pay($order);
		
        if (is_error($pay_params)) {
			
            return $this->result(1, $pay_params);
        }
		//查询当前会员 原openid变更为uid zxq.2018.05.19 in wxapp.base.cls.php in 1368
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$uid = $member["memberid"];
		
		$logs["uniacid"]=$_W["uniacid"];
		$logs["memberid"]=$member["memberid"];
		$logs["type"]=4;//1积分2佣金3补贴4充值5提现
		$logs["logno"]=$tid;
		$logs["title"]=$title;
		$logs["status"]=0;//0 生成 1 成功 2 失败
		$logs["money"]=$fee;//floatval($global['applyleader_fee']);  2018/06/15 by dcy
		$logs["credit1"]=$member["credit1"];
		$logs["credit2"]=$member["credit2"];
		$logs["rechargetype"]="credit2";
		$logs["cashtype"]=$_GPC["cashtype"];
		$logs["remark"]=$remark;
		$logs["created_at"]=time();
		$logs["updated_at"]=time();
		$logs["deleted_at"]=0;
		$i=pdo_insert("nets_hjk_member_logs",$logs);
		$res["order"]=$order;
		$pay_params["tid"]=$tid;
		$res["pay_params"]=$pay_params;
        return $this->result(0, '', $pay_params);
    }
	public function doPageResult(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		haojk_log("申请盟主付费单号：".$_GPC["orderno"].";form_id:".$_GPC['from_id']);
		if(!empty($_GPC["orderno"])){
			$logInfo = pdo_get('nets_hjk_member_logs',array('logno'=>$_GPC["orderno"],'status'=>'0'));
			pdo_update("nets_hjk_member_logs",array("status"=>1),array("logno"=>$_GPC["orderno"]));
			if(!empty($logInfo)&&!empty($logInfo["title"]) && $logInfo["remark"]=="申请合伙人付费"){
                $data['type']=2;
                $data['level']=0;
                $i=pdo_update('nets_hjk_members',$data,array('memberid'=>$logInfo["memberid"]));
                //修改下级会员合伙人id
                $j=pdo_update("nets_hjk_members",array("from_partner_uid"=>$logInfo["memberid"]),array("from_uid"=>$logInfo["memberid"],"from_uid2"=>$logInfo["memberid"]),'OR');
				$msg="申请失败！";
				$status=0;
				if($i>0){
					$msg="申请成功";
					$status=1;
				}
				return $this->result($errno, $msg, $status);
            }
			//查询当前会员 原openid变更为uid zxq.2018.05.19 in wxapp.base.cls.php in 1402
			$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
			$res=applyleader($member["memberid"]);
			$openid=$_W['openid'];
			$prepay_id=$_GPC['prepay_id'];
			$from_id=$_GPC['from_id'];
			$moeny=$_GPC['money'];
			$isapp=1;
			applyloaderMsg($openid,$moeny,$isapp,$from_id,$res["msg"]);
			if($res["code"]==200){
				applyleader_credit2_commission($logInfo['money'],$member['memberid']);
				return $this->result($errno, $message, $member);
			}else{
				return $this->result($errno, $res["msg"], "");
			}
		}
	}
	
	/**
     * 合伙人充值，返还微信充值的信息
     * $money 充值的金额
     * 参数  memberid 会员id
     **/
    public function doPageRecharge() {
        global $_W;
		global $_GPC;
        
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		$tid=date('YmdHis');
		$money=$_GPC["money"];
        $order = array(
                'tid' => $tid,
                'user' => $openid,
                'fee' => floatval($money),
                'title' => '账户充值',
		);
		
        $pay_params = $this->pay($order);
		
        if (is_error($pay_params)) {
			
            return $this->result(1, $pay_params);
		}
		$openid = $_W["openid"];//粉丝在小程序下的openID
		//取消uniacid\openid查询，变更为uid查询当前会员 zxq.2018.05.19 in wxapp.base.cls.php in 1446
		$member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where  em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
        $uid = $member["memberid"];
		$remark="账户充值";
		$logs["uniacid"]=$_W["uniacid"];
		$logs["memberid"]=$member["memberid"];
		$logs["type"]=4;//1积分2佣金3补贴4充值5提现
		$logs["logno"]=$tid;
		$logs["title"]="";
		$logs["status"]=0;//0 生成 1 成功 2 失败
		$logs["money"]=floatval($money);
		$logs["credit1"]=$member["credit1"];
		$logs["credit2"]=$member["credit2"];
		$logs["rechargetype"]="credit2";
		$logs["cashtype"]=0;
		$logs["remark"]=$remark;
		$logs["created_at"]=time();
		$logs["updated_at"]=time();
		$logs["deleted_at"]=0;
		$i=pdo_insert("nets_hjk_partner_logs",$logs);
		$res["order"]=$order;
		$pay_params["tid"]=$tid;
		$res["pay_params"]=$pay_params;
        return $this->result(0, '', $pay_params);
    }
    /**
     * 合伙人充值成功返回，返还微信充值的信息
     * orderno 充值接口返回的订单号，回传过来
     * 参数  memberid 会员id
     **/
    public function doPageRechargeResult(){
		global $_GPC, $_W;
		$errno = 0;
		$message="充值成功";
		
		if(!empty($_GPC["orderno"])){
			$logInfo = pdo_get('nets_hjk_partner_logs',array('logno'=>$_GPC["orderno"],'status'=>'0'));
			$i=pdo_update("nets_hjk_partner_logs",array("status"=>1),array("logno"=>$_GPC["orderno"]));
			$openid = $_W["openid"];//粉丝在小程序下的openID
			//查询当前会员取消 uniacid openid,变更为uid zxq.2018.05.19 in wxapp.base.cls.php in 1485
			$member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_W[""]));
        	$m["credit2"]=$member["credit2"]+$logInfo["money"];
			$m['updated_at']=time();
			if($i>0){
				$j=pdo_update("nets_hjk_members",$m,array("id"=>$member["id"]));
                $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.uniacid=:uniacid and em.openid=:openid",array(":uniacid"=>$_W["uniacid"],":openid"=>$openid));
				return $this->result($errno, $message, $member);
			}else{
                $message="充值失败，请稍后在试";
				return $this->result($errno, $res["msg"], "");
			}
		}
    }
	
	//参数 type: '1积分2佣金3补贴4充值5提现',
	public function doPageLog(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		//删除多余的查询会员，直接取当前fans的uid即可 zxq.2018.05.19 wxapp.base.cls.php in 1506
		
		$uid = $_W["fans"]["uid"];
		$type=2;
		if(!empty($_GPC["type"])){
			$type=$_GPC["type"];	
		}
		$page=$_GPC["page"];
		$pagesize=1000;
		$state=$_GPC["state"];
		$where="where memberid=:memberid and type=".$type;
		$data[":memberid"]=$uid;
		$sql="SELECT *  FROM ".tablename("nets_hjk_member_logs").$where." order by id desc LIMIT ".(($page-1)*$pagesize).",".$pagesize;
		$list=pdo_fetchall($sql,$data);
		//var_dump($list);
		return $this->result($errno, $message, $list);
	}
	//查询签到记录
	public function doPageSignLog(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		//删除多余的查询会员，直接取当前fans的uid即可 zxq.2018.05.19 wxapp.base.cls.php in 1529
		
		$uid = $_W["fans"]["uid"];
		$type=2;
		$page=$_GPC["page"];
		$pagesize=10;
		$state=$_GPC["state"];
		$where="where memberid=:memberid and remark='签到'";
		$data[":memberid"]=$uid;
		$sql="SELECT *,date_format(from_unixtime(created_at), '%m.%d') AS 'today'  FROM ".tablename("nets_hjk_member_logs").$where." order by id desc LIMIT ".(($page-1)*$pagesize).",".$pagesize;
		$list=pdo_fetchall($sql,$data);
		//var_dump($list);
		return $this->result($errno, $message, $list);
	}
	
	//参数 获取用户等级
	public function doPageLevel(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		$memberlevel=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")."  where uniacid=:uniacid  and isuse=1 ",array(":uniacid"=>$_W["uniacid"]));
		for($i=0;$i<count($memberlevel);$i++){
			if(empty($memberlevel[$i]["gradename"])){
				$levelarr=array('普通','白银','黄金','铂金','钻石');
				if($memberlevel[$i]["name"]==0){
					$memberlevel[$i]["gradename"]="普通";
				}
				if($memberlevel[$i]["name"]==1){
					$memberlevel[$i]["gradename"]="白银";
				}
				if($memberlevel[$i]["name"]==2){
					$memberlevel[$i]["gradename"]="黄金";
				}
				if($memberlevel[$i]["name"]==3){
					$memberlevel[$i]["gradename"]="铂金";
				}
				if($memberlevel[$i]["name"]==4){
					$memberlevel[$i]["gradename"]="钻石";
				}
				
			}
			if(empty($memberlevel[$i]["identityname"])){
				if($memberlevel[$i]["type"]==0){
					$memberlevel[$i]["identityname"]="会员";
				}else{
					$memberlevel[$i]["identityname"]="盟主";
				}
			}
		}
		return $this->result($errno, $message, $memberlevel);
	}
	
	//参数1 fee:付费的金额
	//参数2 level:   升级到的等级，对应0,1,2,3,4
	public function doPageLeveUpPay() {
        global $_W;
		global $_GPC;
        $openid = $_W["openid"];//粉丝在小程序下的openID
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		$fee=0;
		if(!empty($_GPC["fee"])){
			$fee=$_GPC["fee"];
		}else{
			return $this->result(0, '', "金额错误");
		}
		$tid=date('YmdHis');
        $order = array(
                'tid' => $tid,
                'user' => $openid,
                'fee' => floatval($fee),
                'title' => '升级盟主',
        );
        $pay_params = $this->pay($order);
		
        if (is_error($pay_params)) {
			
            return $this->result(1, $pay_params);
		}
		//查询当前会员 取消uniacid openid 变更为uid查询，zxq.2018.05.19 in 1608
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$uid = $member["memberid"];
		$remark="升级盟主付费";
		$logs["uniacid"]=$_W["uniacid"];
		$logs["memberid"]=$member["memberid"];
		$logs["type"]=4;//1积分2佣金3补贴4充值5提现
		$logs["logno"]=$tid;
		$logs["title"]=$_GPC["level"];
		$logs["status"]=0;//0 生成 1 成功 2 失败
		$logs["money"]=floatval($fee);
		$logs["credit1"]=$member["credit1"];
		$logs["credit2"]=$member["credit2"];
		$logs["rechargetype"]="credit2";
		$logs["cashtype"]=$_GPC["cashtype"];
		$logs["remark"]=$remark;
		$logs["created_at"]=time();
		$logs["updated_at"]=time();
		$logs["deleted_at"]=0;
		$i=pdo_insert("nets_hjk_member_logs",$logs);
		$res["order"]=$order;
		$pay_params["tid"]=$tid;
		$res["pay_params"]=$pay_params;
        return $this->result(0, '', $pay_params);
    }
	
	//参数1 orderno：第一个LevelUpPay 返回的tid，带回来
	//参数2 level:   升级到的等级，对应0,1,2,3,4
	public function doPageLeveUpResult(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		//查询当前会员 取消uniacid openid 变更为uid查询，zxq.2018.05.19 in 1640
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		if(!empty($_GPC["orderno"])){
			$logInfo	=	pdo_get('nets_hjk_member_logs',array('logno'=>$_GPC["orderno"],'status'=>'0'));
			if(empty($logInfo)){
				return;
			}
			$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
			$credit1=intval($global["credit1_to_credit2"])*floatval($logInfo["money"]);
			$remark="升级盟主充值积分";
			$b= mc_credit_update($member["memberid"], "credit1", 1*$credit1, $log = array(0,$remark,"nets_haojk",0,0,1));
			
			$logs["uniacid"]=$_W["uniacid"];
			$logs["memberid"]=$member["memberid"];
			$logs["type"]=1;//1积分2佣金3补贴4充值5提现
			$logs["logno"]=$tid;
			$logs["title"]=$_GPC["level"];
			$logs["status"]=1;//0 生成 1 成功 2 失败
			$logs["money"]=floatval($credit1);
			$logs["credit1"]=$member["credit1"];
			$logs["credit2"]=$member["credit2"];
			$logs["rechargetype"]="credit2";
			$logs["cashtype"]=$_GPC["cashtype"];
			$logs["remark"]=$remark;
			$logs["created_at"]=time();
			$logs["updated_at"]=time();
			$logs["deleted_at"]=0;
			$i=pdo_insert("nets_hjk_member_logs",$logs);
			$i=pdo_update("nets_hjk_members",array("level"=>$_GPC["level"]),array("memberid"=>$member["memberid"]));
			if($i==1){
				pdo_update("nets_hjk_member_logs",array("status"=>1),array("logno"=>$_GPC["orderno"]));
				$sumcredit1=(floatval($credit1)+floatval($member["credit1"]));
				$remark="升级盟主扣除积分";
				$logs["uniacid"]=$_W["uniacid"];
				$logs["memberid"]=$member["memberid"];
				$logs["type"]=1;//1积分2佣金3补贴4充值5提现
				$logs["logno"]=$tid;
				$logs["title"]=$_GPC["level"];
				$logs["status"]=1;//0 生成 1 成功 2 失败
				$logs["money"]=-1*$sumcredit1;
				$logs["credit1"]=$member["credit1"];
				$logs["credit2"]=$member["credit2"];
				$logs["rechargetype"]="credit2";
				$logs["cashtype"]=$_GPC["cashtype"];
				$logs["remark"]=$remark;
				$logs["created_at"]=time();
				$logs["updated_at"]=time();
				$logs["deleted_at"]=0;
				$i=pdo_insert("nets_hjk_member_logs",$logs);
				$b= mc_credit_update($member["memberid"], "credit1", -1*$sumcredit1, $log = array(0,$remark,"nets_haojk",0,0,1));
				return $this->result($errno, $message, $member);
			}else{
				return $this->result($errno, $res["msg"], "");
			}
		}
		return $this->result($errno, $res["msg"], "");
		
	}
	//积分足够的情况会员等级直接升级
	//参数1 level:   升级到的等级，对应0,1,2,3,4
	public function doPageLeveUp(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		//查询当前会员 取消uniacid openid 变更为uid查询，zxq.2018.05.19 in 1704
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		$level=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and type=1  and isuse=1 and name=:name",array(":uniacid"=>$_W["uniacid"],":name"=>$_GPC["level"]));
		$recharge_get=$level["recharge_get"];//直接升级要扣除的积分	
		$i=pdo_update("nets_hjk_members",array("level"=>$_GPC["level"]),array("memberid"=>$member["memberid"]));
		if($member["credit1"]<$recharge_get){
			return $this->result($errno, "积分不足", "");
		}
		if($i>=1){
			$remark="升级盟主扣除积分";
			$b= mc_credit_update($member["memberid"], "credit1", -1*$recharge_get, $log = array(0,$remark,"nets_haojk",0,0,1));
			return $this->result($errno, $message, $member);
		}else{
			return $this->result($errno, "系统错误", "");
		}
	}
	
	//签到
	public function doPageSign(){
		global $_GPC, $_W;
		$errno = 0;
		$message="";
		$credit1=sign_credit1();
		$formid=$_GPC["formid"];
		if(!empty($formid)){
			//查询当前会员 取消uniacid openid 变更为uid查询，zxq.2018.05.19 in 1729
			$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
			$form["uniacid"]=$_W["uniacid"];
			$form["memberid"]=$member["memberid"];
			$form["fromid"]=$formid;
			$form["created_at"]=time();
			pdo_insert("nets_hjk_wxappform",$form);
		}
		if(!empty($credit1)){
			$message="签到成功，获得".$credit1."积分！";	
		}
		return $this->result($errno, $message, $credit1);
		
	}
	
	//生成商品海报
	/*
	* 获取商品推广海报
	* $skuId 商品标识ID
	* $skuName 商品标题
	* $skuDesc 商品描述
	* $materiaUrl 商品购买链接
	* $picUrl 商品图片
	* $wlPrice 商品原价
	* $wlPrice_after 券后价
	* $discount 优惠券金额
	*/
	public function doPageGetGoodPoster(){
		global $_GPC, $_W;
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		$qrpath="";
		$poster=new Poster();
		if($global["goodsqrtype"]!="1"){
			//查询当前会员 取消uniacid openid 变更为uid查询，zxq.2018.05.19 in 1762
			$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
			
			//生成商品详细页的小程序二维码
			load()->func('communication');
			$token=$this->get_accessToken();
			$url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token='.$token;
			//中文urldecode
			$par["scene"]=$_GPC["skuId"].'_'.$member['memberid'];
			$par["page"]="haojk/pages/detail/index";
			$par["width"]=231;
			$par["auto_color"]=true;
			$c["r"]=0;
			$c["g"]=0;
			$c["b"]=0;
			$par["line_color"]=$c;
			
			$qrpath=IA_ROOT.'/addons/nets_haojk/cache/app_goods_qr_'.$_GPC["skuId"]."_".$member['memberid'].'.jpg';
			$qrurl=$_W['siteroot'].'/addons/nets_haojk/cache/app_goods_qr_'.$_GPC["skuId"]."_".$member['memberid'].'.jpg';
			if(!file_exists($qrpath)){
				$result = ihttp_post($url,json_encode($par));
				haojk_log("商品小程序码返回：".json_encode($result));
				file_put_contents($qrpath, $result["content"]);
				if($global['goodposter']=="2"){
					$poster->resize_image($qrpath,$qrpath,211,211);
				}else{
					$poster->resize_image($qrpath,$qrpath,231,231);
				}
			}
		}
		haojk_log("商品小程序码路径：".$global["goodsqrtype"].":".json_encode($qrpath));
		$errno = 0;
		$message="";
		$skuId=$_GPC["skuId"];
		$skuName=$_GPC["skuName"];
		$skuDesc=$_GPC["skuDesc"];
		$materiaUrl=$_GPC["materiaUrl"];
		$picUrl=$_GPC["picUrl"];
		$wlPrice=$_GPC["wlPrice"];
		$wlPrice_after=$_GPC["wlPrice_after"];
		$discount=$_GPC["discount"];
		if($global['goodposter']=="2"){
			$res=$poster->getGoodPoster2($skuId,$skuName,$skuDesc,$materiaUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,7,$qrpath);
		}else{
			$res=$poster->getGoodPoster($skuId,$skuName,$skuDesc,$materiaUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,7,$qrpath);
		}
		return $this->result($errno, $message, $res);
	}
	
	/*
	* 普通用户提单拿补贴,
	* 只有普通用户才可以提单，
	* 并且是平台开启补贴功能后,global里isopen_subsidy=1的情况才能提单
	* 参数1：orderno，订单号
	*/
	public function doPageSubmitOrder(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$orderno=$_GPC["orderno"];
		if(empty($orderno)){
			$message="订单号不能为空";
			return $this->result($errno, $message, 0);
		}
		$checkorder=checkorders_byorderno($orderno);
		if(empty($checkorder["data"])){
			$message="云服务暂未查到该订单，请稍后在来提单";
			return $this->result($errno, $message, 0);
		}
		//查询当前会员 取消uniacid openid 变更为uid查询，zxq.2018.05.19 in 1831
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
		if($member["type"]!=0){
			$message="盟主不可以提单";
			return $this->result($errno, $message, 0);
		}
		$order=pdo_fetch("select * from ".tablename("nets_hjk_orders")." where orderno=:orderno",array(":orderno"=>$orderno));
		if(!empty($order)){
			$message="该订单号已提交过，不能重复提单！";
			return $this->result($errno, $message, 0);
		}
		$o["orderno"]=$orderno;
		$o["uniacid"]=$_W["uniacid"];
		$o["memberid"]=$member["memberid"];
		$o["created_at"]=time();
		$o["state"]=0;
		$i=pdo_insert("nets_hjk_orders",$o);
		if($i>0){
			$message="提单成功，请等待审核";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $i);
	}

	/*
	* 发送短信验证码2分钟有效期
	* 参数1：mobile，手机号
	*/
	public function doPageSms(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$mobile=$_GPC['mobile'];
		if(empty($_GPC['mobile'])){
			$message="手机号码不能为空";
			return $this->result($errno, $message, 0);
		}
		if(empty($_W['openid'])){
			$message="非法访问";
			//return $this->result($errno, $message, 0);
		}
		$res=sendsms($mobile);
		if(!empty($res)){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
	/*
	* 发送短信验证码2分钟有效期
	* 参数1：mobile，手机号
	* 参数2：code，收到的验证码
	* 参数3：isbind，默认false，在绑定提示手机号码已被其他人绑定后，用户确认再次绑定的时候isbind=true
	*/
	public function doPageBindMobile(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$mobile=$_GPC['mobile'];
		$code=$_GPC['code'];
		$isbind=$_GPC['isbind'];
		$password=$_GPC['password'];
		$memberid=$_GPC['memberid'];
		if(empty($_GPC['mobile'])){
			$message="手机号码不能为空";
			return $this->result($errno, $message, 0);
		}
		if(empty($_GPC['code'])){
			$message="验证码不能为空";
			return $this->result($errno, $message, 0);
		}
		if(empty($_W['openid'])){
			$message="非法访问";
			//return $this->result($errno, $message, 0);
		}

		if (NETS_HAOJIK_NUMBER=="TRUE") {
				$list = pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_numbers")." AS em where em.phone like '%".$_GPC['mobile']."%' ",array(':uniacid'=>$uniacid));
				$listBind = pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_numbers")." AS em where em.phone like '%".$_GPC['mobile']."%' AND em.memberid like '%".$_GPC['memberid']."%'",array(':uniacid'=>$uniacid));
				if (!empty($list)) {  //号码在号码池中
					if (!empty($listBind)) {  //号码已被自己绑定
						$msg['message']='手机号已绑定!';
						$msg['status']='200';
						return $this->result($errno, $message, $msg);
					}else{
						$data = array(
                        'memberid' => $_GPC['memberid'],
                        );
						$res=bindMobile($mobile,$code,$password,$isbind,true);
						$r = pdo_update('nets_hjk_numbers',$data,array('phone'=>$_GPC['mobile']));
						if(!empty($r) and !empty($res)){
						$msg['message']='操作成功';
						$msg['status']='200';
						}else{
						$msg['message']='操作失败';
						$msg['status']='200';
						}
						return $this->result($errno, $message, $msg);
					}
				}else{
					$msg['message']='号码未在号码池内不允许绑定，请联系管理员';
					$msg['status']='200';
					return $this->result($errno, $message, $msg);
				}
		}else{
			$res=bindMobile($mobile,$code,$password,$isbind,true);
			if(!empty($res)){
				$message="操作成功";
			}else{
				$message="操作失败";
			}
			return $this->result($errno, $message, $res);
		}
	}

	/*
	* 设置首页模板
	* 参数1：skinid，默认0老版的首页，1新版的首页
	*/
	public function doPageSetShopSkin(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skinid=$_GPC['skinid'];
		//删除没用的会员查询 直接用fans的uid zxq.2018.05.19 in wxapp.base.cls.php in 1957
		$i=pdo_update('nets_hjk_members',array('homeskinid'=>$skinid),array('id'=>$_W['fans']['id']));
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}

	/*
	* 设置店铺名称
	* 参数1：shopname 店铺名称
	* 参数2：shopdesc 店铺描述
	* 参数3：shoplogo 店铺logo
	* 参数4：jduniacid 京东联盟id，合伙人设置的时候需要
	*/
	public function doPageSetShopName(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$shopname=$_GPC['shopname'];
		$shopdesc=$_GPC['shopdesc'];
		$shoplogo=$_GPC['shoplogo'];
		$jduniacid=$_GPC['jduniacid'];
		//删除没用的会员查询 直接用fans的uid zxq.2018.05.19 in wxapp.base.cls.php in 1982
		$i=pdo_update('nets_hjk_members',array('jduniacid'=>$jduniacid,'shopname'=>$shopname,'shopdesc'=>$shopdesc,'shoplogo'=>$shoplogo),array('memberid'=>$_W['fans']['uid']));
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}

	/**
	 * 查询订单统计
	 */
	public function doPageGetOrdersIncomeStatistics(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersIncomeStatistics();
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
	/**
	 * 今日查询订单统计
	 */
	public function doPageGetordersincomestatistics_today(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersincomestatistics_today();
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
	/**
	 * 昨日查询订单统计
	 */
	public function doPageGetordersincomestatistics_yester(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersincomestatistics_yester();
		if($res>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
	/**
	 * 当月查询订单统计
	 */
	public function doPageGetordersincomestatistics_thismonth(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersincomestatistics_thismonth();
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
	/**
	 * 上月查询订单统计
	 */
	public function doPageGetordersincomestatistics_yestermonth(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=ordersincomestatistics_yestermonth();
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
	/**
	 * 查询订单统计
	 */
	public function doPageGetPartnerOrdersIncomeStatistics(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res=partnerOrdersIncomeStatistics();
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}

	/**
	 * 查询商品详情
	 * 参数1 $skuid  商品skuid
	 */
	public function doPageGetGoodsDetail(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuid=$_GPC['skuid'];
		$res=getGoodsDetail($skuid);
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
/**
	 * 查询商品详情
	 * 参数1 $skuid  商品skuid
	 */
	public function doPageGetGoodsDetailJD(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuid=$_GPC['skuid'];
		$res=getGoodsDetailJD($skuid);
		if($i>0){
			$message="操作成功";
		}else{
			$message="操作失败";
		}
		return $this->result($errno, $message, $res);
	}
	//上传图片，返回图片URL
	/**
	 * 参数 file 的 name为 upfile
	 */
	public function doPageUpload(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		$fiename='/images/'.time().'.jpg';
		move_uploaded_file($_FILES['upfile']['tmp_name'], ATTACHMENT_ROOT.$fiename);
		$filepath=$_W['siteroot']."attachment".$fiename;
		return $this->result($errno, $message, $filepath);
	}

	/**
	 * 查询搜索关键词
	 */
	public function doPageGetsearchkeyword(){
		global $_W;
		global $_GPC;
		$errno = 0;
		$message="操作成功";
		$res=getsearchkeyword();
		return $this->result($errno, $message, $res);
	}

	/*
	* 首页订单弹幕接口虚拟数据列表，弹幕信息从列表里随机抽取一个
	*/
	public function doPageGetOrderTipInfo(){
		global $_GPC, $_W;
		$errno = 0;
		$message="success";
		$ordertip=pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_ordertip")." where uniacid=:uniacid limit 50 ",array(":uniacid"=>$_W["uniacid"]));
		if($ordertip){
			$message="success";
		}else{
			$message="error";
		}
		return $this->result($errno, $message, $ordertip);
	}

	/*
	* 获取免单商品
	* 参数1：page，当前页数
	*/
	public function doPageGetFreeGoods(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$page=$_GPC['page'];
		$res=getFreeGoods($page);
		return $this->result($errno, $message, $res);
	}
	/*
	* 我要砍价,加入到我的砍价列表
	* 参数1：skuId，商品skuId,传0则返回我的砍价列表
	*/
	public function doPageSetFreeGoodsDetail(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuId=$_GPC['skuId'];
		$memberid=$_GPC['memberid'];
		$res=setFreeGoodsDetail($skuId,$memberid);
		return $this->result($errno, $message, $res);
	}
	/*
	* 我的某个商品的砍价明细
	* 参数1：skuId，商品skuId
	* $memberid 发起砍价的memberid
	* $loginmemberid 当前登陆的会员ID
	* 
	*/
	public function doPageGetMyCuttingDetail(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuId=$_GPC['skuId'];
		$memberid=$_GPC['memberid'];
		$loginmemberid=$_GPC['loginmemberid'];
		$res=getMyCuttingDetail($skuId,$memberid);
		$iscutting=pdo_fetch("select * from ".tablename('nets_hjk_membercutting_rec')." where uniacid=:uniacid and cuttingid=:cuttingid and memberid=:memberid"
,array(":uniacid"=>$_W['uniacid'],":cuttingid"=>$res['id'],':memberid'=>$loginmemberid));

		if(empty($iscutting)){
			$iscutting=true;//当前登陆会员可以砍价
		}else{
			$iscutting=false;//当前登陆会员已砍过价，不能砍价
		}
		$res['iscutting']=$iscutting;
		return $this->result($errno, $message, $res);
	}
	/*
	* 我的某个商品的砍价明细
	* 参数1：cuttingid，砍价明细中的id参数
	*/
	public function doPageHelpCutting(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$cuttingid=$_GPC['cuttingid'];
		$memberid=$_GPC['memberid'];
		$res=helpCutting($cuttingid,$memberid);
		return $this->result($errno, $message, $res);
	}

	/*
	* 提交订单
	* 参数1 orderno 订单号
	* 参数2 memberid 会员id
	*/
	function doPageFreeordersubmit(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$orderno=$_GPC['orderno'];
		$memberid=$_GPC['memberid'];
		$res=freeordersubmit($orderno,$memberid);
		return $this->result($errno, $message, $res);
	}
	/*
	* 查询我的订单
	* 参数1 memberid 会员id
	*/
	function doPageGetfreeorders(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$memberid=$_GPC['memberid'];
		$res=getfreeorders($memberid);
		return $this->result($errno, $message, $res);
	}

	/*
	* 获取自定义分类
	*/
	function doPageGetUseCate(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$res = pdo_fetchall("SELECT id,name,icon FROM ".tablename('nets_hjk_menu')." where uniacid=:uniacid and type=3 ORDER BY id DESC",array(":uniacid"=>$_W['uniacid']));

		return $this->result($errno, $message, $res);
	}
	/*
	* 我要砍价,加入到我的砍价列表
	* 参数1：page，当前页数
	* 参数2：cateid，分类id
	*/
	function doPageGetUseCateGoods(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$page=$_GPC['page'];
		$cateid=$_GPC['cateid'];
		$res=$this->getMyuseselGoods($page,$cateid);
		return $this->result($errno, $message, $res);
	}
	function getMyuseselGoods($page,$cateid){
		global $_GPC,$_W;	
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
		
		$pagesize=20;
		$total=pdo_fetch("select count(0) AS 'total' from ".tablename("nets_hjk_usegoods")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
		$total=empty($total['total'])?0:$total['total'];
		$where="";
		if(!empty($cateid)){
			$where=" and menuid=".$cateid;
		}
		$usegoods=pdo_fetchall("select * from ".tablename("nets_hjk_usegoods")." where uniacid=:uniacid ".$where."  order by sort desc limit " . (($page - 1) * $pagesize) . ',' .$pagesize,array(":uniacid"=>$_W['uniacid']));
	
		$skuids="";
		foreach($usegoods AS $ug){
			$skuids.=$ug['skuId'].",";
		}
		$skuids=substr($skuids,0,strlen($skuids)-1);
		$operation = !empty($_GPC['op'])?$_GPC['op']:'useselgoods';
		$data = array(
			'unionId' => $global['jduniacid'],
			'skuIds' => $skuids,
		);
		$url=HAOJK_HOST."index/goodslisbyskuids";
		$temp_url="index/goodslisbyskuids".$_W['uniacid'];
		$filename=getfilename($temp_url);
		load()->func('communication');
		$res=ihttp_post($url,$data);
		$res=$res["content"];	
		$list=json_decode($res);
		$total=$list->total;
		$list=$list->data;
		$json_string = json_encode($list);
		file_put_contents($filename, $json_string);
		$json=file_get_contents($filename);
		$list=json_decode($json, true);
		for($i=0;$i<count($list);$i++){
			foreach($usegoods AS $ug){
				if($ug['skuId']==$list[$i]['skuId']){
					$list[$i]['sort']=$ug['sort'];
					$list[$i]['id']=$ug['id'];
				}
			}
		}
		return $list;
	}
	/**
	 * 提现打款审核
	 * id 提现记录的id
	 */
	public function doPageCashPay(){
		global $_GPC,$_W;
		//当前合伙人
		$tradeno=date('Ymd') . time();
		$_GPC['tradeno']=$tradeno;
		//$member=pdo_fetch("SELECT em.* FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid and em.openid=:openid",array(":uniacid"=>$_W["uniacid"],":openid"=>$_W["openid"]));
		//删除多余的membe查询 直接用fans的uid zxq.2018.05.19 wxapp.base.cls.php in 2333
		$info=pdo_fetch("SELECT a.*,m.openid,m.nickname FROM ".tablename('nets_hjk_member_logs')." AS a LEFT JOIN " .tablename('nets_hjk_members'). " AS m ON a.memberid=m.memberid and m.uniacid=".$_W["uniacid"]." WHERE a.type =5 and a.status = 0 and a.id=:id and partnermemberid=:partnermemberid",array(":id"=>$_GPC['id'],":partnermemberid"=>$_W["fans"]["uid"]));
		$errno = 0;
		$message="打款成功";
		$page=$_GPC['page'];
		$cateid=$_GPC['cateid'];
		if($member["credit2"]>0 && $info["money"]*-1<=$member["credit2"]){
			$res=$this->payWeixin($info['openid'],$info['money'],$cateid);
			if(empty($res['errno'])){
				$data['updated_at'] = time();
         		$data['status'] = 1;
				$data['remark']=$info['remark']."[打款成功，交易单号为：".$_GPC['tradeno']."] ";
				pdo_update("nets_hjk_member_logs",$data,array('id' => $_GPC['id'],'type'=> '5'));
				//打款成功扣除合伙人账户余额
				$m["credit2"]=$member["credit2"]+$info["money"];//这里money是负数 直接加就可以
				$m['updated_at']=time();
				$j=pdo_update("nets_hjk_members",$m,array("id"=>$member["id"]));
				//加入扣款记录
				$remark="用户[".$info["nickname"]."]提现";
				$logs["uniacid"]=$_W["uniacid"];
				$logs["memberid"]=$member["memberid"];
				$logs["type"]=5;//1积分2佣金3补贴4充值5提现
				$logs["logno"]=$tid;
				$logs["title"]="";
				$logs["status"]=1;//0 生成 1 成功 2 失败
				$logs["money"]=$info["money"];
				$logs["credit1"]=$member["credit1"];
				$logs["credit2"]=$member["credit2"];
				$logs["rechargetype"]="credit2";
				$logs["cashtype"]=0;
				$logs["remark"]=$remark;
				$logs["created_at"]=time();
				$logs["updated_at"]=time();
				$logs["deleted_at"]=0;
				$i=pdo_insert("nets_hjk_partner_logs",$logs);
			 }else{
				$data['updated_at'] = time();
				$data['status'] = 0;
				$data['remark']=$info['remark']."[打款失败，交易单号为：".$_GPC['tradeno']."] ";
				pdo_update("nets_hjk_member_logs",$data,array('id' => $_GET['id'],'type'=> '5'));
			}
		}else{
			$res=false;
			$message="账户余额不足";
		}
		
		return $this->result($errno, $message, $res);
        
	}

	function payWeixin($openid,$money){
        global $_GPC, $_W;
        $uniacid=$_W['uniaccount']['uniacid'];
        //测试
        $wxconfig=$this->get_wxconfig_admin();
        $settings["appid"]=$wxconfig['appid'];
        $settings["appsecret"]=$wxconfig['appsecret'];
        $settings["mchid"]=$wxconfig['mchid'];
        $settings["uniacid"]=$_W['uniaccount']['uniacid'];
        $settings['password']=$wxconfig['password'];
        $settings['tj_amount']=-1*$money*100;//money是负数//1*100; //这里要转换成分
        $toUser=$openid;//"onwnCvmcr8-_uDCa2BPLzC4xX3Es";//测试的openid
        $settings['ip']=$wxconfig['ip'];
        $settings['password']=$wxconfig['password'];
        $result=$this->sendhb($settings,$toUser);
        return $result;
    }
  function get_wxconfig_admin(){
		global $_GPC, $_W;
		$uniacid=$_W['uniacid'];
		$sql="select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid";
        $set=pdo_fetch($sql,array(":uniacid"=>$uniacid));
        
        $setting = uni_setting($uniacid, array('payment', 'recharge'));
        $pay = $setting['payment'];
        $wxconfig['appid']=trim($_W['uniaccount']['key']);
		$wxconfig['appsecret']=trim($_W['uniaccount']['secret']);
        $wxconfig['mchid']=trim($set["mchid"]);
        $wxconfig['ip']=$this->get_ip();//服务器IP
        $wxconfig['password']=$pay['wechat']['signkey'];
        //var_dump($wxconfig);
        
         if(!empty($set['wxappid'])){
            $wxconfig['appid']=$set['wxappid'];
         }
         if(!empty($set['wxappid'])){
            $wxconfig['appsecret']=$set['wxkey'];
         }
        return $wxconfig;
    }
	function get_ip(){
		if(isset($_SERVER)){
			if($_SERVER['SERVER_ADDR']){
				$server_ip=$_SERVER['SERVER_ADDR'];
			}
		}else{
			$server_ip = getenv('SERVER_ADDR');
		}
		if($this->_isPrivate($server_ip)||empty($server_ip)){

			$host=$_SERVER['HTTP_HOST'];
			$arr = explode('.',$host);
			if(count($arr)==3){
				$host=$arr[1].".".$arr[2];
			}
			$set["from_host"]=$host;
			$server_ip = gethostbyname($_SERVER["HTTP_HOST"]);
		}
		return $server_ip;
	}
	function _isPrivate($ip) {
		$i = explode('.', $ip);
		if ($i[0] == 10) return true;
		if ($i[0] == 172 && $i[1] > 15 && $i[1] < 32) return true;
		if ($i[0] == 127 && $i[1] == 0) return true;
		if ($i[0] == 192 && $i[1] == 168) return true;
		return false;
	}

    /*
     * 企业微信打款给微信用户
     */
    function sendhb($settings,$toUser){
        global $_GPC, $_W;
        define('MB_ROOT', IA_ROOT . '/attachment/botcert');//定义的微信支付证书路径
        load()->func('communication');
        if (empty($settings['tj_amount'])){
            return;
        }
        $amount=$settings['tj_amount'];
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $pars = array();
        $pars['mch_appid'] =$settings['appid'];
        $pars['mchid'] = $settings['mchid'];
        $pars['nonce_str'] = random(32);
        $pars['partner_trade_no'] = $_GPC['tradeno'];
        $pars['openid'] =$toUser;
        $pars['check_name'] = "NO_CHECK";
        $pars['amount'] =$amount;
        $pars['desc'] = "提现";
        $pars['spbill_create_ip'] =$settings['ip'];
        ksort($pars, SORT_STRING);
        $string1 = '';
        foreach($pars as $k => $v) {
            $string1 .= "{$k}={$v}&";
        }
        $string1 .= "key={$settings['password']}";
        $pars['sign'] = strtoupper(md5($string1));
        $xml = array2xml($pars);
        $extras = array();
        $extras['CURLOPT_CAINFO'] = MB_ROOT . '/rootca.pem.7';
        $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/apiclient_cert.pem.7';
        $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/apiclient_key.pem.7';
        if(!empty($settings["uniacid"])){
            $extras['CURLOPT_CAINFO'] = MB_ROOT . '/rootca.pem.'.$settings["uniacid"];
            $extras['CURLOPT_SSLCERT'] = MB_ROOT . '/apiclient_cert.pem.'.$settings["uniacid"];
            $extras['CURLOPT_SSLKEY'] = MB_ROOT . '/apiclient_key.pem.'.$settings["uniacid"];
        }
        $procResult = null;
        $resp = ihttp_request($url, $xml, $extras);
        if(is_error($resp)){
            $procResult = $resp;
        } else {
            $xml = '<?xml version="1.0" encoding="utf-8"?>' . $resp['content'];
            $dom = new DOMDocument();
            if($dom->loadXML($xml)) {
                $xpath = new DOMXPath($dom);
                $code = $xpath->evaluate('string(//xml/return_code)');
                $ret = $xpath->evaluate('string(//xml/result_code)');
                if(strtolower($code) == 'success' && strtolower($ret) == 'success') {
                    $procResult = true;
                } else {
                    $error = $xpath->evaluate('string(//xml/err_code_des)');
                    $procResult = error(-2, $error);
                }
            } else {
                $procResult = error(-1, 'error response');
            }
            //var_dump($procResult);
        }
        return $procResult;
	}
	
	
	/**
	 * 拼多多统一函数入口
	 * $_GPC post过来的参数，对应方法参数参考cloud.pdd.func.php
	 * pddapi  post过来的需要调用的对应函数，如getpdd_goodlist，参考cloud.pdd.func.php里的方法名
	 */
	public function doPagePindd(){
		global $_GPC, $_W;
		$errno = 0;
		$message = '请求成功';
		$data=array();
		if(function_exists($_GPC['pddapi'])){
			$pddapi=$_GPC['pddapi'];
			$data=$pddapi();
		}else{
			$message = '接口不存在，请检查接口名称是否正确！';
		}
		return $this->result($errno, $message, $data);
	}

    /**
     *购物圈统一函数入口
     * $_GPC post过来的参数，对应方法参数参考cloud.feed.func.php
     * feedapi  post过来的需要调用的对应函数，如getfeed_list，参考cloud.feed.func.php里的方法名
     */
    public function doPageFeed(){
        global $_GPC, $_W;
        $errno = 0;
        $message = '请求成功';
        $data=array();
        if(function_exists($_GPC['feedapi'])){
            $feedapi=$_GPC['feedapi'];
            $data=$feedapi();
        }else{
            $message = '接口不存在，请检查接口名称是否正确！';
        }
        return $this->result($errno, $message, $data);
	}
	//获取商品分类列表
	public function doPagecnamelist(){
		global $_W;
		global $_GPC;
		
		$errno = 0;
		$message="操作成功";
		$clist=getcnamelist("cname",0,0,0,0,0,0,0,0,0);
		return $this->result($errno, $message, $clist);	
	}

	/**
     *订单v2接口
     * $_GPC post过来的参数，对应方法参数参考cloud.ordersv2.func.php
     * orderapi  post过来的需要调用的对应函数，如jd_getorderlist，参考cloud.ordersv2.func.php里的方法名
     */
	public function doPageOrdersv2(){
		global $_GPC, $_W;
        $errno = 0;
        $message = '请求成功';
        $data=array();
        if(function_exists($_GPC['orderapi'])){
            $orderapi=$_GPC['orderapi'];
            $data=$orderapi();
        }else{
            $message = '接口不存在，请检查接口名称是否正确！';
        }
        return $this->result($errno, $message, $data);
	}

	/**
     *助力拉新入口接口
     * $_GPC post过来的参数，对应方法参数参考cloud.helpset.func.php
     * api  post过来的需要调用的对应函数，如 helpset_getone，参考cloud.helpset.func.php里的方法名
     */
	public function doPageHelpset(){
		global $_GPC, $_W;
        $errno = 0;
        $message = '请求成功';
        $data=array();
        if(function_exists($_GPC['api'])){
            $api=$_GPC['api'];
            $data=$api();
        }else{
            $message = '接口不存在，请检查接口名称是否正确！';
        }
        return $this->result($errno, $message, $data);
	}

}
?>