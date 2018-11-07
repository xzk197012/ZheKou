<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
class pddMember_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='pinduoduo.pddMember')
            $this->index();
        elseif ($_W['action']=='pinduoduo.pddMember.distribution')
        $this->distribution();
        elseif ($_W['action']=='pinduoduo.pddMember.modify')
        $this->modify();
        elseif ($_W['action']=='pinduoduo.pddMember.delete')
        $this->delete();
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
            $where .= " AND (em.nickname like '%".$_GPC['keyword']."%' or em.openid like '%".$_GPC['keyword']."%' or em.memberid like '%".$_GPC['keyword']."%' or em.pdd_bitno like '%".$_GPC['keyword']."%')";
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
        //查询拼多多会员  取消从模块表连接查询会员，改为从fans表连接查询会员（统一开放平台的只需fans区分uniacid） zxq.2018.05.21 core\web\member\index.php in 135
        $sql="SELECT em.*,m.credit1,m.credit2 FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.openid=em.openid
            where mf.uniacid=:uniacid  ".$where." ORDER BY em.pdd_bitno DESC limit " . (($pindex - 1) * $psize) . ',' . $psize;
            
        $list=pdo_fetchall($sql,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("mc_mapping_fans")." AS mf INNER JOIN ".tablename("mc_members")." AS m ON mf.uid=m.uid INNER JOIN ".tablename("nets_hjk_members")." AS em ON mf.uid=em.memberid and mf.openid=em.openid
            where mf.uniacid=:uniacid ".$where."",array(':uniacid'=>$uniacid));
        $pager = pagination($totalcount, $pindex, $psize);//var_dump($pager);die;
        $count = 0;
        include $this->template('haojingke/pinduoduo/pddMember');
	}


    //创建多多进宝推广位，并分配给会员
    public function distribution()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $memberid=$_GPC['id'];
        $list=getpdd_Pidcreate($memberid);
        if(!empty($list)){
            show_json(1,"分配成功！");
        }else{
            show_json(1,"分配失败！");
        }
    }
    
    //修改推广位
    public function modify()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $memberid=$_GPC['memberid'];
        $set['pdd_bitno'] = $_GPC['pdd_bitno'];
        $res = pdo_update("nets_hjk_members",$set,array('memberid'=>$memberid));
        if(!empty($res)){
            show_json(1,"修改成功！");
        }else{
            show_json(1,"修改失败！");
        }
    }

    //删除推广位
    public function delete()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $memberid=$_GPC['memberid'];
        $res['pdd_bitno'] = '';
        $j = pdo_update("nets_hjk_members",$res,array('memberid'=>$memberid));
        if(!empty($j)){
            show_json(1,"删除成功！");
        }else{
            show_json(1,"删除失败！");
        }
    }

	
}
?>