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

		if ($_W['action']=='sale.index'||$_W['action']=='sale')
            $this->index();
        elseif ($_W['action']=='sale.barrage')
            $this->barrage();
        elseif ($_W['action']=='sale.barrage_add')
            $this->barrage_add();
        elseif ($_W['action']=='sale.barrage_addpost')
            $this->barrage_addpost();
        elseif ($_W['action']=='sale.barrage_delete')
            $this->barrage_delete();
        elseif ($_W['action']=='sale.barrage_init')
            $this->barrage_init();
        elseif ($_W['action']=='sale.barrage_updatetip')
            $this->barrage_updatetip();
        elseif ($_W['action']=='sale.freegoods')
            $this->freegoods();
        elseif ($_W['action']=='sale.freegoods_addpost')
            $this->freegoods_addpost();
        elseif ($_W['action']=='sale.freegoods_delete')
            $this->freegoods_delete();
        elseif ($_W['action']=='sale.freegoods_update')
            $this->freegoods_update();
        elseif ($_W['action']=='sale.entrancead')
            $this->entrancead();
	}
	//
	public function index()
	{
        global $_GPC, $_W;
        include $this->template('haojingke/sale/index');
	}
    //入口场景广告
    public function entrancead()
    {
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            //echo $_GPC['entrancead_status'];exit;
            $set['entrancead_status']  = $_GPC['entrancead_status']=="on"?1:0;
            $set['entrancead_pic'] = tomedia($_GPC['entrancead_pic']);
            $set['entrancead_jump'] = $_GPC['entrancead_jump'];
            $set['updated_at'] = time();
            if (empty($r)) {
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sale/entrancead'), 'success');
            else
                show_message('操作失败！',webUrl('sale/entrancead'), 'warning');
        }
        include $this->template('haojingke/sale/entrancead');
    }
    //弹幕订单-列表
    public function barrage()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $page=1;
        if(!empty($_GPC["page"]))
            $page = $_GPC["page"];
        $pagesize=20;
        $where='';
        //筛选
        if (!empty($_GPC['keyword'])) {
            $where.= " and (nickname like '%".$_GPC['keyword']."%' or tip like '%".$_GPC['keyword']."%')";
        }
        $total = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_ordertip")."  WHERE uniacid=:uniacid ".$where." ",array(':uniacid'=>$_W['uniacid']));
        $list = pdo_fetchall("SELECT * FROM ".tablename('nets_hjk_ordertip')." WHERE uniacid=:uniacid ".$where." ORDER BY id limit ". (($page - 1) * $pagesize) . ',' . $pagesize,array(':uniacid'=>$_W['uniacid']));
        $pager = pagination($total, $page, $pagesize);
        include $this->template('haojingke/sale/barrage');
    }
    //弹幕订单-添加
    public function barrage_add()
    {
        global $_W;
        global $_GPC;
        include $this->template('haojingke/sale/barrage_add');
    }
    //弹幕订单-添加提交
    public function barrage_addpost()
    {
        global $_W;
        global $_GPC;
        $mark['nickname'] = $_GPC['nickname'];
        $mark['uniacid'] = $_W['uniacid'];
        $mark['avatar'] = $_W['attachurl'].$_GPC['avatar'];
        $mark['tip'] = $_GPC['tip'];
        $mark['createtime'] = time();
        $r = pdo_insert("nets_hjk_ordertip",$mark);
        if($r)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //弹幕订单-添加提交
    public function barrage_updatetipbyid()
    {
        global $_W;
        global $_GPC;
        if(empty($_GPC['tip']))
            show_json(1,"提示语不可为空");
        if(empty($_GPC['id']))
            show_json(1,"信息错误，此条提示语可能已删除，请刷新页面");
        $res['tip'] = $_GPC['tip'];
        $res['createtime'] = time();
        $r = pdo_update('nets_hjk_ordertip',$res,array('uniacid'=>$_W['uniacid'],'id'=>$_GPC['id']));
        if($r)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //弹幕订单-删除
    public function barrage_delete()
    {
        global $_W;
        global $_GPC;
        if(empty($_GPC['id']))
            show_json(1,"信息错误，此条提示语可能已删除，请刷新页面");
        $del_marketing = pdo_delete('nets_hjk_ordertip',array('id'=>$_GPC['id']));
        if($del_marketing)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //弹幕订单-初始化
    public function barrage_init()
    {
        global $_W;
        global $_GPC;
        $marketing =  pdo_fetchall("SELECT avatar,nickname,uniacid FROM ims_nets_hjk_members WHERE uniacid=".$_W['uniacid']." AND avatar != '' ORDER BY rand(id) LIMIT 500");
        foreach ($marketing as $key => $value) {
            $r  = pdo_insert('nets_hjk_ordertip',$value);
        }
        if($r)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //弹幕订单-批量修改提示语
    public function barrage_updatetip()
    {
        global $_W;
        global $_GPC;
        $res['tip'] = $_GPC['tip'];
        $r = pdo_update('nets_hjk_ordertip',$res,array('uniacid'=>$_W['uniacid']));
        if($r)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //免单商品
    public function freegoods()
    {
        global $_GPC, $_W;
        $freeorderfile=IA_ROOT . '/addons/nets_haojk/function/freeorder.func.php';
        if(file_exists($freeorderfile)){
            require_once IA_ROOT . '/addons/nets_haojk/function/freeorder.func.php';
        }else{
            show_message('当前版本不支持此功能！请先升级！', '', 'warning');
        }

        $page=1;
        if(!empty($_GPC["page"]))
            $page = $_GPC["page"];
        $pagesize=20;
        $where=" WHERE uniacid=".$_W['uniacid'];
        //筛选
        if (!empty($_GPC['keyword'])) {
            $where.= " and (skuId like '%".$_GPC['keyword']."%' or skuName like '%".$_GPC['keyword']."%')";
        }
        $total = pdo_fetchcolumn("select count(0) from ".tablename("nets_hjk_freegoods").$where." ");
        $list=pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_freegoods").$where." order by sort desc LIMIT ".(($page-1)*$pagesize).",".$pagesize);
        $pager = pagination($total, $page, $pagesize);
        include $this->template('haojingke/sale/freegoods');
    }
    //免单商品-添加
    public function freegoods_addpost()
    {
        global $_W;
        global $_GPC;
        $freeorderfile=IA_ROOT . '/addons/nets_haojk/function/freeorder.func.php';
        if(file_exists($freeorderfile)){
            require_once IA_ROOT . '/addons/nets_haojk/function/freeorder.func.php';
        }else{
            show_json(1,'当前版本不支持此功能！请先升级！');
        }

//        $goods=json_decode(htmlspecialchars_decode($_GPC['goods']));
        $_GPC['goods']=str_replace('&quot;','"',$_GPC['goods']);
        $goods=json_decode($_GPC['goods']);
        //var_dump($goods);
        $detail=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_freegoods")." WHERE uniacid=:uniacid and skuId=:skuId",array(":uniacid"=>$_W['uniacid'],":skuId"=>$goods->skuId));
        if(!empty($detail))
            show_json(1,"已经存在免单商品库");
        $cuttingnum = $_GPC['cuttingnum'];
        
        $i = setGoodsFreeOrder($goods,$cuttingnum);
        if($i)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }

    //免单商品-添加
    public function freegoods_delete()
    {
        global $_W;
        global $_GPC;
        $id=$_GPC['id'];
        if(empty($id))
            show_json(1,"免单商品不存在或已删除，请刷新页面");
        $i=pdo_delete('nets_hjk_freegoods',array('id'=>$id));
        if($i)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
    //免单商品-更新
    public function freegoods_update()
    {
        global $_W;
        global $_GPC;
        $id=$_GPC['id'];
        if(empty($id))
            show_json(1,"免单商品不存在或已删除，请刷新页面");
        $i=pdo_update('nets_hjk_freegoods',array("sort"=>$_GPC['sort'],"cuttingnum"=>$_GPC['cuttingnum']),array('id'=>$id));
        if($i)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
	
}
?>