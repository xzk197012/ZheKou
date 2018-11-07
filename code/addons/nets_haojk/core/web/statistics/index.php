<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/statistics.func.php';
class Index_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='statistics.index'||$_W['action']=='statistics')
            $this->index();
        elseif($_W['action']=='statistics.orderstatistics')
            $this->orderstatistics();
        elseif($_W['action']=='statistics.getstatistics')
            $this->getstatistics();
        elseif($_W['action']=='statistics.order')
            $this->order();
        elseif($_W['action']=='statistics.member')
            $this->member();
        elseif($_W['action']=='statistics.getorders30')
            $this->getorders30();
	}
	//订单分析
	public function index()
	{
        global $_GPC, $_W;

        $today = strtotime(date('Y-m-d',TIMESTAMP));
        $starttime = $today - 7*60*60*30;
        $endtime =  TIMESTAMP + 86399;

        $uniacid=$_W['uniacid'];
        $page=1;
        $pagesize = 20;

        $list = pdo_fetchall("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join "
            .tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid  ORDER BY id DESC limit 0,10" ,array(':uniacid'=>$uniacid));
        $totalcount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_members")." AS em  WHERE uniacid= " .$uniacid);
        $sevendaycount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_members")." AS em  WHERE created_at>=".$starttime." and uniacid= " .$uniacid);
        $invitecount = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_members")." AS em  WHERE from_uid>0 and uniacid= " .$uniacid);
        $pidusecount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("nets_hjk_probit")." AS p where p.state=1 and p.uniacid=:uniacid  ",array(':uniacid'=>$uniacid));
        $pidallcount = pdo_fetchcolumn("SELECT count(0) FROM ".tablename("nets_hjk_probit")." AS p where p.uniacid=:uniacid  ",array(':uniacid'=>$uniacid));
        $mysourcetotal = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_menu")." where type =3");
        $sourcetotal = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_menu"));

        include $this->template('haojingke/statistics/index');
	}


    //订单分析
    public function orderstatistics()
    {
        global $_GPC, $_W;

        $today = strtotime(date('Y-m-d',TIMESTAMP));
        $starttime = $today - 24*60*60*30;
        $endtime =  TIMESTAMP + 86399;


        include $this->template('haojingke/statistics/orderstatistics');
    }


    //销售统计
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
        $global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
        if(empty($global)&&empty($global["jduniacid"]))
                show_json(1,"请设置联盟ID");
        $data = array(
            'yn' =>  $_GPC['yn'],
            'begintime' => $starttime,
            'endtime' => $endtime,
            'unionId' => $global["jduniacid"],
            'positionId' => $_GPC['positionId']
        );

        load()->func('communication');
        $url=HAOJK_HOST."index/getstatistics";
        $res=ihttp_post($url,$data);
        exit($res["content"]);
    }
    //订单统计
    public function getorders30()
    {
        global $_GPC, $_W;
        $content=getorders30_byadmin();
        exit($content);
    }
    //订单明细
    public function order()
    {
        global $_GPC, $_W;
        include $this->template('haojingke/statistics/order');
    }
    //会员增长趋势
    public function member()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $i = -5;
        while ($i<=5) {
            $years[$i]=date("Y", strtotime("+$i year"));
            $i++;
        }
        $year = $_GPC['year'];
        $j = 1;
        while ($j<= 12) {
            $mouths[$j] = $j;
            $j++;
        }
        $mouth = $_GPC['mouth'];
        if (!empty($_GPC['days'])) {
            switch ($_GPC['days']) {
                case '7':
                    $day_select = recent('7');
                    break;
                case '14':
                    $day_select = recent('14');
                    break;
                case '30':
                    $day_select = recent('30');
                    break;
            }
            $begin = $day_select['begin'];
            $end = $day_select['end'];
            $data = getMemberStatis($day_select['begin'],$day_select['end']);
        }else{
            $r = $_GPC['year'];
            $t = $_GPC['mouth'];
            if (empty($r)) {
                $r = date('Y');
            }
            switch ($t) {
                case  empty($t):
                    $time_select = getShiJianChuo($r,$t);
                    break;
                case !empty($t):
                    $time_select = getShiJianChuo($r,$t);
                    break;
            }
            $begin = $time_select['begin'];
            $end = $time_select['end'];
            $data = getMemberStatis($begin,$end);
        }
        if (empty($data)) {

//            $i = 0;
//            while ($i<=7) {
//                $date['begin']=mktime(0,0,0,date($t),date(0),date($r));
//                $date['end']=mktime(0,0,0,date($t),date($i)+7,date($r));
//                $i++;
//            }

            $date['begin']=mktime(0,0,0,date($t),date(0),date($r));
            $date['end']=mktime(0,0,0,date($t),date($i)+7,date($r));
            $data = getMemberStatis($begin,$end);
            if (empty($data)) {
                $data = array(array('num'=>0),array('num'=>0),array('num'=>0),array('num'=>0),array('num'=>0),array('num'=>0),array('num'=>0));
            }

        }
        include $this->template('haojingke/statistics/member');
    }
	
}
?>