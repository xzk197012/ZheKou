<?php
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.func.php';
include_once IA_ROOT . '/addons/nets_haojk/function/wxBizMsgCrypt.php';

    function get_cname(){
		global $_W,$_GPC;
		$unionid	=	$_W['global']['jduniacid'];
		$url		=	$this->questHost.'/index.php/api/index/cname';
		$result		=	$this->http_request($url,"unionId=$unionid");
		$result		=	$this->object_array(json_decode($result));
		return $result;
	}
	
	function get_good_list($page=1,$size=20,$sort=0){
		global $_W,$_GPC;
		$result		=	getgoodlist('list',$page,$size,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname);
		$result		=	object_array($result);
		return $result;
	}
	
	function get_good_list_search($page=1,$size=20,$sort=0,$keyword=null,$minpirce=null,$maxprice=null,$mincommission=null,$maxcommission=null){
		global $_W,$_GPC;
		$result		=	getgoodlist('listbysearch',$page,$size,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname);
		$result		=	object_array($result);
		return $result;
	}
	
	function get_good_list_cname($page=1,$size=20,$sort=0,$cname=null){
		global $_W,$_GPC;
		$result		=	getgoodlist('listbycname',$page,$size,$sort,$keyword,$minprice,$maxprice,$mincommission,$maxcommission,$cname);
		$result		=	object_array($result);
		return $result;
	}
	
	function get_unionUrl($data){
		global $_W,$_GPC;
		$unionid	=	$_W['global']['jduniacid'];
		$from_uid="";
		if(!empty($data["from_uid"])){
			$from_uid=$data["from_uid"];
		}
		$result		=	getunionurl($data['couponList'],$data['skuId'],$from_uid);
		$result		=	object_array($result);
		return $result['data'];
	}
	function get_unionUrlByuniacid($data,$jduniacid){
		global $_W,$_GPC;
		$result		=	getunionurlbyuniacid($data['couponList'],$data['skuId'],$jduniacid);
		$result		=	object_array($result);
		//var_dump($result);
		return $result['data'];
	}
	
	function getUserInfo($beLogin){
		global $_W,$_GPC;
		$_W['user_info']['subsidyname']="约补";
		get_global();
		$debug=false;//上线的时候要改为false
		if($debug){
			$_W["openid"]="oW2uxv1o1ilYRrzZgaSux5FXDVkQ";
		}
		if(isset($_GPC['from_uid'])){
			$_SESSION['from_uid']	=	$_GPC['from_uid'];
		}
		if(empty($_W['openid'])){//非微信端登录的.zxq.注释 2018.05.16
			if(empty($_SESSION['member'])){
				$login_url=url('entry',array('m'=>'nets_haojk','do'=>'login'));
				if($_GPC['do']!='api' && $_GPC['do']!='register' && $_GPC['do']!='addmoreajax' && $_GPC['do']!='index' && $_GPC['do']!='choiceness' && $_GPC['do']!='searchlist' && $_GPC['do']!='searchdetail'  && $_GPC['do']!='detail'){
					if($beLogin==1){
						Header("Location: $login_url");
					}
				}
			}else{
				$user_info=$_SESSION['member'];
				if(!empty($user_info) && $user_info['pid']>0){
					$probit_info=pdo_get('nets_hjk_probit',array('id'=>$user_info['pid']));
					$user_info['probit']=$probit_info;
				}
				$_W['openid']=$user_info['openid'];
				//非微信端登录的存储一个fans的uid zxq.2018.06.17 mobile.func.php in 81
				$_W['fans']['uid']=$user_info['memberid'];
				$user_info=getmemberinfo('');
				gzh_syncmember();
				$user_info=getmemberinfo('');
				$_SESSION['member']=$user_info;
				$_W['user_info']=	$user_info;	
			}
		}else{//微信端授权登录的.zxq.注释 2018.05.16
			checkauth();
			$uniacid	=	$_W['uniaccount']['uniacid'];
			$openid		=	$_W['openid'];
			if(!empty($_W['openid'])){
				$data	=	array();
				$data['uniacid']=$uniacid;
				$data['memberid']=$_W['fans']['uid'];
				$data['openid']=$_W['openid'];
				$data['nickname']=$_W['fans']['nickname'];
				$data['avatar']=$_W['fans']['avatar'];
				$data['realname']=$_W['member']['realname'];
				$data['email']=$_W['member']['email'];
				if($_W['fans']['tag']['sex']==1)
					$data['sex']='男';
				elseif($_W['fans']['tag']['sex']==2)
					$data['sex']='女';
				else
					$data['sex']='';
				$data['province']=$_W['fans']['tag']['provinc'];
				$data['city']=$_W['fans']['tag']['city'];
				$data['area']=$_W['fans']['tag']['area'];
				
				$tmpData=$data;
				$data=array();
				foreach($tmpData as $key=>$val){
					if(!empty($val)){
						$data[$key]	=	$val;
					}
				}
				//$user_info	=	pdo_get('nets_hjk_members',array('uniacid'=>$uniacid,'openid'=>$openid));
				//统一开放平台验证模块会员是否存在调整 zxq 2018.05.16 mobile.func.php in 116
				$user_info	=	pdo_get('nets_hjk_members',array('openid'=>$_W['openid']));
				if(empty($user_info['id'])){
					if(!empty($_SESSION['from_uid'])){
						$from_member=pdo_fetch("select * from ".tablename("nets_hjk_members")." where memberid=:memberid",array(":memberid"=>$_SESSION['from_uid']));
						if(!empty($from_member)){
							//继承上级用户的jduniacid
                            $data['from_jduniacid']=$from_member['from_jduniacid'];
							//继承上级用户的推荐人id到上上级id
                            $data['from_uid2']=$from_member['from_uid'];
							//继承推荐人的合伙人id
                            if($from_member['type']==2)
                                $data['from_partner_uid']=$from_member['memberid'];
                            else{
                                $data['from_partner_uid']=$from_member['from_partner_uid'];
                            }
						}
					}
					$data['from_uid']=$_SESSION['from_uid'];
					$data['created_at']=time();
					$result=pdo_insert('nets_hjk_members',$data);
					$user_id=$result;
					$user_info=$data;
					$user_info['id']	=	$user_id;
					//新增下级消息提醒方法调用 原openid变更为uid查询 zxq.2018.05.16 mobile.func.php in 134
					sendNewLevelMsg($_W['fans']['uid']);
				}else{
					//更新会员昵称和头像 取消uniacid条件查询当前会员 zxq.2018.05.16 mobile.func.php in 136
					$mcmember=pdo_get('mc_members',array('uid'=>$_W['member']['uid']));
					$data['nickname']=$mcmember['nickname'];
					$data['avatar']	=$mcmember['avatar'];
					$data['updated_at']	=	time();
					$result		=	pdo_update('nets_hjk_members',$data,array('id'=>$user_info['id']));
					$user_id	=	$user_info['id'];
				}
				$user_info=getmemberinfo('');
				gzh_syncmember();
				$user_info=getmemberinfo('');
				if(!empty($user_info) && $user_info['pid']>0){
					$probit_info		=	pdo_get('nets_hjk_probit',array('id'=>$user_info['pid']));
					$user_info['probit']=	$probit_info;
				}
				$_W['user_info']=	$user_info;	
			}
		}
		//删除没用的函数调用 getcmsmember() zxq.2018.05.16 in mobile.func.php in 152
	}
	
	function get_global(){
		global $_W,$_GPC;
		$hjk_global=pdo_fetch("select * from ".tablename("nets_hjk_global")." where uniacid=:uniacid",array(":uniacid"=>$_W["uniacid"]));
		//$member=pdo_fetch("select * from ".tablename("nets_hjk_members")." where openid=:openid",array(":openid"=>$_W["openid"]));
		//统一会员根据uid查询当前会员 zxq.2018.05.16 mobile.func.php in 159
		$member=pdo_fetch("select * from ".tablename("nets_hjk_members")." where memberid=:memberid",array(":memberid"=>$_W['fans']['uid']));
		
		$hjk_global["applygradename"]="盟主";
		$level0=pdo_fetch("select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and name=0 and type=1",array(":uniacid"=>$_W["uniacid"]));
		if(empty($level0["identityname"])){
			if($level0["type"]==0){
				$level0["identityname"]="会员";
			}else{
				$level0["identityname"]="盟主";
			}
		}
		$hjk_global["applygradename"]=$level0["identityname"];
		if($member["type"]==1){
			//如果是盟主，则是否显示补贴使用盟主的控制字段
			$hjk_global["isshow_subsidy"]=$hjk_global["isshow_subsidy_dl"];
		}else{
			//普通会员申请的等级名称展示
			
			
		}	
		//合伙人的等级查询出来计算升级赚使用
		$partnerlevel=pdo_fetch("select * from ".tablename("nets_hjk_memberlevel")." where uniacid=:uniacid and name=0 and type=2",array(":uniacid"=>$_W["uniacid"]));
		if(!empty($partnerlevel)){
			$hjk_global["partnerlevel"]=$partnerlevel;
		}	
		$_W["hjk_global"]=$hjk_global;
		$_W["global"]=$hjk_global;
	}
	function object_array($array) {  
		if(is_object($array)) {  
	        $array = (array)$array;  
	    } 
	    if(is_array($array)) {  
			foreach($array as $key=>$value) {  
				$array[$key] = object_array($value);  
			}  
	    }  
	    return $array;  
	}

	function arr_ru($action){
		if (is_array($action)) {
			foreach ($action as $key => $value) {
				$ru = $value;
			} 
		}
		return $ru; 
	}
	
	function http_request($url, $data = null){
    	$curl = curl_init();
    	curl_setopt($curl, CURLOPT_URL, $url);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    	if(!empty($data))
    	{
        	curl_setopt($curl, CURLOPT_POST, 1);
        	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    	}
    	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    	$output = curl_exec($curl);
    	curl_close($curl);
    	return $output;
	}

	function getfllowqrcode($memberid=''){
		global $_GPC, $_W;
		if(!empty($memberid)){
			$_W['user_info']['memberid']=$memberid;
		}
		$token=getaccessToken();
		$qrurl="404";
		$qrdir=ATTACHMENT_ROOT.'qrcode_user_'.$_W['user_info']['memberid'].'.jpg';
		$tickturl="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
		$data = array(
			'expire_seconds' => 1800,
			'action_name' => "QR_LIMIT_SCENE",
			'action_info' => array("scene"=>array("scene_id"=> $_W['user_info']['memberid'])),
			'scene_id'=>$_W['user_info']['memberid'],
			'scene_str'=>$_W['user_info']['memberid']
		);
		$data = array(
			'action_name' => "QR_LIMIT_STR_SCENE",
			'action_info' => array("scene"=>array("scene_str"=> $_W['user_info']['memberid'])),
			'scene_id'=>$_W['user_info']['memberid'],
			'scene_str'=>$_W['user_info']['memberid']
		);
		$data=json_encode($data);
		$res=ihttp_post($tickturl,$data);
		if(!empty($res)){
			$res=$res["content"];
			$res=json_decode($res);
			$ticket=$res->ticket;
			$showticket="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
			$qrurl=ihttp_get($showticket);
			$qrurl=$qrurl["content"];
			file_put_contents($qrdir,$qrurl);
		}
		return $qrdir;
	}
	function getfllowqrcodeByfreegoods($value=''){
		global $_GPC, $_W;
		$token=getaccessTokenByuse();
		$qrurl="404";
		$qrdir=ATTACHMENT_ROOT.'freeqrcode_user_'.$_W['user_info']['memberid'].'.jpg';
		if(file_exists($qrdir)){
		}
		$tickturl="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$token;
		$scene_str=$_W['user_info']['memberid'];
		if(!empty($value)){
			$scene_str=$value;
		}
		$data = array(
			'action_name' => "QR_LIMIT_STR_SCENE",
			'action_info' => array("scene"=>array("scene_str"=> $scene_str)),
			'scene_id'=>$_W['user_info']['memberid'],
			'scene_str'=>$scene_str
		);
		$data=json_encode($data);
		$res=ihttp_post($tickturl,$data);
		if(!empty($res)){
			$res=$res["content"];
			$res=json_decode($res);
			$ticket=$res->ticket;
			$showticket="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
			$qrurl=ihttp_get($showticket);
			$qrurl=$qrurl["content"];
			file_put_contents($qrdir,$qrurl);
		}
		return $qrdir;
	}
	function getaccessToken(){
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
	function getaccessTokenByuse(){
		global $_GPC, $_W;
		$token=$_W['uniaccount']["token"];
		$encodingaeskey=$_W['uniaccount']["encodingaeskey"];
		$key=$_W['uniaccount']["key"];
		$secret=$_W['uniaccount']["secret"];
		load()->func('communication');
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$key.'&secret='.$secret;
		$result = ihttp_get($url);
		$res=$result["content"];
		if(empty($res)){
			return "500";//'api return error';
		}
        if(!empty($res)){
			$res=json_decode($res);
			haojk_log("0.4消息token：".$res->access_token);
            return $res->access_token;
        }else{
            return "500";//'api return error';
        }
	}

	//根据自定义的menu转换成对应的mobile url 返回数组:url、标签
	function tomobileurl($m){
		global $_GPC, $_W;
		$turl=$m['url'];
		$hmcnarr  = explode('=',$turl); 
    	if(count($hmcnarr)>=2){
			$turl=$hmcnarr[1];
      		$url=url('entry',array('m'=>'nets_haojk','do'=>'choiceness','cname'=>$hmcnarr[1],'cnametype'=>$hmcnarr[2],'cnameid'=>$hmcnarr[1])); 
			  if(!empty($turl)){
                if($turl=="自选推广"){
					$turl="tuiguang";
					
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'tuiguang'));
                }
                if($turl=="砍价"){
                    $turl="bargain";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'bargain')); 
                }
                if($turl=="榜单"){
                    $turl="crunchies";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'crunchies')); 
                }
                if($turl=="2小时跑单"){
                    $turl="crunlist";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'crunlist')); 
                }
                if($turl=="全天销售榜"){
                    $turl="crunlist2";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'crunlist2'));  
                }
                if($turl=="实时排行榜"){
                    $turl="crunlist3";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'crunlist3'));
                }
                if($turl=="拼购"){
                    $turl="pingou";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'pingou'));
                }
                if($turl=="index"){
                    $turl="index";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'index',"rt"=>1));
                }
                if($turl=="choiceness"){
                    $turl="choiceness";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'choiceness'));
                }
                if($turl=="bigsearch"){
                    $turl="supersearch";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'searchlist'));
                }
                if($turl=="my"){
                    $turl="my";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'my'));
                }
                if($turl=="pddindex"){
                    $turl="pddindex";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'pinduoduoindex'));
                }
                
                if($turl=="pddsearch"){
                    $turl="pddsearch";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'pinduoduosearchlist'));
                }
                if($turl=="pddcatelist"){
                    $turl="pddcatelist";
                    $url=url('entry',array('m'=>'nets_haojk','do'=>'pinduoduoclasslist'));
                }
			}
        }else{
            $url=url($turl); 
        }
        
    	if(!empty($m['outer_url'])){
            $url=$m['outer_url'];
		}
		$list=array("url"=>$url,"turl"=>$turl);
		return $list;
	}
?>