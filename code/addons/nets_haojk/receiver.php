<?php
/**
 * hao模块订阅器
 *
 * @author imseal168
 * @url 
 */
defined('IN_IA') or exit('Access Denied');
require_once IA_ROOT . '/addons/nets_haojk/function/cloud.func.php';
class Nets_haojkModuleReceiver extends WeModuleReceiver {
	public function receive() {
	    //这里定义此模块进行消息订阅时的, 消息到达以后的具体处理过程, 请查看微擎文档来编写你的代码
        haojk_log("消息订阅：".$this->message['event']);
        $type = $this->message['type'];
		$event = $this->message['event'];
		//return $this->respText($type);
        if (($event == 'subscribe'))
		{
			$this->saleVirtual($this);
		}
	}
	public function saleVirtual($obj = NULL) 
	{
		global $_W;
		if (empty($obj)) 
		{
			$obj = $this;
		}
		load()->model('account');
		$account = account_fetch($_W['acid']);
		
		$fans=pdo_fetch("select * from ".tablename('mc_mapping_fans')." where openid=:openid",array(":openid"=>$obj->message['from']));
		$glbal=pdo_fetch("select * from ".tablename('nets_hjk_global')." where uniacid=:uniacid",array(":uniacid"=>$fans['uniacid']));
		$message = $fans['nickname']."欢迎关注".$_W['account']['name']."!";
		$message="";
		
		if(!empty($glbal['fllow_msg'])){
			$message=$glbal['fllow_msg'];
			$message=str_replace("[昵称]",$fans['nickname'],$message);
			$message=str_replace("[公众号]",$_W['account']['name'],$message);
			$message=str_replace("[邀请人]","无",$message);
		}
		if(!empty($this->message['eventkey'])){
			$from_nickname="";
			
			if(strpos($obj->message['eventkey'],'skuId=') !== false){
				$urlval=str_replace('qrscene_','',$this->message['eventkey']);
				$url=$_W['siteroot'].$this->createMobileUrl('bargainyou')."&".$urlval;
				$array=array(
					'title' => '欢迎关注，点击这里继续帮好友砍价吧',
					'description' => '帮好友砍价后，您还可以自己创建一个砍价免费拿哦',
					'picurl' => 'http://demo.91fyt.com/addons/nets_haojk/template/mobile/img/yiqikanjia.jpg',
					'url' =>$url, 
					'tagname'=>'item'
				);
				$response[]=$array;
				$message="欢迎关注，点击这里继续帮好友砍价吧!
				".$url."
				"."帮好友砍价后，您还可以自己创建一个砍价免费拿哦";
				return $this->sendText(WeAccount::create($account), $obj->message['from'], $message);
				//return $this->sendNews($array,$account,$obj);
				//return $this->sendText(WeAccount::create($account), $obj->message['from'], $url);
			}
			$fans['from_uid']=str_replace('qrscene_','',$this->message['eventkey']);
			if(!empty($fans['from_uid'])){
				$from_member=pdo_get('nets_hjk_members',array('uniacid'=>$_W['uniacid'],'memberid'=>$fans['from_uid']));
				$from_nickname=$from_member["nickname"];
                $fans['from_uid2']=$from_member['from_uid2'];
                $fans['from_partner_uid']=$from_member['from_partner_uid'];
			}
			if(!empty($glbal['fllow_msg'])){
				$message=$glbal['fllow_msg'];
				$message=str_replace("[昵称]",$fans['nickname'],$message);
				$message=str_replace("[公众号]",$_W['account']['name'],$message);
				$message=str_replace("[邀请人]",$from_nickname,$message);
			}
			//$message.="[您的推荐人是".$from_nickname."_".$fans['from_uid']."]"."_eventkey:".$this->message['eventkey'];
            haojk_log("邀请人的uid：".$fans['from_uid'].";邀请人的上级UID：".$fans['from_uid2']."；合伙人UID：".$fans['from_partner_uid']);
            $i=$this->checkmember($fans);
			$result="系统错误！";
			if($i>0){
				sendNewLevelMsg($obj->message['from'],'');
				$result="加入成功！";
			}else if($i==-1){
				$message="您已加入过".$_W['account']['name']."，不能再接受".$from_nickname."邀请了！";
			}
			//$message.="[".$result."]";
			
		}
		return $this->sendText(WeAccount::create($account), $obj->message['from'], $message);
	}

	public function sendText($acc, $openid, $content) 
	{
		$send['touser'] = trim($openid);
		$send['msgtype'] = 'text';
		$send['text'] = array('content' => urlencode($content));
		$data = $acc->sendCustomNotice($send);
		return $data;
	}
	public function sendNews(array $news,$account,$obj) {
		global $_W;
		//return $this->sendText(WeAccount::create($account), $obj->message['from'], "ok");
		if (empty($news) || count($news) > 10) {
			return error(-1, 'Invaild value');
		}
		$news = array_change_key_case($news);
		if (!empty($news['title'])) {
			$news = array($news);
		}
		$response = array();
		$response['FromUserName'] = $obj->message['to'];
		$response['ToUserName'] = $obj->message['from'];
		$response['MsgType'] = 'news';
		$response['ArticleCount'] = count($news);
		$response['Articles'] = array();
		foreach ($news as $row) {
			$response['Articles'][] = array(
				'Title' => $row['title'],
				'Description' => ($response['ArticleCount'] > 1) ? '' : $row['description'],
				'PicUrl' => tomedia($row['picurl']),
				'Url' => $this->buildSiteUrl($row['url']),
				'TagName' => 'item'
			);
		}
		return $response;
	}
	public function checkmember($fans){
		global $_W;
		//通过海报关注的 验证会员取消openid、uniacid验证，改为uid验证 zxq.2018.05.19 receiver.php in 127
		$user_info = pdo_get('nets_hjk_members',array('memberid'=>$fans['uid']));
        haojk_log("fans：".json_encode($fans));
        haojk_log("user_info：".json_encode($user_info));
        $i=0;
		if(empty($user_info['id'])){
			$member["from_uid"]=$fans["from_uid"];
			if($fans['uid']==$fans["from_uid"]){
				$member["from_uid"]=0;
			}
            $member["from_uid2"]=$fans['from_uid2'];
            $member["from_partner_uid"]=$fans['from_partner_uid'];
			$member["uniacid"]=$_W["uniacid"];
			$member["memberid"]=$fans['uid'];
			$member["openid"]=$fans['openid'];
			$member["sex"]=$fans["gender"]=="1"?"男":"女";
			$member["province"]=$fans["province"];
			$member["city"]=$fans["city"];
			$member["avatar"]=$fans["avatar"];
			$member["username"]=$fans["nickname"];
			$member["nickname"]=$fans["nickname"];
			$member["pid"]=0;
			$member["type"]=0;
			$member["level"]=0;
			$member["created_at"]=time();
			$member["updated_at"]=time();
			$i = pdo_insert('nets_hjk_members',$member);
			sendNewLevelMsg($fans['openid']);
		}else{
			$i=-1;
		}
		return $i;
	}
}