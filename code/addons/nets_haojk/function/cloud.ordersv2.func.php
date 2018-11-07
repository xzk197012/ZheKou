<?php
/***
     * 京东 订单列表 全
     * $parm 参数数组
     * $parm['pids'] 推广位ID 多个逗号分隔
     * $parm['unionId'] 联盟ID
     * $parm['begintime'] 开始时间
     * $parm['endtime'] 结束时间
     * $parm['page'] 页面
     * $parm['pageSize'] 每页条数
     *$parm['orderids'] orderids 多个逗号分隔
     *$parm['yn'] 状态  多个逗号分隔（-1：未知,2.无效-拆单,3.无效-取消,4.无效-京东帮帮主订单,5.无效-账号异常,6.无效-赠品类目不返佣,7.无效-校园订单,8.无效-企业订单,9.无效-团购订单,10.无效-开增值税专用发票订单,11.无效-乡村推广员下单,12.无效-自己推广自己下单,13.无效-违规订单,14.无效-来源与备案网址不符,15.待付款,16.已付款,17.已完成,18.已结算）
     **$parm['status'] 状态 0 无效 1 有效（15.待付款,16.已付款,17.已完成,18.已结算）
***/
function jd_getorderlist(){
	global $_W;
	global $_GPC;
	load()->func('communication');
	$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
	$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
	$global["jdpid"]='';
	//是盟主才验证推广位
	if($member["type"]!=0){
		$global["jdpid"]=$member["jd_bitno"];
	}
	if(!empty($member["from_jduniacid"])){
		$global["jduniacid"]=$member["from_jduniacid"];
	}
	$orderids=$_GPC['keyword'];
	if(!empty($member) && $member["type"]==0){
		$orderids='-1,';
		$page=empty($_GPC["page"])?1:$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
		$hjk_orders=pdo_fetchall("select * from ".tablename("nets_hjk_orders")." where memberid=:memberid and locate('-',orderno)=0  order BY id DESC limit " . (($page - 1) * $pagesize) . "," . $pagesize,array(":memberid"=>$member["memberid"]));
 		foreach($hjk_orders AS $o){
			$orderids.=$o["orderno"].",";
		}
		if(!empty($orderids)){
			$orderids=substr($orderids,0,strlen($orderids)-1);
		}
	}
	if($_GPC["isbyuseradmin"]==1){
		$orderids='-1,';
		$page=empty($_GPC["page"])?1:$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
		$hjk_orders=pdo_fetchall("select * from ".tablename("nets_hjk_orders")." where uniacid=:uniacid  and locate('-',orderno)=0  ".$sortstr." limit ".(($page-1)*$pagesize).",".$pagesize,array(":uniacid"=>$_W["uniacid"]));
		foreach($hjk_orders AS $o){
			$orderids.=$o["orderno"].",";
		}
		if(!empty($orderids)){
			$orderids=substr($orderids,0,strlen($orderids)-1);
		}
		$data["orderids"]=$orderids;
		//返利订单查询条件
		if(!empty($_GPC["userorder_keyword"]))
        {
            $fanli_orders=pdo_fetchall("select * from ".tablename("nets_hjk_orders")." where uniacid=:uniacid and (orderno=:keyword or memberid=:keyword)  and locate('-',orderno)=0  ".$sortstr." limit ".(($page-1)*$pagesize).",".$pagesize,array(":uniacid"=>$_W["uniacid"],":keyword"=>$_GPC["userorder_keyword"]));
            $orderids='-1,';
            foreach($fanli_orders AS $o){
                $orderids.=$o["orderno"].",";
            }
            $data["orderids"]=$orderids;
        }
	}
    if($_GPC["isbyuseradmin"]==2){//砍价订单
        $orderids='-1,';
        $page=empty($_GPC["page"])?1:$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
        $hjk_orders=pdo_fetchall("select * from ".tablename("nets_hjk_freeorders")." where uniacid=:uniacid  and locate('-',orderno)=0  ".$sortstr." order by id desc limit ".(($page-1)*$pagesize).",".$pagesize,array(":uniacid"=>$_W["uniacid"]));
        foreach($hjk_orders AS $o){
            $orderids.=$o["orderno"].",";
        }
        if(!empty($orderids)){
            $orderids=substr($orderids,0,strlen($orderids)-1);
        }
        $data["orderids"]=$orderids;
        //返利订单查询条件
        if(!empty($_GPC["userorder_keyword"]))
        {
            $fanli_orders=pdo_fetchall("select * from ".tablename("nets_hjk_freeorders")." where uniacid=:uniacid and (orderno=:keyword or memberid=:keyword)  and locate('-',orderno)=0  ".$sortstr." limit ".(($page-1)*$pagesize).",".$pagesize,array(":uniacid"=>$_W["uniacid"],":keyword"=>$_GPC["userorder_keyword"]));
            $orderids='-1,';
            foreach($fanli_orders AS $o){
                $orderids.=$o["orderno"].",";
            }
            $data["orderids"]=$orderids;
        }
    }
	$url=HAOJK_HOST."index/getorderlist";
    //普通用户重新组织查询条件
    if(!empty($member) && $member["type"]==0){
        $data["orderids"]=$orderids;
    }
    if(!empty($_GPC["orderids"])){
        $data["orderids"]=$_GPC["orderids"];
    }
    if(!empty($data["orderids"]))
        $_GPC['page']=1;
	//组织参数
	$data["unionId"]=trim($global["jduniacid"]);
	$data["pids"]=trim($global["jdpid"]);
	$data["page"]=max(1, intval($_GPC['page']));
	$data["pageSize"]=$pagesize;
	$data["yn"]="";
	$begintime='';
	$endtime='';
	if(!empty($_GPC['yn'])){
		$data["yn"]=$_GPC['yn'];
	}
	if(!empty($_GPC['pid'])){
		$data["pids"]=$_GPC['pid'];
	}
	if(!empty($_GPC['begintime'])){
		$begintime=$_GPC['begintime'];
		$endtime=$_GPC['endtime'];
		$data["begintime"]=$begintime;
		$data["endtime"]=$endtime;
	}

	$res=ihttp_post($url,$data);
	$orderlist=$res["content"];
	$orderlist=json_decode($orderlist);
	
	//普通用户订单计算补贴
	if(!empty($member) && $member["type"]==0){
		$orderlist->data=order_subsidyv2($orderlist->data,$global,$uri);
	}else{//计算盟主佣金
		$orderlist->data=order_commissionv2($orderlist->data,$global,$uri);
	}
	$orderlist=object_array_v2($orderlist);
	return $orderlist;
}

