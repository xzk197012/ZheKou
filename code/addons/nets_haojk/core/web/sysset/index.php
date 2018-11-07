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

		if ($_W['action']=='sysset.index'||$_W['action']=='sysset')
		{
		    $this->index();
		}
//		else
//		{
//			header('location: ' . webUrl());
//		}
	}
	public function index()
	{
		global $_W;
		global $_GPC;
        include $this->template('haojingke/index');
	}

}
?>