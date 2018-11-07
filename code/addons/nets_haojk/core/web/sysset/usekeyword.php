<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Usekeyword_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='sysset.usekeyword.index'||$_W['action']=='sysset.usekeyword')
            $this->index();
        elseif ($_W['action']=='sysset.usekeyword.add')
            $this->usekeyword_add();
        elseif ($_W['action']=='sysset.usekeyword.edit')
            $this->usekeyword_edit();
        elseif ($_W['action']=='sysset.usekeyword.start')
            $this->usekeyword_start();
        elseif ($_W['action']=='sysset.usekeyword.stop')
            $this->usekeyword_stop();
        elseif ($_W['action']=='sysset.usekeyword.delete')
            $this->usekeyword_delete();
	}
	public function index()
	{
		global $_W;
        global $_GPC;
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        if ($_W['ispost']==1) {
            
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
                show_message('操作成功！',webUrl('sysset/usekeyword/index'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/usekeyword/index'), 'warning');
        }
        $uniacid=$_W['uniacid'];
        $result = '';
        $data = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_keyword'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        include $this->template('haojingke/sysset/usekeyword/index');
	}
    public function usekeyword_edit()
	{
		global $_W;
		global $_GPC;
        $uniacid=$_W['uniacid'];
        $result = '';
        $data=array();
        if(!empty($_GPC["id"])){
            $data = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_keyword'). " WHERE uniacid =:uniacid and id=:id",array(':uniacid'=>$_W['uniacid'],':id'=>$_GPC['id']));
        }
        if ($_W['ispost']==1) {
            $d['uniacid']=$_W["uniacid"];
            $d["keyword"]=$_GPC["keyword"];
            $d["title"]=$_GPC["title"];
            if ($data['picture'] == $_GPC['picture']) {
                $d['picture'] = $_GPC['picture'];
            }else{
                $d['picture'] = $_W['attachurl'].$_GPC['picture'];
            }
            $d['remark']=$_GPC['remark'];
            $d['content']=$_GPC['content'];
            $d['state']=1;
            $d['created_at']=time();
            if(!empty($_GPC["id"])){
                $r["content"]=$_GPC["keyword"];
                pdo_update("rule_keyword",$r,array("module"=>"nets_haojk","uniacid"=>$_W["uniacid"],"content"=>$data["keyword"]));
                $res=pdo_update("nets_hjk_keyword",$d,array("id"=>$_GPC['id']));
            }else{
                $oldr=pdo_get("rule_keyword",array("module"=>"nets_haojk","uniacid"=>$_W["uniacid"],"content"=>$_GPC["keyword"]));
                if(empty($oldr)){
                    $r["uniacid"]=$_W["uniacid"];
                    $r["module"]="nets_haojk";
                    $r["status"]=1;
                    $i=pdo_insert("rule",$r);
                    $r["rid"]=pdo_insertid();
                    $r["content"]=$_GPC["keyword"];
                    $r["type"]=1;
                    pdo_insert("rule_keyword",$r);
                }
                $res=pdo_insert("nets_hjk_keyword",$d);
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/usekeyword/index'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/usekeyword/index'), 'warning');
        }
        include $this->template('haojingke/sysset/usekeyword/post');
	}
    public function usekeyword_start()
	{
		global $_W;
		global $_GPC;
        $uniacid=$_W['uniacid'];
        $result = '';
        $data=array();
        if(!empty($_GPC["id"])){
            $d['state']=1;
            $d['created_at']=time();
            $res=pdo_update("nets_hjk_keyword",$d,array("id"=>$_GPC['id']));
        }
        if($res)
            show_json(1,'操作成功！');
        else
            show_json(0,'操作失败！');

        include $this->template('haojingke/sysset/usekeyword/edit');
    }
    public function usekeyword_stop()
	{
		global $_W;
		global $_GPC;
        $uniacid=$_W['uniacid'];
        $result = '';
        $data=array();
        if(!empty($_GPC["id"])){
            $d['state']=0;
            $d['created_at']=time();
            $res=pdo_update("nets_hjk_keyword",$d,array("id"=>$_GPC['id']));
        }
        if($res)
            show_json(1,'操作成功！');
        else
            show_json(0,'操作失败！');

        include $this->template('haojingke/sysset/usekeyword/edit');
    }
    public function usekeyword_delete()
	{
		global $_W;
		global $_GPC;
        $uniacid=$_W['uniacid'];
        $result = '';
        $data=array();
        if(!empty($_GPC["id"])){
            $res=pdo_delete("nets_hjk_keyword",array("id"=>$_GPC['id']));
        }
        if($res)
            show_json(1,'操作成功！');
        else
            show_json(0,'操作失败！');
        include $this->template('haojingke/sysset/usekeyword/edit');
	}


}
?>