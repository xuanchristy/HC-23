<?php

require_once 'redisData.php';

class transToWxServer{

	//得到access_token，并存入redis中
	public static function getaccess_token(){

		$appid = "wx3faa5c04dbde6ac2";
		$appsecret = "ad2ebcabdbc4277b13ae27d262daabbc";

		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		$access_token = $jsoninfo["access_token"];
		redisData::Set('access_token',$access_token);

	}

	//得到jsapi_ticket，并存入redis中
	public static function getjsapi_ticket(){

		$access_token = redisData::Get('access_token');
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$access_token";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		$jsapi_ticket = $jsoninfo["ticket"];
		redisData::Set('jsapi_ticket',$jsapi_ticket);

	}

	/**
	 * 得到网页授权的用户openid
	 * @return  string
	 */
	public static function getOAuth_openid($code){

		$appid = "wx3faa5c04dbde6ac2";
		$appsecret = "ad2ebcabdbc4277b13ae27d262daabbc";

		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		$openid = $jsoninfo["openid"];
		return $openid;
	}

	//调用客服接口给用户主动推送消息
	public static function transToWxClientMsg($object, $content, $kf_account="")
	{

		$access_token = redisData::Get('access_token');
		$openid = $object->FromUserName;
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=$access_token";
		$post_data ='{
					    "touser":"%s",
					    "msgtype":"text",
					    "text":
					   {
					        "content":"%s"
					   },
					    "customservice":
					    {
					        "kf_account": "%s"
					    }
					}';

		if(func_num_args() == 2)
		$post_data = sprintf($post_data, $openid, $content, $object->KfAccount);
		$post_data = sprintf($post_data, $openid, $content, $kf_account);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//POST数据
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);
	}

	//主动为一个多客服创建会话
	public static function createSalerSession($object, $kf_account, $content)
	{

		$access_token = redisData::Get('access_token');
		$openid = $object->FromUserName;
		$url =  "https://api.weixin.qq.com/customservice/kfsession/create?access_token=$access_token";
		$post_data = ' {
					    "kf_account" : "%s",
					    "openid" : "%s",
					    "text" : "%s"
 					}';

 		$post_data = sprintf($post_data, $kf_account, $openid, $content);
 		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//POST数据
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);
	}

	//主动为一个多客服关闭会话
	public static function closeSalerSession($object, $kf_account, $content)
	{
		$access_token = redisData::Get('access_token');
		$openid = $object->FromUserName;
		$url =  "https://api.weixin.qq.com/customservice/kfsession/close?access_token=$access_token";
		$post_data = '{
					    "kf_account" : "%s",
					    "openid" : "%s",
					    "text" : "%s"
 					}';

 		$post_data = sprintf($post_data, $kf_account, $openid, $content);
 		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		//POST数据
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		$output = curl_exec($ch);
		curl_close($ch);
	}

	//获取在线客服接待信息
	public static function getOnlineCustomSession()
	{

		$access_token = redisData::Get('access_token');
		$url = "https://api.weixin.qq.com/cgi-bin/customservice/getonlinekflist?access_token=$access_token";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		return $jsoninfo["kf_online_list"];
		// return array_column($jsoninfo,"accepted_case","kf_account");
	}

	//找出空闲的客服
	public static function findRelaxCustom($kind)
	{

		$custom_list = array_column(self::getOnlineCustomSession(), "accepted_case", "kf_account");
		ksort($custom_list);
		$array_list = array();
		foreach ($custom_list as $key => $value) {
			if(strstr($key,$kind)){

				$array_list[$key] = $value;

			}
			
		}

		// 去掉接入数量重复的客服
		$array_list = array_unique($array_list);
		// 排序
		asort($array_list);
		return key($array_list);

	}

	//获取客户的会话状态
	public static function findUserSession($object)
	{

		$access_token = redisData::Get('access_token');
		$openid = $object->FromUserName;
		$url = "https://api.weixin.qq.com/customservice/kfsession/getsession?access_token=$access_token&openid=$openid";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$jsoninfo = json_decode($output, true);
		$kf_account = $jsoninfo["kf_account"];
		return $kf_account;
	}
		
}




?>