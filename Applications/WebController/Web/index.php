<?php
/*可以直接向客户端推送消息*/
// use \GatewayWorker\Lib\Gateway;
use \Workerman\Protocols\Http;
require_once 'redisData.php';
require_once 'transToWxServer.php';
require_once dirname(__DIR__).'/GatewayClient/Gateway.php';

//配置TOKEN
// if(!defined("TOKEN"))
// define("TOKEN", "linvorHC");


// //配置REDIS的端口
// if(!defined("REDIS_PORT") || !defined("REDIS_IP")){

//     define("REDIS_IP","127.0.0.1");
//     define("REDIS_PORT","6380");
// }

//配置微信AppID & AppSecret

if(!defined("APPID") || !defined("APPSECRET")){

    define("APPID","wx3faa5c04dbde6ac2");
    define("APPSECRET","ad2ebcabdbc4277b13ae27d262daabbc");
}

$wechatObj = new wechatCallbackapiTest();
// if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
// }else{
//     $wechatObj->valid();
// }

class wechatCallbackapiTest
{
    //验证签名
    // public function valid()
    // {
    //     $echoStr = $_GET["echostr"];
    //     $signature = $_GET["signature"];
    //     $timestamp = $_GET["timestamp"];
    //     $nonce = $_GET["nonce"];
    //     $token = TOKEN;
    //     $tmpArr = array($token, $timestamp, $nonce);
    //     sort($tmpArr);
    //     $tmpStr = implode($tmpArr);
    //     $tmpStr = sha1($tmpStr);
    //     if($tmpStr == $signature)
    //     {
    //         echo $echoStr;
    //         Http::end();
    //     }
    // }

