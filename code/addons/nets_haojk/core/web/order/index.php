<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Index_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='order.index'||$_W['action']=='order')
            $this->index();
        elseif ($_W['action']=='order.order')
            $this->order();
        elseif ($_W['action']=='order.userorder')
            $this->userorder();
	}
	//引入订单importorders
	public function index()
	{
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $jd_orderstate=json_decode(jd_orderstate,true);
        $uri= 'importorders';

        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
            $_GPC['begintime']=$starttime;
            $_GPC['endtime']=$endtime;
        }
        $nickname=$_GPC["keyword"];
        $page=$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
        $pid='';
        if(!empty($nickname)){
            $pid=getPidByNickname($nickname);
            if(empty($pid)){
                $pid=$nickname;
            }
        }
        $_GPC['pid'] = $pid;

        $list=jd_getorderlist();
        $pager = pagination($list['total'], $page, $pagesize);//var_dump($pager);die;
        $list = $list['data'];
        if(empty($list)){
            $list="";
        }
        include $this->template('haojingke/order/index');
	}

    //业绩订单 orders
    public function order()
    {
        global $_GPC, $_W;
        $uri= 'orders';
        $jd_orderstate=json_decode(jd_orderstate,true);
        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }
        $nickname=$_GPC["keyword"];
        $page=$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
        $pid='';
        if(!empty($nickname)){
            $pid=getPidByNickname($nickname);
            if(empty($pid)){
                $pid=$nickname;
            }
        }
        $list=getorders_byadmin($uri, $page, $pagesize,$starttime,$endtime,$pid,0);
        
        $pager = pagination($list['total'], $page, $pagesize);//var_dump($pager);die;
        $list = $list['data'];
        if(empty($list)){
            $list="";
        }
        include $this->template('haojingke/order/order');
    }

    //用户提单
    public function userorder()
    {
        global $_GPC, $_W;
        $uri= !empty($_GPC['uri']) ? $_GPC['uri'] : 'importorders';
        $jd_orderstate=json_decode(jd_orderstate,true);
        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }
        $_GPC['begintime']=$starttime;
        $_GPC['endtime']=$endtime;
        $_GPC["isbyuseradmin"]=1;
        $nickname=$_GPC["keyword"];
        $page=empty($_GPC["page"])?1:$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
        $_GPC["userorder_keyword"]=$_GPC["keyword"];

        $list=jd_getorderlist();
        $total=pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_orders")." where uniacid=".$_W['uniacid']." and locate('-',orderno)=0 ");
        $pager = pagination($total, $page, $pagesize);//var_dump($pager);die;
        $list = $list['data'];
        if(empty($list)){
            $list="";
        }

        include $this->template('haojingke/order/userorder');
    }
    //砍价提单
    public function cuttingorder()
    {
        global $_GPC, $_W;
        $uri= !empty($_GPC['uri']) ? $_GPC['uri'] : 'importorders';
        $jd_orderstate=json_decode(jd_orderstate,true);
        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }
        $nickname=$_GPC["keyword"];
        $page=empty($_GPC["page"])?1:$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
        $pid='';
        if(!empty($nickname)){
            $pid=getPidByNickname($nickname);
            if(empty($pid)){
                $pid=$nickname;
            }
        }
        $_GPC['begintime']=$starttime;
        $_GPC['endtime']=$endtime;
        $_GPC["isbyuseradmin"]=2;
        $nickname=$_GPC["keyword"];
        $page=empty($_GPC["page"])?1:$_GPC["page"];
        $pagesize=$_GPC["pagesize"];
        if(empty($pagesize))
            $pagesize = 20;
        $_GPC["userorder_keyword"]=$_GPC["keyword"];
        $list=jd_getorderlist();
        //var_dump($list);
        $total=pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_freeorders")." where uniacid=".$_W['uniacid']);
        $pager = pagination($total, $page, $pagesize);//var_dump($pager);die;
        $list = $list['data'];
        if(empty($list)){
            $list="";
        }

        include $this->template('haojingke/order/cuttingorder');
    }

    //提前结算订单佣金
    public function countordercommission(){
        global $_GPC, $_W;
        $partnerid=$_GPC["partnerid"];
        $partnercommission=$_GPC["partnercommission"];
        $memberid=$_GPC["memberid"];
        $commission=$_GPC["commission"];
        $membercommission=$_GPC["membercommission"];
        $memberid1=$_GPC["memberid1"];
        $membercommission1=$_GPC["membercommission1"];
        $memberid2=$_GPC["memberid2"];
        $membercommission2=$_GPC["membercommission2"];
        $orderId=$_GPC["orderId"];
        
        $remark="订单[".$orderId."]佣金审核入账";
        //合伙人佣金提前审核
        if(empty($commission) && !empty($partnerid) && !empty($partnercommission)){
            //代理佣金
            $partner=pdo_fetch("select * from ".tablename("mc_members")." where uid=:uid",array(":uid"=>$partnerid));
            $hjkpartner=pdo_fetch("select * from ".tablename("nets_hjk_members")." where uniacid=:uniacid and memberid=:uid",array(":uniacid"=>$_W["uniacid"],":uid"=>$partnerid));
            $m["credit2"]=$partner["credit2"]+floatval($partnercommission);
            //pdo_update("mc_members",$m,array("uid"=>$member["uid"]));
            $b= mc_credit_update($partner["uid"], "credit2", floatval($partnercommission), $log = array(0,$remark,"nets_haojk",0,0,1));
            $logs["uniacid"]=$_W["uniacid"];
            $logs["memberid"]=$partner["uid"];
            $logs["type"]=2;//1积分2佣金3补贴
            $logs["logno"]=$orderId;
            $logs["title"]="";
            $logs["status"]=1;//0 生成 1 成功 2 失败
            $logs["money"]=$partnercommission;
            $logs["credit1"]=$partner["credit1"];
            $logs["credit2"]=$partner["credit2"];
            $logs["rechargetype"]="credit2";
            $logs["remark"]="订单[".$orderId."]合伙人佣金入账";
            $logs["created_at"]=time();
            $logs["updated_at"]=time();
            $logs["deleted_at"]=0;
            $i=pdo_insert("nets_hjk_member_logs",$logs);
            //发送模板消息
            $first=$remark;
            $keyword1="佣金￥".$partnercommission;
            $keyword2="合伙人订单补贴提前审核";
            $time="时间：".date('Y-m-d H:i:s',time()) ;
            $data= array(
                'first'=>array('value'=>$first,'color'=>"#173177"),
                'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
                'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
                'remark'=>array('value'=>$time,'color'=>"#173177"),
            );
            if($_W['acctype']=="公众号"){
                $openid=$hjkpartner['openid'];
                sendTemplateMsg($openid,$data,$url="");
            }else if($_W['acctype']=="小程序"){

            }
        }
        //一级代理佣金提前审核
        if(empty($commission) && !empty($memberid) && !empty($membercommission)){
            //代理佣金
            $member=pdo_fetch("select * from ".tablename("mc_members")." where uid=:uid",array(":uid"=>$memberid));
            $hjkmember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where uniacid=:uniacid and memberid=:uid",array(":uniacid"=>$_W["uniacid"],":uid"=>$memberid));
            $m["credit2"]=$member["credit2"]+floatval($membercommission);
            //pdo_update("mc_members",$m,array("uid"=>$member["uid"]));
            $b= mc_credit_update($member["uid"], "credit2", floatval($membercommission), $log = array(0,$remark,"nets_haojk",0,0,1));
            $logs["uniacid"]=$_W["uniacid"];
			$logs["memberid"]=$member["uid"];
			$logs["type"]=2;//1积分2佣金3补贴
			$logs["logno"]=$orderId;
			$logs["title"]="";
			$logs["status"]=1;//0 生成 1 成功 2 失败
			$logs["money"]=$membercommission;
			$logs["credit1"]=$member["credit1"];
			$logs["credit2"]=$member["credit2"];
			$logs["rechargetype"]="credit2";
			$logs["remark"]=$remark;
			$logs["created_at"]=time();
			$logs["updated_at"]=time();
			$logs["deleted_at"]=0;
            $i=pdo_insert("nets_hjk_member_logs",$logs);
            //发送模板消息
            $first=$remark;
            $keyword1="佣金￥".$membercommission;
            $keyword2="订单补贴提前审核";
            $time="时间：".date('Y-m-d H:i:s',time()) ;
            $data= array(
                'first'=>array('value'=>$first,'color'=>"#173177"),
                'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
                'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
                'remark'=>array('value'=>$time,'color'=>"#173177"),
            );
            if($_W['acctype']=="公众号"){
                $openid=$hjkmember['openid'];
                sendTemplateMsg($openid,$data,$url="");
            }else if($_W['acctype']=="小程序"){
                
            }
        }
        if(empty($commission) && !empty($memberid1) && !empty($membercommission1)){
            //上级佣金
            $member=pdo_fetch("select * from ".tablename("mc_members")." where uid=:uid",array(":uid"=>$memberid1));
            $hjkmember1=pdo_fetch("select * from ".tablename("nets_hjk_members")." where uniacid=:uniacid and memberid=:uid",array(":uniacid"=>$_W["uniacid"],":uid"=>$memberid1));
            
            $m1["credit2"]=$member["credit2"]+floatval($membercommission1);
            //pdo_update("mc_members",$m1,array("uid"=>$member["uid"]));
            $b= mc_credit_update($member["uid"], "credit2", floatval($membercommission1), $log = array(0,$remark,"nets_haojk",0,0,1));
            $logs["uniacid"]=$_W["uniacid"];
			$logs["memberid"]=$member["uid"];
			$logs["type"]=2;//1积分2佣金3补贴
			$logs["logno"]=$orderId;
			$logs["title"]="";
			$logs["status"]=1;//0 生成 1 成功 2 失败
			$logs["money"]=$membercommission1;
			$logs["credit1"]=$member["credit1"];
			$logs["credit2"]=$member["credit2"];
			$logs["rechargetype"]="credit2";
			$logs["remark"]=$remark;
			$logs["created_at"]=time();
			$logs["updated_at"]=time();
			$logs["deleted_at"]=0;
            $i=pdo_insert("nets_hjk_member_logs",$logs);
            //发送模板消息
            $first=$remark;
            $keyword1="佣金￥".$membercommission;
            $keyword2="下级会员[".$hjkmember['nickname']."]订单补贴提前审核";
            $time="时间：".date('Y-m-d H:i:s',time()) ;
            $data= array(
                'first'=>array('value'=>$first,'color'=>"#173177"),
                'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
                'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
                'remark'=>array('value'=>$time,'color'=>"#173177"),
            );
            if($_W['acctype']=="公众号"){
                $openid=$hjkmember1['openid'];
                sendTemplateMsg($openid,$data,$url="");
            }else if($_W['acctype']=="小程序"){
                
            }
        }
        if(empty($commission) && !empty($memberid2) && !empty($membercommission2)){
            //上上级会员佣金
            $member=pdo_fetch("select * from ".tablename("mc_members")." where uid=:uid",array(":uid"=>$memberid2));
            $hjkmember2=pdo_fetch("select * from ".tablename("nets_hjk_members")." where uniacid=:uniacid and memberid=:uid",array(":uniacid"=>$_W["uniacid"],":uid"=>$memberid2));
            $m2["credit2"]=$member["credit2"]+floatval($membercommission2);
            //pdo_update("mc_members",$m2,array("uid"=>$member["uid"]));
            $b= mc_credit_update($member["uid"], "credit2", floatval($membercommission2), $log = array(0,$remark,"nets_haojk",0,0,1));
            $logs["uniacid"]=$_W["uniacid"];
			$logs["memberid"]=$member["uid"];
			$logs["type"]=2;//1积分2佣金3补贴
			$logs["logno"]=$orderId;
			$logs["title"]="";
			$logs["status"]=1;//0 生成 1 成功 2 失败
			$logs["money"]=$membercommission2;
			$logs["credit1"]=$member["credit1"];
			$logs["credit2"]=$member["credit2"];
			$logs["rechargetype"]="credit2";
			$logs["remark"]=$remark;
			$logs["created_at"]=time();
			$logs["updated_at"]=time();
			$logs["deleted_at"]=0;
            $i=pdo_insert("nets_hjk_member_logs",$logs);
            //发送模板消息
            $first=$remark;
            $keyword1="佣金￥".$membercommission;
            $keyword2="下下级会员[".$hjkmember['nickname']."]订单补贴提前审核";
            $time="时间：".date('Y-m-d H:i:s',time()) ;
            $data= array(
                'first'=>array('value'=>$first,'color'=>"#173177"),
                'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
                'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
                'remark'=>array('value'=>$time,'color'=>"#173177"),
            );
            if($_W['acctype']=="公众号"){
                $openid=$hjkmember2['openid'];
                sendTemplateMsg($openid,$data,$url="");
            }else if($_W['acctype']=="小程序"){
                
            }
        }
        //验证是否是用户提单
        $memberorder=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_orders")." AS o  where o.orderno=:orderno",array(":orderno"=>$orderId));
        
        if(!empty($commission) && !empty($memberorder)){
            $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
            //平台补贴
            $subsidy_ratio=$global["subsidy"];
            $subsidy=floatval($commission)*floatval($subsidy_ratio)/100;
            $remark="订单[".$orderId."]补贴入账";
            $member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid and em.memberid=:memberid",array(":uniacid"=>$_W["uniacid"],":memberid"=>$memberorder["memberid"]));
            $hjkmember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where uniacid=:uniacid and memberid=:uid",array(":uniacid"=>$_W["uniacid"],":uid"=>$memberorder["memberid"]));
            
            $b= mc_credit_update($member["memberid"], "credit2", $subsidy, $log = array(0,$remark,"nets_haojk",0,0,1));
			if($b){
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
                //发送模板消息
                $first=$remark;
                $keyword1="佣金￥".$subsidy;
                $keyword2="订单补贴提前审核";
                $time="时间：".date('Y-m-d H:i:s',time()) ;
                $data= array(
                    'first'=>array('value'=>$first,'color'=>"#173177"),
                    'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
                    'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
                    'remark'=>array('value'=>$time,'color'=>"#173177"),
                );
                if($_W['acctype']=="公众号"){
                    $openid=$hjkmember['openid'];
                    sendTemplateMsg($openid,$data,$url="");
                }else if($_W['acctype']=="小程序"){
                    
                }
			}
        }
        show_json(1,"操作成功");
    }

    //提前结算砍价订单佣金
    public function countordercommissionbycutting(){
        global $_GPC, $_W;
        $orderId=$_GPC["orderId"];
        $money=$_GPC["cosPrice"];
        //验证是否是用户提单
        $orderlog=pdo_fetch("select * from ".tablename("nets_hjk_member_logs")." where logno=:orderno and remark like '%购买免单商品返还%'",array(":orderno"=>$orderId));
		if(!empty($orderlog)){
            show_json(1,"该订单已返还过!");
			return;//这个订单已经存在免单返还了，继续下一个
		}
        $memberorder=pdo_fetch("SELECT * FROM ".tablename("nets_hjk_freeorders")." AS o  where o.uniacid=:uniacid and o.orderno=:orderno",array(":uniacid"=>$_W["uniacid"],":orderno"=>$orderId));
        
        if(!empty($money) && !empty($memberorder)){
            
            $remark="[订单号".$orderId."]购买免单商品返还";
            $member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid and em.memberid=:memberid",array(":uniacid"=>$_W["uniacid"],":memberid"=>$memberorder["memberid"]));
            $hjkmember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where uniacid=:uniacid and memberid=:uid",array(":uniacid"=>$_W["uniacid"],":uid"=>$memberorder["memberid"]));
            $b= mc_credit_update($member["memberid"], "credit2", $money, $log = array(0,$remark,"nets_haojk",0,0,1));
			if($b){
				$logs["uniacid"]=$_W["uniacid"];
				$logs["memberid"]=$member["memberid"];
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
                //发送模板消息
                $first=$remark;
                $keyword1="佣金".$money;
                $keyword2="砍价订单补贴提前审核";
                $time="时间：".date('Y-m-d H:i:s',time()) ;
                $data= array(
                    'first'=>array('value'=>$first,'color'=>"#173177"),
                    'keyword1'=>array('value'=>$keyword1,'color'=>"#173177"),
                    'keyword2'=>array('value'=>$keyword2,'color'=>"#173177"),
                    'remark'=>array('value'=>$time,'color'=>"#173177"),
                );
                if($_W['acctype']=="公众号"){
                    $openid=$hjkmember['openid'];
                    sendTemplateMsg($openid,$data,$url="");
                }else if($_W['acctype']=="小程序"){
                    
                }
			}
        }
        show_json(1,"操作成功");
    }


	
}
?>