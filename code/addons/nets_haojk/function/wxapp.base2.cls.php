<?php
/**
 * 好京客模块小程序接口定义，合伙人相关接口类
 *
 * @author zhang
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/wxapp.base.cls.php';
class WxappBase2 extends WxappBase {
    /**
	 * 申请合伙人
     * 参数1 name  姓名
     * 参数1 mobile  手机号
     * 参数1 QQ  QQ号
     * 参数1 weixin  微信号
     * 参数1 applyremark  申请理由
     * 参数  memberid 会员id
	 */
	public function doPageApplayPartner(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        //合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 25
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        $data["uniacid"]=$_W['uniacid'];
        $data['memberid']=$member['memberid'];
        $data['name']=$_GPC['name'];
        $data['mobile']=$_GPC['mobile'];
        $data['QQ']=$_GPC['QQ'];
        $data['weixin']=$_GPC['weixin'];
        $data['applyremark']=$_GPC['applyremark'];
        
        $data['type']=0;
        $data['state']=0;
        $data['remark']='申请合伙人';
        $data['created_at']=time();
        $recode=pdo_fetch("select * from ".tablename("nets_hjk_applypartner")." where memberid=:memberid and uniacid=:uniacid",array(":memberid"=>$member['memberid'],":uniacid"=>$_W['uniacid']));
        if(!empty($recode) && $recode['state']==0){
            
            $message="您已经申请过，请等待审核";
            return $this->result($errno, $message, $recode);
        }
        if(!empty($recode) && $recode['state']==-1){
            $res=pdo_update("nets_hjk_applypartner",$data,array("id"=>$recode["id"]));
            $message="重新申请成功";
            return $this->result($errno, $message, $recode);
        }
        if(!empty($recode) && $recode['state']==1){
            $message="您已经是合伙人了";
            return $this->result($errno, $message, $recode);
        }
        $res=pdo_insert("nets_hjk_applypartner",$data);
        if($res>0){
            pdo_update("nets_hjk_members",array("jduniacid"=>$_GPC['jduniacid']),array("id"=>$member["id"]));
        }
		return $this->result($errno, $message, $res);
    }
     /**
	 * 查询我的申请合伙人状态
     * 查询成功返还申请状态信息
     * 为空没有申请记录可以申请
     * * 参数  memberid 会员id
	 */
	public function doPageGetMyApplayPartner(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        //合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 71
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        $recode=pdo_fetch("select * from ".tablename("nets_hjk_applypartner")." where memberid=:memberid and uniacid=:uniacid",array(":memberid"=>$member['memberid'],":uniacid"=>$_W['uniacid']));
        if(!empty($recode)){
            $recode["jduniacid"]=$member["jduniacid"];
            $message="您已经申请过，请等待审核";
            return $this->result($errno, $message, $recode);
        }
        
		return $this->result($errno, $message, "");
    }

    /**
	 * 合伙人同步推广位
     * 参数  memberid 会员id
	 */
	public function doPageSyncProbit(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        //合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 92
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        if($member['type']!=2){
            //非合伙人
            $message="你不是合伙人不能执行此操作";
            return $this->result($errno, $message, -1);
        }
        if(empty($member['jduniacid'])){
            //没有设置京东联盟id
            $message="请先设置京东联盟ID";
            return $this->result($errno, $message, -1);
        }

        $res=sync_pid($member['jduniacid']);
        
		return $this->result($errno, $message, $res);
    }
    
    /**
	 * 合伙人推广位列表
     * 参数1：pstate 0未使用，1已使用 默认不传参 查询全部
     * 参数  memberid 会员id
	 */
	public function doPageGetProbitList(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        //合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 120
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        if($member['type']!=2){
            //非合伙人
            $message="你不是合伙人不能执行此操作";
            return $this->result($errno, $message, -1);
        }
        if(empty($member['jduniacid'])){
            //没有设置京东联盟id
            $message="请先设置京东联盟ID";
            return $this->result($errno, $message, -1);
        }
        $where=" AND p.jduniacid=".$member['jduniacid'];
        if($_GPC['pstate']=='0'){
            $where.=" AND p.state=0";
        }
        if($_GPC['pstate']=='1'){
            $where.=" AND p.state=1";
        }
        if(!empty($_GPC["keyword"])){
            $keyword=$_GPC["keyword"];
            $where.=" and (em.nickname like '%".$keyword."%' OR p.bitno like '%".$keyword."%')";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $uniacid=$_W['uniacid'];
        $list = pdo_fetchall("SELECT p.*,em.nickname,em.from_uid,em.avatar,m.credit1,m.credit2 FROM ".tablename("nets_hjk_probit")." AS p left join ".tablename("nets_hjk_members")." AS em ON p.memberid=em.memberid  AND em.uniacid=:uniacid   left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where p.uniacid=:uniacid".$where." ORDER BY p.state desc,p.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        $usecount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("nets_hjk_probit")." AS p where p.state=1 and p.uniacid=:uniacid "." AND p.jduniacid=".$member['jduniacid']." and p.state=1  ORDER BY p.id DESC",array(':uniacid'=>$uniacid));
        $allcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("nets_hjk_probit")." AS p where p.uniacid=:uniacid "." AND p.jduniacid=".$member['jduniacid']."  ORDER BY p.id DESC",array(':uniacid'=>$uniacid));
        $res=array("list"=>$list,"usecount"=>$usecount,"totalcount"=>$allcount);
        return $this->result($errno, $message, $res);
    }
    
    /**
	 * 合伙人查询所有订单
     * 参数1 memberid 会员id，指定会员的订单查询 不传查询所有合伙人的所有订单
     * 参数  targetmemberid 指定会员的id，没有传0
     * 参数 uri ,可选[orders 已审订单,importorders 预估收入、未审订单]
	 */
	public function doPagePartnerOrders(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        $memberid=$_GPC['memberid'];
        $targetmemberid=$_GPC['targetmemberid'];
        //合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 166
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        if($member['type']!=2){
            //非合伙人
            $message="你不是合伙人不能执行此操作";
            return $this->result($errno, $message, -1);
        }
        if(empty($member['jduniacid'])){
            //没有设置京东联盟id
            $message="请先设置京东联盟ID";
            return $this->result($errno, $message, -1);
        }
        $uri= !empty($_GPC['uri']) ? $_GPC['uri'] : 'importorders';
        $starttime = 0;
        $endtime=0;
        if(empty($_GPC["begintime"])){
            $starttime=date('Y-m-d 00:00:00', strtotime("-90 day"));
            $starttime=strtotime($starttime);
            $endtime=date('Y-m-d 23:59:59', time());
            $endtime=strtotime($endtime);
        }else{
            $starttime=$_GPC["begintime"];
            $starttime=strtotime($starttime." 00:00:00");
            $endtime=$_GPC["endtime"];
            $endtime=strtotime($endtime." 23:59:59");
        }
        $pid="";
        $isbyuser=0;
        $page = max(1, intval($_GPC['page']));
        $pagesize = 1000;
        if(!empty($targetmemberid)){
            //合伙人查询目标会员推广位 取消关联表查询，直接取会员表里jd_bitno zxq.2018.05.19 in 197
            $m=pdo_fetch("SELECT m.jd_bitno FROM ".tablename("nets_hjk_members")." AS m WHERE  m.memberid=:memberid",array(":memberid"=>$targetmemberid,":uniacid"=>$_W['uniacid']));
            if(empty($m) || empty($m["jd_bitno"])){
                $m["jd_bitno"]="";
            }
            $pid= $m["jd_bitno"];
        }
		$res=getorders_byadmin($uri,$page,$pagesize,$starttime,$endtime,$pid,$isbyuser);
		return $this->result($errno, $message, $res);
    }
    
    
    //参数 memberid 当前登录会员
    //合伙人的专属余额日志
	public function doPagePartnerLog(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        $uniacid=$_W['uniacid'];
		//合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 217
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        $uid = $member["memberid"];
		
		$page=$_GPC["page"];
		$pagesize=1000;
        $state=$_GPC["state"];
        
        $where=" and s.memberid=:memberid ";
        if(!empty($_GPC['targetmemberid'])){
            $data[":memberid"]=$_GPC['targetmemberid'];
        }else{
            $data[":memberid"]=$uid;
        }
        if(!empty($_GPC["type"])){
            $where.=" and s.type=:type";
            $data[":type"]=$_GPC["type"];
        }
        if(!empty($_GPC["credittype"])){
            $where.=" and s.rechargetype=:rechargetype";
            $data[":rechargetype"]=$_GPC["credittype"];
        }
        if(!empty($_GPC["keyword"])){
            $where.=" and s.remark like '%".$_GPC["keyword"]."%'";
        }
        $sql="SELECT s.*,m.nickname,m.mobile,FROM_UNIXTIME(s.created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_partner_logs'). " 
        AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid 
        WHERE  s.uniacid = " .$uniacid.$where." ORDER BY s.id DESC limit " . (($page - 1) * $pagesize) . ',' . $pagesize;
        if(!empty($_GPC['targetmemberid'])){
            $sql="SELECT s.*,m.nickname,m.mobile,FROM_UNIXTIME(s.created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_member_logs'). " 
            AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid 
            WHERE  s.uniacid = " .$uniacid.$where." ORDER BY s.id DESC limit " . (($page - 1) * $pagesize) . ',' . $pagesize;
        }
        
        $list = pdo_fetchall($sql,$data);

		return $this->result($errno, $message, $list);
    }
    
    //参数 memberid 当前登录会员
    //合伙人的会员管理
	public function doPageGetMembers(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        $uniacid=$_W['uniacid'];
        //合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 264
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        $uid = $member["memberid"];
		//var_dump($member);
		$page=$_GPC["page"];
		$pagesize=1000;
		$state=$_GPC["state"];
		$where=" and m.from_jduniacid=:from_jduniacid ";
        $data[":from_jduniacid"]=$member['jduniacid'];
        if(!empty($_GPC["type"])){
            $where.=" AND m.type=1";
        }else{
            $where.=" AND m.type=0";
        }
        if(!empty($_GPC["keyword"])){
            $keyword=$_GPC["keyword"];
            $where.=" and (m.nickname like '%".$keyword."%' OR m.jd_bitno like '%".$keyword."%')";
        }
        //查询会员推广位 取消关联表查询，直接查询会员表的jd_uniacid zxq.2018.05.19 wxapp.base2.cls.php in 282
        $sql="SELECT m.memberid,m.nickname,m.avatar,m.type,m1.credit1,m1.credit2,m.jd_bitno AS 'bitno' FROM ".tablename('nets_hjk_members'). " AS m 
        LEFT JOIN ".tablename('mc_members')." AS m1 ON m1.uid=m.memberid 
        WHERE  m.uniacid = " .$uniacid.$where." ORDER BY m.id DESC limit " . (($page - 1) * $pagesize) . ',' . $pagesize;
        //echo $sql;
        $list = pdo_fetchall($sql,$data);

		return $this->result($errno, $message, $list);
    }
    //参数 memberid 当前登录会员
    //合伙人的提现审核记录
	public function doPageCommissionLog(){
		global $_W;
		global $_GPC;
		$errno = 0;
        $message="操作成功";
        $uniacid=$_W['uniacid'];
		//合伙人查询当前会员 取消uniacid查询 zxq.2018.05.19 wxapp.base2.cls.php in 299
        $member=pdo_fetch("SELECT em.*  FROM ".tablename("nets_hjk_members")." AS em where em.memberid=:memberid",array(":memberid"=>$_GPC['memberid']));
        $uid = $member["memberid"];
		
		$page=$_GPC["page"];
		$pagesize=1000;
		$state=$_GPC["state"];
		$where=" and partnermemberid=:memberid and s.type=5 ";
        $data[":memberid"]=$uid;
        if(!empty($_GPC["type"])){
            $where.=" type=:type";
            $data[":type"]=$_GPC["type"];
        }
        //查询合伙人的提现审核记录 取消uniacid查询 zxq.1989.05.19 wxapp.base2.cls.php in 312 
        $list = pdo_fetchall("SELECT s.*,m.nickname,m.mobile,m.avatar,FROM_UNIXTIME(s.created_at, '%Y-%m-%d %H:%i:%S') as 'created_at1' FROM ".tablename('nets_hjk_member_logs'). " 
        AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid 
        WHERE   ".$where." ORDER BY s.id DESC limit " . (($page - 1) * $pagesize) . ',' . $pagesize,$data);

		return $this->result($errno, $message, $list);
    }
}
?>