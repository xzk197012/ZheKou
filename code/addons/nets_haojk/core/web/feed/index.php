<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.feed.func.php';
class Index_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='feed.index'||$_W['action']=='feed')
            $this->index();
        elseif ($_W['action']=='feed.top_menu')
            $this->top_menu();
        elseif ($_W['action']=='feed.second_menu')
            $this->second_menu();
        elseif ($_W['action']=='feed.feedentry')
            $this->feedentry();
	}
	//小程序入口
    public function feedentry()
    {
        global $_GPC, $_W;
        include $this->template('haojingke/feed/feedentry');
    }

    //购物圈设置
	public function index()
	{
        global $_GPC, $_W;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_feed_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['share_rule']  = $_GPC['share_rule'];
            $set['share_logo']  = tomedia($_GPC['share_logo']);
            $set['share_title'] = $_GPC['share_title'];
            $set['share_remark'] = $_GPC['share_remark'];
            $set['share_end_qrcode'] = tomedia($_GPC['share_end_qrcode']);
            if (empty($r)) {
                $set['created_at'] = time();
                $set['uniacid'] = $_W['uniacid'];
                $res = pdo_insert("nets_hjk_feed_global",$set);
            }else{
                $set['updated_at'] = time();
                $res = pdo_update("nets_hjk_feed_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('feed/index'), 'success');
        }
        include $this->template('haojingke/feed/index');
	}

    //购物圈设置
    public function top_menu()
    {
        global $_GPC, $_W;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_feed_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $data['top_menu'] = json_decode($r['top_menu']);
        $array =  object_array($data['top_menu']);
        $arr = $array['list'];
        $tags=getfeed_tag();
        $tags=object_array($tags);
        if ($_W['ispost']==1) {
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
            $menu['sort'] = 0;
            $menu['list'] = $menu_one;
            $set["top_menu"]=json_encode($menu);
            if (empty($r)) {
                $set['created_at'] = time();
                $set['uniacid'] = $_W['uniacid'];
                $res = pdo_insert("nets_hjk_feed_global",$set);
            }else{
                $set['updated_at'] = time();
                $res = pdo_update("nets_hjk_feed_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('feed/top_menu'), 'success');
        }
        include $this->template('haojingke/feed/top_menu');
    }


    //购物圈设置
    public function second_menu()
    {
        global $_GPC, $_W;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_feed_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $data['second_menu'] = json_decode($r['second_menu']);
        $array =  object_array($data['second_menu']);
        $arr = $array['list'];
        $tags=getfeed_tag();
        $tags=object_array($tags);
        if ($_W['ispost']==1) {
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
            $menu['sort'] = 0;
            $menu['list'] = $menu_one;
            $set["second_menu"]=json_encode($menu);
            if (empty($r)) {
                $set['created_at'] = time();
                $set['uniacid'] = $_W['uniacid'];
                $res = pdo_insert("nets_hjk_feed_global",$set);
            }else{
                $set['updated_at'] = time();
                $res = pdo_update("nets_hjk_feed_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('feed/second_menu'), 'success');
        }
        include $this->template('haojingke/feed/second_menu');
    }




}
?>