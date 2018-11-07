<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Stylemenu_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='sysset.stylemenu.index'||$_W['action']=='sysset.stylemenu')
            $this->index();
        elseif ($_W['action']=='sysset.stylemenu.menu_activity')
            $this->menu_set();
        elseif ($_W['action']=='sysset.stylemenu.menu_ad')
            $this->menu_set();
        elseif ($_W['action']=='sysset.stylemenu.menu_banner')
            $this->menu_set();
        elseif ($_W['action']=='sysset.stylemenu.menu_bottom')
            $this->menu_set();
        elseif ($_W['action']=='sysset.stylemenu.menu_pic')
            $this->menu_set();
        elseif ($_W['action']=='sysset.stylemenu.menu_top')
            $this->menu_set();
        elseif ($_W['action']=='sysset.stylemenu.help')
            $this->menu_set();
	}
	public function index()
	{
		global $_W;
		global $_GPC;
        $style=MODULE_URL."skin/0.jpg";
        $style2=MODULE_URL."skin/1.jpg";
        $style3=MODULE_URL."skin/2.jpg";
        $memberstyle=MODULE_URL."skin/memberskin0.jpg";
        $memberstyle2=MODULE_URL."skin/memberskin1.jpg";
        $uniacid=$_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['homeskinid'] = $_GPC['homeskinid'];
            $set['memberskin'] = $_GPC['memberskin'];
            $set['isopen_diypage'] = $_GPC['isopen_diypage'];
            $set['created_at'] = time();
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/stylemenu/index'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/stylemenu/index'), 'warning');
        }
        include $this->template('haojingke/sysset/stylemenu/index');
	}

    public function menu_set()
    {
        global $_W;
        global $_GPC;

        $uniacid=$_W['uniacid'];
        $result = '';
        $head_menu = array();
        $picture_menu = array();
        $picture_menu2 = array();
        $ad_menu =array();
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
        $data['help'] = json_decode($r['help']);
        $data['savemoney_help'] = json_decode($r['savemoney_help']);
        $data['makemoney_help'] = json_decode($r['makemoney_help']);
        $data['banner'] = json_decode($r['banner']);
        $array =  object_array($data['banner']);
        $arr = $array['list'];
        $arr_result = arr_ru($arr);
        $record_arr = arr_ru($record);
        $data['head_menu'] = json_decode($r['head_menu']);
        $re_head_menu_list = object_array($data['head_menu']);//头部
        $re_head_menu = $re_head_menu_list['list'];
        $data['picture_menu'] = json_decode($r['picture_menu']);
        $re_picture_menu_list = object_array($data['picture_menu']);//图片
        $re_picture_menu = $re_picture_menu_list['list'];
        $data['picture_menu2'] = json_decode($r['picture_menu2']);
        $re_picture_menu2_list = object_array($data['picture_menu2']);//活动
        $re_picture_menu2 = $re_picture_menu2_list['list'];
        $data['ad_menu'] = json_decode($r['ad_menu']);//广告
        $re_ad_menu_list = object_array($data['ad_menu']);
        $re_ad_menu = $re_ad_menu_list['list'];
        $data['tab_menu'] = json_decode($r['tab_menu']);
        $re_bottom_menu_list = object_array($data['tab_menu']); //底部
        $re_bottom_menu = $re_bottom_menu_list['list'];

        if ($_W['ispost']==1) {
            $result_img = $_GPC['cube_img'];
            $result_url = $_GPC['cube_url'];
            $result_sort = $_GPC['sort'];
            $result_menu_name = $_GPC['banner_name'];
            $banner_outer_url = $_GPC['banner_outer_url'];
            $result = array();
            if (is_array($result_img)) {
                foreach ($result_img as $key => $img ) {
                    $cube_img_count =  substr_count($img,'http');
                    if ($cube_img_count >= 1) {
                        $result[] =array('img' => $img, 'outer_url' => trim($banner_outer_url[$key]),'url' => trim($result_url[$key]));
                    }else{
                        $result[] = array('img' => $_W['attachurl'].$img, 'outer_url' => trim($banner_outer_url[$key]),'url' => trim($result_url[$key]));
                    }
                }
            }
            $res['banner_name'] = $result_menu_name;
            $res["sort"]=$result_sort;
            $res["list"]=$result;
            $head_img= $_GPC['head_menu_img'];
            $head_name = $_GPC['head_menu_name'];
            $head_url = $_GPC['head_menu_url'];
            $head_sort = $_GPC['sort'];
            $head_menu_name = $_GPC['head_menu_name_show'];
            $head_outer_url = $_GPC['head_outer_url'];
            if (is_array($head_img)) {
                foreach ($head_img as $key => $img ) {
                    $head_img_count =  substr_count($img,'http');
                    if ($head_img_count >= 1) {
                        $head_menu_one[] = array('img' => $img,'name'=>trim($head_name[$key]),'outer_url' => trim($head_outer_url[$key]),'url' => trim($head_url[$key]));
                    }else{
                        $head_menu_one[] = array('img' => $_W['attachurl'].$img,'name'=>trim($head_name[$key]),'outer_url' => trim($head_outer_url[$key]),'url' => trim($head_url[$key]));
                    }
                }
            }
            $head_menu['head_menu_name'] = $head_menu_name;
            $head_menu['sort'] = $head_sort;
            $head_menu['list'] = $head_menu_one;
            $picture_img = $_GPC['picture_menu_img'];
            $picture_name = $_GPC['picture_menu_name'];
            $picture_url = $_GPC['picture_menu_url'];
            $picture_sort = $_GPC['sort'];
            $picture_menu_name = $_GPC['picture_menu_name_show'];
            $picture_outer_url = $_GPC['picture_outer_url'];
            if (is_array($picture_img)) {
                foreach ($picture_img as $key => $img ) {
                    $picture_img_count =  substr_count($img,'http');
                    if ($picture_img_count >= 1) {
                        $picture_menu_one[] = array('img' => $img,'name'=>trim($picture_name[$key]),'outer_url' => trim($picture_outer_url[$key]),'url' => trim($picture_url[$key]));
                    }else{
                        $picture_menu_one[] = array('img' => $_W['attachurl'].$img,'name'=>trim($picture_name[$key]),'outer_url' => trim($picture_outer_url[$key]),'url' => trim($picture_url[$key]));
                    }
                }
            }
            $picture_menu['picture_menu_name'] =$picture_menu_name;
            $picture_menu['sort'] = $picture_sort;
            $picture_menu['list'] = $picture_menu_one;
            $picture2_img = $_GPC['action_menu_img'];
            $picture2_name = $_GPC['action_menu_name'];
            $picture2_url = $_GPC['action_menu_url'];
            $picture2_sort = $_GPC['sort'];
            $picture_menu2_name = $_GPC['action_menu'];
            $action_outer_url = $_GPC['action_outer_url'];
            if (is_array($picture2_img)) {
                foreach ($picture2_img as $key => $img ) {
                    $picture2_img_count =  substr_count($img,'http');
                    if ($picture2_img_count >= 1) {
                        $picture_menu2_one[] = array('img' => $img,'name'=>trim($picture2_name[$key]),'outer_url' => trim($action_outer_url[$key]),'url' => trim($picture2_url[$key]));
                    }else{
                        $picture_menu2_one[] = array('img' => $_W['attachurl'].$img,'name'=>trim($picture2_name[$key]),'outer_url' => trim($action_outer_url[$key]),'url' => trim($picture2_url[$key]));
                    }
                }
            }
            $picture_menu2['action_menu_name'] = $picture_menu2_name;
            $picture_menu2['sort'] = $picture2_sort;
            $picture_menu2['list'] = $picture_menu2_one;
            $ad_menu_img = $_GPC['ad_menu_img'];
            $ad_menu_url = $_GPC['ad_menu_url'];
            $ad_menu_sort = $_GPC['sort'];
            $ad_menu_name = $_GPC['ad_menu_name'];
            $ad_outer_url = $_GPC['ad_outer_url'];
            if (is_array($ad_menu_img)) {
                foreach ($ad_menu_img as $key => $img ) {
                    $ad_menu_img_count =  substr_count($img,'http');
                    if ($ad_menu_img_count >= 1) {
                        $ad_menu_one[] = array('img' => $img,'outer_url' => trim($ad_outer_url[$key]),'url' => trim($ad_menu_url[$key]));
                    }else{
                        $ad_menu_one[] = array('img' => $_W['attachurl'] .$img,'outer_url' => trim($ad_outer_url[$key]),'url' => trim($ad_menu_url[$key]));
                    }
                }
            }
            $ad_menu['ad_menu_name'] = $ad_menu_name;
            $ad_menu['sort'] = $ad_menu_sort;
            $ad_menu['list'] = $ad_menu_one;

            $bottom_img= $_GPC['bottom_menu_img'];
            $bottom_name = $_GPC['bottom_menu_name'];
            $bottom_url = $_GPC['bottom_menu_url'];
            $bottom_sort = $_GPC['sort'];
            $bottom_menu_name = $_GPC['bottom_menu_name_show'];
            $bottom_outer_url = $_GPC['bottom_outer_url'];
            if (is_array($bottom_img)) {
                foreach ($bottom_img as $key => $img ) {
                    $bottom_img_count =  substr_count($img,'http');
                    if ($bottom_img_count >= 1) {
                        $bottom_menu_one[] = array('img' => $img,'name'=>trim($bottom_name[$key]),'outer_url' => trim($bottom_outer_url[$key]),'url' => trim($bottom_url[$key]));
                    }else{
                        $bottom_menu_one[] = array('img' => $_W['attachurl'].$img,'name'=>trim($bottom_name[$key]),'outer_url' => trim($bottom_outer_url[$key]),'url' => trim($bottom_url[$key]));
                    }
                }
            }
            $tab_menu['head_menu_name'] = $bottom_menu_name;
            $tab_menu['sort'] = $bottom_sort;
            $tab_menu['list'] = $bottom_menu_one;
            if (empty($_GPC['makemoney_help']) && empty($_GPC['savemoney_help']) && empty($_GPC['help']) && empty($ad_menu['list']) && empty($picture_menu2['list']) && empty($picture_menu['list']) && empty($head_menu['list']) && $_GPC['banner_name'] == '轮播图设置') {
                $set['banner'] = json_encode($res);
            }elseif (empty($res['list']) && empty($_GPC['makemoney_help']) && empty($_GPC['savemoney_help']) && empty($_GPC['help']) && empty($ad_menu['list']) && empty($picture_menu2['list']) && empty($picture_menu['list']) && $_GPC['head_menu_name_show'] == '头部菜单') {
                $set['head_menu'] = json_encode($head_menu);
            }elseif (empty($res['list']) && empty($_GPC['makemoney_help']) && empty($_GPC['savemoney_help']) && empty($_GPC['help']) && empty($ad_menu['list']) && empty($picture_menu2['list']) &&  empty($head_menu['list']) && $_GPC['picture_menu_name_show'] == '图片菜单') {
                $set['picture_menu'] = json_encode($picture_menu);
            }elseif (empty($res['list']) && empty($_GPC['makemoney_help']) && empty($_GPC['savemoney_help']) && empty($_GPC['help']) && empty($ad_menu['list']) &&  empty($picture_menu['list']) && empty($head_menu['list']) && $_GPC['action_menu'] == '活动菜单') {
                $set['picture_menu2'] = json_encode($picture_menu2);
            }elseif (empty($res['list']) && empty($_GPC['makemoney_help']) && empty($_GPC['savemoney_help']) && empty($_GPC['help']) && empty($picture_menu2['list']) && empty($picture_menu['list']) && empty($head_menu['list']) && $_GPC['ad_menu_name'] == '广告图片设置') {
                $set['ad_menu'] = json_encode($ad_menu);
            }elseif(empty($res['list']) && empty($_GPC['makemoney_help']) && empty($_GPC['savemoney_help']) && empty($_GPC['help']) && empty($picture_menu2['list']) && empty($picture_menu['list']) && empty($head_menu['list']) && empty($ad_menu['list']) && $_GPC['bottom_menu_name_show'] == '低部菜单'){
                $set['tab_menu'] = json_encode($tab_menu);
                $set['search_tip'] = $_GPC['search_tip'];
            }elseif (empty($res['list'])  && empty($ad_menu['list']) && empty($picture_menu2['list']) && empty($picture_menu['list']) && empty($head_menu['list']) && $_GPC['help_name'] == '帮助') {
                $set['help']  = json_encode($_GPC['help']);
                $set['savemoney_help']  = json_encode($_GPC['savemoney_help']);
                $set['makemoney_help']  = json_encode($_GPC['makemoney_help']);
            }
            $set['created_at'] = time();
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }

            $routes = explode('.', $_W['action']);
            $action = $routes[count($routes)-1];
            if($res)
                show_message('操作成功！',webUrl('sysset/stylemenu/'.$action), 'success');
            else
                show_message('操作失败！',webUrl('sysset/stylemenu/'.$action), 'warning');
        }

        if ($_W['action']=='sysset.stylemenu.menu_activity')
            include $this->template('haojingke/sysset/stylemenu/menu_activity');
        elseif ($_W['action']=='sysset.stylemenu.menu_ad')
            include $this->template('haojingke/sysset/stylemenu/menu_ad');
        elseif ($_W['action']=='sysset.stylemenu.menu_banner')
            include $this->template('haojingke/sysset/stylemenu/menu_banner');
        elseif ($_W['action']=='sysset.stylemenu.menu_bottom')
            include $this->template('haojingke/sysset/stylemenu/menu_bottom');
        elseif ($_W['action']=='sysset.stylemenu.menu_pic')
            include $this->template('haojingke/sysset/stylemenu/menu_pic');
        elseif ($_W['action']=='sysset.stylemenu.menu_top')
            include $this->template('haojingke/sysset/stylemenu/menu_top');
        elseif ($_W['action']=='sysset.stylemenu.help')
            include $this->template('haojingke/sysset/base/help');
        else
            include $this->template('haojingke/sysset/stylemenu/menu_banner');
    }



}
?>