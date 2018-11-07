<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Recharge_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='finance.recharge.index'||$_W['action']=='finance.recharge')
            $this->index('全部');
        elseif ($_W['action']=='finance.recharge.unpay')
            $this->index('未付款');
        elseif ($_W['action']=='finance.recharge.pay')
            $this->index('已付款');
	}
	//列表
	public function index($title)
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
        $uniacid=$_W['uniacid'];
        $psize = 20;
        $where = " AND s.type !=5 ";
        if($title=='全部')
            $where .= " AND (s.status = 0 OR s.status = 1 OR s.status = 2)";
        if($title=='未付款')
            $where .= " AND (s.status = 2 OR  s.status = 0)";
        if($title=='已付款')
            $where .= "  AND s.status = 1";
        if(!empty($_GPC["type"])){
            $where .= "  AND s.type = ".$_GPC["type"];
        }
        if(!empty($endtime)){
            $where.= " AND s.created_at >= ".$starttime. " AND s.created_at <=" .$endtime;
        }
        if(!empty($_GPC['keyword'])){
            $where.= " AND ( s.remark like '%".$_GPC['keyword']."%' or m.nickname like '%".$_GPC['keyword']."%'  or m.mobile like '%".$_GPC['keyword']."%' )";
        }
        $pindex = max(1, intval($_GPC['page']));
        $list = pdo_fetchall("SELECT s.*,m.nickname,m.mobile FROM ".tablename('nets_hjk_member_logs'). " AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid WHERE  s.uniacid = " .$uniacid." and m.uniacid=".$uniacid." ".$where." ORDER BY s.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize);
        $total = pdo_fetchcolumn("SELECT count(s.id) FROM " .tablename('nets_hjk_member_logs'). " AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid WHERE  s.uniacid = " .$uniacid." and m.uniacid=".$uniacid." ".$where);

        $pager = pagination($total, $pindex, $psize);
        include $this->template('haojingke/finance/recharge/index');
	}
	
}
?>