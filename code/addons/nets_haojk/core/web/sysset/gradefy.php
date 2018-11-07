<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Gradefy_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='sysset.gradefy.index'||$_W['action']=='sysset.gradefy')
            $this->gradefy();
        elseif ($_W['action']=='sysset.gradefy.add')
            $this->gradefyadd();
        elseif ($_W['action']=='sysset.gradefy.post')
            $this->gradefypost();
        elseif ($_W['action']=='sysset.gradefy.start')
            $this->gradefystart();
        elseif ($_W['action']=='sysset.gradefy.stop')
            $this->gradefystop();
        elseif ($_W['action']=='sysset.gradefy.init')
            $this->gradefyinit();
//		else
//		{
//			header('location: ' . webUrl());
//		}
	}

    public function gradefy()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        //验证是否存在合伙人的等级记录,不存在添加一条初始化记录
        $checksql="select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and type=2";//合伙人的记录
        $levelitem=pdo_fetch($checksql,array(":uniacid"=>$_W["uniacid"]));
        if(empty($levelitem)){
            //添加合伙人的记录
            $res2['name'] = 0;
            $res2['type'] = 2;//合伙人
            $res2['sign_credit1'] = 10;
            $res2['order_credit1'] = 10;
            $res2['releader_credit1'] = 10;
            $res2['refriend_credit1'] = 10;
            $res2['myteam_credit2'] = 9;
            $res2['myleader1_credit2'] = 5;
            $res2['myleader2_credit2'] = 4;
            $res2['commission_formonth'] = 3;
            $res2['recharge_get'] = 999;
            $res2['show_recharge_get'] = 999;
            $res2['show_sign_credit1'] = 0;
            $res2['show_order_credit1'] = 0;
            $res2['show_releader_credit1'] = 0;
            $res2['show_refriend_credit1'] = 0;
            $res2['show_commission_formonth'] = 8;
            $res2['show_myteam_credit2'] = 9;
            $res2['show_myleader1_credit2'] = 5;
            $res2['show_myleader2_credit2'] = 4;
            $res2['isuse'] = 0;
            $res2['uniacid'] = $_W['uniacid'];
            $j = pdo_insert('nets_hjk_memberlevel',$res2);
        }
        $data = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_memberlevel')." WHERE uniacid=:uniacid ",array(':uniacid'=>$uniacid));
        include $this->template('haojingke/sysset/gradefy/index');
    }

    public function gradefyadd()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        if(!empty($_GPC["id"])){
            $leve = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_memberlevel')." WHERE uniacid=:uniacid AND id=:id ",array(':uniacid'=>$uniacid,":id"=>$_GPC["id"]));
        }
        include $this->template('haojingke/sysset/gradefy/gradefy');
    }


    public function gradefypost()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        if ($_GPC['ispost']==1) {
            $record['uniacid'] = $uniacid;
            $record['name'] = $_GPC['name'];
            $record['type'] = $_GPC['type'];
            $record['sign_credit1'] = $_GPC['sign_credit1'];
            $record['order_credit1'] = $_GPC['order_credit1'];
            $record['releader_credit1'] = $_GPC['releader_credit1'];
            $record['refriend_credit1'] = $_GPC['refriend_credit1'];
            $record['myteam_credit2'] = $_GPC['myteam_credit2'];
            $record['myleader1_credit2'] = $_GPC['myleader1_credit2'];
            $record['myleader2_credit2'] = $_GPC['myleader2_credit2'];
            $record['commission_formonth'] = $_GPC['commission_formonth'];
            $record['recharge_get'] = $_GPC['recharge_get'];

            $record['show_recharge_get'] = $_GPC['show_recharge_get'];
            $record['show_sign_credit1'] = $_GPC['show_sign_credit1'];
            $record['show_order_credit1'] = $_GPC['show_order_credit1'];
            $record['show_releader_credit1'] = $_GPC['show_releader_credit1'];
            $record['show_refriend_credit1'] = $_GPC['show_refriend_credit1'];
            $record['show_myteam_credit2'] = $_GPC['show_myteam_credit2'];
            $record['show_myleader1_credit2'] = $_GPC['show_myleader1_credit2'];
            $record['show_myleader2_credit2'] = $_GPC['show_myleader2_credit2'];
            $record['show_commission_formonth'] = $_GPC['show_commission_formonth'];
            $record['max_credit2'] = $_GPC['max_credit2'];
            $record['show_max_credit2'] = $_GPC['show_max_credit2'];
            $record['updated_at'] = time();
            // if(empty($record['myteam_credit2'])){
            // 	message('会员佣金比例不能为空！',"","error");
            // }
            // if(empty($record['myleader1_credit2'])){
            // 	message('一级盟主佣金比例不能为空！',"","error");
            // }
            // if(empty($record['myleader2_credit2'])){
            // 	message('二级盟主佣金比例不能为空！',"","error");
            // }
            $record['gradename'] = $_GPC['gradename'];
            $record['identityname'] = $_GPC['identityname'];
//            $record['isuse'] = $_GPC['isuse'];
            if (empty($_GPC["id"])) {
                $record['created_at'] = time();
                $res = pdo_insert("nets_hjk_memberlevel",$record);
            }else{
                $res = pdo_update("nets_hjk_memberlevel",$record,array("id"=>$_GPC["id"]));
            }
            if($res)
                show_json(1,"操作成功");
            else
                show_json(1,"操作失败");
        }
    }


    public function gradefystart()
    {
        global $_W;
        global $_GPC;
        $record['isuse'] = '1';
        $record['updated_at'] = time();
        if (empty($_GPC["id"])) {
            show_json(1,"数据不存在");
        }else{
            $res = pdo_update("nets_hjk_memberlevel",$record,array("id"=>$_GPC["id"]));
        }
        if($res)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }


    public function gradefystop()
    {
        global $_W;
        global $_GPC;
        $record['isuse'] = '0';
        $record['updated_at'] = time();
        if (empty($_GPC["id"])) {
            show_json(1,"数据不存在");
        }else{
            $res = pdo_update("nets_hjk_memberlevel",$record,array("id"=>$_GPC["id"]));
        }
        if($res)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }

    public function gradefyinit()
    {
        global $_W;
        global $_GPC;

        $path   = MODULE_URL."/skin/initialize.json";
        $json = file_get_contents($path);
        $string = json_decode($json);
        $strdata = object_array($string);
        //memberlevel filed
        $e = pdo_delete('nets_hjk_memberlevel',array('uniacid'=>$_W['uniacid']));
        foreach ($strdata as $key => $value) {
            $string_data = $value;
            //save memberlevel
            $res['name'] = $string_data['name'];
            $res['type'] = $string_data['type'];
            $res['sign_credit1'] = $string_data['sign_credit1'];
            $res['order_credit1'] = $string_data['order_credit1'];
            $res['releader_credit1'] = $string_data['releader_credit1'];
            $res['refriend_credit1'] = $string_data['refriend_credit1'];
            $res['myteam_credit2'] = $string_data['myteam_credit2'];
            $res['myleader1_credit2'] = $string_data['myleader1_credit2'];
            $res['myleader2_credit2'] = $string_data['myleader2_credit2'];
            $res['commission_formonth'] = $string_data['commission_formonth'];
            $res['recharge_get'] = $string_data['recharge_get'];
            $res['show_recharge_get'] = $string_data['show_recharge_get'];
            $res['show_sign_credit1'] = $string_data['show_sign_credit1'];
            $res['show_order_credit1'] = $string_data['show_order_credit1'];
            $res['show_releader_credit1'] = $string_data['show_releader_credit1'];
            $res['show_refriend_credit1'] = $string_data['show_refriend_credit1'];
            $res['show_commission_formonth'] = $string_data['show_commission_formonth'];
            $res['show_myteam_credit2'] = $string_data['show_myteam_credit2'];
            $res['show_myleader1_credit2'] = $string_data['show_myleader1_credit2'];
            $res['show_myleader2_credit2'] = $string_data['show_myleader2_credit2'];
            $res['uniacid'] = $_W['uniacid'];
            $j = pdo_insert('nets_hjk_memberlevel',$res);
        }
        //验证是否存在合伙人的等级记录
        $checksql="select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and type=2";//合伙人的记录
        $levelitem=pdo_fetch($checksql,array(":uniacid"=>$_W["uniacid"]));
        if(empty($levelitem)){
            //添加合伙人的记录
            $res2['name'] = 0;
            $res2['type'] = 2;//合伙人
            $res2['sign_credit1'] = 10;
            $res2['order_credit1'] = 10;
            $res2['releader_credit1'] = 10;
            $res2['refriend_credit1'] = 10;
            $res2['myteam_credit2'] = 9;
            $res2['myleader1_credit2'] = 5;
            $res2['myleader2_credit2'] = 4;
            $res2['commission_formonth'] = 3;
            $res2['recharge_get'] = 999;
            $res2['show_recharge_get'] = 999;
            $res2['show_sign_credit1'] = 0;
            $res2['show_order_credit1'] = 0;
            $res2['show_releader_credit1'] = 0;
            $res2['show_refriend_credit1'] = 0;
            $res2['show_commission_formonth'] = 8;
            $res2['show_myteam_credit2'] = 9;
            $res2['show_myleader1_credit2'] = 5;
            $res2['show_myleader2_credit2'] = 4;
            $res2['isuse'] = 0;
            $res2['uniacid'] = $_W['uniacid'];
            $j = pdo_insert('nets_hjk_memberlevel',$res2);
        }
        if($res)
            show_json(1,"操作成功");
        else
            show_json(1,"操作失败");
    }
}
?>