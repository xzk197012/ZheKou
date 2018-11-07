<?php
if (!(defined('IN_IA'))) 
{
    exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.mgj.func.php';
class Orderlist_NetsHaojkPage extends WebPage
{
    public function main() 
    {

        global $_GPC;
        global $_W;
         
        if ($_W['action']=='mogujie.orderlist')           
            $this->order();
    }
 
   public function order()
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
            $_GPC["start_time"]=$start_time;
            $_GPC["end_time"]=$end_time;
        }
        $keyword=$_GPC["keyword"];
         
        if(!empty($_GPC["page"]))
            $_GPC["page"] = $_GPC["page"];
        else{
            $_GPC["page"]=1;
        }
        $_GPC["pageSize"]=20;
        $pid='';
        if(!empty($keyword)){
            $pid=pdo_fetchcolumn("select pdd_bitno from ".tablename("nets_hjk_members")." where nickname like '%".$keyword."%' OR username like '%".$keyword."%' OR memberid='".$keyword."' OR pdd_bitno like '%".$keyword."%'");
            if(!empty($pid)){
                $_GPC["pdd_bitno"]=$pid;
            }
            else{$_GPC["pdd_bitno"]=$keyword;}
        }
        
        $list=mgj_getorderlist();
        
        $pager = pagination($list['total'],$_GPC["page"], $_GPC["pageSize"]);
        $list=$list["data"];

        include $this->template('haojingke/mogujie/orderlist');
    }


}
?>