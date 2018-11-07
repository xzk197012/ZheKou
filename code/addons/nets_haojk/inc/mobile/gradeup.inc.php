<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';


global $_W,$_GPC;


getUserInfo(1);
get_global();
$credit1_to_credit2 = intval($_W["hjk_global"]['credit1_to_credit2']);
if(intval($credit1_to_credit2)<=0)
    $credit1_to_credit2 = 100;
//等级升级 查询会员等级信息 取消uniacid，原openid变更为uid gradeup.inc.php 14
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2,l.name as levelname,l.recharge_get FROM ".tablename("nets_hjk_members")." AS em left join "
    .tablename("mc_members")." AS m on em.memberid=m.uid  left join "
    .tablename("nets_hjk_memberlevel")." AS l on em.level=l.name and l.type=1  and l.uniacid=:uniacid  where em.memberid=:memberid"
    ,array(":uniacid"=>$_W["uniacid"],":memberid"=>$_W["fans"]["uid"]));
if(empty($member['levelname'])){
	$member['levelname']=0;
}
$credit1 = intval($_W['user_info']['credit1']);//用户当前积分余额
$recharge_get = 0;//升级已花费积分
if(!empty($member['levelname']))
    $recharge_get=$member['recharge_get'];
$levelname = $member['levelname'];//用户当前等级
$maxlevelrecharge_get = 0;

$levelmsg = '当前积分：'.$credit1;

//$memberlevel=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")."  where type =1 and uniacid=:uniacid  order by name",array(":uniacid"=>$_W["uniacid"]));
//echo $levelname;
$memberlevel=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")."  where type =1 and uniacid=:uniacid and  isuse=1  and name>:name order by name",array(":uniacid"=>$_W["uniacid"],":name"=>$levelname));
//var_dump($memberlevel);
foreach ($memberlevel as $level){
    //
    if(intval($level['name'])==intval($member['levelname'])+1){
        $nextlevelname = '白银盟主';
        if($level['name']=='1')
            $nextlevelname = '白银盟主';
        if($level['name']=='2')
            $nextlevelname = '黄金盟主';
        if($level['name']=='3')
            $nextlevelname = '铂金盟主';
        if($level['name']=='4')
            $nextlevelname = '钻石盟主';
        //升级所需积分
        $releader_credit1=intval($level['recharge_get'])-intval($recharge_get);
        //积分满足升级条件
        if(intval($credit1)>=$releader_credit1&&$releader_credit1>0){
            $i=pdo_update("nets_hjk_members",array("level"=>$level['name']),array("memberid"=>$member["memberid"]));
            if($i==1){
                $logno	=	date('YmdHis').rand(111,999);
                $releader_credit1=intval($level['recharge_get'])-intval($recharge_get);
                $remark="升级".$nextlevelname;
                $logs["uniacid"]=$_W["uniacid"];
                $logs["memberid"]=$member["memberid"];
                $logs["type"]=1;//1积分2佣金3补贴
                $logs["logno"]=$logno;
                $logs["title"]="";
                $logs["status"]=1;//0 生成 1 成功 2 失败
                $logs["money"]=-$releader_credit1;
                $logs["credit1"]=$member["credit1"];
                $logs["credit2"]=$member["credit2"];
                $logs["rechargetype"]="credit1";
                $logs["cashtype"]="";
                $logs["remark"]=$remark;
                $logs["created_at"]=time();
                $logs["deleted_at"]=0;
                $i=pdo_insert("nets_hjk_member_logs",$logs);
                $b= mc_credit_update($member["memberid"], "credit1", -$releader_credit1, $log = array(0,$remark,"nets_haojk",0,0,1));
                message('升级成功',$this->createMobileUrl('grade'),'success');
            }
            else
            {
                message('升级失败',$this->createMobileUrl('grade'),'error');
            }
        }
    }
}

include $this->template('gradeup');

?>