    /**
     * 响应消息
     * @return string
     */
    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA); //解析得到的XML对象
            $RX_TYPE = trim($postObj->MsgType); //得到消息的类型
             
            //消息类型分离
            switch ($RX_TYPE)
            {
                //事件消息
                case "event":
                    $result = $this->receiveEvent($postObj);
                    break;

                //文本消息
                case "text":
                    $result = $this->receiveText($postObj);
                    break;

                //图片消息
                case "image":
                    $result = $this->receiveImage($postObj);
                    break;

                //地理位置消息
                case "location":
                    $result = $this->receiveLocation($postObj);
                    break;

                //语音消息
                case "voice":
                    $result = $this->receiveVoice($postObj);
                    break;

                //视频消息
                case "video":
                    $result = $this->receiveVideo($postObj);
                    break;

                //链接消息
                case "link":
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = "unknown msg type: ".$RX_TYPE;
                    break;
            }
            // $this->logger("T ".$result);
            echo $result;
            // HTTP::end();
        }else {
            echo "";
        }

        HTTP::end();
    }

    /**
     * 接收事件消息
     * @var object
     * @return string
     */
    private function receiveEvent($object)
    {

        $result = "";
        $client_openid = $object->FromUserName;
        switch ($object->Event)
        {
            
        	//关注事件
            case "subscribe":
                $content = "感谢关注广州汇承信息科技微信服务号！ [太阳]\n\n";
                $content.="点击菜单：'HC汇承'\n";
                $content.="得到最新产品咨询、HC产品列表汇总、HC汇承官网信息\n";
                $content.="--------------------------\n";
                $content.="点击菜单：'人工咨询'\n";
                $content.="在线技术支持、在线销售咨询\n";
                $content.="--------------------------\n";
                $content.="点击菜单：'HC智能❤'\n";
                $content.="暂时没有开放..\n\n";
                $content.="电话：020-4008881803";
                $result = $this->transmitText($object, $content);
                break;
            
            //取消关注事件
            case "unsubscribe":
                $content = "取消关注";
                break;
            /*case "SCAN":
                $content = "扫描场景 ".$object->EventKey;
                break;*/

            //点击事件
            case "CLICK":
                switch ($object->EventKey)
                {
                    //最新产品
                    case "NewProduct":
                        $content = array();
                        $content[] = array(
                            "Title"=>"HC-08蓝牙4.0BLE串口模块",
                            "Description"=>"HC-08支持和iPhone4s以上的苹果手机或安卓4.3蓝牙4.0的安卓手。。,两模块之前也可通讯",
                            "PicUrl"=>"https://mmbiz.qlogo.cn/mmbiz/JnIZvkFV6c0gu2PEYJkcP3ngibwfIufAIPcjwpx1W2brgWTfibVe2dhFqKcQEntc6wXVZm8P6LaQ6qic9UOFibyFaA/0?wx_fmt=jpeg",
                            "Url" =>"http://www.hchchchc.com/Tpl/product_arc.php?model=HC-08"
                            );

                        $result = $this->transmitNews($object, $content);
                        break;

                    // 在线销售
                    case "OnlineSaler":
                        $getcustom_mark = redisData::hGet($client_openid,'custom');
                        
                        // 重复click
                        if($getcustom_mark == "1"){

                            echo "";

                            HTTP::end();
                        }else{

                            // 得到空闲客服帐号
                            $kf_account = transToWxServer::findRelaxCustom("HCS");
                            if(!empty($kf_account)){

                                $result = $this->transmitService($object, $kf_account);
                                redisData::hSet($client_openid,'custom',"1");

                            }else{

                                    // 客服不在线
                                    $content = "客服已离线，请稍候重试！";
                                    $result = $this->transmitText($object, $content);
                                }
                            }
                    	break;

                    // 在线技术客服
                    case "THelp":
                        $getcustom_mark = redisData::hGet($client_openid,'custom');
                        
                        // 重复click
                        if($getcustom_mark == "1"){

                            echo "";

                            HTTP::end();
                        }else{

                            // 得到空闲客服帐号
                            $kf_account = transToWxServer::findRelaxCustom("HCT");
                            if(!empty($kf_account)){

                                $result = $this->transmitService($object, $kf_account);
                                redisData::hSet($client_openid,'custom',"1");

                            }else{

                                    // 客服不在线
                                    $content = "客服已离线，请稍候重试！";
                                    $result = $this->transmitText($object, $content);
                                }
                            }            
                        break;

                    //汇承官网
                    case "IndexHC":
                    	break;

                    //智能控制
                    case "IntelligentControl":
                    //     $getintell_mark = redisData::hGet($client_openid,'intell');
                    // if($getintell_mark == "0")
                    //     {
                    //         redisData::hSet($client_openid,'intell',"1");
                    //         $content = "成功进入智能控制！\n\n";
                    //         $content.="发送数字'0'可退出智能模式";
                    //         $result = $this->transmitText($object, $content);
                    //     }else{

                    //         $content = "智能控制中...！";
                    //         $result = $this->transmitText($object, $content);
                    //     }
                    	break;

                    //用户主动退出多客服会话
                    case "Exit":

                        $getcustom_mark = redisData::hGet($client_openid,'custom');
                        if($getcustom_mark == "1"){

                            $content = "提示： 用户主动结束会话！";
                            $kf_account = transToWxServer::findUserSession($object);
                            transToWxServer::closeSalerSession($object, $kf_account, $content);
                            // Gateway::sendToAll($error);
                            redisData::hSet($client_openid,'custom',"0");
                        }else{

                            echo "";

                            HTTP::end();
                        }
                        break;
                }
                break;

            case "VIEW":

                $openid = $object->FromUserName;
                $_SESSION['username'] = $openid;
                break;


            //多客服主动关闭会话事件
            case "kf_close_session":

                $content = "感谢您的咨询，我们下次再见!";
                transToWxServer::transToWxClientMsg($object,$content);
                redisData::hSet($object->FromUserName,'custom',"0");
                // return $result;

                // $content = "关闭了会话";
                // $result = $this->transmitText($object, $content);
                break;

            //多客服转接会话事件
            case "kf_switch_session":

                $service_id = $object->ToKfAccount;

                //得到客服的昵称
                $service_name = redisData::Get($service_id);
                $content = "已转接到 客服: $service_name";
                transToWxServer::transToWxClientMsg($object,$content);

                break;
        }

        return $result;
    }


    /**
     * 接收文本消息
     * @return string
     */
    private function receiveText($object)
    {
        $result = "";
        //得到关键字
        $keyword = $object->Content;

        //得到用户的Openid
        $client_openid = $object->FromUserName;

        //得到智能控制标志
        // $intell_mark = redisData::hGet($client_openid,'intell');

        //分析关键字
        if(strstr($keyword, "您好") || strstr($keyword, "你好") || strstr($keyword, "在吗"))
            {

                $kf_account = transToWxServer::findRelaxCustom("HCS");
                $result = $this->transmitService($object, $kf_account);
            }else{
                
                // if($intell_mark !== "1")
                // {

                    $content = "感谢关注广州汇承信息科技微信服务号！ [太阳]\n\n";
                    $content.="点击菜单：'HC汇承'\n";
                    $content.="得到最新产品咨询、HC产品列表汇总、HC汇承官网信息\n";
                    $content.="--------------------------\n";
                    $content.="点击菜单：'人工咨询'\n";
                    $content.="在线技术支持、在线销售咨询\n";
                    $content.="--------------------------\n";
                    $content.="点击菜单：'HC智能❤'\n";
                    $content.="暂时没有开放..\n\n";
                    $content.="电话：020-4008881803";
                    $result = $this->transmitText($object, $content);

                // }
                // else if($keyword == 0)
                //     {
                        
                //         redisData::hSet($client_openid,'intell',"0");
                //         $content ="已退出智能模式";
                //         $result = $this->transmitText($object, $content);

                //     }
            }

        return $result;
    }


    /**
     * 接收图片消息
     * @return string
     */
    private function receiveImage($object)
    {
        $content = array("MediaId"=>$object->MediaId);
        $result = $this->transmitImage($object, $content);
        return $result;
    }

    //接收位置消息
    private function receiveLocation($object)
    {
        $content = "你发送的是位置，纬度为：".$object->Location_X."；经度为：".$object->Location_Y."；缩放级别为：".$object->Scale."；位置为：".$object->Label;
        $result = $this->transmitText($object, $content);
        return $result;
    }

    //接收语音消息
    private function receiveVoice($object)
    {
        if (isset($object->Recognition) && !empty($object->Recognition)){
            $content = "你刚才说的是：".$object->Recognition;
            $result = $this->transmitText($object, $content);
        }else{
            $content = array("MediaId"=>$object->MediaId);
            $result = $this->transmitVoice($object, $content);
        }

        return $result;
    }


    /**
     * 接收视频消息
     * @return string
     */
    private function receiveVideo($object)
    {
        $content = array("MediaId"=>$object->MediaId, "ThumbMediaId"=>$object->ThumbMediaId, "Title"=>"", "Description"=>"");
        $result = $this->transmitVideo($object, $content);
        return $result;
    }

    /**
     * 接收链接消息
     * @return string
     */
    private function receiveLink($object)
    {
        $content = "你发送的是链接，标题为：".$object->Title."；内容为：".$object->Description."；链接地址为：".$object->Url;
        $result = $this->transmitText($object, $content);
        return $result;
    }


    /**
     * 回复文本消息
     * @return string
     */
    private function transmitText($object, $content)
    {
        $xmlTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[%s]]></Content>
					</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), $content);
        return $result;
    }


    /**
     * 回复图片消息
     * @return string
     */
    private function transmitImage($object, $imageArray)
    {
        $itemTpl = "<Image>
    					<MediaId><![CDATA[%s]]></MediaId>
					</Image>";

        $item_str = sprintf($itemTpl, $imageArray['MediaId']);

        $xmlTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[image]]></MsgType>
					$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }


    /**
     * 回复语音消息
     * @return string
     */
    private function transmitVoice($object, $voiceArray)
    {
        $itemTpl = "<Voice>
    					<MediaId><![CDATA[%s]]></MediaId>
					</Voice>";

        $item_str = sprintf($itemTpl, $voiceArray['MediaId']);

        $xmlTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[voice]]></MsgType>
					$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }


    /**
     * 回复视频消息
     * @return string
     */
    private function transmitVideo($object, $videoArray)
    {
        $itemTpl = "<Video>
					    <MediaId><![CDATA[%s]]></MediaId>
					    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
					    <Title><![CDATA[%s]]></Title>
					    <Description><![CDATA[%s]]></Description>
					</Video>";

        $item_str = sprintf($itemTpl, $videoArray['MediaId'], $videoArray['ThumbMediaId'], $videoArray['Title'], $videoArray['Description']);

        $xmlTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[video]]></MsgType>
					$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }


    /**
     * 回复图文消息
     * @return string
     */
    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "<item>
				        <Title><![CDATA[%s]]></Title>
				        <Description><![CDATA[%s]]></Description>
				        <PicUrl><![CDATA[%s]]></PicUrl>
				        <Url><![CDATA[%s]]></Url>
				    </item>";
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $xmlTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[news]]></MsgType>                                                                                          
					<ArticleCount>%s</ArticleCount>
					<Articles>
					$item_str</Articles>
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }


    /**
     * 回复音乐消息
     * @return string
     */
    private function transmitMusic($object, $musicArray)
    {
        $itemTpl = "<Music>
					    <Title><![CDATA[%s]]></Title>
					    <Description><![CDATA[%s]]></Description>
					    <MusicUrl><![CDATA[%s]]></MusicUrl>
					    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
					</Music>";

        $item_str = sprintf($itemTpl, $musicArray['Title'], $musicArray['Description'], $musicArray['MusicUrl'], $musicArray['HQMusicUrl']);

        $xmlTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[music]]></MsgType>
					$item_str
					</xml>";

        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }


    /**
     * 接入多客服消息
     * @return string
     */
    private function transmitService($object,$kf_account)
    {
        $xmlTpl = "<xml>
					<ToUserName><![CDATA[%s]]></ToUserName>
					<FromUserName><![CDATA[%s]]></FromUserName>
					<CreateTime>%s</CreateTime>
					<MsgType><![CDATA[transfer_customer_service]]></MsgType>
                    <TransInfo>
                        <KfAccount><![CDATA[%s]]></KfAccount>
                    </TransInfo>
					</xml>";
        $result = sprintf($xmlTpl, $object->FromUserName, $object->ToUserName, time(),$kf_account);
        return $result;
    }


    /**
     * 日志记录
     * @return void
     */
    /*private function logger($log_content)
    {
        if(isset($_SERVER['HTTP_APPNAME'])){   //SAE
            sae_set_display_errors(false);
            sae_debug($log_content);
            sae_set_display_errors(true);
        }else if($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){ //LOCAL
            $max_size = 10000;
            $log_filename = "log.xml";
            if(file_exists($log_filename) and (abs(filesize($log_filename)) > $max_size)){unlink($log_filename);}
            file_put_contents($log_filename, date('H:i:s')." ".$log_content."\r\n", FILE_APPEND);
        }
    }*/

}


?>