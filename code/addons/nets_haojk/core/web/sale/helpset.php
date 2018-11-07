<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.helpset.func.php';
class Helpset_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='sale.helpset.index'||$_W['action']=='sale.helpset')
            $this->index();
        elseif ($_W['action']=='sale.helpset.helpset_add')
            $this->helpset_add();
        elseif ($_W['action']=='sale.helpset.helpset_addpost')
            $this->helpset_addpost();
        elseif ($_W['action']=='sale.helpset.helpset_delete')
            $this->helpset_delete();
	}

	//待发记录
	public function index()
	{
        global $_GPC, $_W;
        $res=helpset_list();
        include $this->template('haojingke/sale/helpset/index');
	}

    //群发(商品)消息-添加
    public function helpset_add()
    {
        global $_W;
        global $_GPC;
        $help=pdo_fetch("select * from ".tablename('nets_hjk_helpset')." where id=:id",array(":id"=>$_GPC['id']));
        $help_h="1";
        $help_m="00";
        if(!empty($help)){
            $help_h=explode(":",$help["endtime"])[0];
            $help_m=explode(":",$help["endtime"])[1];
        }
        $helppicture=explode(",",$help['picture']);
        include $this->template('haojingke/sale/helpset/helpedit');
    }
    //群发(商品)消息-添加提交
    public function helpset_addpost()
    {
        global $_W;
        global $_GPC;
        $i=helpset_edit();
        show_json(1,"操作成功");
    }

    
    public function helpset_delete()
    {
        global $_W;
        global $_GPC;
        if(!empty($_GPC['id'])){
            //删除发送失败
            $help=pdo_fetch("select * from ".tablename('nets_hjk_helpset')." where id=:id",array(":id"=>$_GPC['id']));
            $data['startime']=-1;//活动停用的
            if($help['startime']<0){
                $data['startime']=0;//活动开始的    
            }
            $j=pdo_update("nets_hjk_helpset",$data,array("id"=>$help['id']));
            if ($j>0) {
                show_json(1,"操作成功");
            }else{
                show_json(1,"操作失败");
            }
        }
        show_json(1,"信息错误，此条记录可能已删除，请刷新页面");
    }

	
}
?>