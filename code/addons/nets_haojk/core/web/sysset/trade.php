<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
class Trade_NetsHaojkPage extends WebPage
{
	public function main() 
	{
        global $_GPC;
        global $_W;

		if ($_W['action']=='sysset.trade.index'||$_W['action']=='sysset.trade')
            $this->index();
        elseif ($_W['action']=='sysset.trade.payment')
            $this->payment();
        elseif ($_W['action']=='sysset.trade.payment_test')
            $this->payment_test();
        elseif ($_W['action']=='sysset.trade.alipaypayment_test')
            $this->alipaypayment_test();
	}

    public function index()
    {
        global $_W;
        global $_GPC;
        $uniacid=$_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        if ($_W['ispost']==1) {
            $set['isopen_subsidy'] = $_GPC['isopen_subsidy'];
            if($_GPC['isopen_subsidy']==2){
                $set['isopen_subsidy']=0;
            }
            $set['isshow_subsidy'] = $_GPC['isshow_subsidy'];
            if($_GPC['isshow_subsidy']==2){
                $set['isshow_subsidy']=0;
            }
            $set['member_subsidename'] = $_GPC['member_subsidename'];
            $set['leader_subsidename'] = $_GPC['leader_subsidename'];
            $set['credit1_to_credit2'] = $_GPC['credit1_to_credit2'];
            $set['applyleader_remark'] = $_GPC['applyleader_remark'];
            $set['applyleader'] = $_GPC['applyleader'];
            if($_GPC['applyleader']==2){
                $set['applyleader']=0;
            }
            $set['isshow_subsidy_dl'] = $_GPC['isshow_subsidy_dl'];
            if($_GPC['isshow_subsidy_dl']==2){
                $set['isshow_subsidy_dl']=0;
            }
            $set['isopen_paycommission'] = $_GPC['isopen_paycommission'];
            if ($_GPC['isopen_paycommission'] == 2) {
                $set['isopen_paycommission'] = 0;
            }
            $set['applyleader_fee'] = $_GPC['applyleader_fee'];
            $set['subsidy'] = $_GPC['subsidy'];

            $set['isopen_partner'] = $_GPC['isopen_partner'];
            if ($_GPC['isopen_partner'] == 2) {
                $set['isopen_partner'] = 0;
            }
            $set['partner_fee'] = $_GPC['partner_fee'];
            $set['partner_commission'] = $_GPC['partner_commission'];
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/trade/index'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/trade/index'), 'warning');
        }
        include $this->template('haojingke/sysset/trade/index');
    }

    //提现设置
    public function payment()
    {
        global $_W;
        global $_GPC;

        $uniacid=$_W['uniacid'];
        $ip=get_ip();
        $cert_path = ATTACHMENT_ROOT . 'botcert/';
        $result = '';

        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));

        $record = pdo_fetchall("SELECT * FROM " .tablename('nets_hjk_menu'));
        if (!file_exists($cert_path)){ mkdir ($cert_path);}//创建证书文件夹
        $iscert=0;
        $iskey=0;
        $isca=0;
        if (!file_exists($cert_path)){ mkdir ($cert_path);}//创建证书文件夹
        $iscert=0;
        $iskey=0;
        $isca=0;
        if(file_exists($cert_path."apiclient_cert.pem.".$uniacid)){
            $iscert=1;
        }
        if(file_exists($cert_path."apiclient_key.pem.".$uniacid)){
            $iskey=1;
        }
        if(file_exists($cert_path."rootca.pem.".$uniacid)){
            $isca=1;
        }
        $mincash = $r['mincash'];
        $rate = $r['rate']*100;

        if ($_W['ispost']==1) {

            //上传证书
            if(!empty($_FILES['weixin_cert_file'])){
                file_upload1($_FILES['weixin_cert_file'], 'pem', 'apiclient_cert.pem');
            }
            if(!empty($_FILES['weixin_key_file'])){
                file_upload1($_FILES['weixin_key_file'], 'pem', 'apiclient_key.pem');
            }
            if(!empty($_FILES['weixin_root_file'])){
                file_upload1($_FILES['weixin_root_file'], 'pem', 'rootca.pem');
            }
            $b=true;
            if(!empty($_GPC['cert'])) {
                $content=$_GPC['cert'];//file_get_contents($cert_path . 'apiclient_cert.pem.' . $uniacid);
                $ret = file_put_contents($cert_path . 'apiclient_cert.pem.' . $uniacid, trim($content));
                $b = $b && $ret;
            }
            if(!empty($_GPC['key'])) {
                $content=$_GPC['key'];//file_get_contents($cert_path . 'apiclient_key.pem.' . $uniacid);
                $ret = file_put_contents($cert_path . 'apiclient_key.pem.' . $uniacid, trim($content));
                $b = $b && $ret;
            }
            if(!empty($_GPC['ca'])) {
                $content=$_GPC['ca'];//file_get_contents($cert_path . 'rootca.pem.' . $uniacid);
                $ret = file_put_contents($cert_path . 'rootca.pem.' . $uniacid, trim($content));
                $b = $b && $ret;
            }
            if ($_GPC['cashtype']==5) {
                $set['cashtype'] = 0;
            }else{
                $set['cashtype'] = $_GPC['cashtype'];
            }
            $set['mincash']  = $_GPC['mincash'];
            $set['rate']  = $_GPC['rate']/100;
            $set['mchid']  = $_GPC['mchid'];
            $set['alipay_appid']  = $_GPC['alipay_appid'];
            $set['alipay_privatekey']  = $_GPC['alipay_privatekey'];
            $set['created_at'] = time();
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
//            $set['wxappid']  = $_GPC['wxappid'];
//            $set['wxkey']  = $_GPC['wxkey'];
            if (empty($r)) {
                $res = pdo_insert("nets_hjk_global",$set);
            }else{
                $res = pdo_update("nets_hjk_global",$set,array('uniacid'=>$_W['uniacid']));
            }
            if($res)
                show_message('操作成功！',webUrl('sysset/trade/payment'), 'success');
            else
                show_message('操作失败！',webUrl('sysset/trade/payment'), 'warning');
        }
        include $this->template('haojingke/sysset/trade/payment');
    }

    //提现测试
    public function payment_test()
    {
        global $_W;
        global $_GPC;

        $tradeno=date('Ymd') . time();
        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $openid=$_GPC["openid"];
        $money=-1;
        $mchid=$r['mchid'];

        $res =  payWeixin($openid,$money,$mchid,$tradeno);
        //微信打款失败的
        //var_dump($res);
        if(empty($res['errno'])){
            $remark="[打款成功，交易单号为：".$tradeno."] ";
            show_json(1,$remark);
        }else{
            show_json(1,'微信打款失败,交易单号为：'.$tradeno.'，错误码['.$res['errno'].']['.$res['message'].']');
        }
    }

    //支付宝提现测试
    public function alipaypayment_test()
    {
        global $_W;
        global $_GPC;

        $r = pdo_fetch("SELECT * FROM " .tablename('nets_hjk_global'). " WHERE uniacid =:uniacid",array(':uniacid'=>$_W['uniacid']));
        $openid=$_GPC["openid"];
        $alipay_appid=$r['alipay_appid'];

        $biz_content = array();
        //单号
        $biz_content['out_biz_no'] = time();
        $biz_content['payee_type'] = 'ALIPAY_LOGONID';
        //支付宝账号
        $biz_content['payee_account'] =$openid;
        //支付金额 最低0.1
        $biz_content['amount'] = 0.1;
        $biz_content['payer_show_name'] = '提现测试';
        $biz_content['payee_real_name'] = '';
        $biz_content['remark'] = '提现测试';
        $biz_content = array_filter($biz_content);
        $config['method'] = 'alipay.fund.trans.toaccount.transfer';
        //app_id
        $config['app_id'] = $alipay_appid;
        //private_key
        $config['private_key'] = $r["alipay_privatekey"];
        $config['biz_content'] = json_encode($biz_content);
        $res = publicAliPay($config);
        if($res['alipay_fund_trans_toaccount_transfer_response']['code']==10000){
            $remark="[打款成功，交易单号为：".$res['alipay_fund_trans_toaccount_transfer_response']['order_id']."] ";
            show_json(1,$remark);
        }else{
            show_json(1,'支付宝打款失败，错误信息['.$res['alipay_fund_trans_toaccount_transfer_response']['sub_msg'].']');
        }
    }
}
?>