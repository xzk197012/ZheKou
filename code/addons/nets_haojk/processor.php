<?php
/**
 * hao模块处理程序
 *
 * @author imseal168
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.pdd.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/mobile.func.php';
require_once IA_ROOT . '/addons/nets_haojk/function/img.func.php';
require_once IA_ROOT . '/addons/nets_haojk/defines.php';
class Nets_haojkModuleProcessor extends WeModuleProcessor {
	
	public function postText($openid, $text) {
		$post = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $text . '"}}';
		$ret = $this->postRes($this->getAccessToken(), $post);
		return $ret;
	}
	private function postRes($access_token, $data) {
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token={$access_token}";
		load()->func('communication');
		$ret = ihttp_request($url, $data);
		$content = @json_decode($ret['content'], true);
		return $content['errcode'];
	}
	
	private function getAccessToken() {
		global $_W;
		load()->model('account');
		$acid = $_W['acid'];
		if (empty($acid)) {
			$acid = $_W['uniacid'];
		}
		$account = WeAccount::create($acid);
        $token = $account->getAccessToken();
		return $token;
	}
	private function sendImage($openid, $media_id) {
	    $data = array(
	      "touser"=>$openid,
	      "msgtype"=>"image",
	      "image"=>array("media_id"=>$media_id));
	    $ret = $this->postRes($this->getAccessToken(), json_encode($data));
	    return $ret;
	  }

	private function uploadImage($img) {
		//return $img;
		//$img='/addons/nets_haojk/cache/1_bg.png';
		$file_info=array(
			'filename'=>$img,  //图片相对于网站根目录的路径
			'content-type'=>'image/png',  //文件类型
			'filelength'=>'90011'         //图文大小
		);
		$token=$this->getAccessToken();
		$url="https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$token}&type=image";
		$curl = curl_init ();
		$real_path=$_SERVER['DOCUMENT_ROOT'].$file_info['filename'];
		
		if(!file_exists($real_path)){
			return  array('code'=>404,'value'=>'文件['.$real_path.']不存在');
		}
		$data= array("media"=>new CURLFile(realpath($real_path)));
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_TIMEOUT, 60);
		//curl_setopt($curl, CURLOPT_SAFE_UPLOAD, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        
		$result = curl_exec ( $curl );
		
		curl_close ( $curl );
		$res=json_decode($result,true);
		if(!empty($res['media_id'])){
			return array('code'=>200,'value'=>$res['media_id']);
		}
		return  array('code'=>500,'value'=>$res['errmsg']);
	}
	
	public function respond() {
		global $_W;
		$response=array();
		$message = $this->message;
		$content = $this->message['content'];
		$msgtype = strtolower($message['msgtype']);
		$event = strtolower($message['event']);
		$content = $message['content'];
		//先通过openid查询fans表，取消uniacid条件 zxq.2018.05.19 in processor.php in 116
		$fans=pdo_get('mc_mapping_fans',array('openid'=>$this->message['from']));
		$member=pdo_get('nets_hjk_members',array('memberid'=>$fans["uid"]));
		$_W['fans']['uid']=$fans["uid"];//这里赋值全局fans的uid 里面函数直接使用
		//验证自定义关键词回复，优先级最高
		if($this->message["type"]=="text"){
			$searchkey = pdo_fetch("SELECT id,keyword,picture,title,remark FROM " .tablename('nets_hjk_keyword'). " WHERE uniacid =:uniacid and state=1 and keyword=:keyword",array(':uniacid'=>$_W['uniacid'],':keyword'=>$content));
			if(!empty($searchkey['keyword'])){
				$helpurl=$_W['siteroot']."app/index.php?i=".$_W["uniacid"]."&c=entry&m=nets_haojk&do=helpmsg&k=".$searchkey['id'];
					 
				$array=array(
					'title' => $searchkey['title'],
					'description' => $searchkey['remark'],
					'picurl' => $searchkey['picture'],
					'url' => $helpurl, 
					'tagname'=>'item'
				);
				$response[]=$array;
				return $this->respNews($response);
			}
		}
		if($this->message["type"]=="text" && ($content=="我的海报" || $content=="京东海报")){
			$qrpath="";
			$posterpath="";
			$media_id="";
			$glbal=pdo_fetch("select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid",array(":uniacid"=>$_W['uniacid']));
				
			if(true){
				$qrpath=getfllowqrcode($member['memberid']);
				$posterSize=5;
				$poster=new Poster();
				$res=$poster->getQrPoster($member["memberid"],0,$qrpath);
				$posterpath=$res['res'];
				$posterpath="/addons/nets_haojk/cache/".$member['memberid']."_exqrcode.jpg";
				$res = $this->uploadImage($posterpath);  
				if($res['code']==200){
					$media_id=$res['value'];
					return $this->respImage($media_id);
				}else{
					return $this->respText(json_encode($res));
				} 
			}
			return $this->respText("正在生成你的专属海报，请稍后……");
		}
		if($this->message["type"]=="text"){
			$keyword=str_replace('买','',$content);
			$keyword=str_replace('找','',$keyword);
			$keyword=str_replace('京东','',$keyword);
			$keyword=str_replace('拼多多','',$keyword);
			$keyword=str_replace(' ','',$keyword);
			$op="";
			$isgoodurl=false;
			//直接发链接的,京东
			if(!strpos($content,'yangkeduo.com') && !strpos($content,'领券') && preg_match('/(https|http):\/\/[\w.]+[\w\/]*[\w.]*\??[\w=&\+\%]*/is',$keyword)){
				$op="byskuid";
				$jdurl=$keyword;
				
				if(strpos($jdurl,'.html') !== false){
					$isgoodurl=true;
					$jdurl_arr=explode('?',$jdurl);
					$jdurl=$jdurl_arr[0];	
					$strarr=explode("/",$jdurl);
					$keyword=$strarr[count($strarr)-1];
					$keyword=str_replace(".html","",$keyword);
				}
				if(strpos($jdurl,'sku=') !== false){
					$isgoodurl=true;
					$jdurl_arr=explode('sku=',$jdurl);
					$jdurl=$jdurl_arr[1];
					$strarr=explode("&",$jdurl);
					$keyword=$strarr[0];
				}
				
			}
			//只支持京东的文案转链，过滤拼多多的链接
			if(!strpos($content,'yangkeduo.com') && !$isgoodurl){
				//发推广文案的提取二合一链接
				preg_match_all('/(https|http)?:\/\/union-click[\w-.%#?\/\\\=]+/i',$content,$urllist); 
				$unionurl_old=$urllist[0][0];
				//02.提取短链接文案内容

				preg_match_all('/http?:\/\/[\w-.%#?&\/\\\=]+/i',$content,$urllist2);
                $unionurl_old2=$urllist2[0][0];
				//03.内容中含有skuId
                $skucontents=$content;
                $skucontents=str_replace($unionurl_old2,'',$skucontents);
                preg_match_all('/\d{5,20}/i',$skucontents,$skuidnumber);
				$skuid=$skuidnumber[0][0];
				
				if(!empty($unionurl_old)){
					$openid=$this->message['from'];
					$keyword=$unionurl_old;
					$res=getunionurl($unionurl_old,$unionurl_old,null);
					$content=str_replace($unionurl_old,$res->data,$content);
					return $this->respText($content);
				}else if(!empty($unionurl_old2) && !empty($skuid)){
					// $text=$unionurl_old2;
					// $text.="  ".$skuid;
					// preg_match_all('/P\d{2}/',$content,$tempstrlist);
					// $tempstr=$tempstrlist[0][0];
					// $text.="  ".$tempstr;
					//return $this->respText($unionurl_old2."___".$skuid);
					$res=getunionurl($unionurl_old2,$skuid,null);
					$content=str_replace($unionurl_old2,$res->data,$content);
					$pattern = '/(.*)'.$skuid."(.*)/";
					preg_match_all($pattern,$content,$removelist);
					$removestr=$removelist[0];
					$content=str_replace($removestr,"",$content);
					return $this->respText($content);
				}	
			}
			$page=1;
			$pagesize=20;
			$sort=0;//最高佣金排序
			$minprice="";
			$maxprice="";
			$mincommission="";
			$maxcommission="";
			$cname="";
			$goodstype="";
			$sortby="";
			$goods= array();
			
			if($op=="byskuid"){
				$res=getunionurlbysku($keyword);
				if(!empty($res->data) && !empty($res->info)){
					
					$g=array(
						'detail'=>$res->info->detail
						,'skuName'=>$res->info->skuName
						,'wlPrice'=>$res->info->wlPrice
						,'discount'=>$res->info->discount
						,'wlCommissionShare'=>$res->info->wlCommissionShare
					,'skuId'=>$res->info->skuId
					,'wlPrice_after'=>$res->info->wlPrice_after
					,'skuDesc'=>$res->info->skuDesc
					,'uninonUrl'=>$res->data
					,'picUrl'=>$res->info->picUrl);
					$goods[]=$g;
				}
			}else{
				if(strpos($content,'京东') !== false){
					$goods=getgoodlist("listall",$page,$pagesize,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname,$goodstype,$goodslx,$sortby);
				}elseif(strpos($content,'拼多多') !== false || strpos($content,'yangkeduo.com') !== false){
					$_GPC['page']=1;
					$_GPC['pageSize']=1;
					$_GPC["keyword"]=$keyword;
					haojk_log("拼多多筛选关键词：".$keyword);
					$goods=getpdd_goodlist($keyword);
				}else{
					$goods=getgoodlist("listall",$page,$pagesize,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname,$goodstype,$goodslx,$sortby);
				
				}
			}
			
			$num=0;
			$openid=$this->message['from'];
			$fans=pdo_fetch("select * from ".tablename('mc_mapping_fans')." where openid=:openid",array(":openid"=>$this->message['from']));
			$glbal=pdo_fetch("select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid",array(":uniacid"=>$fans['uniacid']));
			$member=pdo_get('nets_hjk_members',array('uniacid'=>$fans['uniacid'],'memberid'=>$fans['uid']));
			$level=pdo_fetchall("SELECT * FROM ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid",array(":uniacid"=>$fans['uniacid']));
			$rate=$glbal["subsidy"];
			
			foreach($level AS $l){
				//当前会员类型的等级佣金比例
				if($l["type"]==$member["type"] && $l["name"]==$member["level"] ){
					if($member["type"]==0){
						$rate=$glbal["subsidy"];
					}else{
						$rate=$l["myteam_credit2"];	
					}
				}
			}
			if(empty($goods)){
				return $this->respText("没找到商品，换个试试吧");
			}
			foreach($goods AS $g){
				if(empty($g["skuId"])){
					return $this->respText("没找到该商品，换个试试吧");
					break;//没找到商品继续跳出循环
				}
				$num++;
				if($num>5){
					break;
				}
				$detail =   $g['detail'];
				$skuId  =   $g["skuId"];
				$json_string=   json_encode($g);
				$filename   =   getfilename($skuId);
				file_put_contents($filename, $json_string);
				$yuebu=number_format($g['wlPrice_after']*$g['wlCommissionShare']/100*$rate/100*0.9,2);
                $searchurl=$_W['siteroot']."app/index.php?i=".$fans['uniacid']."&c=entry&m=nets_haojk&do=searchlist&keyword=".$keyword;
					
                if(!empty($g['uninonUrl'])){
					$unionUrl=$g['uninonUrl'];
				}else{
					if(strpos($content,'京东') !== false){
						 $unionUrl	=	getunionurl($g['couponList'],$g['skuId'],'');
						 $unionUrl		=	$unionUrl->data;
                        $yuebu=number_format($g['wlPrice_after']*$g['wlCommissionShare']/100*$rate/100*0.9,2);
                        $searchurl=$_W['siteroot']."app/index.php?i=".$fans['uniacid']."&c=entry&m=nets_haojk&tap_type=jd&do=searchlist&keyword=".$keyword;
					}elseif(strpos($content,'拼多多') !== false || strpos($content,'yangkeduo.com') !== false){
						$unionUrl	=	getpdd_Unionurl('','',$g['skuId']);
						$unionUrl		=	$unionUrl['data'];
                        $yuebu=number_format($g['min_group_price']*$g['wlCommissionShare']/100*$rate/100,2);
                        $searchurl=$_W['siteroot']."app/index.php?i=".$fans['uniacid']."&c=entry&m=nets_haojk&do=searchlist&tap_type=pdd&keyword=".$keyword;
					}else{
						$unionUrl	=	getunionurl($g['couponList'],$g['skuId'],'');
						$unionUrl		=	$unionUrl->data;
					}
				 
				}
                $yuebu_str="
约    补：￥".$yuebu;
                if(empty($glbal['isopen_subsidy']) || empty($glbal['isshow_subsidy'])){
                    $yuebu_str="";
                }
				 
				$array=array(
					'title' => $g['skuName'].'  券后价￥'.$g['wlPrice_after'].''.$member['nickname'],
					'description' => $g['skuDesc'],
					'picurl' => $g['picUrl'],
					'url' => $this->createMobileUrl('searchdetail', array('skuId' => $g['skuId'])), 
					'tagname'=>'item'
				);
				//生成海报
				$posterSize=7;
				if($glbal['goodsqrtype']=="2"){
					$posterSize=7;
				}
				$poster=new Poster();
				$skuId=$g["skuId"];
				$skuName=$g["skuName"];
				$skuDesc=$g["skuDesc"];
				$materiaUrl=$g["materiaUrl"];
				$picUrl=$g["picUrl"];
				$wlPrice=$g["wlPrice"];
				$wlPrice_after=$g["wlPrice_after"];
				$discount=$g["discount"];
				if(strpos($content,'京东') !== false){
					$res=$poster->getGoodPoster2($skuId,$skuName,$skuDesc,$unionUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,$posterSize);
				
				}elseif(strpos($content,'拼多多') !== false || strpos($content,'yangkeduo.com') !== false){
					$wlPrice=$g["min_group_price"];
					$res=$poster->getPddGoodPoster2($skuId,$skuName,$skuDesc,$unionUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,$posterSize);
				
				}else{
					
					$res=$poster->getGoodPoster2($skuId,$skuName,$skuDesc,$unionUrl,$picUrl,$wlPrice,$wlPrice_after,$discount,$posterSize);
				
				}
				$detailurl=url('entry',array('m'=>'nets_haojk','do'=>'searchdetail','skuId'=>$g['skuId']));
				$detailurl=$_W['siteroot'].substr($detailurl,2,strlen($detailurl));
$message_str="【JD】".$g['skuName']."
————————
京东价：￥".$g['wlPrice']."
内购价：￥".$g['wlPrice_after'].$yuebu_str."
领券优惠购买：".$unionUrl."
————————
【推荐理由】".$g['skuDesc']."
京东商城 正品保证"."  <a href='".$searchurl."'>更多结果</a>";
if(strpos($content,'拼多多') !== false || strpos($content,'yangkeduo.com') !== false){
	$detailurl=url('entry',array('m'=>'nets_haojk','do'=>'searchdetail','skuId'=>$g['skuId']));
	$detailurl=$_W['siteroot'].substr($detailurl,2,strlen($detailurl));
	$message_str="【拼多多】".$g['skuName']."
————————
单购价：￥".$g['wlPrice_after']."
拼购价：￥".$g['min_group_price'].$yuebu_str."
领券优惠购买：".$unionUrl."
————————
【推荐理由】".$g['skuDesc']."
拼多多 正品保证"."  <a href='".$searchurl."'>更多结果</a>";
}
				load()->model('account');
				$acid = $_W['acid'];
				if (empty($acid)) {
					$acid = $_W['uniacid'];
				}	
				$message = array(
					'msgtype' => 'text',
					'text' => array('content' => urlencode($message_str)),
					'touser' => $openid,
				);
				$account_api = WeAccount::create();
				$status = $account_api->sendCustomNotice($message);
				
				$postpath='/addons/nets_haojk/cache/'.$g['skuId'].'_exqrcode.jpg';
				
				$res = $this->uploadImage($postpath);  
				if($res['code']==200){
					$media_id=$res['value'];
					return $this->respImage($media_id);
				}else{
					return $this->respText(json_encode($res));
				} 	
				break;
				$response[]=$array;
			}
			$content="没找到你要的商品";
		}
		
	}

}