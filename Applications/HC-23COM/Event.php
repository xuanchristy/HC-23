<?php
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Db;
use \GatewayWorker\Lib\Store;
// require_once 'Protocols/Json.php';
// require_once 'Protocols/User.php';
// require_once 'Web/transToWxServer.php';
// require_once 'Web/redisData.php';

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
**/
class Event
{    

    /**
     * 服务器macid
     * @var string
     */
    const SERVER_MACID = '000000000000';

	/**
	 * 当客户端连接时触发
	 * @param  int
	 * @return void
	 */
    public static function onConnect($client_id)
    {
    	// 请求客户端MAC
    	// $message = "";
    	// Gateway::sendToCurrentClient($message);
      // $time_ini = 2;
      // \Workerman\Lib\Timer::add($time_ini, function(){
      //   $connnect->send("11111");
      // });
    }

	/**
	 * 当客户端发来消息时触发
	 * @param  int
	 * @param  string
	 * @return void
	 */
  
    // public static function get_cpufree()
    // {
    //     $cmd =  "top -n 1 -b -d 0.1 | grep 'Cpu'";//调用top命令和grep命令
    //     $lastline = exec($cmd,$output);
       
    //     preg_match('/(\S+)%id/',$lastline, $matches);//正则表达式获取cpu空闲百分比
    //     $cpufree = $matches[1];
    //     return $cpufree;
    // }
   	public static function onMessage($client_id, $message)
   	{
      	
      	// var_dump(self::get_cpufree());
        Gateway::sendToCurrentClient($message);
	}
   
   
    /**
     * 当用户断开连接时触发
     * @param  int
     * @return void
     */
   public static function onClose($client_id)
   {
       
       // ..code

   }

}