//计算用户订单补贴
function order_subsidyv2($orders,$global,$uri){
	global $_W;
	global $_GPC;
	
	$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
	//统一会员调整 根据openid查询会员变更为uid zxq.2018.05.18 cloud.orders.func.php in 708
	//$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid and em.openid=:openid",array(":uniacid"=>$_W["uniacid"],":openid"=>$_W["openid"]));
	$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
	$level_commission="";
	/*
	$log = array(
		0 => 操作管理员uid
		1 => 增减积分备注
		2 => 模块标识，例如：we7_store
		3 => 店员uid
		4 => 门店id
		5 => 1(线上操作) 2(系统后台,公众号管理员和操作员) 3(店员)
	);
	*/
	//已经计算过补贴的订单id
	$orderid_str="";
	//为空直接返回
	if(empty($orders)){
		return $orders;
	}
	foreach($orders AS $o){
		$commission=0;
		//从商品中累加可获得的佣金，计算该会员可得的佣金
		foreach($o->skus AS $sk){
			$commission=floatval($commission)+floatval($sk->commission)*0.9;
		}
		$o->commission=$commission;
		$positionId=$o->positionId;
		$orderId=$o->orderId;
		//推广位归属会员
		$memberorder=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_orders")." AS o  where o.uniacid=:uniacid and o.orderno=:orderno",array(":uniacid"=>$_W["uniacid"],":orderno"=>$orderId));
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$memberorder["memberid"]));
		$o->my_commission=0;
		//平台补贴
		$subsidy_ratio=$global["subsidy"];
		$subsidy=floatval($commission)*floatval($subsidy_ratio)/100;
		$subsidy=sprintf("%.2f",$subsidy);
		$o->my_commission=$subsidy;
		$o->my_commission1=$subsidy;
		$o->subsidy_ratio=$subsidy_ratio;
		$o->members=$member;
		//给当前会员计算补贴
		$remark="订单[".$orderId."]补贴入账";
		$log=pdo_fetch("SELECT * from ".tablename("nets_hjk_member_logs")." WHERE logno=:logno AND memberid=:memberid",array(":logno"=>$orderId,":memberid"=>$member["memberid"]));
		$o->iscountcommission="false";//没有计算过佣金的
		if(!empty($log)){
			$o->iscountcommission="true";//计算过佣金的
		}
		if(empty($member) || $member["type"]==1){
			continue;//只计算出展示用，不入账户
		}
		$o->iscountcommission="false";//没有计算过佣金的
		if(!empty($log)){
			$o->iscountcommission="true";//计算过佣金的
			//echo "123";
			$o->my_commission=$log["money"];
			$o->my_commission1=$log["money"];
			continue;
		}else{
			if($o->validCode==18){//新版改版后订单状态等于18（已结算）才计算佣金入账
				$b= mc_credit_update($member["memberid"], "credit2", $subsidy, $log = array(0,$remark,"nets_haojk",0,0,1));
				if($b){
					$orderid_str.=$orderId.",";
					$logs["uniacid"]=$_W["uniacid"];
					$logs["memberid"]=$member["memberid"];
					$logs["type"]=3;//1积分2佣金3补贴
					$logs["logno"]=$orderId;
					$logs["title"]="";
					$logs["status"]=1;//0 生成 1 成功 2 失败
					$logs["money"]=$subsidy;
					$logs["credit1"]=$member["credit1"];
					$logs["credit2"]=$member["credit2"];
					$logs["rechargetype"]="credit2";
					$logs["remark"]=$remark;
					$logs["created_at"]=time();
					$logs["updated_at"]=time();
					$logs["deleted_at"]=0;
					$i=pdo_insert("nets_hjk_member_logs",$logs);
				}
			}
		}
	}
	return $orders;
}
//计算盟主订单佣金
function order_commissionv2($orders,$global,$uri){
	global $_W;
	global $_GPC;
	$level_commission="";
	if($uri=="orders"){
		$iscountfreeorder=checkorderbyfreegoodsv2($orders);
		if($iscountfreeorder){
			return;//如果计算了免单返还，这里不计算佣金了，下次进来在重新计算
		}
	}
	//已经计算过佣金的订单id
	$orderid_str="";
	//为空直接返回
	if(empty($orders)){
		return $orders;
	}
	foreach($orders AS $o){
		$commission=0;
		$positionId=$o->positionId;
		$orderId=$o->orderId;
		
		$o->my_commission=0;
		//推广位归属会员 统一会员变更查询  zxq.2018.05.18 cloud.orders.func.php in 430
		$member=pdo_fetch("SELECT m.*,mm.credit1,mm.credit2 from ".tablename("nets_hjk_members")." AS m
		left join ".tablename("mc_members")." AS mm on m.memberid=mm.uid 
WHERE m.jd_bitno=:bitno",array(":bitno"=>$positionId));
		//不存在当前归属会员
		if(empty($member)){
		}
		$level_commission=pdo_fetch("SELECT * from ".tablename("nets_hjk_memberlevel")." where name=:id and type=:type and uniacid=:uniacid",array(":id"=>$member["level"],":type"=>$member["type"],":uniacid"=>$_W["uniacid"]));
		//当前等级佣金比例不存在
		if(empty($level_commission)){
			//return $orders;
		}
		//如果有升级赚的佣金比例,则使用升级赚的
		$dataday=date("Ymd",$o->orderTime);
        $commissionlog=pdo_fetch("select * from ".tablename("nets_hjk_membercommission_log")." where memberid=:memberid and dataday=:dataday",array(":memberid"=>$member["memberid"],":dataday"=>$dataday));
        if(!empty($commissionlog["commission"])){
			$level_commission["myteam_credit2"]=$commissionlog["commission"];
		}    
		//从商品中累加可获得的佣金，计算该会员可得的佣金
		$cosPrice=0;
		foreach($o->skus AS $sk){
			$commission=floatval($commission)+(floatval($sk->commission)*0.9);
			$cosPrice=floatval($cosPrice)+floatval($sk->cosPrice);
		}
		$o->commission=$commission;
		$o->cosPrice=$cosPrice;
		//当前推广位归属会员计算佣金，拿我的用户佣金
		$level1_commission_ratio=$level_commission["myteam_credit2"];
		$level1_commission=floatval($commission)*floatval($level1_commission_ratio)/100;
		$level1_commission=sprintf("%.2f",$level1_commission);
		$o->my_commission=$level1_commission;
		$o->my_commission1=$level1_commission;
		$o->level1_commission_ratio=$level1_commission_ratio;
		$o->members=$member;
		//计算当前推广位用户获得的积分
		$credit1_log=pdo_fetch("SELECT * from ".tablename("nets_hjk_member_logs")." WHERE logno=:logno and type=1 AND memberid=:memberid",array(":logno"=>$orderId,":memberid"=>$member["memberid"]));
		
		if(empty($credit1_log) && $o->validCode==18){//新版改版后订单状态等于18（已结算）才计算佣金入账

			$level_crdit1=$level_commission["order_credit1"];
			$b= mc_credit_update($member["memberid"], "credit1", $level_crdit1, $log = array(0,$remark,"nets_haojk",0,0,1));
			if($b){
				$remark="订单[".$orderId."]积分入账";
				$logs["uniacid"]=$_W["uniacid"];
				$logs["memberid"]=$member["memberid"];
				$logs["type"]=1;//1积分2佣金3补贴
				$logs["logno"]=$orderId;
				$logs["title"]="";
				$logs["status"]=1;//0 生成 1 成功 2 失败
				$logs["money"]=$level_crdit1;
				$logs["credit1"]=$member["credit1"];
				$logs["credit2"]=$member["credit2"];
				$logs["rechargetype"]="credit1";
				$logs["remark"]=$remark;
				$logs["created_at"]=time();
				$logs["updated_at"]=time();
				$logs["deleted_at"]=0;
				$i=pdo_insert("nets_hjk_member_logs",$logs);

				$first=$remark;
				$keyword1="积分￥".$level_crdit1;
				$keyword2="订单积分结算";
				$time="时间：".date('Y-m-d H:i:s',time()) ;
				$data= array(
					'first'=>array('value'=>$first,'color'=>"#173177"),
					'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
					'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
					'remark'=>array('value'=>$time,'color'=>"#173177"),
				);
				$openid=$member['openid'];
				sendTemplateMsg($openid,$data,$url="");
				
			}
		}
		//给当前会员计算佣金
		$remark="订单[".$orderId."]佣金入账";
		$log=pdo_fetch("SELECT * from ".tablename("nets_hjk_member_logs")." WHERE logno=:logno and type=2 AND memberid=:memberid",array(":logno"=>$orderId,":memberid"=>$member["memberid"]));
		//验证补贴是否结算
		$subsidylog=pdo_fetch("SELECT * from ".tablename("nets_hjk_member_logs")." WHERE logno=:logno and type=3 ",array(":logno"=>$orderId));
		
		
		$o->iscountcommission="false";//没有计算过佣金的
		$o->iscountsubsidy="false";//没有计算过补贴的
		if(!empty($subsidylog)){
			$o->iscountsubsidy="true";
		}
		if(!empty($log)){
			$o->iscountcommission="true";//计算过佣金的
			$o->my_commission=$log["money"];
			//2级盟主 查询上级盟主取消uniacid查询 zxq.2018.05.18 cloud.orders.func.php in 500
			$member2=pdo_fetch("SELECT m.*,mm.credit1,mm.credit2 from  ".tablename("nets_hjk_members")." AS m 
			left join ".tablename("mc_members")." AS mm on m.memberid=mm.uid 
	WHERE m.memberid=:memberid",array(":memberid"=>$member["from_uid"]));
			//var_dump($member2);
			$o->my_commission2=0;
			$o->members2="";
			if(!empty($member2)){
				//查询佣金记录日志 取消uniacid条件 zxq.2018.05.18 cloud.orders.func.php in 508
				$log=pdo_fetch("SELECT * from ".tablename("nets_hjk_member_logs")." WHERE logno=:logno AND memberid=:memberid and remark=:remark",array(":logno"=>$orderId,":memberid"=>$member2["memberid"],":remark"=>"订单[".$orderId."]佣金审核入账"));
				$o->members2=$member2;
				if(!empty($log)){
					$o->my_commission2=$log["money"];
				}
			}
			//3级盟主 查询上上级盟主取消uniacid查询 zxq.2018.05.18 cloud.orders.func.php in 515
			$member3=pdo_fetch("SELECT m.*,mm.credit1,mm.credit2 from  ".tablename("nets_hjk_members")." AS m 
			left join ".tablename("mc_members")." AS mm on m.memberid=mm.uid 
	WHERE m.memberid=:memberid",array(":memberid"=>$member2["from_uid"]));
			$o->my_commission3=0;
			$o->members3="";
			if(!empty($member3)){
				//查询佣金记录日志 取消uniacid条件 zxq.2018.05.18 cloud.orders.func.php in 522
				$log=pdo_fetch("SELECT * from ".tablename("nets_hjk_member_logs")." WHERE logno=:logno AND memberid=:memberid and remark=:remark",array(":logno"=>$orderId,":memberid"=>$member3["memberid"],":remark"=>"订单[".$orderId."]佣金审核入账"));
				$o->members3=$member3;
				if(!empty($log)){
					$o->my_commission3=$log["money"];
				}
			}
		}
		else{
			if($o->validCode==18){//新版改版后订单状态等于18（已结算）才计算佣金入账
				$b= mc_credit_update($member["memberid"], "credit2", $level1_commission, $log = array(0,$remark,"nets_haojk",0,0,1));
				if($b){
					$orderid_str.=$orderId.",";
					$logs["uniacid"]=$_W["uniacid"];
					$logs["memberid"]=$member["memberid"];
					$logs["type"]=2;//1积分2佣金3补贴
					$logs["logno"]=$orderId;
					$logs["title"]="";
					$logs["status"]=1;//0 生成 1 成功 2 失败
					$logs["money"]=$level1_commission;
					$logs["credit1"]=$member["credit1"];
					$logs["credit2"]=$member["credit2"];
					$logs["rechargetype"]="credit2";
					$logs["remark"]=$remark;
					$logs["created_at"]=time();
					$logs["updated_at"]=time();
					$logs["deleted_at"]=0;
					$i=pdo_insert("nets_hjk_member_logs",$logs);

					$first=$remark;
					$keyword1="佣金￥".$level1_commission;
					$keyword2="订单佣金结算";
					$time="时间：".date('Y-m-d H:i:s',time()) ;
					$data= array(
						'first'=>array('value'=>$first,'color'=>"#173177"),
						'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
						'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
						'remark'=>array('value'=>$time,'color'=>"#173177"),
					);
					$openid=$member['openid'];
					sendTemplateMsg($openid,$data,$url="");

					//验证是否存在合伙人并计算合伙人佣金
					$o=count_partnercommission($o,$member,$global);
				}
			}
			//2级盟主 查询上级盟主取消uniacid查询 zxq.2018.05.18 cloud.orders.func.php in 568
			$member2=pdo_fetch("SELECT m.*,mm.credit1,mm.credit2 from  ".tablename("nets_hjk_members")." AS m 
			left join ".tablename("mc_members")." AS mm on m.memberid=mm.uid 
	WHERE m.memberid=:memberid",array(":memberid"=>$member["from_uid"]));
			
			if(empty($member2)){
				//continue;
			}
			$level_commission=pdo_fetch("SELECT * from ".tablename("nets_hjk_memberlevel")." where name=:id and type=:type and uniacid=:uniacid",array(":id"=>$member2["level"],":type"=>$member2["type"],":uniacid"=>$_W["uniacid"]));
			//当前等级佣金比例不存在 
			if(empty($level_commission)){
				//continue;
			}
			//如果有升级赚的佣金比例,则使用升级赚的
			// $dataday=date("Ymd",$o->orderTime);
			// $commissionlog=pdo_fetch("select * from ".tablename("nets_hjk_membercommission_log")." where memberid=:memberid and dataday=:dataday",array(":memberid"=>$member2["memberid"],":dataday"=>$dataday));
			// if(!empty($commissionlog["commission"])){
			// 	$level_commission["myleader1_credit2"]=$commissionlog["commission"];
			// }
			//当前推广位归属会员上级会员计算佣金，为1级盟主比例
			$level2_commission_ratio=$level_commission["myleader1_credit2"];
			$level2_commission=floatval($commission)*floatval($level2_commission_ratio)/100;
			$level2_commission=sprintf("%.2f",$level2_commission);
			//$o->my_commission=$level2_commission;
			$o->my_commission2=$level2_commission;
			$o->level2_commission_ratio=$level2_commission_ratio;
			$o->members2=$member2;
			
			if($o->validCode==18){//新版改版后订单状态等于18（已结算）才计算佣金入账
				$b= mc_credit_update($member2["memberid"], "credit2", $level2_commission, $log = array(0,$remark,"nets_haojk",0,0,1));
				if($b){
					$remark="下级会员[".$member["nickname"]."]订单[".$orderId."]结算佣金入账";
					$logs["uniacid"]=$_W["uniacid"];
					$logs["memberid"]=$member2["memberid"];
					$logs["type"]=2;//1积分2佣金3补贴4充值5提现
					$logs["logno"]=$orderId;
					$logs["title"]="";
					$logs["status"]=1;//0 生成 1 成功 2 失败
					$logs["money"]=$level2_commission;
					$logs["credit1"]=$member2["credit1"];
					$logs["credit2"]=$member2["credit2"];
					$logs["rechargetype"]="credit2";
					$logs["remark"]=$remark;
					$logs["created_at"]=time();
					$logs["updated_at"]=time();
					$logs["deleted_at"]=0;
					$i=pdo_insert("nets_hjk_member_logs",$logs);
					$first=$remark;
					$keyword1="佣金￥".$level2_commission;
					$keyword2="订单佣金结算";
					$time="时间：".date('Y-m-d H:i:s',time()) ;
					$data= array(
						'first'=>array('value'=>$first,'color'=>"#173177"),
						'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
						'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
						'remark'=>array('value'=>$time,'color'=>"#173177"),
					);
					$openid=$member2['openid'];
					sendTemplateMsg($openid,$data,$url="");
					//验证是否存在合伙人并计算合伙人佣金
					$o=count_partnercommission($o,$member2,$global);
				}
			}
			
			//3级盟主 查询上上级盟主取消uniacid查询 zxq.2018.05.18 cloud.orders.func.php in 627
			$member3=pdo_fetch("SELECT m.*,mm.credit1,mm.credit2 from  ".tablename("nets_hjk_members")." AS m 
			left join ".tablename("mc_members")." AS mm on m.memberid=mm.uid 
	WHERE m.memberid=:memberid",array(":memberid"=>$member2["from_uid"]));
			if(empty($member3)){
				//continue;
			}
			$level_commission=pdo_fetch("SELECT * from ".tablename("nets_hjk_memberlevel")." where name=:id and type=:type and uniacid=:uniacid",array(":id"=>$member3["level"],":type"=>$member3["type"],":uniacid"=>$_W["uniacid"]));
			//当前等级佣金比例不存在
			if(empty($level_commission)){
				//continue;
			}
			//如果有升级赚的佣金比例,则使用升级赚的
			// $dataday=date("Ymd",$o->orderTime);
			// $commissionlog=pdo_fetch("select * from ".tablename("nets_hjk_membercommission_log")." where memberid=:memberid and dataday=:dataday",array(":memberid"=>$member3["memberid"],":dataday"=>$dataday));
			// if(!empty($commissionlog["commission"])){
			// 	$level_commission["myleader2_credit2"]=$commissionlog["commission"];
			// }
			//当前推广位归属会员上级会员计算佣金，为1级盟主比例
			$level3_commission_ratio=$level_commission["myleader2_credit2"];
			$level3_commission=floatval($commission)*floatval($level3_commission_ratio)/100;
			$level3_commission=sprintf("%.2f",$level3_commission);
			$o->my_commission3=$level3_commission;
			$o->level3_commission_ratio=$level3_commission_ratio;
			$o->members3=$member3;
			if($o->validCode==18){//新版改版后订单状态等于18（已结算）才计算佣金入账
				$b= mc_credit_update($member3["memberid"], "credit2", $level3_commission, $log = array(0,$remark,"nets_haojk",0,0,1));
				if($b){
					$remark="下下级会员[".$member["nickname"]."]订单[".$orderId."]结算佣金入账";
					$logs["uniacid"]=$_W["uniacid"];
					$logs["memberid"]=$member3["memberid"];
					$logs["type"]=2;//1积分2佣金3补贴4充值5提现
					$logs["logno"]=$orderId;
					$logs["title"]="";
					$logs["status"]=1;//0 生成 1 成功 2 失败
					$logs["money"]=$level3_commission;
					$logs["credit1"]=$member3["credit1"];
					$logs["credit2"]=$member3["credit2"];
					$logs["rechargetype"]="credit2";
					$logs["remark"]=$remark;
					$logs["created_at"]=time();
					$logs["updated_at"]=time();
					$logs["deleted_at"]=0;
					$i=pdo_insert("nets_hjk_member_logs",$logs);
					$first=$remark;
					$keyword1="佣金￥".$level3_commission;
					$keyword2="订单佣金结算";
					$time="时间：".date('Y-m-d H:i:s',time()) ;
					$data= array(
						'first'=>array('value'=>$first,'color'=>"#173177"),
						'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
						'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
						'remark'=>array('value'=>$time,'color'=>"#173177"),
					);
					$openid=$member3['openid'];
					sendTemplateMsg($openid,$data,$url="");
					//验证是否存在合伙人并计算合伙人佣金
					$o=count_partnercommission($o,$member3,$global);
				}
			}
		}
        $o=count_partnercommission($o,$member,$global,"",true);
	}

	return $orders;
}
/**
 * 验证是否是砍价订单
 */
function checkorderbyfreegoodsv2($orders){
	global $_W;
	global $_GPC;
	$iscountfree=false;
	if(empty($orders)){
		return $iscountfree;
	}
	foreach($orders AS $o){
		
		$commission=0;
		//从商品中累加可获得的佣金，计算该会员可得的佣金
		foreach($o->skus AS $sk){
			$commission=floatval($commission)+floatval($sk->commission);
		}
		$positionId=$o->positionId;
		$orderId=$o->orderId;
		$orderlog=pdo_fetch("select * from ".tablename("nets_hjk_member_logs")." where logno=:orderno and remark like '%购买免单商品反还%'",array(":orderno"=>$orderId));
		if(!empty($orderlog)){
			continue;//这个订单已经存在免单返还了，继续下一个
		}
		$checkisfreeorder=pdo_fetch("select * from ".tablename("nets_hjk_freeorders")." where orderno=:orderno",array(":orderno"=>$orderId));
		
		if(!empty($checkisfreeorder)){
			//提交该订单的会员,返还订单的金额，会员提单的时候验证了订单中是否有砍价商品
			$ordermemberid=$checkisfreeorder['memberid'];
			$money=$o->cosPrice;
			$remark="[订单号".$orderId."]购买免单商品反还";
			$iscountfree=true;
			$b= mc_credit_update($ordermemberid, "credit2", $money, $log = array(0,$remark,"nets_haojk",0,0,1));
			if($b){
				$iscountfree=true;
				$orderid_str.=$orderId.",";
				$logs["uniacid"]=$_W["uniacid"];
				$logs["memberid"]=$ordermemberid;
				$logs["type"]=3;//1积分2佣金3补贴
				$logs["logno"]=$orderId;
				$logs["title"]="";
				$logs["status"]=1;//0 生成 1 成功 2 失败
				$logs["money"]=$money;
				$logs["credit1"]=$member["credit1"];
				$logs["credit2"]=$member["credit2"];
				$logs["rechargetype"]="credit2";
				$logs["remark"]=$remark;
				$logs["created_at"]=time();
				$logs["updated_at"]=time();
				$logs["deleted_at"]=0;
				$i=pdo_insert("nets_hjk_member_logs",$logs);
			}
		}
	}
	return $iscountfree;
}

//计算合伙人的佣金
//isshow=true 只显示用
function count_partnercommission($order,$member,$global,$uri='',$isshow=false){

	global $_W,$_GPC;
	//haojk_log("1会员id:".$member["memberid"].";合伙人id：".$partner_member["memberid"]);
		//var_dump($global);
	if(!empty($member["from_partner_uid"])){
		//查询订单归属会员的所属合伙人
		$partner_member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$member["from_partner_uid"]));
		//haojk_log("2会员id:".$partner_member["memberid"].";合伙人id：".$partner_member["memberid"]);
		if(!empty($partner_member)){
			//计算合伙人的佣金
			$commission=0;
			//从商品中累加可获得的佣金，计算该会员可得的佣金
			foreach($order->skus AS $sk){
				$commission=floatval($commission)+floatval($sk->commission);
			}
			$orderId=$order->orderId;
			$partnercommission=$global["partner_commission"];
			$level_commission=floatval($commission)*floatval($partnercommission)*0.9/100;
			//合伙人获得的佣金
			$level_commission=sprintf("%.2f",$level_commission);
			$order->partner_commission=$partnercommission;
			$order->partner_levelcommission=$level_commission;
			$order->partner_member=$partner_member;
			$remark="订单[".$orderId."]合伙人佣金入账";
			$credit2_log=pdo_fetch("SELECT * from ".tablename("nets_hjk_member_logs")." WHERE logno=:logno and type=2 AND memberid=:memberid AND remark=:remark",array(":logno"=>$orderId,":memberid"=>$partner_member["memberid"],":remark"=>$remark));
			//haojk_log("3佣金：".$commission.";会员id:".$member["memberid"].";合伙人获得：".$level_commission.";日志：".$credit2_log["id"]);
			if(empty($credit2_log) && $order->validCode==18){
				$b= mc_credit_update($partner_member["memberid"], "credit2", $level_commission, $log = array(0,$remark,"nets_haojk",0,0,1));
				if($b){
					
					$logs["uniacid"]=$_W["uniacid"];
					$logs["memberid"]=$partner_member["memberid"];
					$logs["type"]=2;//1积分2佣金3补贴
					$logs["logno"]=$orderId;
					$logs["title"]="";
					$logs["status"]=1;//0 生成 1 成功 2 失败
					$logs["money"]=$level_commission;
					$logs["credit1"]=$partner_member["credit1"];
					$logs["credit2"]=$partner_member["credit2"];
					$logs["rechargetype"]="credit2";
					$logs["remark"]=$remark;
					$logs["created_at"]=time();
					$logs["updated_at"]=time();
					$logs["deleted_at"]=0;
					$i=pdo_insert("nets_hjk_member_logs",$logs);

					$first=$remark;
					$keyword1="佣金￥".$level_commission;
					$keyword2=$remark;
					$time="时间：".date('Y-m-d H:i:s',time()) ;
					$data= array(
						'first'=>array('value'=>$first,'color'=>"#173177"),
						'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
						'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
						'remark'=>array('value'=>$time,'color'=>"#173177"),
					);
					$openid=$partner_member['openid'];
					sendTemplateMsg($openid,$data,$url="");
				}
			}
		}
	}
	return $order;
}

	function object_array_v2($array) {  
		if(is_object($array)) {  
			$array = (array)$array;  
		} 
		if(is_array($array)) {  
			foreach($array as $key=>$value) {  
				$array[$key] = object_array_v2($value);  
			}  
		}  
		return $array;  
	}

	//同步订单
	function getordertoday(){
		global $_W;
		global $_GPC;
		load()->func('communication');
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid",array(":memberid"=>$_W["fans"]["uid"]));
	
		$url=HAOJK_HOST."index/getordertoday";
		$data["unionId"]=trim($global["jduniacid"]);
		$res=ihttp_post($url,$data);

		$global=pdo_fetch("select * from ".tablename("nets_hjk_pdd_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		$pddurl=HAOJK_HOST."pdd/getordertoday";
		$data["unionId"]= $global["mobile"];
		$data["p_id"]= $member['pdd_bitno'];
		$res=ihttp_post($pddurl,$data);
		return 200;
	}

	//累加订单中的合伙人获得的佣金
	function sumorder_partnercommission($orders){
		//var_dump($orders);echo '<br/><br/>';
		$sum_commission=0;
		if(empty($orders)){
			return 0;
		}
		foreach($orders AS $o){
			if(!empty($o->partner_levelcommission)){
				$sum_commission=floatval($sum_commission)+floatval($o->partner_levelcommission);
			}
		}
		//echo $sum_commission."<br/>";
		return $sum_commission;
	}
?>