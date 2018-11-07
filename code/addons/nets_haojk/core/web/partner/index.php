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

		if ($_W['action']=='partner.index'||$_W['action']=='partner')
            $this->index();
        elseif ($_W['action']=='partner.partner_set')
            $this->partner_set();
        elseif ($_W['action']=='partner.partner_argee')
            $this->partner_argee();
        elseif ($_W['action']=='partner.partner_refuse')
            $this->partner_refuse();
        elseif ($_W['action']=='partner.partner')
            $this->partner();
        elseif ($_W['action']=='partner.importorder')
            $this->importorder();
        elseif ($_W['action']=='partner.order')
            $this->order();
        elseif ($_W['action']=='partner.userorder')
            $this->userorder();
        elseif ($_W['action']=='partner.member')
            $this->member();
        elseif ($_W['action']=='partner.position')
            $this->position();
        elseif ($_W['action']=='partner.logs')
            $this->logs();
	}
	//合伙人-列表
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
            $where .= " AND p.created_at >= ".$starttime." AND p.created_at <=".$endtime;
        }
        if(!empty($_GPC['keyword'])){
            $where .= " AND (em.nickname like '%".$_GPC['keyword']."%' or em.openid like '%".$_GPC['keyword']."%' or em.jduniacid like '%".$_GPC['keyword']."%') ";
        }
        if($_GPC['state']!=''){
            $where .= " AND p.state = ".$_GPC['state'];
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("SELECT em.*,m.credit1 as mccredit1,m.credit2 as mccredit2,p.`name`,p.mobile as pmobile,p.applyremark,p.state,p.remark,p.created_at as pcreated_at,p.id as partnerid FROM ".tablename("nets_hjk_members")." AS em left join ".
            tablename("mc_members")." AS m on em.memberid=m.uid  left join ".
            tablename("nets_hjk_applypartner")." AS p on p.memberid=em.memberid where em.uniacid=:uniacid  and p.id >0 ".$where." ORDER BY id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("select count(0) FROM ".tablename("nets_hjk_members")." AS em left join ".
            tablename("mc_members")." AS m on em.memberid=m.uid  left join ".
            tablename("nets_hjk_applypartner")." AS p on p.memberid=em.memberid where em.uniacid=:uniacid  and p.id >0 ".$where,array(':uniacid'=>$uniacid));
        $pager = pagination($totalcount, $pindex, $psize);//var_dump($pager);die;
        include $this->template('haojingke/partner/index');
	}

    //合伙人-设置
    public function partner_set()
    {
        global $_GPC, $_W;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            if ($r['partnerimg'] == $_GPC['partnerimg']) {
                $set['partnerimg'] = $_GPC['partnerimg'];
            }else{
                $set['partnerimg'] = $_W['attachurl'].$_GPC['partnerimg'];
            }
            $set['partnerdesc']  = $_GPC['partnerdesc'];
            $set['isopenpartner']  = $_GPC['isopenpartner'];
            $set['updated_at'] = time();
            if (empty($r)) {
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('partner/partner_set'), 'success');
            else
                show_message('操作失败！',webUrl('partner/partner_set'), 'warning');
        }
        include $this->template('haojingke/partner/partner_set');
    }

    //合伙人-详情
    public function partner()
    {
        global $_GPC, $_W;

        include $this->template('haojingke/partner/partner');
    }

    //同意申请合伙人
    public function partner_argee()
    {
        global $_W;
        global $_GPC;

        if(empty($_GPC['id']))
            show_json(1,"申请记录不存在");
        $r = pdo_fetch("SELECT em.id,em.jduniacid FROM ".tablename("nets_hjk_members")." AS em left join ".
            tablename("nets_hjk_applypartner")." AS p on em.memberid=p.memberid where em.uniacid=:uniacid and em.jduniacid >0  and p.id = ".$_GPC['id'],array(':uniacid'=>$_W['uniacid']));

        if(empty($r))
            show_json(1,"用户联盟ID没填写！");


        $data['updated_at'] = time();
        $data['state'] = '1';
        $j = pdo_update('nets_hjk_applypartner',$data,array('id'=>$_GPC['id']));
        $m["type"]=2;
        $m["updated_at"]=time();
        $j = pdo_update('nets_hjk_members',$m,array('id'=>$r['id']));
        if($j)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }

    //拒绝申请合伙人
    public function partner_refuse()
    {
        global $_W;
        global $_GPC;
        if(empty($_GPC['id']))
            show_json(1,"申请记录不存在");
        $data['updated_at'] = time();
        $data['remark'] = $_GPC['remark'];
        $data['state'] = '-1';
        $j = pdo_update('nets_hjk_applypartner',$data,array('id'=>$_GPC['id']));
        if($j)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }

    //合伙人-销售统计
    public function getstatistics()
    {
        global $_GPC, $_W;

        $today = strtotime(date('Y-m-d',TIMESTAMP));
        $starttime = $today - 24*60*60*7;
        $endtime =  $today + 86399;
        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }
        if(empty($_GPC["jduniacid"]))
            show_json(1,"合伙人联盟ID不存在");
        $data = array(
            'yn' =>  $_GPC['yn'],
            'begintime' => $starttime,
            'endtime' => $endtime,
            'unionId' => $_GPC["jduniacid"],
            'positionId' => $_GPC['positionId']
        );

        load()->func('communication');
        $url=HAOJK_HOST."index/getstatistics";
        $res=ihttp_post($url,$data);
        exit($res["content"]);
    }

    //引入订单importorders
    public function importorder()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $jduniacid =  $_GPC["jduniacid"];

        $uri= 'importorders';

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

        $list=getorders_byadmin_partner($uri, $page, $pagesize,$starttime,$endtime,$pid,0,$jduniacid);
