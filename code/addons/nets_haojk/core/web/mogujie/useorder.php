<?php
if (!(defined('IN_IA'))) 
{
    exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.mgj.func.php';
class Useorder_NetsHaojkPage extends WebPage
{
    public function main() 
    {

        global $_GPC;
        global $_W;
         
        if ($_W['action']=='mogujie.useorder')           
            $this->useorder();
    }
 
   public function useorder()
    {
        global $_GPC, $_W;
        $mgj_orderstate=json_decode(mgj_orderstate,true);
        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $start_time = 0;
        $end_time =  TIMESTAMP + 86399;
        if(count($date)==2){
            $start_time =strtotime($date[0]);
            $end_time =strtotime($date[1]) + 86399;
        }
        $keyword=$_GPC["keyword"];
        $page=empty($_GPC["page"])?1:$_GPC["page"];
        $_GPC["pagesize"]=20;
        $pid='';
        if(!empty($keyword)){
            $pid=pdo_fetchcolumn("select pdd_bitno from ".tablename("nets_hjk_members")." where nickname like '%".$keyword."%' OR username like '%".$keyword."%' OR pdd_bitno like '%".$keyword."%'");
            if(!empty($pid)){
                $_GPC["pdd_bitno"]=$pid;
            }
        }
        $_GPC["start_time"]=$start_time;
        $_GPC["end_time"]=$end_time;
        $total=pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_orders")." where uniacid=".$_W['uniacid']." and locate('-',orderno)>0 ");

        $list=getmgj_GetorderrangeByuse();
        $pager = pagination($total,$_GPC["page"], $_GPC["pagesize"]);
        
        include $this->template('haojingke/mogujie/useorder');
    }


}
?>