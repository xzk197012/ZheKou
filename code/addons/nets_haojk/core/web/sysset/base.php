<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Base_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

        if ($_W['action']=='sysset.base.index'||$_W['action']=='sysset.base')
            $this->index();
        elseif ($_W['action']=='sysset.base.share')
            $this->share_set();
        elseif ($_W['action']=='sysset.base.appentry')
            $this->appentry();
        elseif ($_W['action']=='sysset.base.indexset')
            $this->indexset();
        elseif ($_W['action']=='sysset.base.coupontype')
            $this->coupontype();
        elseif ($_W['action']=='sysset.base.moduletype')
            $this->moduletype();
	}
	public function index()
	{
		global $_W;
		global $_GPC;
        $uniacid=$_W['uniacid'];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['wxapp_uniacid']  = $_GPC['wxapp_uniacid'];
            $set['notice_tplno_app'] = $_GPC['notice_tplno_app'];
            $set['service_msg'] = $_GPC['service_msg'];
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/base/index'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/base/index'), 'warning');
        }
        include $this->template('haojingke/sysset/base/index');
	}

    public function share_set()
    {
        global $_W;
        global $_GPC;
        $haibao=MODULE_URL."cache/bg.png";
        $uniac_haibao=MODULE_URL."cache/".$_W['uniacid']."_bg.png";
        $uniac_haibao_root=MODULE_ROOT."/cache/".$_W['uniacid']."_bg.png";
//echo $uniac_haibao;
        if(file_exists($uniac_haibao_root)){
            $haibao=$uniac_haibao;
        }
        $haibao_root=MODULE_ROOT."/cache/".$_W['uniacid']."_bg.png";
        if(!empty($_GPC["haibao"]) && $_GPC["haibao"]!=$haibao){
            //替换海报图片
            $imgpath=ATTACHMENT_ROOT.$_GPC["haibao"];
            if( file_exists($imgpath)){
                copy($imgpath, $haibao_root);
            }else{
				$url=$_W['setting']['remote']['qiniu']['url'];
				if($_W['setting']['remote']['type']==1){
					$url=$_W['setting']['remote']['ftp']['url'];
				}
				if($_W['setting']['remote']['type']==2){
					$url=$_W['setting']['remote']['alioss']['url'];
				}
				if($_W['setting']['remote']['type']==3){
					$url=$_W['setting']['remote']['qiniu']['url'];
				}
				if($_W['setting']['remote']['type']==4){
					$url=$_W['setting']['remote']['cos']['url'];
				}
                $remoturl=$url."/".$_GPC["haibao"];
				unlink ($haibao_root);
                GrabImage($remoturl,$haibao_root);
            }
            //$filename = basename($sourcefile);
        }
        $uniacid=$_W['uniacid'];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $data['title'] = $r['title'];
        $data['remark'] = $r['remark'];

        if ($_W['ispost']==1) {
            $set['isuse_parent'] = $_GPC['isuse_parent'];
            if($_GPC['isuse_parent']==2){
                $set['isuse_parent']=0;
            }
            if ($r['logo'] == $_GPC['logo']) {
                $set['logo'] = $_GPC['logo'];
            }else{
                $set['logo'] = $_W['attachurl'].$_GPC['logo'];
            }
            if ($r['kefuqr'] == $_GPC['kefuqr']) {
                $set['kefuqr'] = $_GPC['kefuqr'];
            }else{
                $set['kefuqr'] = $_W['attachurl'].$_GPC['kefuqr'];
            }
            $set['title']  = $_GPC['title'];
            $set['remark']  = $_GPC['remark'];
            $set['subscribeurl'] = $_GPC['subscribeurl'];
            $set['exqrtype'] = $_GPC['exqrtype'];
            $set['goodposter'] = $_GPC['goodposter'];
            $set['goodsqrtype'] = $_GPC['goodsqrtype'];
            $set['isshow_detail'] = $_GPC['isshow_detail'];
            if ($_GPC['isshow_detail']==2) {
                $set['isshow_detail'] = 0;
            }
            $set['created_at'] = time();
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/base/share'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/base/share'), 'warning');
        }
        include $this->template('haojingke/sysset/base/share');
    }

    public function appentry()
    {
        include $this->template('haojingke/sysset/base/appentry');
    }
    public function indexset()
	{
		global $_W;
		global $_GPC;
        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu')." where uniacid=:uniacid",array(':uniacid'=>$_W['uniacid']));
        
        $jsonrecord=json_encode($record);
        $global = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        if ($_W['ispost']==1) {
            $set['homepage_status']  = $_GPC['homepage_status'];
            
            if (empty($global)) {
                $set['uniacid'] = $_W['uniacid'];
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/base/indexset'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/base/indexset'), 'warning');
        }
        include $this->template('haojingke/sysset/base/indexset');
    }
    public function coupontype()
	{
		global $_W;
		global $_GPC;
        $uniacid=$_W['uniacid'];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['hjkappid']  = $_GPC['hjkappid'];
            $set['couptype']  = $_GPC['couptype'];
            if (empty($r)) {
                $set['uniacid'] = $_W['uniacid'];
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/base/coupontype'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/base/coupontype'), 'warning');
        }
        include $this->template('haojingke/sysset/base/coupontype');
    }
    public function moduletype()
	{
		global $_W;
		global $_GPC;
        $uniacid=$_W['uniacid'];
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['jdmodule_status']  = $_GPC['jdmodule_status']=="on"?1:0;
            $set['pddmodule_status']  = $_GPC['pddmodule_status']=="on"?1:0;
            $set['mgjmodule_status']  = $_GPC['mgjmodule_status']=="on"?1:0;
            //var_dump($set);exit;
            if (empty($r)) {
                $set['uniacid'] = $_W['uniacid'];
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/base/moduletype'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/base/moduletype'), 'warning');
        }
        include $this->template('haojingke/sysset/base/moduletype');
	}
}
?>