<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';


global $_W,$_GPC;



getUserInfo(1);
get_global();

$global		=	$_W['global'];
//我的等级 查询当前会员等级信息 取消uniacid，原openid变更为uid zxq.2018.05.17 grade.inc.php in 14
$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2,l.name as levelname,l.recharge_get FROM ".tablename("nets_hjk_members")." AS em left join "
    .tablename("mc_members")." AS m on em.memberid=m.uid  left join "
    .tablename("nets_hjk_memberlevel")." AS l on em.level=l.name and l.type=1  and l.uniacid=:uniacid  where em.memberid=:memberid"
    ,array(":uniacid"=>$_W["uniacid"],":memberid"=>$_W["fans"]["uid"]));

$credit1 = intval($member['credit1']);//用户当前积分余额
$recharge_get = 0;//升级已花费积分
if(!empty($member['levelname']))
    $recharge_get=$member['recharge_get'];
$levelname = $member['levelname'];//用户当前等级
$maxlevelrecharge_get = 0;
$levelmsg = '';

$memberlevel=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")."  where type =1 and isuse=1 and uniacid=:uniacid order by name",array(":uniacid"=>$_W["uniacid"]));
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
        
        $memberlevel[$i]["identityname"]="盟主";
    }
}
foreach ($memberlevel as $level){
    

    if($level['name']=='4')
        $maxlevelrecharge_get = intval($level['recharge_get']);
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

        $nextlevelname=$level['gradename'].$level['identityname'];
        $nextcredit1 = intval($level['recharge_get'])-intval($recharge_get)-$credit1;
        if($nextcredit1>0)
            $levelmsg = '距离'.$nextlevelname.'还需要'.$nextcredit1.'分';
        else
            $levelmsg = '您已经满足升级'.$nextlevelname.'。';
    }
}
if($member['levelname']=='4'){
    $levelmsg = '您已经是钻石盟主，享有最高权限！';
}
$pct = 10;

if($maxlevelrecharge_get>0)
    $pct = (intval($recharge_get)+intval($credit1))*100/$maxlevelrecharge_get;



include $this->template('grade');

?>