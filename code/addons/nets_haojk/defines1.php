<?php
if (!(defined('IN_IA'))) 
{
	exit('Access Denied');
}
define('NETS_HAOJIK_DEBUG', true);
define("HAOJK_HOST", "http://api1.haojingke.com/index.php/api/");
define("jd_orderstate",json_encode(array(
	array("name"=>"全部","value"=>''),
	array("name"=>"待付款","value"=>15),
	array("name"=>"已付款","value"=>16),
	array("name"=>"已完成","value"=>17),
	array("name"=>"已结算","value"=>18)
)));
define("pdd_orderstate",json_encode(array(
	array("name"=>"全部","value"=>''),
	array("name"=>"未支付","value"=>-1),
	array("name"=>"已支付","value"=>'0'),
	array("name"=>"已成团","value"=>1),
	array("name"=>"确认收货","value"=>2),
	array("name"=>"审核成功","value"=>3),
	array("name"=>"审核失败","value"=>4),
	array("name"=>"已经结算","value"=>5),
	array("name"=>"无佣金订单","value"=>8)
)));
define("mgj_orderstate",json_encode(array(
	array("name"=>"全部","value"=>''),
	array("name"=>"无效订单","value"=>-1),
	array("name"=>"已支付","value"=>'0'),
	array("name"=>"确认收货","value"=>2),
	array("name"=>"已结算","value"=>5)
)));
!(defined('NETS_HAOJIK_PATH')) && define('NETS_HAOJIK_PATH', IA_ROOT . '/addons/nets_haojk/');
!(defined('NETS_HAOJIK_CORE')) && define('NETS_HAOJIK_CORE', NETS_HAOJIK_PATH . 'core/');
!(defined('NETS_HAOJIK_INC')) && define('NETS_HAOJIK_INC', NETS_HAOJIK_CORE . 'inc/');
!(defined('NETS_HAOJIK_FUNC')) && define('NETS_HAOJIK_FUNC', NETS_HAOJIK_CORE . 'function/');
!(defined('NETS_HAOJIK_VENDOR')) && define('NETS_HAOJIK_VENDOR', NETS_HAOJIK_PATH . 'vendor/');
!(defined('NETS_HAOJIK_CORE_WEB')) && define('NETS_HAOJIK_CORE_WEB', NETS_HAOJIK_CORE . 'web/');
!(defined('NETS_HAOJIK_CORE_MOBILE')) && define('NETS_HAOJIK_CORE_MOBILE', NETS_HAOJIK_CORE . 'mobile/');
!(defined('NETS_HAOJIK_PLUGIN')) && define('NETS_HAOJIK_PLUGIN', NETS_HAOJIK_PATH . 'plugin/');
!(defined('NETS_HAOJIK_URL')) && define('NETS_HAOJIK_URL', $_W['siteroot'] . 'addons/nets_haojk/');
!(defined('NETS_HAOJIK_LOCAL')) && define('NETS_HAOJIK_LOCAL', '../addons/nets_haojk/');
!(defined('NETS_HAOJIK_STATIC')) && define('NETS_HAOJIK_STATIC', NETS_HAOJIK_URL . 'static/');
!(defined('NETS_HAOJIK_WEB_STYLE')) && define('NETS_HAOJIK_WEB_STYLE',  '/addons/nets_haojk/template/web/haojingke/style/');
!(defined('NETS_HAOJIK_NUMBER')) && define('NETS_HAOJIK_NUMBER', 'FALSE');
!(defined('NETS_HAOJIK_NUMBER')) && define('NETS_HAOJIK_NUMBER', 'FALSE');
!(defined('JD_APPID')) && define('JD_APPID', 'wx13e41a437b8a1d2e');
!(defined('JD_CUSTOMERINFO')) && define('JD_CUSTOMERINFO', 'fengyuntai');
?>