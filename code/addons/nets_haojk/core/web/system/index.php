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

        if ($_W['action'] == 'system.index' || $_W['action'] == 'system')
            $this->index();
        elseif ($_W['action'] == 'system.initialize')
            $this->initialize();
        elseif ($_W['action'] == 'system.clearmember')
            $this->clearmember();
        elseif ($_W['action'] == 'system.restore')
            $this->restore();
        elseif ($_W['action'] == 'system.position')
            $this->position();
        elseif ($_W['action'] == 'system.positionset')
            $this->positionset();
        elseif ($_W['action'] == 'system.position_delete')
            $this->position_delete();
        elseif ($_W['action'] == 'system.position_allot')
            $this->position_allot();
        elseif ($_W['action'] == 'system.restorerelation')
            $this->restorerelation();
        elseif ($_W['action'] == 'system.position_new')
            $this->position_new();
    }

    //京盟设置
    public function index()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " . tablename('nets_hjk_global') . " WHERE uniacid =:uniacid", array(':uniacid' => $_W['uniacid']));
        $data['jduniacid'] = $r['jduniacid'];
        $data['jdpid'] = $r['jdpid'];
        $data['jduniackey'] = $r['jduniackey'];
		
        if ($_W['ispost'] == 1) {
             $set['jduniackey'] = $_GPC['jduniackey'];
            $set['jduniacid'] = $_GPC['jduniacid'];
            $set['jdpid'] = $_GPC['jdpid'];
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
			$_GPC['target_url']= "{$_W['siteroot']}app/index.php?i={$_W['uniacid']}&c=entry&m=nets_haojk&a=wxapp&do=wxapi&model=order&apiname=order_notice";
			
            $resdata =  setmgj_weordernoticeurl();
			
            if (empty($r)) {
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global", $set);
            } else {
                $res = pdo_update("nets_hjk_global", $set, array('uniacid' => $_W['uniacid']));
            }
            if ($res)
                show_message('操作成功！', webUrl('system/index'), 'success');
//            show_message('操作成功！',webUrl('system/index'), 'success');
        }
        include $this->template('haojingke/system/index');
    }

    //京东推广位基础设置
    public function positionset()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];
        $result = '';
        $r = pdo_fetch("SELECT * FROM " . tablename('nets_hjk_global') . " WHERE uniacid =:uniacid", array(':uniacid' => $_W['uniacid']));
        $data['jd_key'] = $r['jd_key'];
        $data['position_prefix'] = $r['position_prefix'];

        if ($_W['ispost'] == 1) {
            $set['jd_key'] = $_GPC['jd_key'];
            $set['position_prefix'] = $_GPC['position_prefix'];
            $set['updated_at'] = time();
            $set['uniacid'] = $_W['uniacid'];
            if (empty($r)) {
                $set['created_at'] = time();
                $res = pdo_insert("nets_hjk_global", $set);
            } else {
                $res = pdo_update("nets_hjk_global", $set, array('uniacid' => $_W['uniacid']));
            }
            if ($res)
                show_message('操作成功！', webUrl('system/positionset'), 'success');
        }
        include $this->template('haojingke/system/positionset');
    }

    //一键初始化
    public function initialize()
    {
        global $_GPC, $_W;

        if ($_W['ispost'] == 1) {
            $path = MODULE_URL . "/skin/initialize.json";
            $path3 = MODULE_URL . "/skin/test1.json";
            $path4 = MODULE_URL . "/skin/test2.json";
            $path5 = MODULE_URL . "/skin/test3.json";
            $path6 = MODULE_URL . "/skin/test4.json";
            $path7 = MODULE_URL . "/skin/test5.json";
            +
            $path10 = MODULE_URL . "/skin/test9.json";
            $path11 = MODULE_URL . "/skin/test.json";
            //json file write in
            $json3 = str(file_get_contents($path3));//banner
            $json4 = str(file_get_contents($path4));//head_menu
            $json5 = str(file_get_contents($path5));//picture_menu
            $json6 = str(file_get_contents($path6));//picture_menu2
            $json7 = str(file_get_contents($path7));//ad_menu
            $json10 = str(file_get_contents($path10));//tab_menu
            $json11 = file_get_contents($path11);
            $string_json = json_decode($json11);
            $json = file_get_contents($path);
            $string = json_decode($json);
            $strdata = object_array($string);
            $filed['banner'] = $json3;
            $filed['head_menu'] = $json4;
            $filed['picture_menu'] = $json5;
            $filed['picture_menu2'] = $json6;
            $filed['ad_menu'] = $json7;
            //$filed['tab_menu'] = $json10;
            $filed['homeskinid'] = $string_json->homeskinid;
            $filed['logo'] = str_replace("{hello}", MODULE_URL . "/skin", $string_json->logo);
            $filed['title'] = $string_json->title;
            $filed['remark'] = $string_json->remark;
            $filed['subscribeurl'] = $string_json->subscribeurl;
            $filed['exqrtype'] = $string_json->exqrtype;
            $filed['goodsqrtype'] = $string_json->goodsqrtype;
            $filed['isshow_subsidy'] = $string_json->isshow_subsidy;
            $filed['isshow_detail'] = $string_json->isshow_detail;
            $filed['isopen_subsidy'] = $string_json->isopen_subsidy;
            $filed['subsidy'] = $string_json->subsidy;
            $filed['mincash'] = $string_json->mincash;
            $filed['rate'] = $string_json->rate;
            $filed['cashtype'] = $string_json->cashtype;
            $filed['credit1_to_credit2'] = $string_json->credit1_to_credit2;
            //save nets_hjk_global
            $q = pdo_fetchall("SELECT * FROM " . tablename('nets_hjk_global') . " WHERE uniacid=" . $_W['uniacid']);
            if (empty($q)) {
                $filed['uniacid'] = $_W['uniacid'];
                $r = pdo_insert('nets_hjk_global', $filed);
            } else {
                $r = pdo_update('nets_hjk_global', $filed, array('uniacid' => $_W['uniacid']));
            }
            //memberlevel filed
            $e = pdo_delete('nets_hjk_memberlevel', array('uniacid' => $_W['uniacid']));
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
                $j = pdo_insert('nets_hjk_memberlevel', $res);
            }

            show_message('初始化成功！', webUrl('system/initialize'), 'success');
        }
        include $this->template('haojingke/system/initialize');
    }

    //一键清除无效会员 粉丝表有会员表没有
    public function clearmember()
    {
        global $_GPC, $_W;
        if ($_W['ispost'] == 1) {
            $deletesql = "DELETE from " . tablename("mc_mapping_fans") . " where uid=0;";
            $res = pdo_query($deletesql);
            $deletesql = "DELETE from " . tablename("nets_hjk_members") . " where memberid=0;";
            $res = pdo_query($deletesql);
            if ($res)
                show_message('操作成功！', webUrl('system/clearmember'), 'success');
            else
                show_message('没有要清除的数据！', webUrl('system/clearmember'), 'warning');
        }
        include $this->template('haojingke/system/clearmember');
    }


    //一键修复
    public function restore()
    {
        global $_GPC, $_W;
        if ($_W['ispost'] == 1) {
//fields
			if (!pdo_fieldexists('nets_hjk_global', 'mgjmodule_status')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD mgjmodule_status int  DEFAULT 1 NOT NULL COMMENT '蘑菇街模块状态1启用 0禁用';");
            }
			if (!pdo_fieldexists('nets_hjk_global', 'jduniackey')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD jduniackey  varchar(50)  comment '京东联盟KEY'; ");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'notice_newlevel')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `notice_newlevel` VARCHAR(50)  DEFAULT '' NOT NULL COMMENT '新增下级通知';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'notice_applycash')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `notice_applycash` VARCHAR(50)  DEFAULT '' NOT NULL COMMENT '申请提现通知';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'notice_auditingcash')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `notice_auditingcash` VARCHAR(50)  DEFAULT '' NOT NULL COMMENT '提现审核通知';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'subscribeurl')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `subscribeurl` VARCHAR(500)  DEFAULT '' NOT NULL COMMENT '引导关注URL';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'sign_credit1')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `sign_credit1` int(11)  DEFAULT 0 NOT NULL COMMENT '签到积分,0为关闭签到';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'credit1_to_credit2')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `credit1_to_credit2` int(11)  DEFAULT 0 NOT NULL COMMENT '1元等于多少积分';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'recharge_get')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `recharge_get` decimal(8,2)  DEFAULT 0 NOT NULL COMMENT '充值提升等级的金额';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'notice_tplno')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `notice_tplno`   VARCHAR(50)  DEFAULT '' NOT NULL COMMENT '任务通知编号';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'cashtype')) {
                pdo_query("alter table " . tablename('nets_hjk_member_logs') . " ADD `cashtype` int comment '提现类型 1支付宝2微信';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'notice_tplno_app')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `notice_tplno_app`   VARCHAR(50)  DEFAULT '' NOT NULL COMMENT '小程序任务通知编号';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'owner_openid')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `owner_openid` varchar(50)  DEFAULT 0 NOT NULL COMMENT '管理员openid';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'owner_openid_app')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `owner_openid_app` varchar(50)  DEFAULT 0 NOT NULL COMMENT '小程序管理员openid';");
            }
            if (!pdo_fieldexists('nets_hjk_menu', 'query')) {
                pdo_query("alter table " . tablename('nets_hjk_menu') . " ADD `query` varchar(1000)  DEFAULT '' NOT NULL COMMENT '菜单查询条件';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isopen_subsidy')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `isopen_subsidy` varchar(50)  DEFAULT 0 NOT NULL COMMENT '是否开启会员补贴';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'subsidy')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `subsidy` decimal(8,2) DEFAULT 0 NOT NULL COMMENT '补贴比例';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'applyleader_remark')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `applyleader_remark` varchar(1000) DEFAULT '支付后即可成为盟主' NOT NULL COMMENT '申请盟主文字描述';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isuse_parent')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `isuse_parent` int(11)  DEFAULT 0 NOT NULL COMMENT '是否是否使用上级推广位';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'cashtype')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `cashtype` int(11) DEFAULT 0 NOT NULL COMMENT '提现方式0全部，1微信，2支付宝';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'member_subsidename')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `member_subsidename`  VARCHAR(50) DEFAULT '约补' comment '普通会员补贴名称，默认约补';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'leader_subsidename')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD leader_subsidename  VARCHAR(50) DEFAULT '约赚' comment '盟主补贴名称，默认约赚';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_recharge_get')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_recharge_get` decimal(8,2) comment '升级积分展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_sign_credit1')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_sign_credit1` int comment '签到积分/天展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_order_credit1')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_order_credit1` int comment '订单积分*订单金额展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_releader_credit1')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_releader_credit1` int comment '推荐(成为)盟主积分*订单金额展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_refriend_credit1')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_refriend_credit1` int comment '推荐好友积分展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_myteam_credit2')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_myteam_credit2` decimal(8,2) comment '我的用户佣金比例 如5%展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_myleader1_credit2')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_myleader1_credit2` decimal(8,2) comment '我的一级盟主佣金比例如5%展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_myleader2_credit2')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_myleader2_credit2` decimal(8,2) comment '我的二级盟主佣金比例如5%展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'show_commission_formonth')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD `show_commission_formonth`  int comment '佣金结算 次/月 如2次/月展示用';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'sms_type')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD sms_type int(11) DEFAULT 0 NOT NULL COMMENT '短信服务商';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'wxapp_uniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `wxapp_uniacid` int(11) DEFAULT 0 NOT NULL COMMENT '同步到微信小程序的uniacid，从公众号绑定小程序';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'homeskinid')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `homeskinid` int(11) DEFAULT 0 NOT NULL COMMENT '首页风格，默认0';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'homeskinid')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . " ADD `homeskinid` int(11) DEFAULT 0 NOT NULL COMMENT '首页风格，默认0';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'jduniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . " ADD `jduniacid` varchar(50)  DEFAULT '' NOT NULL COMMENT '京东联盟id';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'dayu_appid')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `dayu_appid` VARCHAR(500)  DEFAULT '' NOT NULL COMMENT '大于短信appid';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'dayu_appkey')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `dayu_appkey` VARCHAR(500)  DEFAULT '' NOT NULL COMMENT '大于短信key';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'dayu_smstplid')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `dayu_smstplid` VARCHAR(500)  DEFAULT '' NOT NULL COMMENT '大于短信key';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'dayu_smssign')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `dayu_smssign` VARCHAR(500)  DEFAULT '' NOT NULL COMMENT '大于短信签名';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'exqrtype')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD  `exqrtype`  int(11) DEFAULT 0 NOT NULL COMMENT '推广二维码链接，1首页2关注';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'goodsqrtype')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD  `goodsqrtype` int(11) DEFAULT 0 NOT NULL COMMENT '商品二维码链接，1京东页2商品页';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'tab_menu')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD  `tab_menu` text comment '底部菜单，json数组格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'search_tip')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD  `search_tip` text comment '搜索提示文字内容';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'sms_tpl')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD  `sms_tpl` text comment '短信模板';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isopen_paycommission')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD `isopen_paycommission` varchar(50)  DEFAULT 0 NOT NULL COMMENT '是否开启盟主付费返佣，0否1是';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isshow_detail')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD isshow_detail int  DEFAULT 1 NOT NULL COMMENT '是否展示商品详情，1是0否';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isshow_subsidy')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD isshow_subsidy int  DEFAULT 1 NOT NULL COMMENT '是否显示补贴，1是0否';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'fllow_msg')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD fllow_msg varchar(500)  DEFAULT '' NOT NULL COMMENT '关注公众号消息';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'fllow_msg')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD fllow_msg varchar(500)  DEFAULT '' NOT NULL COMMENT '关注公众号消息';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'service_msg')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD service_msg  VARCHAR(500) comment '默认客服消息';");
            }
            if (!pdo_fieldexists('nets_hjk_menu', 'uniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_menu') . " ADD uniacid  int(11) COMMENT '公众号id';");
            }
            if (!pdo_tableexists('nets_hjk_ordertip')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_ordertip` (
                `id` int(10)  NOT NULL AUTO_INCREMENT,
                `uniacid` int(10)  NOT NULL,
                `nickname` varchar(50) DEFAULT NULL COMMENT '昵称',
                `avatar` varchar(500) NOT NULL DEFAULT '' COMMENT '头像',
                `tip` varchar(500) NOT NULL DEFAULT '' COMMENT '提示语',
                `createtime` int(10)  NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_smsrecord')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_smsrecord` (
                `id` int(10)  NOT NULL AUTO_INCREMENT,
                `uniacid` int(10)  NOT NULL,
                `uid` int(10) DEFAULT NULL COMMENT '会员UID',
                `code` varchar(50) NOT NULL DEFAULT '' COMMENT '验证码',
                `mobile` varchar(50) NOT NULL DEFAULT '' COMMENT '手机号',
                `createtime` int(10)  NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_fieldexists('nets_hjk_ordertip', 'uniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_ordertip') . " ADD uniacid  int(11) COMMENT '公众号id';");
            }
            if (!pdo_fieldexists('nets_hjk_ordertip', 'nickname')) {
                pdo_query("alter table " . tablename('nets_hjk_ordertip') . " ADD nickname  varchar(50) DEFAULT NULL COMMENT '昵称';");
            }
            if (!pdo_fieldexists('nets_hjk_ordertip', 'avatar')) {
                pdo_query("alter table " . tablename('nets_hjk_ordertip') . " ADD avatar  varchar(500) NOT NULL DEFAULT '' COMMENT '头像';");
            }
            if (!pdo_fieldexists('nets_hjk_ordertip', 'tip')) {
                pdo_query("alter table " . tablename('nets_hjk_ordertip') . " ADD tip  varchar(500) NOT NULL DEFAULT '' COMMENT '提示语';");
            }
            if (!pdo_fieldexists('nets_hjk_ordertip', 'createtime')) {
                pdo_query("alter table " . tablename('nets_hjk_ordertip') . " ADD createtime  int(10)  NOT NULL;");
            }
            if (!pdo_tableexists('nets_hjk_sendmsg')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_sendmsg` (
                      `id` int(10)  NOT NULL AUTO_INCREMENT,
                      `uniacid` int(10)  NOT NULL,
                      `topcolor` varchar(50) DEFAULT '' COMMENT '顶部颜色',
                      `title` varchar(50) DEFAULT '' COMMENT '标题',
                      `titlecolor` varchar(50) DEFAULT '' COMMENT '标题颜色',
                      `taskname` varchar(50) DEFAULT '' COMMENT '任务名称',
                      `tasknamecolor` varchar(50) DEFAULT '' COMMENT '任务名称颜色',
                      `tasktype` varchar(50) DEFAULT '' COMMENT '任务类型',
                      `tasktypecolor` varchar(50) DEFAULT '' COMMENT '任务类型颜色',
                      `taskresult` varchar(50) DEFAULT '' COMMENT '处理结果',
                      `taskresultcolor` varchar(50) DEFAULT '' COMMENT '处理结果颜色',
                      `remark` varchar(50) DEFAULT '' COMMENT '尾备注',
                      `remarkcolor` varchar(50) DEFAULT '' COMMENT '尾备注颜色',
                      `url` varchar(200) DEFAULT '' COMMENT '详情url',
                      `createtime` int(10)  NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'uniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD uniacid  int(11) COMMENT '公众号id';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'topcolor')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD topcolor  varchar(50) DEFAULT '' COMMENT '顶部颜色';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'title')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD title varchar(50) DEFAULT '' COMMENT '标题';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'titlecolor')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD titlecolor varchar(50) DEFAULT '' COMMENT '标题颜色';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'taskname')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD taskname varchar(50) DEFAULT '' COMMENT '任务名称';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'tasknamecolor')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD tasknamecolor varchar(50) DEFAULT '' COMMENT '任务名称颜色';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'tasktype')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD tasktype varchar(50) DEFAULT '' COMMENT '任务类型';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'tasktypecolor')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD tasktypecolor varchar(50) DEFAULT '' COMMENT '任务类型颜色';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'taskresult')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD taskresult varchar(50) DEFAULT '' COMMENT '处理结果';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'taskresultcolor')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD taskresultcolor varchar(50) DEFAULT '' COMMENT '处理结果颜色';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'remark')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD remark varchar(50) DEFAULT '' COMMENT '尾备注';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'remarkcolor')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD remarkcolor varchar(50) DEFAULT '' COMMENT '尾备注颜色';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'url')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD url varchar(200) DEFAULT '' COMMENT '详情url';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg', 'createtime')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg') . " ADD createtime int(10)  NOT NULL;");
            }
            if (!pdo_tableexists('nets_hjk_sendmsg_rec')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_sendmsg_rec` (
                      `id` int(10)  NOT NULL AUTO_INCREMENT,
                      `uniacid` int(10)  NOT NULL,
                      `msgid` int(10) DEFAULT '0' COMMENT '消息id',
                      `goods` varchar(2000) DEFAULT '' COMMENT '群发的商品,json格式',
                      `title` varchar(50) DEFAULT '' COMMENT '标题',
                      `openid` varchar(50) DEFAULT '' COMMENT '会员openid',
                      `iswxapp` int(10)   DEFAULT '0' COMMENT '0公众号1小程序',
                      `type` int(10)   DEFAULT '0' COMMENT '0普通消息1商品消息',
                      `state` int(10)   DEFAULT '0' COMMENT '发送状态，0待发送,1发送成功,-1发送失败(或拒绝接受)',
                      `createtime` int(10)  NOT NULL,
                      PRIMARY KEY (`id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'createtime')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD createtime int(10)  NOT NULL;");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'uniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD uniacid int(10)  NOT NULL;");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'msgid')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD msgid int(10) DEFAULT '0' COMMENT '消息id';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'goods')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD goods varchar(2000) DEFAULT '' COMMENT '群发的商品,json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'title')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD title varchar(50) DEFAULT '' COMMENT '标题';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'openid')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD openid varchar(50) DEFAULT '' COMMENT '会员openid';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'iswxapp')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD iswxapp int(10)   DEFAULT '0' COMMENT '0公众号1小程序';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'type')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD type int(10)   DEFAULT '0' COMMENT '0普通消息1商品消息';");
            }
            if (!pdo_fieldexists('nets_hjk_sendmsg_rec', 'state')) {
                pdo_query("alter table " . tablename('nets_hjk_sendmsg_rec') . " ADD state int(10)   DEFAULT '0' COMMENT '发送状态，0待发送,1发送成功,-1发送失败(或拒绝接受)';");
            }


            if (!pdo_fieldexists('nets_hjk_members', 'shopname')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . " ADD `shopname` varchar(500) DEFAULT 0 NOT NULL COMMENT '';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'shoplogo')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . " ADD `shoplogo` varchar(500) DEFAULT 0 NOT NULL COMMENT '';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'shopdesc')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . " ADD `shopdesc` varchar(500) DEFAULT 0 NOT NULL COMMENT '';");
            }

            if (!pdo_fieldexists('nets_hjk_menu', 'icon')) {
                pdo_query("alter table " . tablename('nets_hjk_menu') . " ADD icon  VARCHAR(200) DEFAULT '' comment '菜单图标';");
            }
            pdo_query("DELETE FROM " . tablename('modules_bindings') . " WHERE module='nets_haojk' and entry='menu' AND title='推广位' AND `do`='data_show'");


            if (!pdo_tableexists('nets_hjk_freeorders')) {
                pdo_query("create table ims_nets_hjk_freeorders
                (
                id                   int(10)  not null auto_increment,
                uniacid				int(11) not null comment '公众号ID',
                orderno              text comment '订单号',
                memberid             text comment '会员ID，会员ID',
                created_at           int(11) default 0 comment '创建时间',
                updated_at           int(11) default 0 comment '更新时间',
                deleted_at           int(11) default NULL comment '删除时间',
                primary key (id)
                ) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COMMENT = '';");
            }
            if (!pdo_tableexists('nets_hjk_membercutting_rec')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_membercutting_rec` (
                `id` int(10)  NOT NULL AUTO_INCREMENT,
                `uniacid` int(10)  NOT NULL,
                `cuttingid` int(10) DEFAULT 0 COMMENT '砍价的ID',
                `memberid` int(10)  NOT NULL  COMMENT '帮砍价的会员ID',
                `nickname` varchar(50) DEFAULT '' COMMENT '会员昵称',
                `avatar` varchar(200) DEFAULT '' COMMENT '会员头像',
                `cuttingprice` decimal(8,2) DEFAULT 0 COMMENT '砍掉的价格',
                `createtime` int(10)  NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_membercutting')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_membercutting` (
                `id` int(10)  NOT NULL AUTO_INCREMENT,
                `uniacid` int(10)  NOT NULL,
                `memberid` int(10)  NOT NULL  COMMENT '发起砍价的会员ID',
                `nickname` varchar(50) DEFAULT '' COMMENT '会员昵称',
                `avatar` varchar(200) DEFAULT '' COMMENT '会员头像',
                `skuId` varchar(50) DEFAULT '' COMMENT '商品id',
                `skuName` varchar(500) DEFAULT '' COMMENT '标题',
                `picUrl` varchar(500) DEFAULT '' COMMENT '图片',
                `wlPrice` decimal(8,2) DEFAULT 0 COMMENT '原价',
                `wlPrice_after` decimal(8,2) DEFAULT 0 COMMENT '券后价',
                `goodjson` varchar(2000) DEFAULT '' COMMENT '商品的json数据',
                `createtime` int(10)  NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_freegoods')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_freegoods` (
                `id` int(10)  NOT NULL AUTO_INCREMENT,
                `uniacid` int(10)  NOT NULL,
                `skuId` varchar(50) DEFAULT '' COMMENT '商品id',
                `skuName` varchar(500) DEFAULT '' COMMENT '标题',
                `picUrl` varchar(500) DEFAULT '' COMMENT '图片',
                `wlPrice` decimal(8,2) DEFAULT 0 COMMENT '原价',
                `wlPrice_after` decimal(8,2) DEFAULT 0 COMMENT '券后价',
                `goodjson` varchar(2000) DEFAULT '' COMMENT '商品的json数据',
                `cuttingnum` int DEFAULT 0 COMMENT '需要砍价的次数',
                `cuttingprice` decimal(8,2)  DEFAULT 0 COMMENT '需要砍到的底价',
                `sort` int  DEFAULT 0 COMMENT '排序',
                `createtime` int(10)  NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_usegoods')) {
                pdo_query("CREATE TABLE `ims_nets_hjk_usegoods` (
                `id` int(10)  NOT NULL AUTO_INCREMENT,
                `uniacid` int(10)  NOT NULL,
                `menuid` int(10)  NOT NULL,
                `skuId` varchar(50) DEFAULT '' COMMENT '商品id',
                `sort` int  DEFAULT 0 COMMENT '排序',
                `createtime` int(10)  NOT NULL,
                PRIMARY KEY (`id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }


            if (!pdo_fieldexists('nets_hjk_global', 'kefuqr')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD kefuqr  VARCHAR(200) DEFAULT '' comment '客服二维码';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'gradename')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD gradename   VARCHAR(10) comment '等级描述';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'identityname')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD identityname   VARCHAR(10) comment '身份描述';");
            }
            if (!pdo_fieldexists('nets_hjk_memberlevel', 'isuse')) {
                pdo_query("alter table " . tablename('nets_hjk_memberlevel') . " ADD isuse   VARCHAR(1) DEFAULT 1 comment '是否启用，1启用 0禁用';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'from_jduniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . " ADD from_jduniacid int(11) DEFAULT 0 NOT NULL COMMENT '来自哪个合伙人推荐的';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isopenpartner')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD isopenpartner  int DEFAULT 0 comment '是否开启合伙人';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'partnerimg')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD partnerimg  VARCHAR(200) DEFAULT '' comment '申请合伙人页面图片介绍';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'partnerdesc')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD partnerdesc  VARCHAR(1000) DEFAULT '' comment '申请合伙人页面文字介绍';");
            }
            if (!pdo_fieldexists('nets_hjk_probit', 'jduniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_probit') . " ADD jduniacid  VARCHAR(50) DEFAULT '' comment '京东联盟id，同步合伙人过来的';");
            }
            if (!pdo_fieldexists('nets_hjk_probit', 'jduniacid')) {
                pdo_query("alter table " . tablename('nets_hjk_probit') . " ADD jduniacid  VARCHAR(50) DEFAULT '' comment '京东联盟id，同步合伙人过来的';");
            }

            pdo_query("alter table " . tablename('nets_hjk_member_logs') . " modify column remark varchar(500);");
            pdo_query("ALTER  TABLE  " . tablename('nets_hjk_members') . "  ADD  INDEX m_members (  `memberid`  );");
            pdo_query("ALTER  TABLE  " . tablename('nets_hjk_member_logs') . "  ADD  INDEX fm_members (  `memberid`  );");
            pdo_query("ALTER  TABLE  " . tablename('nets_hjk_members') . "  ADD  INDEX fum_members (  `from_uid`  );");
            pdo_query("update  " . tablename('nets_hjk_members') . "  set shopname='' where shopname='0';");
            pdo_query("ALTER  TABLE  " . tablename('nets_hjk_members') . "  ADD  INDEX(`jd_bitno`);");
            pdo_query("ALTER  TABLE  " . tablename('nets_hjk_probit') . "  ADD  INDEX(`bitno`);");
            if (!pdo_fieldexists('nets_hjk_members', 'from_uid2')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . "  ADD from_uid2 int(11) DEFAULT 0 NOT NULL COMMENT '上上级会员ID';");
            }

            if (!pdo_fieldexists('nets_hjk_member_logs', 'partnermemberid')) {
                pdo_query("alter table " . tablename('nets_hjk_member_logs') . " ADD partnermemberid  int(11) default 0 comment '合伙人的会员id';");
            }
            if (!pdo_tableexists('nets_hjk_applypartner')) {
                pdo_query("create table " . tablename('nets_hjk_applypartner') . "
                (
                    `id` int(10)  NOT NULL AUTO_INCREMENT,
                    `uniacid` int(10)  NOT NULL,
                     memberid int(10) comment '会员ID',
                     `name` varchar(50) DEFAULT '' COMMENT '姓名',
                     `mobile` varchar(50) DEFAULT '' COMMENT '手机号',
                     `QQ` varchar(50) DEFAULT '' COMMENT 'QQ',
                     `weixin` varchar(50) DEFAULT '' COMMENT '微信号',
                     `applyremark` varchar(50) DEFAULT '' COMMENT '申请理由',
                    `type` int(10)   DEFAULT '0' COMMENT '申请合伙人',
                    `state` int(10)   DEFAULT '0' COMMENT '状态，0待审核,1审核通过,-1申请失败(或拒绝接受)',
                    `remark` varchar(50) DEFAULT '' COMMENT '描述',
                    created_at           int(11) default 0 comment '创建时间',
                    updated_at           int(11) default 0 comment '更新时间',
                    deleted_at           int(11) default NULL comment '删除时间',
                    PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_partner_logs')) {
                pdo_query("create table " . tablename('nets_hjk_partner_logs') . "
                (
                    id                   int(11) not null auto_increment,
                    uniacid				int(11) not null comment '公众号ID',
                    memberid              varchar(255),
                    type                 tinyint(3) comment '1积分2佣金3补贴4充值5提现',
                    logno                 varchar(255),
                    title                 varchar(255),
                    status               int(11) default 0 comment '0 生成 1 成功 2 失败',
                    money                decimal(10,2) default 0.00 comment '变动的金额',
                    credit1              int comment '当前积分（获得或消费后的积分）',
                    credit2              decimal(10,2) comment '当前余额（充值或消费后的账户余额）',
                    rechargetype         varchar(255) comment '充值类型，credit1、credit2',
                    cashtype             int comment '提现类型 1支付宝2微信',
                    remark               varchar(50) comment '备注说明',
                    partnermemberid      int(11) default 0 comment '合伙人的会员id',
                    created_at           int(11) default 0 comment '创建时间',
                    updated_at           int(11) default 0 comment '更新时间',
                    deleted_at           int(11) default NULL comment '删除时间',
                    primary key (id)
                ) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COMMENT = '';");
            }
            if (!pdo_tableexists('nets_hjk_keyword')) {
                pdo_query("create table " . tablename('nets_hjk_keyword') . "
                (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `uniacid` int(11) NOT NULL,
                    `title` varchar(200) DEFAULT NULL,
                    `remark` varchar(500) DEFAULT NULL,
                    `keyword` varchar(100) DEFAULT NULL,
                    `picture` varchar(100) DEFAULT NULL,
                    `content` text DEFAULT NULL,
                    `state` int(1) DEFAULT 1 comment'1启用0禁用',
                    `created_at` int(11) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isshow_subsidy_dl')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD isshow_subsidy_dl int  DEFAULT 1 NOT NULL COMMENT '是否代理显示补贴，1是0否';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'goodposter')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD goodposter int  DEFAULT 1 NOT NULL COMMENT '海报类型，默认1，2是新海报背景';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'credit1')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . "   ADD credit1   int comment '当前积分（获得或消费后的积分）';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'credit2')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . "  ADD credit2   decimal(10,2) comment '当前余额（充值或消费后的账户余额）';");
            }

            if (!pdo_fieldexists('nets_hjk_global', 'homepage_itemjson')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD homepage_itemjson  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isopen_diypage')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD isopen_diypage int  DEFAULT 0 NOT NULL COMMENT '是否开启自定义页面，0不开启1开启';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'memberskin')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD memberskin  int  DEFAULT 0 NOT NULL COMMENT '会员中心模板0原模板1新模板，新模板不区分合伙人';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'banner2')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD banner2  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'banner3')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD banner3  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'head_menu2')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD head_menu2  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'head_menu3')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD head_menu3  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'head_menu4')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD head_menu4  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'ad_menu2')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD ad_menu2  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'ad_menu3')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD ad_menu3  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'picture_menu3')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD picture_menu3  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'picture_menu4')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD picture_menu4  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'picture_menu5')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD picture_menu5  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'picture_menu6')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD picture_menu6  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'picture_menu7')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD picture_menu7  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'picture_menu8')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD picture_menu8  text  DEFAULT '' NOT NULL COMMENT '首页布局json格式';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'isforce_mobile')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD isforce_mobile int  DEFAULT 0  COMMENT '是否强制开启绑定手机号0否1是';");
            }
            if (!pdo_tableexists('nets_hjk_wxappform')) {
                pdo_query("create table " . tablename('nets_hjk_wxappform') . "
            (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uniacid` int(11) NOT NULL,
                `memberid` int(11) NOT NULL,
                `fromid` varchar(500) DEFAULT NULL,
                `created_at` int(11) DEFAULT NULL,
                PRIMARY KEY (`id`)) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_pdd_global')) {
                pdo_query("create table " . tablename('nets_hjk_pdd_global') . "
                (
                id int(11) NOT NULL AUTO_INCREMENT,
                uniacid				int(11) not null comment '公众号ID',
                default_pid            varchar(50) DEFAULT ''  comment '拼多多默认推广位',
                couptype            int DEFAULT 0  comment '领券方式，0默认客服消息，1直接跳转',
                mobile               varchar(11) comment '联盟手机号',
                banner               text comment 'banner轮播图json数组格式',
                head_menu            text comment '头部菜单，json数组格式',
                picture_menu         text comment '图片菜单',
                picture_menu2        text comment '图片菜单2',
                ad_menu              text comment '广告图片，json格式',	  
                homepage_itemjson text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                isopen_diypage int  DEFAULT 0 NOT NULL COMMENT '是否开启自定义页面，0不开启1开启',
                banner2 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                banner3 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                head_menu2 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                head_menu3 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                head_menu4 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                ad_menu2 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                ad_menu3 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                picture_menu3 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                picture_menu4 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                picture_menu5 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                picture_menu6 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                picture_menu7 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                picture_menu8 text  DEFAULT '' NOT NULL COMMENT '首页布局json格式',
                created_at           int(11) default 0 comment '创建时间',
                updated_at           int(11) default 0 comment '更新时间',
                deleted_at           int(11) default NULL comment '删除时间',
                primary key (id)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'pdd_bitno')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . "  ADD pdd_bitno varchar(50) DEFAULT '' NOT NULL COMMENT '拼多多推广位';");
            }

            if (!pdo_fieldexists('nets_hjk_global', 'homepage_status')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD homepage_status varchar(200)  DEFAULT '' NOT NULL COMMENT '默认首页';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'hjkappid')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD  hjkappid varchar(50)  DEFAULT '' NOT NULL COMMENT '好京客小程序appid';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'couptype')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD  couptype int  DEFAULT 0 NOT NULL COMMENT '领券方式，0默认客服消息，1直接跳转';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'jdmodule_status')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD  jdmodule_status int  DEFAULT 1 NOT NULL COMMENT '京东模块状态1启用 0禁用';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'pddmodule_status')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD  pddmodule_status int  DEFAULT 1 NOT NULL COMMENT '拼多多模块状态1启用 0禁用';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'entrancead_status')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD  entrancead_status int  DEFAULT 1 NOT NULL COMMENT '入口场景广告状态1启用 0禁用';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'entrancead_pic')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD  entrancead_pic varchar(500)  DEFAULT '' NOT NULL COMMENT '广告图路径';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'entrancead_jump')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD entrancead_jump varchar(200)  DEFAULT '' NOT NULL COMMENT '跳转路径';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'jd_key')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD jd_key varchar(200)  DEFAULT '' NOT NULL COMMENT '京东授权Key';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'position_prefix')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD position_prefix varchar(100)  DEFAULT '' NOT NULL COMMENT '推广位名称前缀';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'membermenu')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . " ADD membermenu text  DEFAULT '' NOT NULL COMMENT '会员中心自定义菜单';");
            }
            pdo_query("alter table " . tablename('nets_hjk_global') . "   ENGINE=MYISAM;");
            pdo_query("DELETE FROM " . tablename('modules_bindings') . " where module='nets_haojk' AND do!='index' AND entry='menu'; ");

            //购物圈 添加
            if (!pdo_tableexists('nets_hjk_feed_goods_history')) {
                pdo_query("create table " . tablename('nets_hjk_feed_goods_history') . "
        (
               id                   int(11) not null auto_increment,   
   uniacid				int(11) not null comment '公众号ID',
   memberid             int(11) not null comment '会员ID，微擎系统会员标示',
   openid               varchar(50) not null comment '微信用户openid',
   skuid                varchar(50) not null comment '商品id',
   historytype          int(11) DEFAULT 0 NOT NULL COMMENT '类型，浏览（足迹）0，收藏1',
   deleted_at           int(11) default NULL comment '删除时间',
   created_at           int(11) default 0 comment '创建时间',
   updated_at           int(11) default 0 comment '更新时间',
   primary key (id)) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COMMENT = '购物圈-商品浏览（足迹）、收藏表';");
            }

            if (!pdo_tableexists('nets_hjk_feed_global')) {
                pdo_query("create table " . tablename('nets_hjk_feed_global') . "
        (
               id                   int(10)  not null auto_increment,
   uniacid				int(11) not null comment '公众号ID',
   top_menu            	text comment '首页头部轮播图',
   second_menu          text comment '首页第二个轮播图',
   share_rule           text comment '分享规则',
   share_logo           varchar(500) comment 'LOGO图标',
   share_title          varchar(100) comment '分享图标',
   share_remark         varchar(500) comment '分享描述',
   share_end_qrcode     varchar(500)  comment '分享完成站二维码',
   created_at           int(11) default 0 comment '创建时间',
   updated_at           int(11) default 0 comment '更新时间',
   deleted_at           int(11) default NULL comment '删除时间',
   primary key (id)) ENGINE = MyISAM AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8 COMMENT = '购物圈-系统设置表';");
            }

            if (!pdo_tableexists('nets_hjk_pdd_menu')) {
                pdo_query("create table " . tablename('nets_hjk_pdd_menu') . "
            (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `name` varchar(50) DEFAULT NULL COMMENT '分类名称',
                `url` varchar(50) DEFAULT NULL COMMENT '分类URL',
                `type` int(11) DEFAULT NULL COMMENT '菜单类型1系统菜单，2分类菜单',
                `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
                `updated_at` int(11) DEFAULT '0' COMMENT '更新时间',
                `deleted_at` int(11) DEFAULT NULL COMMENT '删除时间',
                `query` varchar(5000) DEFAULT '' COMMENT '菜单查询条件',
                `uniacid` int(11) DEFAULT NULL COMMENT '公众号id',
                `icon` varchar(200) DEFAULT '' COMMENT '菜单图标',
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
            }

            //20180515后 数据表更新
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " MODIFY COLUMN logo VARCHAR(200);");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " MODIFY COLUMN title VARCHAR(100); ");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " MODIFY COLUMN remark VARCHAR(200);");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_members") . " ADD jd_bitno varchar(50) DEFAULT '' NOT NULL COMMENT '京东推广位';");
            pdo_query("UPDATE " . tablename("nets_hjk_members") . " AS m," . tablename("nets_hjk_probit") . " AS p SET m.jd_bitno=p.bitno WHERE m.pid=p.id; ");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_members") . " ADD membermenu text  DEFAULT '' NOT NULL COMMENT '会员中心自定义菜单';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " ADD isopen_partner int  DEFAULT 0  COMMENT '是否开启合伙人0否1是';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " ADD partner_fee decimal(8,2)  DEFAULT 0  COMMENT '申请合伙人费用';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " ADD partner_commission decimal(8,2)  DEFAULT 0  COMMENT '合伙人佣金比例';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " ADD alipay_appid varchar(30)  DEFAULT '' NOT NULL COMMENT '支付宝公转私APPID';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_global") . " ADD alipay_privatekey text  DEFAULT '' NOT NULL COMMENT '支付宝公转私私钥';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_members") . " ADD from_partner_uid int(11) DEFAULT 0 NOT NULL COMMENT '来自哪个合伙人推荐的uid';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_members") . " ADD alipay_no varchar(30) DEFAULT '' NOT NULL COMMENT '支付宝账号';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_memberlevel") . " ADD max_credit2 decimal(8,2)  DEFAULT 0  COMMENT '盟主可获得最大的佣金比例';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_memberlevel") . " ADD show_max_credit2 decimal(8,2)  DEFAULT 0  COMMENT '盟主可获得最大的佣金比例,前台显示';");
            pdo_query("ALTER TABLE " . tablename("nets_hjk_wxappform") . " ADD fromtype int(11) DEFAULT 0 NOT NULL COMMENT '获得类型0默认1助力获得';");
            pdo_query("UPDATE " . tablename("nets_hjk_members") . " SET homeskinid=0;");
            pdo_query("UPDATE " . tablename("nets_hjk_global") . " SET memberskin=1;");

            if (!pdo_tableexists('nets_hjk_helpset')) {
                pdo_query("create table " . tablename('nets_hjk_helpset') . "
            (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `uniacid` int(11) DEFAULT 0 COMMENT '公众号ID',
                `title` varchar(200)  DEFAULT ''  COMMENT '标题',
                `logo` varchar(200)  DEFAULT ''  COMMENT '图标',
                `picture` varchar(400)  DEFAULT ''  COMMENT '图片',
                `remark` varchar(1000) DEFAULT '' COMMENT '描述',
                `dlremark` varchar(200) DEFAULT '' COMMENT '代理描述',
                `type` int  DEFAULT 0  COMMENT '类型1实物商品2现金',
                `money` decimal(8,2)  DEFAULT 0  COMMENT '现金金额，助力完成开奖后才可获得',
                `maxhelp` int  DEFAULT 0  COMMENT '最大助力数，完成后到时间即开奖',
                `startime` int(11)  DEFAULT 0  COMMENT '助力开始时间，-1未开始 0开始',
                `endtime` varchar(20)  DEFAULT 0  COMMENT '助力结束时间，每天固定开奖时间',
                `tplmsg` varchar(50)  DEFAULT ''  COMMENT '助力模板消息设置',
                `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_memberhelp')) {
                pdo_query("create table " . tablename('nets_hjk_memberhelp') . "
            (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `memberid` int(11) DEFAULT 0 COMMENT '会员id',
                `helpid` int(11)  DEFAULT 0  COMMENT '抽奖的id',
                `state` int(11)  DEFAULT 0  COMMENT '-1未开奖 0未中奖1中奖 2已开奖',
                `from_uid` int(11)  DEFAULT 0  COMMENT '邀请人uid，通过谁邀请进来的',
                `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
            }
            if (!pdo_tableexists('nets_hjk_helpset')) {
                pdo_query("create table " . tablename('nets_hjk_helpset') . "
            (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `memberid` int(11) DEFAULT 0 COMMENT '会员id',
                `commission` decimal(8,2)  DEFAULT 0  COMMENT '当日获得的佣金比例',
                `memberhelpid` int(11)  DEFAULT 0  COMMENT '来自哪个发起的助力获得的',
                `from_memberid` int(11) DEFAULT 0 COMMENT '会员id',
                `dataday` int(11) DEFAULT 0 COMMENT '当天日期 年月日格式',
                `created_at` int(11) DEFAULT '0' COMMENT '创建时间',
                PRIMARY KEY (`id`)
              ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
            }
            if (!pdo_fieldexists('nets_hjk_helpset', 'targetwins')) {
                pdo_query("alter table " . tablename('nets_hjk_helpset') . " ADD targetwins int  DEFAULT 1  COMMENT '中奖人数';");
            }

            if (!pdo_fieldexists('nets_hjk_memberhelp', 'fromid')) {
                pdo_query("alter table " . tablename('nets_hjk_memberhelp') . "   ADD fromid varchar(50) DEFAULT '' NOT NULL COMMENT '模板消息的formid';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'alipay_appid')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD alipay_appid varchar(30)  DEFAULT '' NOT NULL COMMENT '支付宝公转私APPID';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'alipay_privatekey')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "  ADD  alipay_privatekey text  DEFAULT '' NOT NULL COMMENT '支付宝公转私私钥';");
            }
            if (!pdo_fieldexists('nets_hjk_members', 'alipay_no')) {
                pdo_query("alter table " . tablename('nets_hjk_members') . "  ADD  alipay_no varchar(30) DEFAULT '' NOT NULL COMMENT '支付宝账号';");
            }
            if (!pdo_fieldexists('nets_hjk_global', 'islimited')) {
                pdo_query("alter table " . tablename('nets_hjk_global') . "   ADD islimited int  DEFAULT 0 NOT NULL COMMENT '是否受约束的，0默认通用的，1独立运营';");
            }
            show_message('修复成功！', webUrl('system/restore'), 'restore');
        }


        include $this->template('haojingke/system/restore');
    }

    //重建上下级关系
    public function restorerelation()
    {
        global $_GPC, $_W;

        if ($_W['ispost'] == 1) {
            $result = pdo_query("update " . tablename("nets_hjk_members") . " as u2," . tablename("nets_hjk_members") . " as u1 set u2.from_uid2=u1.from_uid WHERE u2.from_uid=u1.memberid");
            if (!empty($result)) {
                show_message('会员关系重建成功', webUrl('system/restorerelation'), 'success');
            }
        }
        include $this->template('haojingke/system/restorerelation');
    }

    //推广位-列表
    public function position()
    {
        global $_GPC, $_W;
        $uniacid = $_W['uniacid'];

        $date = array();
        if (!empty($_GPC['daterange']))
            $date = explode(' ~ ', $_GPC['daterange']);
        $starttime = 0;
        $endtime = 0;
        if (count($date) == 2) {
            $starttime = strtotime($date[0]);
            $endtime = strtotime($date[1]) + 86399;
        }
        if (count($date) == 2) {
            $starttime = strtotime($date[0]);
            $endtime = strtotime($date[1]) + 86399;
        }
        $where = '';
        if (!empty($starttime)) {
            $where .= " AND p.created_at >= " . $starttime;
        }
        if (!empty($endtime)) {
            $where .= " AND p.created_at <=" . $endtime;
        }
        if ($_GPC['pstate'] == 1) {
            $where .= " AND p.state = 1";
        }
        if ($_GPC['pstate'] == 2) {
            $where .= " AND p.state = 0";
        }
        if (!empty($_GPC['keyword'])) {
            $where .= " AND (p.memberid='" . $_GPC['keyword'] . "' or p.bitno='" . $_GPC['keyword'] . "' or p.remark='" . $_GPC['keyword'] . "')";
//            $where .= " AND ( em.nickname like '%".$_GPC['keyword']."%'  or  p.bitno like '%".$_GPC['keyword']."%' or  p.remark like '%".$_GPC['keyword']."%'or  em.openid like '%".$_GPC['keyword']."%' )";
        }
        $pindex = max(1, intval($_GPC['page']));
        $psize = 20;
        $list = pdo_fetchall("SELECT p.* FROM " . tablename("nets_hjk_probit") . " AS p where p.uniacid=:uniacid" . $where . " ORDER BY p.state desc,p.id DESC limit " . (($pindex - 1) * $psize) . ',' . $psize, array(':uniacid' => $uniacid));
        $totalcount = pdo_fetchcolumn("SELECT count(0) FROM " . tablename("nets_hjk_probit") . " AS p where p.uniacid=:uniacid  " . $where, array(':uniacid' => $uniacid));
        $usecount = pdo_fetchcolumn("SELECT count(0) FROM " . tablename("nets_hjk_probit") . " AS p where p.state=1 and p.uniacid=:uniacid  ", array(':uniacid' => $uniacid));
        $allcount = pdo_fetchcolumn("SELECT count(0) FROM " . tablename("nets_hjk_probit") . " AS p where p.uniacid=:uniacid  ", array(':uniacid' => $uniacid));
        $pager = pagination($totalcount, $pindex, $psize);


        include $this->template('haojingke/system/position');
    }


    //推广位-删除
    public function position_delete()
    {
        global $_W;
        global $_GPC;
        $uniacid = $_W['uniacid'];
        $d = pdo_fetch("SELECT * FROM " . tablename('nets_hjk_probit') . " WHERE id = " . $_GPC['id'] . " and uniacid = " . $uniacid);
        if (!empty($d)) {
            $j = pdo_delete("nets_hjk_probit", array('id' => $_GPC['id']));
            if ($j > 0) {
                $data['pid'] = 0;
                $data['type'] = 0;
                $member_pid = pdo_update('nets_hjk_members', $data, array('memberid' => $d['memberid']));
                show_json(1, "删除成功");
                if ($member_pid)
                    show_json(1, "删除成功");
                else
                    show_json(1, "删除失败");
            } else {
                show_json(1, "删除失败");
            }
        }
        show_json(1, "删除失败,推广位不存在或已删除！");
    }

    //推广位-分配
    public function position_allot()
    {
        global $_W;
        global $_GPC;
        $uniacid = $_W['uniacid'];
        $allot = $_GPC['openid'];//var_dump($allot);die;
        if (!empty($allot)) {
            $d = pdo_fetch("SELECT * FROM " . tablename('nets_hjk_probit') . " WHERE id = " . $_GPC['id'] . " and uniacid = " . $uniacid);
            $allot_data = pdo_fetch("SELECT * FROM " . tablename('nets_hjk_members') . " WHERE openid = :openid ", array(':openid' => $allot));//var_dump($allot_data);die;
            if (!empty($allot_data)) {
                $data['pid'] = $_GPC['id'];
                $data['type'] = 1;
                $data['jd_bitno'] = $d['bitno'];
                $re['memberid'] = $allot_data['memberid'];
                $re['state'] = 1;
                $re['updated_at'] = time();
                $allot_pid = pdo_update('nets_hjk_members', $data, array('openid' => $allot, 'uniacid' => $uniacid));
                $memberid = pdo_update('nets_hjk_probit', $re, array('id' => $_GPC['id']));
                if ($memberid)
                    show_json(1, "操作成功");
                else
                    show_json(1, "分配失败");
            } else {
                show_json(1, "分配失败,用户不存在");
            }
        } else
            show_json(1, "分配失败，没有提交openid");
    }


    //推广位-同步
    public function position_update()
    {
        global $_W;
        global $_GPC;
        ini_set('max_execution_time', '0');
        sync_pid();
        show_json(1, "操作成功");
    }

    //新建50个推广位
    public function position_new()
    {
        global $_W;
        global $_GPC;
        load()->func('communication');
        $global = pdo_fetch("select * from " . tablename("nets_hjk_global") . " where uniacid=:uniacid", array(":uniacid" => $_W["uniacid"]));
        $parm_jd_key = $global["jd_key"];
        $parm_jduniacid = $global["jduniacid"];
        $position_prefix = $global["position_prefix"];
        $parm_position_prefix = "";
        for ($x = 1; $x <= 50; $x++) {
            $parm_position_prefix = $parm_position_prefix . $position_prefix . $x . ",";
        }
        $url = HAOJK_HOST . "index/createjdpid";
        $data = array(
            'unionId' => $parm_jduniacid,
            'key' => $parm_jd_key,
            'pidtype' => 4,
            'spaceName' => $parm_position_prefix
        );
        $res = ihttp_post($url, $data);
        $pids = $res["content"];
        $pids = json_decode($pids);
        if ($pids->message == "error") {
            show_json(1, $pids->data->message);
        }
        $pids = $pids->data->resultList;
        $pids = json_decode(json_encode($pids), true);
        if (empty($pids)) {
            return;
        }
        $y = 1;
        foreach ($pids AS $p) {
            if (empty($p)) {
                continue;
            }
            $p1["uniacid"] = $_W["uniacid"];
            $p1["bitno"] = $p;
            $p1["state"] = 0;
            $p1["memberid"] = 0;
            $p1["remark"] = $position_prefix . $y;
            $p1["created_at"] = time();
            if (!empty($jduniacid)) {
                $p1["jduniacid"] = $jduniacid;
            }
            pdo_insert("nets_hjk_probit", $p1);
            $y++;
        }
        show_json(1, "操作成功");
    }

    //从公众号同步推广位
    public function position_sync()
    {
        global $_W;
        global $_GPC;
        load()->func('communication');
        $global = pdo_fetch("select * from " . tablename("nets_hjk_global") . " where uniacid=:uniacid", array(":uniacid" => $_W["uniacid"]));
        $wxapp_uniacid = $global["wxapp_uniacid"];
        $sql = "INSERT " . tablename("nets_hjk_probit") . "(uniacid,bitno,state,remark,created_at) 
        SELECT DISTINCT " . $_W["uniacid"] . ",bitno,0,remark,UNIX_TIMESTAMP(NOW()) from " . tablename("nets_hjk_probit") . " 
        AS A where A.uniacid=" . $wxapp_uniacid . " and not EXISTS(select 1 from " . tablename("nets_hjk_probit") . " AS B where B.uniacid=" . $_W['uniacid'] . " and B.bitno=A.bitno)";
        $result = pdo_query($sql);
        if (!empty($result)) {
            show_json(1, "操作成功");
        } else {
            show_json(0, "操作失败");
        }
    }
}
?>