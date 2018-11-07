<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Diypage_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

        if ($_W['action']=='sysset.diypage.index'||$_W['action']=='sysset.diypage')
            $this->index();
        if ($_W['action']=='sysset.diypage.diyfoot')
            $this->diyfoot();
        if ($_W['action']=='sysset.diypage.diyfoot_save')
            $this->diyfoot_save();
        elseif ($_W['action']=='sysset.diypage.save')
            $this->diypage_save();
        elseif ($_W['action']=='sysset.diypage.getglobal')
            $this->getglobal();
        elseif ($_W['action']=='sysset.diypage.savepageitem')
            $this->savepageitem();
        elseif ($_W['action']=='sysset.diypage.getpageitem')
            $this->getpageitem();
        elseif ($_W['action']=='sysset.diypage.resetpage')
            $this->resetpage();
        if ($_W['action']=='sysset.diypage.membermenu')
            $this->membermenu();
	}
	public function index()
	{
		global $_W;
		global $_GPC;
        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
        $jsonrecord=json_encode($record);
        $global = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        
        include $this->template('haojingke/sysset/diypage/index');
    }
    public function diyfoot()
	{
		global $_W;
		global $_GPC;
        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
        $jsonrecord=json_encode($record);
        $global = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        
        include $this->template('haojingke/sysset/diypage/diyfoot');
    }
    public function membermenu()
	{
		global $_W;
		global $_GPC;
        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
        $jsonrecord=json_encode($record);
        $global = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        
        include $this->template('haojingke/sysset/diypage/membermenu');
    }
    public function diyfoot_save()
    {
        global $_W;
        global $_GPC;
        //var_dump($_GPC);
        $itemtitle=$_GPC["itemtitle"];
        $itemremark=$_GPC["itemremark"];
        $menu_tpl=$_GPC["menu_tpl"];
        $menu_img = $_GPC[$_GPC["cloum"].'_img'];
        $menu_url = $_GPC[$_GPC["cloum"].'_url'];
        $menu_sort = $_GPC[$_GPC["cloum"].'_sort'];
        $menu_name = $_GPC[$_GPC["cloum"].'_name'];
        $outer_url = $_GPC[$_GPC["cloum"].'_outer_url'];
        if (is_array($menu_img)) {
            foreach ($menu_img as $key => $img ) {
                $menu_img_count =  substr_count($img,'http');
                if ($menu_img_count >= 1) {
                    $menu_one[] = array('img' => $img,'name'=>trim($menu_name[$key]),'outer_url' => trim($outer_url[$key]),'url' => trim($menu_url[$key]));
                }else{
                    $menu_one[] = array('img' => $_W['attachurl'] .$img,'name'=>trim($menu_name[$key]),'outer_url' => trim($outer_url[$key]),'url' => trim($menu_url[$key]));
                }
            }
        }
        $cloum=$_GPC["cloum"];
        $menu[$cloum."_name"] = $itemtitle;
        $menu["itemtitle"]=$itemtitle;
        $menu["itemremark"]=$itemremark;
        $menu["menu_tpl"]=$menu_tpl;
        $menu['sort'] = 0;
        $menu['list'] = $menu_one;
        $res=0;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $row[$cloum]=json_encode($menu);
        if (empty($r)) {
            $row["uniacid"]=$_W["uniacid"];
            $row["created_at"]=time();
            $res = pdo_insert("nets_hjk_global",$row);
        }else{
            $row["updated_at"]=time();
            $res = pdo_update("nets_hjk_global",$row,array('uniacid'=>$_W['uniacid']));
        }
        if($cloum=="tab_menu"){
            if($res)
            show_message('操作成功！',webUrl('sysset/diypage/diyfoot'), 'success');
            else
            show_message('操作失败！',webUrl('sysset/diypage/diyfoot'), 'error');
        }
        if($cloum=="membermenu"){
            if($res)
            show_message('操作成功！',webUrl('sysset/diypage/membermenu'), 'success');
            else
            show_message('操作失败！',webUrl('sysset/diypage/membermenu'), 'error');
        }
        include $this->template('haojingke/sysset/base/share');
    }
    public function indexyl()
	{
		global $_W;
		global $_GPC;
        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
        $jsonrecord=json_encode($record);
        $global = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        
        include $this->template('haojingke/sysset/diypage/indexyl');
	}

    public function diypage_save()
    {
        global $_W;
        global $_GPC;
        //var_dump($_GPC);
        $itemtitle=$_GPC["itemtitle"];
        $itemremark=$_GPC["itemremark"];
        $menu_tpl=$_GPC["menu_tpl"];
        $menu_img = $_GPC[$_GPC["cloum"].'_img'];
        $menu_url = $_GPC[$_GPC["cloum"].'_url'];
        $menu_sort = $_GPC[$_GPC["cloum"].'_sort'];
        $menu_name = $_GPC[$_GPC["cloum"].'_name'];
        $outer_url = $_GPC[$_GPC["cloum"].'_outer_url'];
        if (is_array($menu_img)) {
            foreach ($menu_img as $key => $img ) {
                $menu_img_count =  substr_count($img,'http');
                if ($menu_img_count >= 1) {
                    $menu_one[] = array('img' => $img,'name'=>trim($menu_name[$key]),'outer_url' => trim($outer_url[$key]),'url' => trim($menu_url[$key]));
                }else{
                    $menu_one[] = array('img' => $_W['attachurl'] .$img,'name'=>trim($menu_name[$key]),'outer_url' => trim($outer_url[$key]),'url' => trim($menu_url[$key]));
                }
            }
        }
        $cloum=$_GPC["cloum"];
        $menu[$cloum."_name"] = $itemtitle;
        $menu["itemtitle"]=$itemtitle;
        $menu["itemremark"]=$itemremark;
        $menu["menu_tpl"]=$menu_tpl;
        $menu['sort'] = 0;
        $menu['list'] = $menu_one;
        $res=0;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $row[$cloum]=json_encode($menu);
        if (empty($r)) {
            $row["uniacid"]=$_W["uniacid"];
            $row["created_at"]=time();
            $res = pdo_insert("nets_hjk_global",$row);
        }else{
            $row["updated_at"]=time();
            $res = pdo_update("nets_hjk_global",$row,array('uniacid'=>$_W['uniacid']));
        }
        if($res)
        show_message('操作成功！',webUrl('sysset/diypage/index'), 'success');
        else
        show_message('操作失败！',webUrl('sysset/diypage/index'), 'error');
        include $this->template('haojingke/sysset/base/share');
    }

    public function getglobal(){
        global $_W;
        global $_GPC;
        $cloum=$_GPC["cloum"];
        $g=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
        //var_dump($g);
        $list=json_decode($g[$cloum]);

        // $res["code"]=0;
        // $res["data"]=$list->list;
        // $res["count"]=count($list->list);
        // $res["msg"]="";
        $json=json_encode($list);
        if(!empty($json)){
            echo $json;
        }else{
            echo "";
        }
        exit;
    }
    public function getpageitem(){
        global $_W;
        global $_GPC;
        
        $res=0;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        echo $r["homepage_itemjson"];
        
    }
    public function savepageitem(){
        global $_W;
        global $_GPC;
        $cloum=$_GPC["cloum"];
        $res=0;
        $pageitem=$_GPC["pageitemjson"];
        $pageitemhtml=$_GPC["pageitemhtml"];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $row1["itemjson"]=json_encode($pageitem);
        $row1["itemhtml"]=json_encode($pageitemhtml);
        $row1["title"]=$_GPC["title"];
        $row["homepage_itemjson"]=json_encode($row1);
        if (empty($r)) {
            $row["uniacid"]=$_W["uniacid"];
            $row["created_at"]=time();
            $res = pdo_insert("nets_hjk_global",$row);
        }else{
            $row["updated_at"]=time();
            $res = pdo_update("nets_hjk_global",$row,array('uniacid'=>$_W['uniacid']));
        }
        if($res)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    public function resetpage(){
        global $_W;
        global $_GPC;
        $cloum=$_GPC["cloum"];
        $res=0;
        
        $row["homepage_itemjson"]="";
        
        $row["updated_at"]=time();
        $res = pdo_update("nets_hjk_global",$row,array('uniacid'=>$_W['uniacid']));
        show_message('操作成功！',webUrl('sysset/diypage/index'), 'success');
    }
}
?>