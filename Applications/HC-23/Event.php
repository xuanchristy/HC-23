<?php
use \GatewayWorker\Lib\Gateway;
use \GatewayWorker\Lib\Db;
use \GatewayWorker\Lib\Protocol;
use \GatewayWorker\Lib\Store;

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
**/
class Event
{   
    /**
     * 心跳包时间间隔
     * @var int
     */
    private static $heartInteval = 31;
    /**
     * 连接REDIS实例
     * @var array
     */
    private static $redisConnection = array();    
    /**
     * 连接MYSQL实例
     * @var null
     */
    private static $connectHC = null;
    /**
     * 注册请求数据包
     * @var string
     */
    private static $pushRegistMessage = '';
    /**
     * 初始化
     * @return void
     */
    private static function init(){
    	// 生成连接REDIS实例
    	if(!isset(self::$redisConnection['timeid']))
    	{
    		self::connectRedis('timeid');
    	}
    	// 生成连接MYSQL实例
    	if(!isset(self::$connectHC))
    	{
    		self::$connectHC = Db::instance('ConnectDb');
    	}
    	// 生成注册请求包
    	if(empty(self::$pushRegistMessage))
    	{
    		$data = array();
    		$message = '';
    		$data['HeadSign']		= 	'++HC'; 						//数据头
    		$data['Protocol']		= 	"\x01\x01"; 					//协议版本
    		$data['MessageLength']	= 	"\x00\x2A"; 					//数据长度
    		$data['TargetAddr']		= 	Protocol::$V1['DEFALUT_MMAC']; 	//数据目的客户端MAC
    		$data['StartAddr']		= 	Protocol::$V1['DEFALUT_SMAC']; 	//数据起始客户端MAC
    		$data['Model']			= 	Protocol::$V1['SERVER_MODEL']; 	//数据源设备类型
    		$data['Class']			= 	"\x00"; 						//数据类型
    		$data['Left']			= 	"\x00"; 						//参数左标记
    		$data['AT']				= 	Protocol::$V1['REGISTER_MAC']; 	//AT类型
    		$data['Param']			= 	"\xFF"; 						//参数
    		$data['right']			= 	"\x00"; 						//参数右标记
    		$data['End']			= 	"HC"."\r\n"; 					//数据尾
    		foreach ($data as $key => $value)
    		{
				$message .=$value;
    		}
    		self::$pushRegistMessage = $message.$message.$message;
    	}
    }
    /**
     * 初始化SESSION中的信息
     * @param string $startaddr
     */
    private static function setSessionStart($startaddr){
    	$_SESSION['registsuccess'] 	=	"++HC\x01\x01\x00\x2A".$startaddr.Protocol::$V1['DEFALUT_SMAC'].Protocol::$V1['SERVER_MODEL']."\x00\x00".Protocol::$V1['STATUS_CONNECTED']."\xFF\x00HC\r\n";
		$_SESSION['transerror'] 	=	"++HC\x01\x01\x00\x2A".$startaddr.Protocol::$V1['DEFALUT_SMAC'].Protocol::$V1['SERVER_MODEL']."\x00\x00".Protocol::$V1['STATUS_DISONLINE']."\xFF\x00HC\r\n";
    	$_SESSION['inisession'] 	= 	1;
    }
    /**
     * redis数据库链接
     * @param string $redis_name
     * @return resouce
     */
    private static function connectRedis($redis_name){

    	if(!isset(self::$redisConnection[$redis_name]))
    	{
			self::$redisConnection[$redis_name] = new Redis();
			$redisconf = Store::instance($redis_name);
			self::$redisConnection[$redis_name]->connect($redisconf['host'], $redisconf['port']);
    	}
    }
    /**
     * 更新定时器
     * 注册成功或收到心跳包，更新定时器
     * @param string $client_id
     */
    private static function setTimeid($client_id){
    	
    	// 删除旧定时器，创建新定时器
    	\Workerman\Lib\Timer::del(self::$redisConnection['timeid']->get($client_id));
			$timeid = \Workerman\Lib\Timer::add(self::$heartInteval, function($client_id){
		    Gateway::closeClient($client_id);
		    },array($client_id),false);
		self::$redisConnection['timeid']->set($client_id, $timeid);
    }

	/**
	 * 当客户端连接时触发
	 * @param  int $client_id
	 * @return void
	 */
    public static function onConnect($client_id){
    	// 初始化
    	self::init();
    	// 增加定时器(心跳包)
    	$timeid = \Workerman\Lib\Timer::add(self::$heartInteval, function($client_id){
    		Gateway::closeClient($client_id);
    	},array($client_id),false);
    	self::$redisConnection['timeid']->set($client_id, $timeid);
    	// 请求客户端注册
    	Gateway::sendToCurrentClient(self::$pushRegistMessage);
    }

