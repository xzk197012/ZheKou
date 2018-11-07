<?php
	/**
	 * 对外开放的api,不需要验证用户身份
	 * API 地址：http://localhost:801/app/index.php?i=56&c=entry&m=nets_haojk&do=freeorderapi 
	 * 请求方式：POST，ajaxurl生成方式：
	 * var posturl = "{php echo url('entry',array('m'=>'nets_haojk','do'=>'freeorderapi','oper'=>'下面的方法名'))}";
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
	* 获取免单商品
	* 参数1：page，当前页数
	*/
	function ajaxGetFreeGoods(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$page=$_GPC['page'];
		$res=getFreeGoods($page);
		return array('code'=>0,'message'=>'success','res'=>$res);
	}
	/*
	* 我要砍价,加入到我的砍价列表
	* 参数1：skuId，商品skuId,传0则返回我的砍价列表
	*/
	function ajaxSetFreeGoodsDetail(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuId=$_GPC['skuId'];
		$memberid=$_W['user_info']['memberid'];
		$res=setFreeGoodsDetail($skuId,$memberid);
		return array('code'=>0,'message'=>'success','res'=>$res);
	}
	/*
	* 我的某个商品的砍价明细
	* 参数1：skuId，商品skuId
	*/
	function ajaxGetMyCuttingDetail(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$skuId=$_GPC['skuId'];
		$memberid=$_W['user_info']['memberid'];
		$res=getMyCuttingDetail($skuId,$memberid);
		return array('code'=>0,'message'=>'success','res'=>$res);
	}
	/*
	* 我的某个商品的砍价明细
	* 参数1：cuttingid，砍价明细中的id参数
	*/
	function ajaxHelpCutting(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$cuttingid=$_GPC['cuttingid'];
		$memberid=$_W['user_info']['memberid'];
		$res=helpCutting($cuttingid,$memberid);
		return array('code'=>0,'message'=>'success','res'=>$res);
	}

	/*
	* 提交订单
	* 参数1 orderno 订单号
	*/
	function ajaxFreeordersubmit(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$orderno=$_GPC['orderno'];
		$memberid=$_W['user_info']['memberid'];
		$checkorder=checkorders_byorderno($orderno);
		//var_dump($checkorder);
		if(empty($checkorder["data"])){
			return array('code'=>0,'message'=>'error','res'=>"云服务暂未查到该订单，请稍后再试");
		}else{
			$skuId= $checkorder["data"][0]['skus'][0]['skuId'];
			$checkisfreeordergoods=pdo_fetch("select * from ".tablename("nets_hjk_membercutting")." where skuId=:skuId",array(":skuId"=>$skuId));
			if(empty($checkisfreeordergoods)){
				return array('code'=>0,'message'=>'error','res'=>"订单号错误，未找到砍价商品订单中存在砍价商品");
			}
		}
		$res=freeordersubmit($orderno,$memberid);

		return array('code'=>0,'message'=>'success','res'=>$res);
	}
	/*
	* 查询我的订单
	*/
	function ajaxGetfreeorders(){
		global $_GPC, $_W;
		$errno = 0;
		$message="操作成功";
		$memberid=$_W['user_info']['memberid'];
		$res=getfreeorders($memberid);
		return array('code'=>0,'message'=>'success','res'=>$res);
	}

?>