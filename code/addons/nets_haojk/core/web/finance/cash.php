<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Cash_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='finance.cash.index'||$_W['action']=='finance.cash')
            $this->index('全部');
        elseif ($_W['action']=='finance.cash.unpay')
            $this->index('待审核');
        elseif ($_W['action']=='finance.cash.pay')
            $this->index('已完成');
        elseif ($_W['action']=='finance.cash.refuse')
            $this->index('已拒绝');
        elseif ($_W['action']=='finance.cash.cash_alipay')
            $this->cash_alipay();
        elseif ($_W['action']=='finance.cash.cash_wechart')
            $this->cash_wechart();
        elseif ($_W['action']=='finance.cash.cash_allow')
            $this->cash_allow();
        elseif ($_W['action']=='finance.cash.cash_refuse')
            $this->cash_refuse();
	}

    //列表
    public function index($title)
	{
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];

        $date = array();
        if(!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime =  TIMESTAMP + 86399;
        if(count($date)==2){
            $starttime =strtotime($date[0]);
            $endtime =strtotime($date[1]) + 86399;
        }
        $psize = 20;
        $where = "";
        if ($title == '全部') {
            $where .= " AND (s.status = 0 OR s.status = 1 OR s.status = 2) ";
        }elseif ($title == '已完成'){
            $where .= " AND s.status = 1";
        }elseif ($title == '已拒绝') {
            $where .= " AND s.status = 2 ";
        }elseif ($title == '待审核') {
            $where .= " AND s.status = 0 ";
        }

        if(!empty($starttime)){
            $where.= " AND s.created_at >= ".$starttime. " AND s.created_at <=" .$endtime;
        }
        if(!empty($_GPC['keyword'])){
            $where.= " AND (m.nickname like '%".$_GPC['keyword']."%'  or m.mobile like '%".$_GPC['mobile']."%' )";
        }
        if($_GPC['cashtype']== '5'){
            $where.= " AND s.cashtype ";
        }
        if ($_GPC['cashtype'] == '1') {
            $where.=" AND (s.cashtype = '1' OR s.cashtype = '0' OR s.cashtype = null)";
        }
        if ($_GPC['cashtype'] == '2') {
            $where.=" AND s.cashtype = 2";
        }
        $pindex = max(1, intval($_GPC['page']));
        $list = pdo_fetchall("SELECT s.*,m.nickname,m.mobile FROM ".tablename('nets_hjk_member_logs')." AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid and m.uniacid=".$uniacid." WHERE s.type = '5' AND s.uniacid= ".$uniacid.$where." ORDER BY s.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize);
        $total = pdo_fetchcolumn("SELECT count(s.id) FROM " .tablename('nets_hjk_member_logs'). " AS s LEFT JOIN " .tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid WHERE s.type = '5' AND  s.uniacid = " .$uniacid.$where);
        $pager = pagination($total, $pindex, $psize);
        //导出数据
        if(!empty($_GPC["import"]) && $_GPC["import"]==1){
            $importdata = pdo_fetchall("SELECT s.*,m.nickname,m.mobile FROM ".tablename('nets_hjk_member_logs')." AS s LEFT JOIN ".tablename('nets_hjk_members')." AS m ON s.memberid = m.memberid and m.uniacid=".$uniacid." WHERE s.type = '5' AND s.uniacid= ".$uniacid.$where." ORDER BY s.id DESC ");

            $header = array(
                'memberid' => '会员ID', 'nickname' => '昵称', 'mobile' => '手机', 'money' => '提现金额','cashtype' => '提现方式', 'status' => '状态', 'createtime' => '申请时间','remark' => '备注',
            );
            $keys = array_keys($header);
            $html = "\xEF\xBB\xBF";
            foreach ($header as $li) {
                $html .= $li . "\t ,";
            }
            $html .= "\n";

            if (!empty($importdata)) {
                $size = ceil(count($importdata) / 500);
                for ($i = 0; $i < $size; $i++) {
                    $buffer = array_slice($importdata, $i * 500, 500);
                    $user = array();
                    foreach ($buffer as $row) {
                        $row['money']=floatval($row['money'])*-1;
                        $row['cashtype']=$row['cashtype']==2?'支付宝':'微信';
                        if($row['status']==0){
                            $row['status']='申请提现';
                        }
                        if($row['status']==1){
                            $row['status']='已完成';
                        }
                        if($row['status']==2){
                            $row['status']='已拒绝';
                        }
                        $row['createtime'] = date('Y-m-d H:i:s', $row['created_at']);

                        foreach ($keys as $key) {
                            $data[] = $row[$key];
                        }
                        $user[] = implode("\t ,", $data) . "\t ,";
                        unset($data);
                    }
                    $html .= implode("\n", $user) . "\n";
                }
            }

            header("Content-type:text/csv");
            header("Content-Disposition:attachment; filename=提现记录.csv");
            echo $html;
            exit();
        }

        include $this->template('haojingke/finance/cash/index');
	}

    //支付宝打款
    public function cash_alipay()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $_GPC['tradeno']=date('Ymd') . time();
        if (!empty($_GPC['id'])) {
            $data['updated_at'] = time();
            $data['status'] = 1;
            $sql="select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid";
            $set=pdo_fetch($sql,array(":uniacid"=>$uniacid));
            $info=pdo_fetch("SELECT a.*,m.alipay_no FROM ".tablename('nets_hjk_member_logs')." AS a LEFT JOIN " .tablename('nets_hjk_members'). " AS m ON a.memberid=m.memberid and m.uniacid=a.uniacid and m.uniacid=".$uniacid." WHERE a.type =5 and a.status = 0 and a.id=:id",array(":id"=>$_GPC['id']));
            if ($info['cashtype'] == 2) {
                if (empty($set) || empty($set["alipay_appid"]))
                    show_json(1,'操作失败，请在系统提现设置中设置支付宝商户提现信息');
            }
            $biz_content = array();
            //单号
            $biz_content['out_biz_no'] = time();
            $biz_content['payee_type'] = 'ALIPAY_LOGONID';
            //支付宝账号
            $biz_content['payee_account'] =$info["alipay_no"];
            //支付金额 最低0.1
            $biz_content['amount'] = abs($info["money"]);
            $biz_content['payer_show_name'] = '余额提现';
            $biz_content['payee_real_name'] = '';
            $biz_content['remark'] = '余额提现';
            $biz_content = array_filter($biz_content);
            $config['method'] = 'alipay.fund.trans.toaccount.transfer';
            //app_id
            $config['app_id'] = $set["alipay_appid"];
            //private_key
            $config['private_key'] = $set["alipay_privatekey"];
            $config['biz_content'] = json_encode($biz_content);
            $res = publicAliPay($config);
            if($res['alipay_fund_trans_toaccount_transfer_response']['code']==10000){
                $data['remark']="[打款成功，交易单号为：".$res['alipay_fund_trans_toaccount_transfer_response']['order_id']."] ";
                pdo_update("nets_hjk_member_logs",$data,array('id' => $_GPC['id'],'type'=> '5'));
                show_json(1,$data['remark']);
            }else{
                $data['updated_at'] = time();
                $data['status'] = 0;
                $data['remark']=$info['remark'].'支付宝打款失败，错误信息['.$res['alipay_fund_trans_toaccount_transfer_response']['sub_msg']."] ";
                pdo_update("nets_hjk_member_logs",$data,array('id' => $_GPC['id'],'type'=> '5'));
                show_json(1,'支付宝打款失败，错误信息['.$res['alipay_fund_trans_toaccount_transfer_response']['sub_msg'].']');
            }
        }else{
            show_json(1,"数据不存在或者其他管理员已审核，请刷新页面！");
        }
    }

    //微信打款
    public function cash_wechart()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $_GPC['tradeno']=date('Ymd') . time();
        if (!empty($_GPC['id'])) {
            $data['updated_at'] = time();
            $data['status'] = 1;
            $sql="select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid";
            $set=pdo_fetch($sql,array(":uniacid"=>$uniacid));
            $info=pdo_fetch("SELECT a.*,m.openid FROM ".tablename('nets_hjk_member_logs')." AS a LEFT JOIN " .tablename('mc_mapping_fans'). " AS m ON a.memberid=m.uid and m.uniacid=".$uniacid." WHERE a.type =5 and a.status = 0 and a.id=:id",array(":id"=>$_GPC['id']));
            if ($info['cashtype'] != 2) {
                if (empty($set) || empty($set["mchid"]))
                    show_json(1,'操作失败，请在系统设置中设置微信商户信息');
            }
            $res = false;
            $i=pdo_update("nets_hjk_member_logs",$data,array('id' => $_GPC['id'],'type'=> '5'));
            if ($info['cashtype'] == 2) {
                if($i>0){
                    show_json(1,"操作成功");
                }else{
                    show_json(1,"操作失败,数据不存在或者其他管理员已审核，请刷新页面！");
                }

            }else{
                if($i>0){
                    $res =  payWeixin($info['openid'],$info["money"],$set["mchid"],$_GPC['tradeno']);
                    //微信打款失败的
                    //var_dump($res);
                    if(empty($res['errno'])){
                        $data['remark']=$info['remark']."[打款成功，交易单号为：".$_GPC['tradeno']."] ";
                        pdo_update("nets_hjk_member_logs",$data,array('id' => $_GPC['id'],'type'=> '5'));
                    }else{
                        $data['updated_at'] = time();
                        $data['status'] = 0;
                        $data['remark']=$info['remark']."[打款失败，交易单号为：".$_GPC['tradeno']."] ";
                        pdo_update("nets_hjk_member_logs",$data,array('id' => $_GPC['id'],'type'=> '5'));
                        show_json(1,'微信打款失败，错误码['.$res['errno'].']['.$res['message'].']');
                    }
                    show_json(1,"操作成功");
                }else{
                    show_json(1,"操作失败");
                }
            }
        }else{
            show_json(1,"数据不存在或者其他管理员已审核，请刷新页面！");
        }
    }

    //手动发放
    public function cash_allow()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $_GPC['tradeno']=date('Ymd') . time();
        if (!empty($_GPC['id'])) {
            $data['status'] = 1;
            $data['updated_at'] = time();
            $data['remark']= "手动发放";
            pdo_update("nets_hjk_member_logs",$data,array('id' => $_GPC['id'],'type'=> '5'));
            show_json(1,'手动发放成功！');
        }else{
            show_json(1,"数据不存在或者其他管理员已审核，请刷新页面！");
        }
    }
    //拒绝审核
    public function cash_refuse(){
        global $_GPC, $_W;
        $uniacid=$_W['uniacid'];
        if (!empty($_GPC['id'])) {
            $data['updated_at'] = time();
            $data['status'] = 2;
            $b=  pdo_update('nets_hjk_member_logs',$data,array('id'=>$_GPC['id'],'type'=> '5'));
            //退回余额
            $logInfo = pdo_get('nets_hjk_member_logs',array('id'=>$_GPC['id']));
            if($logInfo){
                $oldmoney=str_replace("提现","",$logInfo["title"]);
                $sql="select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid";
                $set=pdo_fetch($sql,array(":uniacid"=>$uniacid));
                $member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join "
                    .tablename("mc_members")." AS m on em.memberid=m.uid  where em.memberid=:memberid "
                    ,array(":memberid"=>$logInfo["memberid"]));

                //$set["rate"]

                $credit1 = $oldmoney;
                $logno	=	date('YmdHis').rand(111,999);
                $remark="拒绝提现退回";
                $logs["uniacid"]=$logInfo["uniacid"];
                $logs["memberid"]=$logInfo["memberid"];
                $logs["type"]=2;//1积分2佣金3补贴
                $logs["logno"]=$logno;
                $logs["title"]="";
                $logs["status"]=1;//0 生成 1 成功 2 失败
                $logs["money"]=$credit1;
                $logs["credit1"]=$member["credit1"];
                $logs["credit2"]=$member["credit2"];
                $logs["rechargetype"]="credit2";
                $logs["cashtype"]="";
                $logs["remark"]=$remark;
                $logs["created_at"]=time();
                $logs["deleted_at"]=0;
                $i=pdo_insert("nets_hjk_member_logs",$logs);
                $b= mc_credit_update($logInfo["memberid"], "credit2", $credit1, $log = array(0,$remark,"nets_haojk",0,0,1));
            }
            if($b)
                show_json(1,"操作成功");
            else
                show_json(1,"操作失败");
        }
        else
            show_json(1,"数据不存在或者其他管理员已审核，请刷新页面！");
    }



	
}
?>