	/**
	 * 当客户端发来消息时触发
	 * @param  int $client_id
	 * @param  string $message
	 * @return void
	 */
   	public static function onMessage($client_id, $message)
   	{
		// var_dump(bin2hex($message));
		// Gateway::sendToCurrentClient($message);
		// 得到协议版本
		$wifiversion 	= 	substr($message,4,2);
		// 得到消息中的目的源地址
		$targetaddr 	= 	substr($message,8,12);
		// 得到消息中的数据源地址
		$startaddr 		= 	substr($message,20,12);
		// 得到数据类型
		$messageClass 	= 	substr($message,33,1);
		// 得到心跳包类型
		$onlinedata 	= 	substr($message,35,1);
		// 更新心跳包
		if($onlinedata === "\xE2" && isset($_SESSION['registflag']))
		{

		    self::setTimeid($client_id);
		    return;
		}
		// 初始化SESSION
		if(!isset($_SESSION['initsession']))
		{
			self::setSessionStart($startaddr);
		}
		

		// 客户端之间通讯
		if($wifiversion === "\x01\x01" && $targetaddr !== Protocol::$V1['DEFALUT_SMAC'])
		{
		    /**
		     * 判断SESSION缓存中客户端client_id是否在线
		     * 找不到缓存，查找MYSQL
		     */
		    if(isset($_SESSION["$targetaddr"]))
			{
		    	// if($messageClass === "\x02"){
			    // 	// 记录控制次数
			    // 	self::$connectHC->query("UPDATE `HC` SET `control` = `control`+1 WHERE macid='$startaddr'");
			    // }
			    Gateway::sendToClient($_SESSION["$targetaddr"], $message);
			    return;
			}
		    else
		    {
		    	// 得到目的客户端client_id
				$targetaddrclientid = self::$connectHC->single("SELECT clientid FROM `HC` WHERE macid='$targetaddr'");
				// 如果目的客户端在线则转发
				if(Gateway::isOnline("$targetaddrclientid"))
				{
					// 写入$_SESSION缓存
					$_SESSION["$targetaddr"] = $targetaddrclientid;
			   		// if($messageClass === "\x02"){
			    	// 	// 记录控制次数
			    	// 	self::$connectHC->query("UPDATE `HC` SET `control` = `control`+1 WHERE macid='$startaddr'");
			    	// }
			    	Gateway::sendToClient($targetaddrclientid, $message);
			    	return;
				}
				// 目的客户端不在线则回复
				Gateway::sendToCurrentClient($_SESSION['transerror']);
			}
		}
		// 客户端注册
		if($targetaddr === Protocol::$V1['DEFALUT_SMAC'])
		{
			// 重复注册
			if(isset($_SESSION['registflag']) && $_SESSION['registflag'] === 1)
			{
				self::setTimeid($client_id);
				Gateway::sendToCurrentClient($_SESSION['registsuccess']);
				return;
			}
		    /**
		     * 查询MYSQL, 得到客户端client_id
		     * 如果不存在就插入新的
		     * 如果存在就更新client_id
		     */
		    $selectclientid = self::$connectHC->single("SELECT clientid FROM `HC` WHERE macid='$startaddr'");
		    if($selectclientid !== false)
		    {
		    	// 更新MYSQL数据库
		    	self::$connectHC->query("UPDATE `HC` SET `clientid` = '$client_id', `lastintime` = CURRENT_TIMESTAMP() WHERE macid='$startaddr'");
		    	$_SESSION['registflag'] = 1;
		    	self::setTimeid($client_id);
		    	Gateway::sendToCurrentClient($_SESSION['registsuccess']);
		    	return;
		    }
		    // 插入MYSQL数据库
		    self::$connectHC->query("INSERT INTO `HC` ( `macid`,`clientid`,`lastintime`) VALUES ('$startaddr', '$client_id',CURRENT_TIMESTAMP())");
		    $_SESSION['registflag'] = 1;
		    self::setTimeid($client_id);
		    Gateway::sendToCurrentClient($_SESSION['registsuccess']);
		    return;
		}
    }
   
   
    /**
     * 当用户断开连接时触发
     * @param  int $client_id
     * @return void
     */
   public static function onClose($client_id)
   {

       \Workerman\Lib\Timer::del(self::$redisConnection['timeid']->get($client_id));
       self::$redisConnection['timeid']->del($client_id);
       // 更新MYSQL数据库
       self::$connectHC->query("UPDATE `HC` SET `clientid` = '00000000000000000000', `lastouttime` = CURRENT_TIMESTAMP() WHERE clientid='$client_id'");

   }

}