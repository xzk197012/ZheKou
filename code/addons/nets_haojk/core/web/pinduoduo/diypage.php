<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
class Diypage_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;
        if ($_W['action']=='pinduoduo.diypage')
            $this->index();
        elseif ($_W['action']=='pinduoduo.diypage.save')
            $this->diypage_save();
        elseif ($_W['action']=='pinduoduo.diypage.getglobal')
            $this->getglobal();
        elseif ($_W['action']=='pinduoduo.diypage.savepageitem')
            $this->savepageitem();
        elseif ($_W['action']=='pinduoduo.diypage.getpageitem')
            $this->getpageitem();
        elseif ($_W['action']=='pinduoduo.diypage.resetpage')
            $this->resetpage();
	}
	public function index()
	{
		global $_W;
        global $_GPC;
        $record = getpdd_cname();
        $jsonrecord=json_encode($record);
        $global = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $menu = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_pdd_menu'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $jsonmenu=json_encode($menu);
        include $this->template('haojingke/pinduoduo/diypage');
    }
    public function diyfoot()
	{
		global $_W;
		global $_GPC;
        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
        $jsonrecord=json_encode($record);
        $global = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        
        include $this->template('haojingke/pinduoduo/diypage/diyfoot');
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
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $row[$cloum]=json_encode($menu);
        if (empty($r)) {
            $row["uniacid"]=$_W["uniacid"];
            $row["created_at"]=time();
            $res = pdo_insert("nets_hjk_pdd_global",$row);
        }else{
            $row["updated_at"]=time();
            $res = pdo_update("nets_hjk_pdd_global",$row,array('uniacid'=>$_W['uniacid']));
        }
        if($res)
        show_message('操作成功！',webUrl('pinduoduo/diypage/diyfoot'), 'success');
        else
        show_message('操作失败！',webUrl('pinduoduo/diypage/diyfoot'), 'error');
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
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $row[$cloum]=json_encode($menu);
        if (empty($r)) {
            $row["uniacid"]=$_W["uniacid"];
            $row["created_at"]=time();
            $res = pdo_insert("nets_hjk_pdd_global",$row);
        }else{
            $row["updated_at"]=time();
            $res = pdo_update("nets_hjk_pdd_global",$row,array('uniacid'=>$_W['uniacid']));
        }
        if($res)
        show_message('操作成功！',webUrl('pinduoduo/diypage'), 'success');
        else
        show_message('操作失败！',webUrl('pinduoduo/diypage'), 'error');
        include $this->template('haojingke/pinduoduo/base/share');
    }

    public function getglobal(){
        global $_W;
        global $_GPC;
        $cloum=$_GPC["cloum"];
        $g=pdo_fetch("select * from ".tablename("nets_hjk_pdd_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
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
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        echo $r["homepage_itemjson"];
        
    }
    public function savepageitem(){
        global $_W;
        global $_GPC;
        $cloum=$_GPC["cloum"];
        $res=0;
        $pageitem=$_GPC["pageitemjson"];
        $pageitemhtml=$_GPC["pageitemhtml"];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $row1["itemjson"]=json_encode($pageitem);
        $row1["itemhtml"]=json_encode($pageitemhtml);
        $row1["title"]=$_GPC["title"];
        $row["homepage_itemjson"]=json_encode($row1);
        if (empty($r)) {
            $row["uniacid"]=$_W["uniacid"];
            $row["created_at"]=time();
            $res = pdo_insert("nets_hjk_pdd_global",$row);
        }else{
            $row["updated_at"]=time();
            $res = pdo_update("nets_hjk_pdd_global",$row,array('uniacid'=>$_W['uniacid']));
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
        $row["banner"]='{"banner_name":"\\u62fc\\u591a\\u591a\\u8f6e\\u64ad","itemtitle":"\\u62fc\\u591a\\u591a\\u8f6e\\u64ad","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/l1W5Uh88W63Z23334sM3sis83H64r8.jpg","name":"","outer_url":"","url":"../search/index?cid=14"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/xchYFy5q840qY8y8YZCZcKRCKf84Qd.jpg","name":"","outer_url":"","url":"../search/index?cid=818"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/ej4Sh0HZnjwXd5UbBuanvxn5VtC5M8.jpg","name":"","outer_url":"","url":"../search/index?cid=743"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/X5kdD91KY3O5Y6k2391kfyDNHDNhWW.jpg","name":"","outer_url":"","url":"../search/index?cid=13"}]}';
        $row["head_menu"]='{"head_menu_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/Lq08PQD0Qld84l0P4butQ4Qa020U02.jpg","name":"\\u7f8e\\u5986\\u4e2a\\u62a4","outer_url":"","url":"../search/index?cid=16"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/k6r9ZdLqGLg50LyLGrlL9O0H5gQqz9.jpg","name":"\\u5973\\u88c5","outer_url":"","url":"../search/index?cid=14"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/Jaz2E22s8L2A2as4ZEa02SRRR4AHEH.jpg","name":"\\u978b\\u54c1\\u7bb1\\u5305","outer_url":"","url":"../search/index?cid=1281"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/vRw71ff51cIrCz1CAWqCGCfq9Xc9x1.jpg","name":"\\u73e0\\u5b9d\\u9996\\u9970","outer_url":"","url":"../search/index?cid=14"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/InntKgrkKvN6705tNJ7t04T4kxRGkx.jpg","name":"\\u670d\\u9970\\u5185\\u8863","outer_url":"","url":"../search/index?cid=14"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/gZmF0AKFDC77nhr7rdyny5MfYFrmH7.jpg","name":"\\u6570\\u7801\\u5bb6\\u7535","outer_url":"","url":"../search/index?cid=15"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/DEc906NAfEi5du6R9uIae9H9eiuu8R.jpg","name":"\\u98df\\u54c1","outer_url":"","url":"../search/index?cid=818"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/zhCgv3HcGTaTcCh9SyDsdLS3ScZcH7.jpg","name":"\\u5c45\\u5bb6","outer_url":"","url":"../search/index?cid=818"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/E76P7N2Sp3P57567uhuPDyhNl7rr5l.jpg","name":"\\u6bcd\\u5a74\\u73a9\\u5177","outer_url":"","url":"../search/index?cid=4"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/Gha6tDN5RNlyuAxUtXnrHuL7eXhavn.jpg","name":"\\u8fd0\\u52a8\\u6237\\u5916","outer_url":"","url":"../search/index?cid=15"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/qJV1vJ190gUBcDvdbG5w5cZBjg51cD.jpg","name":"\\u751f\\u6d3b","outer_url":"","url":"../search/index?cid=15"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/Q6iDf2366Yf6DIM66FY2xMIX2rUiim.jpg","name":"\\u7537\\u88c5","outer_url":"","url":"../search/index?cid=743"}]}';
        $row["picture_menu"]='{"picture_menu_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/zw51KkWPeneRXKShXKTVntgVpRP3kX.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/NdFmh68MDhNd5ZJVF111np64M145N1.jpg","name":"","outer_url":"","url":"../search/index?cid=4"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/T38PzquM8gEUgU99e066RV8G83G682.jpg","name":"","outer_url":"","url":"../search/index?cid=14"}]}';
        $row["picture_menu2"]='{"picture_menu2_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/xxZ73FXz63kvRY2BXK7z3K3Y3YRn37.jpg","name":"\\u5b9e\\u65f6\\u6392\\u884c\\u699c","outer_url":"","url":"../choiceness/index?name=\\u5b9e\\u65f6\\u6392\\u884c\\u699c"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/JjYUWbieBp7H0e0eMJtYhuyYJePhhY.jpg","name":"\\u5168\\u5929\\u9500\\u552e\\u699c","outer_url":"","url":"../choiceness/index?name=\\u5168\\u5929\\u9500\\u552e\\u699c"}]}';
        $row["ad_menu"]='{"ad_menu_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/he040rW8j4BL7m8BL38M5MK05WjB2s.png","name":"","outer_url":"wx26f2e878166da5de","url":"../search/index?cid=1"}]}';
        $row["homepage_itemjson"]="";
        $row["isopen_diypage"]='0';
        $row["banner2"]='{"banner2_name":"\\u62fc\\u591a\\u591a\\u8f6e\\u64ad","itemtitle":"\\u62fc\\u591a\\u591a\\u8f6e\\u64ad","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/LF2Zt2Mg6b2AiVA67IwI2mTJZEXz00.jpg","name":"","outer_url":"","url":"../search/index?cid=14"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/c8Arh05rH50c8KG0nb555CEaA3AE7n.jpg","name":"","outer_url":"","url":"../search/index?cid=16"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/TSLbz2BL358403lt22s2MZ2tRzC5S2.png","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["banner3"]='{"banner3_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/FJkrju7X2dr13kw15q11jKW93SmGjr.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/gu3X5Y8Ux30W8WMXadd8xu8AO0pxa5.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/k1E49p4jv1T9XN045vZ5X9x35UU3p3.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["head_menu2"]='{"head_menu2_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/P4IzRCYRNk0Z0MGUROZ77rKrUZ9bRc.jpg","name":"\\u6570\\u7801\\u5bb6\\u7535","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/L5EL39J949L45EQ5l5Z9ECljIr293k.png","name":"\\u5c45\\u5bb6\\u751f\\u6d3b","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/MFtU1844tyR2zttDzo5kXf5yRe4C8K.jpg","name":"\\u6bcd\\u5a74\\u73a9\\u5177","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/tC9UZfyEsH3h3GKqNENaOzaQSEDUA6.jpg","name":"\\u7f8e\\u5986\\u4e2a\\u62a4","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/T2peM9zs6bNY91BymebS9R11mzRY9e.jpg","name":"\\u5973\\u88c5","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/xH737O77pAz0Y7a7NKfsp7O0L707GH.jpg","name":"\\u7537\\u88c5","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/T59fZOULMOFZ0uAAz4fXl5AtxOquql.jpg","name":"\\u670d\\u9970\\u5185\\u8863","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/12/uddCM08CfN1g8dd00fVnNmgrsDGyMr.jpg","name":"\\u6587\\u4f53\\u8f66\\u54c1","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/zyHT5ch5hhMWwwHuWuJW5hlB5Ty2ZM.jpg","name":"\\u978b\\u54c1\\u7bb1\\u5305","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/jxXHGCP6pXPCl9pG759pO5lrY9JpGp.jpg","name":"\\u8fd0\\u52a8\\u6237\\u5916","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/T6ZttUeFz6UUTTKUuctULmItt31tem.jpg","name":"\\u5c45\\u5bb6\\u751f\\u6d3b","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/PoC995i65FZoyC3PYX88b83x83o8i8.jpg","name":"\\u98df\\u54c1\\u9152\\u6c34","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/KefriktNFScKbPSC9cSP6ZPT4Qaq9Q.png","name":"\\u6570\\u7801\\u5bb6\\u7535","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/C87j7s67BSVs5RgJdO76jz7NRSd8K6.jpg","name":"\\u780d\\u4ef7","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/C87j7s67BSVs5RgJdO76jz7NRSd8K6.jpg","name":"\\u62fc\\u8d2d","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/C87j7s67BSVs5RgJdO76jz7NRSd8K6.jpg","name":"9.9","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["head_menu3"]=$row["head_menu"];//'{"head_menu_name":"\\u5934\\u90e8\\u83dc\\u5355","sort":null,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/DF1f2OfKzPch6pmF2ok61Qc6M4ZMfl.jpg","name":"\\u7f8e\\u5986\\u4e2a\\u62a4","outer_url":"","url":"../choiceness/index?name=\\u7f8e\\u5986\\u4e2a\\u62a4=2=5795"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/EZM8QYuD7E3N08yu8lE7VjM0Ey6q7E.jpg","name":"\\u5973\\u88c5","outer_url":"","url":"../choiceness/index?name=\\u5973\\u88c5=2=5799"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/Fb1kkcWtSc16Te1j2Jb6jfBf1PPFki.jpg","name":"\\u978b\\u54c1\\u7bb1\\u5305","outer_url":"","url":"../choiceness/index?name=\\u978b\\u54c1\\u7bb1\\u5305=2=5792"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/rI0Rk5250TT0N6TC3XX4xz0KrR4RI0.jpg","name":"\\u73e0\\u5b9d\\u9996\\u9970","outer_url":"","url":"../choiceness/index?name=\\u65f6\\u5c1a\\u914d\\u9970=2=5788"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/Nx6OLHC1wSHlgFzowcc9CCn86XCw8W.jpg","name":"\\u670d\\u9970\\u5185\\u8863","outer_url":"","url":"../choiceness/index?name=\\u670d\\u9970\\u5185\\u8863=2=5797"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/MPbEfjgPhG6Hp0gbjpv04PB0aAfBPF.png","name":"\\u6570\\u7801\\u5bb6\\u7535","outer_url":"","url":"../choiceness/index?name=\\u6570\\u7801\\u529e\\u516c=2=5796"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/us192Yl29xYOsSLoFPlsn7X2XP6nGf.jpg","name":"\\u98df\\u54c1","outer_url":"","url":"../choiceness/index?name=\\u98df\\u54c1\\u9152\\u6c34=2=5793"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/AQ0QKXROY4jxoy8poo5vEiX1Y45e0y.jpg","name":"\\u5c45\\u5bb6","outer_url":"","url":"../choiceness/index?name=\\u5c45\\u5bb6\\u751f\\u6d3b=2=5789"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/rGMM5KRQpr56g5GDmgiDMkDRRikKMQ.jpg","name":"\\u6bcd\\u5a74\\u73a9\\u5177","outer_url":"","url":"../choiceness/index?name=\\u6bcd\\u5a74\\u73a9\\u5177=2=5794"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/R77uY737HAHTzDq7m7W3wga3avd2gw.png","name":"\\u7535\\u8111\\u529e\\u516c","outer_url":"","url":"../choiceness/index?name=\\u597d\\u8d27\\u60c5\\u62a5\\u5c40=3=3451"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/tQX8Q11t11RSV21u18POqtph2ZHXXQ.jpg","name":"\\u8fd0\\u52a8\\u6237\\u5916","outer_url":"","url":"../choiceness/index?name=\\u8fd0\\u52a8\\u6237\\u5916=2=5800"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/z0jwHfPFwfp7u7iunU4U79UfH4TiIw.png","name":"\\u751f\\u6d3b","outer_url":"","url":"../choiceness/index?name=\\u5c45\\u5bb6\\u751f\\u6d3b=2=5789"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/jfCF9j3d9J9l6E91DYCEC2Cf26FEe9.jpg","name":"\\u780d\\u4ef7","outer_url":"","url":"../choiceness/index?name=\\u780d\\u4ef7"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/Mq06w6uE7jwILSble1qBlqnwn6U4wN.jpg","name":"\\u62fc\\u8d2d","outer_url":"","url":"../choiceness/index?name=\\u62fc\\u8d2d"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/XZzZcRCvr68JdmpR5XH86xRM6vQxV2.jpg","name":"\\u4fdd\\u5065","outer_url":"wx2437c32c7bf7f9de","url":"../choiceness/index?name=\\u54c1\\u724c\\u4f18\\u9009=3=3445"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/10/cBC9cckMtzcIiHHIQITi7DuoTEqi33.jpg","name":"\\u7537\\u88c5","outer_url":"","url":"../choiceness/index?name=\\u7537\\u88c5=2=5790"},{"img":"http://demo.91fyt.com/attachment/images/67/2017/11/S9ST9KtKOjTYJVDUqSqWkJG7osqQZJ.jpg","name":"\\u699c\\u5355","outer_url":"","url":"../choiceness/index?name=\\u699c\\u5355"}]}';
        $row["head_menu4"]='{"head_menu4_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/gZmF0AKFDC77nhr7rdyny5MfYFrmH7.jpg","name":"\\u6570\\u7801\\u5bb6\\u7535","outer_url":"","url":"../search/index?cid=18"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/L5EL39J949L45EQ5l5Z9ECljIr293k.png","name":"\\u5c45\\u5bb6\\u751f\\u6d3b","outer_url":"","url":"../search/index?cid=13"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/MFtU1844tyR2zttDzo5kXf5yRe4C8K.jpg","name":"\\u6bcd\\u5a74\\u73a9\\u5177","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/tC9UZfyEsH3h3GKqNENaOzaQSEDUA6.jpg","name":"\\u7f8e\\u5986\\u4e2a\\u62a4","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/T2peM9zs6bNY91BymebS9R11mzRY9e.jpg","name":"\\u5973\\u88c5","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/xH737O77pAz0Y7a7NKfsp7O0L707GH.jpg","name":"\\u7537\\u88c5","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/T59fZOULMOFZ0uAAz4fXl5AtxOquql.jpg","name":"\\u670d\\u9970\\u5185\\u8863","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/12/uddCM08CfN1g8dd00fVnNmgrsDGyMr.jpg","name":"\\u6587\\u4f53\\u8f66\\u54c1","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/zyHT5ch5hhMWwwHuWuJW5hlB5Ty2ZM.jpg","name":"\\u978b\\u54c1\\u7bb1\\u5305","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/jxXHGCP6pXPCl9pG759pO5lrY9JpGp.jpg","name":"\\u8fd0\\u52a8\\u6237\\u5916","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/T6ZttUeFz6UUTTKUuctULmItt31tem.jpg","name":"\\u5c45\\u5bb6\\u751f\\u6d3b","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/10/PoC995i65FZoyC3PYX88b83x83o8i8.jpg","name":"\\u98df\\u54c1\\u9152\\u6c34","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/KefriktNFScKbPSC9cSP6ZPT4Qaq9Q.png","name":"\\u6570\\u7801\\u5bb6\\u7535","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/C87j7s67BSVs5RgJdO76jz7NRSd8K6.jpg","name":"\\u780d\\u4ef7","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/C87j7s67BSVs5RgJdO76jz7NRSd8K6.jpg","name":"\\u62fc\\u8d2d","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/56/2017/11/C87j7s67BSVs5RgJdO76jz7NRSd8K6.jpg","name":"9.9","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["ad_menu2"]='{"ad_menu2_name":"\\u767e\\u5927\\u54c1\\u724c\\u7279\\u5356\\u8fdb\\u884c\\u65f6","itemtitle":"\\u767e\\u5927\\u54c1\\u724c\\u7279\\u5356\\u8fdb\\u884c\\u65f6","itemremark":"\\u9650\\u65f6\\u6298\\u6263  \\u5929\\u5929\\u4e0d\\u9650\\u91cf","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/VbbrTq37eeSb0BB3S0vwRZtieqJ53V.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["ad_menu3"]='{"ad_menu3_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/yXBl59x0eA0oBJlnJ1L9QdQj1NE1l1.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["picture_menu3"]='{"picture_menu3_name":"\\u8d2d\\u7269\\u5708","itemtitle":"\\u8d2d\\u7269\\u5708","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/UZdDb3PYsoOpYb1HHdd3hB2DWxOZZK.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/H7pj26jqba755IP0YqA4q5aA2pb2bz.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/LEPDu2tzT8rr2uudKGdUJdeTP8rkij.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["picture_menu4"]='{"picture_menu4_name":"\\u70ed\\u95e8\\u9891\\u9053","itemtitle":"\\u70ed\\u95e8\\u9891\\u9053","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/X31al4411AG41uIfOu3lGZllz7lJlL.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/XQXCN995n4nxuXxjrpuTNx0NJCjTrx.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/K6G5WNWbW32Piu3zIAkaOoP2GwNaTk.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/A4rxAx4PSa3k2w4pz44rw22A2OoA5Q.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["picture_menu5"]='{"picture_menu5_name":"\\u7cbe\\u9009\\u7c7b\\u76ee","itemtitle":"\\u7cbe\\u9009\\u7c7b\\u76ee","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/VZMlJgZkTfo8LXMj1JVMgi1QZjVFW1.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/UmErSVMYrxyPSUpPSY5spyHZPXZ5cS.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/TFY2757iGbG775772bL9R4179Kf9i2.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/lGZ2bPkTwddfdWwJtlttvXgBkDdVvG.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/lofzD90j8oAOAO8RkrdIQ88O989AQ8.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/VqqVVZKglQv9larknRLR3atCbWVKTk.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["picture_menu6"]='{"picture_menu6_name":"\\u8d2d \\u7cbe\\u9009","itemtitle":"\\u8d2d \\u7cbe\\u9009","itemremark":"SELECTED","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/B3h2H9m1q4SJj1QD2ZADFcp1PN1A11.jpg","name":"","outer_url":"","url":"../choiceness/index?name=\\u670d\\u9970\\u5185\\u8863=2=5841"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/wc4aR9mrmHxRgHaHL2OpCHOhHapleh.jpg","name":"","outer_url":"","url":"../choiceness/index?name=\\u5c45\\u5bb6\\u751f\\u6d3b=2=5845"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/I9ByP1U4888k82Zu4t48it4114VbHt.jpg","name":"","outer_url":"","url":"../choiceness/index?name=\\u6bcd\\u5a74\\u73a9\\u5177=2=5848"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/h1c1s1771792tTP2c915TGpt75TnTq.jpg","name":"","outer_url":"","url":"../choiceness/index?name=\\u670d\\u9970\\u5185\\u8863=2=5841"}]}';
        $row["picture_menu7"]='{"picture_menu7_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/z1Z45I1xdiZWPX9z541D9W19DWzZ14.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/vl7fs33dP93Nm99ZNmj9FZzuu7j3LZ.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/fIu4F4UA0J0v1PV4V9K5vTN79E1NFZ.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/03/nQD6Kz84KztZQqDtoMGHht8wwqs1Sw.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';
        $row["picture_menu8"]='{"picture_menu8_name":"","itemtitle":"","itemremark":"","menu_tpl":null,"sort":0,"list":[{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/cmttA4Om7HBKBDL4BeoA44t33o4ZqM.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/Ix5X0p8uw5xs3Sp25yxxlL0pN5S95p.jpg","name":"","outer_url":"","url":"../search/index?cid=1"},{"img":"http://demo.91fyt.com/attachment/images/67/2018/04/F76Mc4XUyE55uSQnuxZ5ZN37K3JNe4.jpg","name":"","outer_url":"","url":"../search/index?cid=1"}]}';

        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_pdd_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        if (empty($r)) {
            $row["uniacid"]=$_W["uniacid"];
            $row["created_at"]=time();
            $res = pdo_insert("nets_hjk_pdd_global",$row);
        }else{
            $row["updated_at"]=time();
            $res = pdo_update("nets_hjk_pdd_global",$row,array('uniacid'=>$_W['uniacid']));
        }
        show_message('操作成功！',webUrl('pinduoduo/diypage'), 'success');
    }
}
?>