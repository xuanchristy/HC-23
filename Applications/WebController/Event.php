<?php
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Db;
// use \GatewayWorker\Lib\Store;
// require_once 'Web/transToWxServer.php';
require_once 'Web/redisData.php';

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
**/
class Event
{    

    /**
     * 接收信息字节长度
     * @var int
     */
    const MESSAGE_LENGTH = 16;
    /**
     * 绑定状态
     * @var int
     */
    const BIND_STATE = 1;
    /**
     * 客户端连接成功
     * @var sting
     */
    const SUCCESS_CONNECTION = "success";
    /**
     * 修改蓝牙标志成功
     * @var sting
     */
    const SUCCESS_SET_SIGN = "sign success";

	/**
	 * 绑定macid
	 * @param  int
	 * @param  string
	 * @return void
	 */
	private static function bindMacId($client_id,$mac_id)
	{

		$connectHC = Db::instance('ConnectHC');
		$result = $connectHC->single("SELECT clientid FROM `WEBHC` WHERE macid='$mac_id'");
		// clientid不存在
		if($result === false){
			$connectHC->query("INSERT INTO `WEBHC` ( `macid`,`clientid`, `param`) VALUES ( '$mac_id', '$client_id', '1/2/3/4/5/6/7/8/9/10/11/12/13/14/15/16/17/18/19/20/21/22/23/24/25/26/27/28/29/30')");
			$_SESSION['bind'] = self::BIND_STATE;
			Gateway::sendToCurrentClient(self::SUCCESS_CONNECTION);
			return;
		// clientid等于0
		}else if($result === '0'){
			$row = $connectHC->query("UPDATE `WEBHC` SET `clientid` = '$client_id', `sign` = 1 WHERE macid='$mac_id'");
			if($row === 1){
				$_SESSION['bind'] = self::BIND_STATE;
				Gateway::sendToCurrentClient(self::SUCCESS_CONNECTION);
			}else{
				Gateway::sendToCurrentClient("fail");
			}
			return;
		}
		// clientid存在且不等于0
		if($result === $client_id){
			Gateway::sendToCurrentClient(self::SUCCESS_CONNECTION);
		}else{
			$row = $connectHC->query("UPDATE `WEBHC` SET `clientid` = '$client_id' WHERE macid='$mac_id'");
			if($row === 1){
				Gateway::closeClient($result);
				Gateway::sendToCurrentClient(self::SUCCESS_CONNECTION);
			}else{
				Gateway::sendToCurrentClient("fail");
			}
		}
	}

	/**
	 * 当客户端连接时触发
	 * @param  int
	 * @return void
	 */
    public static function onConnect($client_id)
    {
       
    }

	/**
	 * 当客户端发来消息时触发
	 * @param  int
	 * @param  string
	 * @return void
	 */
   public static function onMessage($client_id, $message)
   {
   		
 		$connectHC = Db::instance('ConnectHC');
      	// 没有登录
      	if(!isset($_SESSION['bind'])){
      		// 接收到的是JSON数据包
      		if(is_array($message)){
      			self::bindMacId($client_id, $message['macid']);
      			return;
      		}
      		// 回复心跳包
      		Gateway::sendToCurrentClient(1);
		}else{
			var_dump($message);
			Gateway::sendToCurrentClient(1);
			if(is_array($message)){
				// 更新蓝牙连接标志
				if(isset($message['sign'])){
					$sign = $message['sign'];
					$data = $connectHC->query("UPDATE `WEBHC` SET `sign` = $sign WHERE clientid='$client_id'");
					Gateway::sendToCurrentClient(self::SUCCESS_SET_SIGN);
					return;
				}
				// 更新CLIENTID
				if(isset($message['macid'])){
					self::bindMacId($client_id, $message['macid']);
					return;
				}
				// 更新APP键名
				$param = $connectHC->row("SELECT `param` FROM `WEBHC` WHERE clientid='$client_id'");
				$parambefore = explode('/', $param['param']);
				foreach ($message as $key => $value) {
					$parambefore[$key-1] = $value;
				}
				$paramafter = implode('/', $parambefore);
				$connectHC->query("UPDATE `WEBHC` SET `param` = '$paramafter' WHERE clientid='$client_id'");
			}else{
				// 回复心跳包
				Gateway::sendToCurrentClient(1);
			}

		}
    }
   
   
    /**
     * 当用户断开连接时触发
     * @param  int
     * @return void
     */
   public static function onClose($client_id)
   {
       
       $connectHC = Db::instance('ConnectHC');
       $connectHC->query("UPDATE `WEBHC` SET `clientid` = 0 WHERE clientid='$client_id'");
       unset($_SESSION['bind']);

   }

}
