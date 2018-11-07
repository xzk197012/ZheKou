<?php
/**
 * 模块微站定义
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.mgj.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.ordersv2.func.php';
require_once IA_ROOT . '/addons/nets_haojk/defines.php';
class Nets_haojkModuleSite extends WeModuleSite {

	function __construct()
    {
        global $_W,$_GPC;
		
        
        
        /*
		* 是否测试环境
        */
        $_W['acctype'] = "公众号";
        
        $iswxapp = pdo_fetch("SELECT * FROM " .tablename('account_wxapp'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        if($iswxapp)
            $_W['acctype'] = "小程序";

        $_W['global']['applygradename']="盟主";
        
        $res=cloud_authcheckweb();
		$this->sync_category();
		
    }

    public function doWebWeb()
    {
        global $_W,$_GPC;
        require_once NETS_HAOJIK_INC . 'functions.php';
        $hjk_global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		
        $_W['global']=$hjk_global;
        m('route')->run();
    }
    public function doMobileHelpmsg(){
        global $_W,$_GPC;
        $id=$_GPC['k'];
        $data = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_keyword'). " WHERE uniacid =:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['k']));
        $data["content"]=html_entity_decode($data["content"]);
        $_W['page']['title']=$data["title"];
        include $this->template('../wxapp/helpmsg');
    }
	
	//同步商品分类数据
	public function sync_category(){
        global $_W,$_GPC;
		//var_dump($clist);
		$menu=pdo_fetch("select * from ".tablename("nets_hjk_menu")." where type=2 and uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
		if(empty($menu)){
			$menu["created_at"]=0;
		}
		$limit=time()-$menu["created_at"];
		//分类一天更新一次
		if($limit<60*60*24){
			return;
		}
        $clist=getcnamelist("cname",0,0,0,0,0,0,0,0,0);
        if(empty($clist)){
            return;
        }
		pdo_delete("nets_hjk_menu",array("type"=>2,'uniacid'=>$_W['uniacid']));
		foreach($clist AS $c){
			$cname=$c["cname"];
			$menu=pdo_fetch("select * from ".tablename("nets_hjk_menu")." where type=2 and name=:name and uniacid=:uniacid",array(":name"=>$cname,":uniacid"=>$_W['uniacid']));
			if(!empty($menu)){
				continue;
			}
            $m["name"]=$cname;
            $m["uniacid"]=$_W['uniacid'];
			$m["url"]="../choiceness/index?name=".$cname;
			$m["type"]=2;
			$m["created_at"]=time();
			$m["updated_at"]=time();
			$m["deleted_at"]=0;
			pdo_insert("nets_hjk_menu",$m);
        }
        
	}


	public function payResult($params) {
		global $_W,$_GPC;
		haojk_log("充值返回了site");
		if ($params['result'] == 'success' && $params['from'] == 'notify')
		{
			//此处会处理一些支付成功的业务代码
            $orderno = $params['tid'];
            haojk_log("申请盟主付费单号：".$params['tid']);
            $logInfo = pdo_get('nets_hjk_member_logs',array('logno'=>$orderno,'status'=>'0'));
            $member = pdo_get('nets_hjk_members',array('memberid'=>$logInfo['memberid']));
            haojk_log("申请盟主会员ID：".$logInfo['memberid']."|".$logInfo["remark"]);
			if(!empty($logInfo)&&!empty($logInfo["title"]) && $logInfo["remark"]=="升级盟主付费"){
                $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$logInfo["uniacid"]));
                if(!empty($logInfo)){
                    $credit1_to_credit2 = intval($global['credit1_to_credit2']);
                    if(intval($credit1_to_credit2)<=0)
                        $credit1_to_credit2 = 100;
                    //计算充值所获得的积分
                    $credit1 =  $credit1_to_credit2*floatval($logInfo['money']);
                    //积分充值
                    $logno	=	date('YmdHis').rand(111,555);
                    $remark="积分充值_".$logInfo["remark"];
                    $logs["uniacid"]=$logInfo["uniacid"];
                    $logs["memberid"]=$logInfo["memberid"];
                    $logs["type"]=1;//1积分2佣金3补贴
                    $logs["logno"]=$logno;
                    $logs["title"]="";
                    $logs["status"]=1;//0 生成 1 成功 2 失败
                    $logs["money"]=$credit1;
                    $logs["credit1"]=$logInfo["credit1"];
                    $logs["credit2"]=$logInfo["credit2"];
                    $logs["rechargetype"]="credit1";
                    $logs["cashtype"]="";
                    $logs["remark"]=$remark;
                    $logs["created_at"]=time();
                    $logs["deleted_at"]=0;
                    $i=pdo_insert("nets_hjk_member_logs",$logs);
                    $b= mc_credit_update($logInfo["memberid"], "credit1", $credit1, $log = array(0,$remark,"nets_haojk",0,0,1));

                    $member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join "
                        .tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid "
                        ,array(":memberid"=>$logInfo["memberid"]));
                    $credit1 = -$member['credit1'];//扣除用户当前积分余额
                    //扣除升级所需积分
                    $logno	=	date('YmdHis').rand(556,999);
                    $remark="扣除升级所需积分_".$logInfo["remark"];
                    $logs["uniacid"]=$logInfo["uniacid"];
                    $logs["memberid"]=$logInfo["memberid"];
                    $logs["type"]=1;//1积分2佣金3补贴
                    $logs["logno"]=$logno;
                    $logs["title"]="";
                    $logs["status"]=1;//0 生成 1 成功 2 失败
                    $logs["money"]=$credit1;
                    $logs["credit1"]=$logInfo["credit1"];
                    $logs["credit2"]=$logInfo["credit2"];
                    $logs["rechargetype"]="credit1";
                    $logs["cashtype"]="";
                    $logs["remark"]=$remark;
                    $logs["created_at"]=time();
                    $logs["deleted_at"]=0;
                    $i=pdo_insert("nets_hjk_member_logs",$logs);
                    $b= mc_credit_update($logInfo["memberid"], "credit1", $credit1, $log = array(0,$remark,"nets_haojk",0,0,1));
                    
                    $data		=	array();
                    $data['status']		=	1;
                    $data['updated_at']	=	time();
                    $res1		=	pdo_update('nets_hjk_member_logs',$data,array('logno'=>$orderno));
                    //更新会员的盟主信息
                    $i=pdo_update("nets_hjk_members",array("level"=>$logInfo["title"]),array("memberid"=>$logInfo["memberid"]));
                    
                    if($i)
                    {
                        message('升级成功',$this->createMobileUrl('gradefy'),'success');
                    }
                    else
                    {
                        message('升级失败',$this->createMobileUrl('gradefy'),'error');
                    }
                }
			}else if(!empty($logInfo)&&!empty($logInfo["title"]) && $logInfo["remark"]=="账户充值"){
                $b= mc_credit_update($logInfo["memberid"], "credit2", $logInfo["money"], $log = array(0,$logInfo["remark"],"nets_haojk",0,0,1));
                $data['status']		=	1;
                $data['updated_at']	=	time();
                pdo_update('nets_hjk_member_logs',$data,array('logno'=>$orderno));
                return;
            }else if(!empty($logInfo)&&!empty($logInfo["title"]) && $logInfo["remark"]=="申请合伙人付费"){
                $data['type']=2;
                $data['level']=0;
                pdo_update('nets_hjk_members',$data,array('memberid'=>$logInfo["memberid"]));
                //修改下级会员合伙人id
                pdo_update("nets_hjk_members",array("from_partner_uid"=>$logInfo["memberid"]),array("from_uid"=>$logInfo["memberid"],"from_uid2"=>$logInfo["memberid"]),'OR');

                return;
            }
            else{
                //申请盟主付费后计算佣金,openid 变更为uid了，zxq.2018.05.16 site.php in 169
                applyleader_credit2_commission($logInfo['money'],$member['memberid']);
            }
			$data		=	array();
			$data['status']		=	1;
			$data['updated_at']	=	time();
			$res1		=	pdo_update('nets_hjk_member_logs',$data,array('logno'=>$orderno));
            haojk_log("申请盟主会员ID：".$logInfo['memberid']);
			$res2		=	applyleader($logInfo['memberid']);
			applyloaderMsg($member['openid'],0,'','',$res2['msg']);
			sendOwnerApplayMsg($member['nickname'],0,'','',$res2['msg']);
			if($res2['code']==200)
			{
				message($res2['msg'],$this->createMobileUrl('my'),'success');
			}
			else
			{
				message($res2['msg'],$this->createMobileUrl('my'),'success');
				//message($res2['msg'],$this->createMobileUrl('my'),'error');
			}
		}
		else
		{
			message('申请成功',$this->createMobileUrl('my'),'success');
		}
	}


}