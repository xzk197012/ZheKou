<?php


/**
 * 设置商品到免单列表
 */
function setGoodsFreeOrder($goods,$cuttingnum){
	global $_W;
	global $_GPC;
	$data['uniacid']=$_W['uniacid'];
	$data['skuId']=$goods->skuId;
	$data['skuName']=$goods->skuName;
	$data['picUrl']=$goods->picUrl;
	$data['wlPrice']=$goods->wlPrice;
	$data['wlPrice_after']=$goods->wlPrice_after;
	$data['goodjson']=json_encode($goods);
	$data['cuttingnum']=$cuttingnum;
	$data['cuttingprice']=0;
	$data['createtime']=time();
	$i=pdo_insert("nets_hjk_freegoods",$data);
	return $i;
}
//分页查询免单商品
function getFreeGoods($page){
	global $_W;
	global $_GPC;
	$pagesize=200;
	$where=" WHERE uniacid=".$_W['uniacid'];
	$list=pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_freegoods").$where." order by sort desc LIMIT ".(($page-1)*$pagesize).",".$pagesize);
	return $list;	
}

//我要砍价,加入到我的砍价列表
function setFreeGoodsDetail($skuId,$memberid){
	global $_W;
	global $_GPC;
	$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid and em.memberid=:memberid",array(":uniacid"=>$_W["uniacid"],":memberid"=>$memberid));
	
	if(!empty($skuId)){
		$detail=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_freegoods")." WHERE uniacid=:uniacid and skuId=:skuId",array(":uniacid"=>$_W['uniacid'],":skuId"=>$skuId));
		$data['uniacid']=$_W['uniacid'];
		$data['memberid']=$memberid;
		$data['avatar']=$member['avatar'];
		$data['nickname']=$member['nickname'];
		$data['skuId']=$detail['skuId'];
		$data['skuName']=$detail['skuName'];
		$data['picUrl']=$detail['picUrl'];
		$data['wlPrice']=$detail['wlPrice'];
		$data['wlPrice_after']=$detail['wlPrice_after'];
		$data['goodjson']=json_encode($detail);
		$data['createtime']=time();
		$membercutting=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_membercutting")." WHERE uniacid=:uniacid and skuId=:skuId and memberid=:memberid",array(":uniacid"=>$_W['uniacid'],":skuId"=>$skuId,":memberid"=>$memberid));
		
		if(empty($membercutting)){
			$i=pdo_insert("nets_hjk_membercutting",$data);
			if($i>0){
				//发送一条模板消息
				$openid=$_W['openid'];
				$detailurl=$_W['siteroot']."/app/index.php?i=".$_W['uniacid']."&c=entry&do=bargainyou&m=nets_haojk&skuId=".$detail['skuId']."&from_uid=".$memberid;//.substr($detailurl,2,strlen($detailurl));
				$url=$detailurl;
				$data= array();
				$first=array();
				$first['value']="我发起了【".$detail['skuName']."】的砍价，赶快邀请好友来助阵吧";
				$first['color']="#173177";
				$data['first']=$first;
				
				$keyword1=array();
				$keyword1['value']="砍价免费拿";
				$keyword1['color']="#173177";
				$data['keyword1']=$keyword1;
				

				$keyword2=array();
				$keyword2['value']="砍价";
				$keyword2['color']="#173177";
				$data['keyword2']=$keyword2;
				

				$keyword3=array();
				$keyword3['value']="邀请好友";
				$keyword3['color']="#173177";
				$data['keyword3']=$keyword3;
				

				$remark=array();
				$remark['value']="点击去邀请好友";
				$remark['color']="#ff0404";
				$data['remark']=$remark;
				
				$res=sendTemplateMsgBySingle($openid,$data,$url);	
				//var_dump($res);
			}
		}else{
			//echo $membercutting['createtime']-time();
			if($membercutting['createtime']-time()<-1*1*24*60*60){
				pdo_delete("nets_hjk_membercutting",array("id"=>$membercutting['id']));
				$i=pdo_insert("nets_hjk_membercutting",$data);
				//$i=pdo_update("nets_hjk_membercutting",$data,array("uniacid"=>$_W['uniacid'],"skuId"=>$skuId,"memberid"=>$memberid));
			}
		}
	}
	$mycuttinglist=pdo_fetchall("SELECT *,(wlPrice_after-(SELECT sum(cuttingprice)  FROM ".tablename("nets_hjk_membercutting_rec")." WHERE uniacid=:uniacid and cuttingid=r.id))  AS 'yikan'  FROM ".tablename("nets_hjk_membercutting")." AS r WHERE uniacid=:uniacid and memberid=:memberid",array(":uniacid"=>$_W['uniacid'],":memberid"=>$memberid));
	
	return $mycuttinglist;
}
//我的砍价明细
function getMyCuttingDetail($skuId,$memberid){
	global $_W;
	global $_GPC;
	$goods=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_freegoods")." WHERE uniacid=:uniacid and skuId=:skuId",array(":uniacid"=>$_W['uniacid'],":skuId"=>$skuId));
	
	$detail=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_membercutting")." WHERE uniacid=:uniacid and memberid=:memberid and skuId=:skuId order by id",array(":uniacid"=>$_W['uniacid'],":memberid"=>$memberid,":skuId"=>$skuId));
	//var_dump($detail);
	if(empty($detail)){
		setFreeGoodsDetail($skuId,$memberid);
		$detail=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_membercutting")." WHERE uniacid=:uniacid and memberid=:memberid and skuId=:skuId order by id",array(":uniacid"=>$_W['uniacid'],":memberid"=>$memberid,":skuId"=>$skuId));
	
	}
	$cuttinglist=pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_membercutting_rec")." WHERE uniacid=:uniacid and cuttingid=:id limit 0,15",array(":uniacid"=>$_W['uniacid'],":id"=>$detail['id']));
	$cuttingmsg=array('花钱买，不如砍价免费拿','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀','路见砍价，随手一砍','如果能重来，我要砍的嗨','看来老夫宝刀已老','一刀接一刀，我也砍一刀','看我的青龙偃月刀');
	
	for($i=0;$i<count($cuttinglist);$i++){
		$cuttinglist[$i]['cuttmsg']=$cuttingmsg[$i];
	}
	//var_dump($cuttinglist);
	$cuttingprice=pdo_fetch("SELECT sum(cuttingprice) AS 'cuttingprice'  FROM ".tablename("nets_hjk_membercutting_rec")." WHERE uniacid=:uniacid and cuttingid=:id",array(":uniacid"=>$_W['uniacid'],":id"=>$detail['id']));
	if(empty($cuttingprice['cuttingprice'])){
		$cuttingprice['cuttingprice']=0;
	}

	$detail['cuttinglist']=$cuttinglist;
	$detail['cuttingprice']=$cuttingprice['cuttingprice'];
	$detail['goods']=$goods;
	return $detail;	
}
//帮砍价
function helpCutting($cuttingid,$memberid){
	global $_W;
	global $_GPC;
	$detail=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_membercutting")." WHERE uniacid=:uniacid and id=:cuttingid",array(":uniacid"=>$_W['uniacid'],":cuttingid"=>$cuttingid));
	$cuttingopenid="";
	$cuttingmemberid="";
	if(!empty($detail)){
		$cuttingmember=pdo_fetch("select * from ".tablename("nets_hjk_members")." where memberid=:memberid",array(":memberid"=>$detail['memberid']));
		$cuttingopenid=$cuttingmember['openid'];
		$cuttingmemberid=$cuttingmember['memberid'];
	}
	//已砍的价格
	$cuttingprice=pdo_fetch("SELECT sum(cuttingprice) AS 'cuttingprice'  FROM ".tablename("nets_hjk_membercutting_rec")." WHERE uniacid=:uniacid and cuttingid=:id",array(":uniacid"=>$_W['uniacid'],":id"=>$cuttingid));
	$cuttingprice=$cuttingprice['cuttingprice'];
	if(empty($cuttingprice)){
		$cuttingprice=0;
	}
	//已砍的人数
	$cuttingnum=pdo_fetch("SELECT count(0) AS 'cuttingnum'  FROM ".tablename("nets_hjk_membercutting_rec")." WHERE uniacid=:uniacid and cuttingid=:id",array(":uniacid"=>$_W['uniacid'],":id"=>$cuttingid));
	$cuttingnum=$cuttingnum['cuttingnum'];
	if(empty($cuttingnum)){
		$cuttingnum=1;
	}else{
		$cuttingnum=$cuttingnum+1;
	}
	
	$skuId=$detail['skuId'];
	$goods=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_freegoods")." WHERE uniacid=:uniacid and skuId=:skuId",array(":uniacid"=>$_W['uniacid'],":skuId"=>$skuId));
	//$cuttingprice=$goods['wlPrice_after']/$goods['cuttingnum'];
	//随机砍价金额
	$min=0.01;
	$max=$goods['wlPrice_after']-$cuttingprice;
	//$total=$total-$money;
	$max=($max-($goods['cuttingnum']-$cuttingnum)*$min)/($goods['cuttingnum']-$cuttingnum);
	
	// if($max>$goods['wlPrice_after']/2){
	// 	$max=$goods['wlPrice_after']/2;
	// }
	//最后一个砍价名额了
	if($goods['cuttingnum']==$cuttingnum){
		$max=$goods['wlPrice_after']-$cuttingprice;
		//echo $goods['wlPrice_after']."_".$cuttingprice;
		//exit($max);
		$cuttingprice=$max;
		//发送一条模板消息
		$openid=$_W['openid'];
		$detailurl=$_W['siteroot']."/app/index.php?i=".$_W['uniacid']."&c=entry&do=bargainyou&m=nets_haojk&skuId=".$detail['skuId']."&from_uid=".$cuttingmemberid;//.substr($detailurl,2,strlen($detailurl));
		$url=$detailurl;
		$data= array();
		$first=array();
		$first['value']="恭喜您砍价成功！商品【".$goods['skuName']."】已砍到0元，赶快来购买吧";
		$first['color']="#173177";
		$data['first']=$first;
		
		$keyword1=array();
		$keyword1['value']="砍价免费拿";
		$keyword1['color']="#173177";
		$data['keyword1']=$keyword1;
		

		$keyword2=array();
		$keyword2['value']="砍价";
		$keyword2['color']="#173177";
		$data['keyword2']=$keyword2;
		

		$keyword3=array();
		$keyword3['value']="免费领券";
		$keyword3['color']="#173177";
		$data['keyword3']=$keyword3;
		

		$remark=array();
		$remark['value']="点击立即去购买";
		$remark['color']="#ff0404";
		$data['remark']=$remark;
		if(!empty($cuttingopenid)){
			$res=sendTemplateMsgBySingle($cuttingopenid,$data,$url);
		}
	}else{
		$cuttingprice=randomFloat($min,$max);
	}
	
	$member=pdo_fetch("SELECT em.*,m.credit1,m.credit2 FROM ".tablename("nets_hjk_members")." AS em left join ".tablename("mc_members")." AS m on em.memberid=m.uid  where em.uniacid=:uniacid and em.memberid=:memberid",array(":uniacid"=>$_W["uniacid"],":memberid"=>$memberid));
	$checknum="SELECT count(0) AS 'count' FROM ".tablename("nets_hjk_membercutting_rec")."  WHERE memberid=:memberid 
	and DATE_FORMAT(FROM_UNIXTIME(createtime),'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d') ";
	$checkcount=pdo_fetch($checknum,array(":memberid"=>$memberid));
	//echo $checknum."_".$memberid;
	//var_dump($checkcount);
	//return $checkcount['count'];
	if($checkcount['count']>=3){
		return -1;
	}
	
	$checkcutting="SELECT count(0) AS 'count' FROM ".tablename("nets_hjk_membercutting_rec")."  WHERE memberid=:memberid and cuttingid=:cuttingid";
	$checkcuttingcount=pdo_fetch($checkcutting,array(":memberid"=>$memberid,":cuttingid"=>$cuttingid));
	if($checkcuttingcount['count']==1){
		//该砍价已砍过
		return -2;
	}
	$cuttingprice=number_format($cuttingprice,2);
	if($cuttingprice==0){
		return 0;
	}
	$data1['uniacid']=$_W['uniacid'];
	$data1['memberid']=$memberid;
	$data1['cuttingid']=$cuttingid;
	$data1['nickname']=$member['nickname'];
	$data1['avatar']=$member['avatar'];
	$data1['cuttingprice']=$cuttingprice;
	$data1['createtime']=time();
	$i=pdo_insert("nets_hjk_membercutting_rec",$data1);
	if($i>0){
		return $cuttingprice;
	}else{
		return 0;
	}
}
function randomFloat($min = 0, $max = 1) {
    return mt_rand($min*100,$max*100)/100; 
}

//提交订单
function freeordersubmit($orderno,$memberid){
	global $_W;
	global $_GPC;
	$data['uniacid']=$_W['uniacid'];
	$data['memberid']=$memberid;
	$data['orderno']=$orderno;
	$data['created_at']=time();
	$data['updated_at']=time();
	$data['deleted_at']=time();
		
	$order=pdo_fetch("SELECT *  FROM ".tablename("nets_hjk_freeorders")." WHERE uniacid=:uniacid and orderno=:orderno ",array(":uniacid"=>$_W['uniacid'],":orderno"=>$orderno));
	$i=0;
	if(empty($order)){
		$i=pdo_insert("nets_hjk_freeorders",$data);
	}
	return $i;
}
//查询订单
function getfreeorders($memberid){
	global $_W;
	global $_GPC;
	$order=pdo_fetchall("SELECT *  FROM ".tablename("nets_hjk_freeorders")." WHERE uniacid=:uniacid and memberid=:memberid",array(":uniacid"=>$_W['uniacid'],":memberid"=>$memberid));
	return $order;
}
?>