//        $list=getorders_byadmin($uri, $page, $pagesize,$starttime,$endtime,$pid,0);

        $pager = pagination($list['total'], $page, $pagesize);//var_dump($pager);die;
        $list = $list['data'];
        if(empty($list)){
            $list="";
        }
        include $this->template('haojingke/partner/importorder');
    }

    //业绩订单 orders
    public function order()
    {
        global $_GPC, $_W;
        $uri= 'orders';
        $jduniacid =  $_GPC["jduniacid"];

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

        $list=getorders_byadmin_partner($uri, $page, $pagesize,$starttime,$endtime,$pid,0,$jduniacid);

        $pager = pagination($list['total'], $page, $pagesize);//var_dump($pager);die;
        $list = $list['data'];
        if(empty($list)){
            $list="";
        }
        include $this->template('haojingke/partner/order');
    }

    //用户提单
    public function userorder()
    {
        global $_GPC, $_W;
        $uri= !empty($_GPC['uri']) ? $_GPC['uri'] : 'importorders';
        $jduniacid =  $_GPC["jduniacid"];

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

        $list=getorders_byadmin_partner($uri, $page, $pagesize,$starttime,$endtime,$pid,1,$jduniacid);
//        $list=getorders_byadmin($uri, $page, $pagesize,$starttime,$endtime,$pid,1);

        $pager = pagination($list['total'], $page, $pagesize);//var_dump($pager);die;
        $list = $list['data'];
        if(empty($list)){
            $list="";
        }

        include $this->template('haojingke/partner/userorder');
    }

    //会员列表列表
    public function member()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $jduniacid =  $_GPC["jduniacid"];

        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }

        $where=' and from_jduniacid = \''.$jduniacid.'\'';
        // 筛选
        if(!empty($endtime)){
            $where .= " AND em.created_at >= ".$starttime." AND em.created_at <=".$endtime;
        }
        if(!empty($_GPC['keyword'])){
            $where .= " AND (em.nickname like '%".$_GPC['keyword']."%' or em.openid like '%".$_GPC['keyword']."%') ";
        }
        if($_GPC['type']==1){
            $where .= " AND em.type = 0";
        }
        if($_GPC['type']==2){
            $where .= " AND em.type = 1";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid ".$where." ORDER BY id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_members")." AS em  WHERE uniacid= " .$uniacid.$where);
        $pager = pagination($totalcount, $pindex, $psize);//var_dump($pager);die;
        $count = 0;
        include $this->template('haojingke/partner/member');
    }

    //推广位-列表
    public function position()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $jduniacid =  $_GPC["jduniacid"];


        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime = 0 ;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }
        $where=' and jduniacid = \''.$jduniacid.'\'';
        if(!empty($starttime)){
            $where .= " AND p.created_at >= ".$starttime;
        }
        if(!empty($endtime)){
            $where .= " AND p.created_at <=".$endtime;
        }
        if($_GPC['pstate']==1){
            $where .= " AND p.state = 1";
        }
        if($_GPC['pstate']==2){
            $where .= " AND p.state = 0";
        }
        if(!empty($_GPC['keyword'])){
            $where .= " AND (p.memberid='".$_GPC['keyword']."' or p.bitno='".$_GPC['keyword']."' or p.remark='".$_GPC['keyword']."')";
//            $where .= " AND ( em.nickname like '%".$_GPC['keyword']."%'  or  p.bitno like '%".$_GPC['keyword']."%' or  p.remark like '%".$_GPC['keyword']."%'or  em.openid like '%".$_GPC['keyword']."%' )";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("SELECT p.* FROM ".tablename("nets_hjk_probit")." AS p where p.uniacid=:uniacid".$where." ORDER BY p.state desc,p.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("nets_hjk_probit")." AS p where p.uniacid=:uniacid  ".$where,array(':uniacid'=>$uniacid));
        $usecount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("nets_hjk_probit")." AS p where p.state=1 and p.uniacid=:uniacid  ",array(':uniacid'=>$uniacid));
        $allcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("nets_hjk_probit")." AS p where p.uniacid=:uniacid  ",array(':uniacid'=>$uniacid));
        $pager = pagination($totalcount, $pindex, $psize);


        include $this->template('haojingke/partner/position');
    }


    //合伙人余额记录
    public function logs()
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
        $psize = 20;
        $where = "";

        if(!empty($starttime)){
            $where.= " AND s.created_at >= ".$starttime. " AND s.created_at <=" .$endtime;
        }
        if(!empty($_GPC['keyword'])){
            $where.= " AND (m.nickname like '%".$_GPC['keyword']."%'  or m.mobile like '%".$_GPC['mobile']."%' )";
        }
        if($_GPC['status']!= ''){
            $where.=" AND s.status = '".$_GPC['status']."'";
        }
        $pindex = max(1, intval($_GPC['page']));
        $list = pdo_fetchall("SELECT s.*,m.nickname,m.mobile FROM ".tablename('nets_hjk_partner_logs')." AS s LEFT JOIN "
            .tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid and m.uniacid=".$uniacid." WHERE s.type = '5' AND s.uniacid= ".$uniacid.$where." ORDER BY s.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize);
        $total = pdo_fetchcolumn("SELECT count(s.id) FROM " .tablename('nets_hjk_partner_logs'). " AS s LEFT JOIN "
            .tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid WHERE s.type = '5' AND  s.uniacid = " .$uniacid.$where);
        $pager = pagination($total, $pindex, $psize);
        //导出数据
        if(!empty($_GPC["import"]) && $_GPC["import"]==1){
            $importdata = pdo_fetchall("SELECT s.*,m.nickname,m.mobile FROM ".tablename('nets_hjk_partner_logs')." AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid and m.uniacid=".$uniacid." WHERE s.type = '5' AND s.uniacid= ".$uniacid.$where." ORDER BY s.id DESC ");

            $header = array(
                'memberid' => '会员ID', 'nickname' => '昵称', 'mobile' => '手机', 'money' => '提现金额','cashtype' => '提现方式', 'status' => '状态', 'createtime' => '申请时间','remark' => '备注',
            );
            $keys = array_keys($header);
            $html = "\xEF\xBB\xBF";
            foreach ($header as $li) {
                $html .= $li . "\t ,";
            }
            $html .= "\n";

            if (!empty($importdata)) {
                $size = ceil(count($importdata) / 500);
                for ($i = 0; $i < $size; $i++) {
                    $buffer = array_slice($importdata, $i * 500, 500);
                    $user = array();
                    foreach ($buffer as $row) {
                        $row['money']=floatval($row['money'])*-1;
                        $row['cashtype']=$row['cashtype']==2?'支付宝':'微信';
                        if($row['status']==0){
                            $row['status']='申请提现';
                        }
                        if($row['status']==1){
                            $row['status']='已完成';
                        }
                        if($row['status']==2){
                            $row['status']='已拒绝';
                        }
                        $row['createtime'] = date('Y-m-d H:i:s', $row['created_at']);

                        foreach ($keys as $key) {
                            $data[] = $row[$key];
                        }
                        $user[] = implode("\t ,", $data) . "\t ,";
                        unset($data);
                    }
                    $html .= implode("\n", $user) . "\n";
                }
            }

            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=提现记录.csv");
            echo $html;
            exit();
        }

        include $this->template('haojingke/partner/logs');
    }
}
?>