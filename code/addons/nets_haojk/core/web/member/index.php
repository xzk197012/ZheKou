<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
class Index_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='member.index'||$_W['action']=='member')
            $this->index();
        elseif ($_W['action']=='member.position_delete')
            $this->member_delete();
        elseif ($_W['action']=='member.member_edit')
            $this->member_edit();
        elseif ($_W['action']=='member.member_editpost')
            $this->member_editpost();
        elseif ($_W['action']=='member.member_subordinate')
            $this->member_subordinate();
	}
	//列表
	public function index()
	{
        global $_GPC, $_W;

        $uniacid=$_W['uniacid'];


        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }

        $where='';
        // 筛选
        if(!empty($endtime)){
            $where .= " AND em.created_at >= ".$starttime." AND em.created_at <=".$endtime;
        }
        if(!empty($_GPC['keyword'])){
            $where .= " AND (em.nickname like '%".$_GPC['keyword']."%' or em.openid like '%".$_GPC['keyword']."%' or em.memberid like '%".$_GPC['keyword']."%') ";
        }
        if($_GPC['type']==1){
            $where .= " AND (em.type = 0 OR ISNULL(em.type))";
        }
        if($_GPC['type']==2){
            $where .= " AND em.type = 1";
        }
        if($_GPC['type']==3){
            $where .= " AND em.type = 2";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        //取消从模块表连接查询会员，改为从fans表连接查询会员（统一开放平台的只需fans区分uniacid） zxq.2018.05.21 core\web\member\index.php in 58
        //$list = pdo_fetchall("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid ".$where." ORDER BY id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        $sql="SELECT mf.openid  AS  'mfopenid', em.*,m.uniacid as 'memberuniacid',m.credit1,m.credit2 FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.uniacid=em.uniacid
        where mf.uniacid=:uniacid ".$where." ORDER BY em.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize;
        
        $list=pdo_fetchall($sql,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.openid=em.openid
        where mf.uniacid=:uniacid ".$where."",array(':uniacid'=>$uniacid));
        $pager = pagination($totalcount, $pindex, $psize);//var_dump($pager);die;
        $count = 0;
        include $this->template('haojingke/member/index');
	}



    //会员删除-删除
    public function member_delete()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        //删除会员 取消验证fans 直接删除模块内会员 zxq.2018.05.21  core\web\member\index.php in 79
        $j = pdo_delete('nets_hjk_members',array('id'=>$_GPC['id']));
        if ($j>0) {
            show_json(1,"删除成功！");
        }else{
            show_json(1,"删除失败！");
        }
    }

    //下属会员
    public function member_subordinate(){
          global $_GPC, $_W;

        $uniacid=$_W['uniacid'];

       
        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }

        $where='';
        // 筛选
        if(!empty($endtime)){
            $where .= " AND em.created_at >= ".$starttime." AND em.created_at <=".$endtime;
        }
        if(!empty($_GPC['keyword'])){
            $where .= " AND (em.nickname like '%".$_GPC['keyword']."%' or em.openid like '%".$_GPC['keyword']."%' or em.memberid like '%".$_GPC['keyword']."%') ";
        }
        if($_GPC['type']==1){
            $where .= " AND (em.type = 0 OR ISNULL(em.type))";
        }
        if($_GPC['type']==2){
            $where .= " AND em.type = 1";
        }
        
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;

        if ($_GPC['level']==1){
            //查询会员的一级会员 取消从模块表连接查询会员，改为从fans表连接查询会员（统一开放平台的只需fans区分uniacid） zxq.2018.05.21 core\web\member\index.php in 124
        // $list = pdo_fetchall("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em   left join ".tablename("mc_members")." AS m on em.from_uid=m.uid   where em.uniacid=:uniacid and em.from_uid = ".$_GPC['memberid'].$where." ORDER BY id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        // $totalcount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_members")." AS em  WHERE uniacid= :uniacid and em.from_uid = ".$_GPC['memberid'].$where,array(':uniacid'=>$uniacid));
        $sql="SELECT em.*,m.credit1,m.credit2 FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid AND mf.uniacid = em.uniacid and mf.openid=em.openid
        where mf.uniacid=:uniacid  and em.from_uid = ".$_GPC['memberid'].$where." ORDER BY em.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize;

        $list=pdo_fetchall($sql,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid AND mf.uniacid = em.uniacid and mf.openid=em.openid
        where mf.uniacid=:uniacid and em.from_uid = ".$_GPC['memberid'].$where."",array(':uniacid'=>$uniacid));
        }
        elseif ($_GPC['level']==2){
        //查询会员的二级会员 取消从模块表连接查询会员，改为从fans表连接查询会员（统一开放平台的只需fans区分uniacid） zxq.2018.05.21 core\web\member\index.php in 135

        // $list = pdo_fetchall("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em   left join ".tablename("mc_members")." AS m on em.from_uid2=m.uid   where em.uniacid=:uniacid and em.from_uid2 = ".$_GPC['memberid'].$where." ORDER BY id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        // $totalcount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_members")." AS em  WHERE uniacid= :uniacid and em.from_uid2 = ".$_GPC['memberid'].$where,array(':uniacid'=>$uniacid));
            $sql="SELECT em.*,m.credit1,m.credit2 FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.openid=em.openid
            where mf.uniacid=:uniacid  and em.from_uid2 = ".$_GPC['memberid'].$where." ORDER BY em.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize;
            
            $list=pdo_fetchall($sql,array(':uniacid'=>$uniacid));
            $totalcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.openid=em.openid
            where mf.uniacid=:uniacid and em.from_uid2 = ".$_GPC['memberid'].$where."",array(':uniacid'=>$uniacid));
        }
        elseif ($_GPC['level']==0){
            //查询会员的二级会员 取消从模块表连接查询会员，改为从fans表连接查询会员（统一开放平台的只需fans区分uniacid） zxq.2018.05.21 core\web\member\index.php in 135

            // $list = pdo_fetchall("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em   left join ".tablename("mc_members")." AS m on em.from_uid2=m.uid   where em.uniacid=:uniacid and em.from_uid2 = ".$_GPC['memberid'].$where." ORDER BY id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
            // $totalcount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_members")." AS em  WHERE uniacid= :uniacid and em.from_uid2 = ".$_GPC['memberid'].$where,array(':uniacid'=>$uniacid));
            $sql="SELECT em.*,m.credit1,m.credit2 FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.openid=em.openid
            where mf.uniacid=:uniacid  and  em.from_partner_uid = ".$_GPC['memberid'].$where." ORDER BY em.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize;

            $list=pdo_fetchall($sql,array(':uniacid'=>$uniacid));
            $totalcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.openid=em.openid
            where mf.uniacid=:uniacid and em.from_uid2 = ".$_GPC['memberid'].$where."",array(':uniacid'=>$uniacid));
        }
        
        
        $pager = pagination($totalcount, $pindex, $psize);//var_dump($pager);die;
        $count = 0;
        include $this->template('haojingke/member/member_subordinate');    
    }

   

    //会员-修改
    public function member_edit()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $edit_level = pdo_fetch("SELECT * FROM ". tablename('nets_hjk_members'). " where id = ".$_GPC['id']);
        include $this->template('haojingke/member/member_edit');
    }

    //会员-修改
    public function member_editpost()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $data['level'] = $_GPC['level'];
        $data['type'] = $_GPC['type'];
        $memberid = 0;
        $m  = pdo_fetch("SELECT * FROM ".tablename('nets_hjk_members'). " WHERE id=:id",array(':id'=>$_GPC['id']));
        $memberid = $m['memberid'];
        if (!empty($_GPC['invite'])) {
            //手动修改邀请人 从fans表里查询目标会员uid zxq.2018.05.21 core\web\member\index.php in 174
            $r  = pdo_fetch("SELECT * FROM ".tablename('nets_hjk_members'). " WHERE uniacid=:uniacid and (openid = :memberid or memberid=:memberid)",array(':uniacid'=>$uniacid,':memberid'=>$_GPC['invite']));
            if (empty($r)) {
                show_json(1,"邀请人不存在或者会员ID/openid错误！");
            }else{
                if ($m['memberid'] == $r['memberid']) {
                    show_json(1,"邀请人不能选择自己!");
                }else{
                    $data['from_uid'] = $r['memberid'];
                    $data['from_uid2'] = $r['from_uid'];
                    $data['from_partner_uid'] = $r['from_partner_uid'];
                }
            }
        }else{
            $data['from_uid'] =0;
        }
        if (!empty($_GPC['partner_uid'])) {
            //手动修改合伙人从fans表里查询目标会员
            $p  = pdo_fetch("SELECT * FROM ".tablename('nets_hjk_members'). " WHERE type=2 and uniacid=:uniacid and (openid = :memberid or memberid=:memberid)",array(':uniacid'=>$uniacid,':memberid'=>$_GPC['partner_uid']));
            if (empty($p)) {
                show_json(1,"合伙人不存在或者会员ID/openid错误！");
            }else{
                //$m  = pdo_fetch("SELECT * FROM ".tablename('nets_hjk_members'). " WHERE id=:id",array(':id'=>$_GPC['id']));
                if ($m['memberid'] == $p['memberid']) {
                    show_json(1,"合伙人不能选择自己!");
                }else{
                    $data['from_partner_uid'] = $p['memberid'];
                }
            }
        }else{
            $data['from_partner_uid'] =0;
        }

        $data['updated_at'] = time();
        $data['created_at'] = time();
        if($memberid>0)
        {
            $r = pdo_update('nets_hjk_members',$data,array('memberid'=>$memberid));
        }
        else
            $r = pdo_update('nets_hjk_members',$data,array('id'=>$_GPC['id']));
        if($r)
        {
            if($_GPC['type']==1 || $_GPC['type']==2)
            {
                if($_W["wxpddauth"]==1 && $_W['global']['pddmodule_status']==1 && $m['pdd_bitno']=="")
                {
                    getpdd_Pidcreate($memberid);
                }
                if(($_W["wxgzhauth"]==1 || $_W["wxappauth"]==1)&& $_W['global']['jdmodule_status']==1 && $m['jd_bitno']=="")
                {
                    $otherwxapp_uniacid=0;
                    if($_W['acctype']=="小程序")
                        $otherwxapp_uniacid=$_W['global']['wxapp_uniacid'];
                    if($_W['acctype']=="公众号")
                        $otherwxapp_uniacid=pdo_fetch("select uniacid from ".tablename('nets_hjk_global')." where wxapp_uniacid=:wxapp_uniacid",array(':wxapp_uniacid'=>$_W['uniacid']));
                    $jdbit  = pdo_fetch("select bitno from ".tablename('nets_hjk_probit')." where uniacid=:uniacid and not exists(select 1 from ".tablename('nets_hjk_members')." where jd_bitno=bitno and (uniacid=:uniacid or uniacid=:wxapp_uniacid))",array(':uniacid'=>$_W['uniacid'],':wxapp_uniacid'=>$otherwxapp_uniacid));
                    $da['jd_bitno']=$jdbit['bitno'];
                    $jd = pdo_update('nets_hjk_members',$da,array('memberid'=>$memberid));
                    $probit['state']=1;
                    $probit['memberid']=$memberid;
                    pdo_update('nets_hjk_probit',$probit,array('bitno'=>$da['jd_bitno'],'uniacid'=>$_W['uniacid']));
                    if(!$jd)
                        show_json(1,"京东推广位分配失败，请手工分配！");
                }
            }
            show_json(1,"操作成功");
        }
        else
            show_json(1,"操作失败");
    }



	
}
?>