<?php
	/**
	 * 对外开放的api,不需要验证用户身份
	 * API 地址：http://localhost:801/app/index.php?i=56&c=entry&m=nets_haojk&do=usegoodsapi
	 * 请求方式：POST，ajaxurl生成方式：
	 * var posturl = "{php echo url('entry',array('m'=>'nets_haojk','do'=>'usegoodsapi','oper'=>'下面的方法名'))}";
	 * 参数：
	 * 1. oper：执行的操作，枚举可选下面对应的函数名
	 */
	defined('IN_IA') or exit('Access Denied');
	require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
	require_once IA_ROOT . '/addons/nets_haojk/function/freeorder.func.php';
	global $_GPC, $_W;
	getUserInfo(1);
	//var_dump($_W['user_info']);
	$oper=$_GPC["oper"];
	$openid=$_W["openid"];
	$result=array("code"=>500,"result"=>0,"msg"=>"操作失败,不允许的操作");
	//通过传递的oper调用对应的函数,oper参数与函数名一致
	if (function_exists($oper)) {
		$result=$oper();
	}
	
	exit(json_encode($result));
	
	/*
	* 获取自定义分类
	*/
	function ajaxGetUseCate(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$page=$_GPC['page'];
		$res = pdo_fetchall("SELECT id,name,icon FROM ".tablename('nets_hjk_menu')." where uniacid=:uniacid and type=3 ORDER BY id DESC",array(":uniacid"=>$_W['uniacid']));

		return array('code'=>0,'message'=>'success','res'=>$res);
	}
	/*
	* 我要砍价,加入到我的砍价列表
	* 参数1：page，当前页数
	* 参数2：cateid，分类id
	*/
	function ajaxGetUseCateGoods(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$page=$_GPC['page'];
		$cateid=$_GPC['cateid'];
		$res=getMyuseselGoods($page,$cateid);
		return array('code'=>0,'message'=>'success','res'=>$res);
	}
	function getMyuseselGoods($page,$cateid){
		global $_GPC,$_W;	
		$global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
		
		$pagesize=20;
		$total=pdo_fetch("select count(0) AS 'total' from ".tablename("nets_hjk_usegoods")." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
		$total=empty($total['total'])?0:$total['total'];
		$where="";
		if(!empty($cateid)){
			$where=" and menuid=".$cateid;
		}
		$usegoods=pdo_fetchall("select * from ".tablename("nets_hjk_usegoods")." where uniacid=:uniacid ".$where."  order by sort desc limit " . (($page - 1) * $pagesize) . ',' .$pagesize,array(":uniacid"=>$_W['uniacid']));
	
		$skuids="";
		foreach($usegoods AS $ug){
			$skuids.=$ug['skuId'].",";
		}
		$skuids=substr($skuids,0,strlen($skuids)-1);
		$operation = !empty($_GPC['op'])?$_GPC['op']:'useselgoods';
		$data = array(
			'unionId' => $global['jduniacid'],
			'skuIds' => $skuids,
		);
		$url=HAOJK_HOST."index/goodslisbyskuids";
		$temp_url="index/goodslisbyskuids".$_W['uniacid'];
		$filename=getfilename($temp_url);
		load()->func('communication');
		$res=ihttp_post($url,$data);
		$res=$res["content"];	
		$list=json_decode($res);
		$total=$list->total;
		$list=$list->data;
		$json_string = json_encode($list);
		file_put_contents($filename, $json_string);
		$json=file_get_contents($filename);
		$list=json_decode($json, true);
		for($i=0;$i<count($list);$i++){
			foreach($usegoods AS $ug){
				if($ug['skuId']==$list[$i]['skuId']){
					$list[$i]['sort']=$ug['sort'];
					$list[$i]['id']=$ug['id'];
				}
			}
		}
		//var_dump($list);
		return $list;
	}

?>