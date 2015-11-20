<?php
require_once dirname(dirname(dirname(dirname(__DIR__)))).'/Workerman/Autoloader.php';
require_once dirname(__DIR__).'/redisData.php';
require_once dirname(dirname(__DIR__)).'/GatewayClient/Gateway.php';
\Workerman\Autoloader::setRootPath(__DIR__.'/../../');
use \Workerman\Protocols\Http;
use \GatewayWorker\Lib\Db;
HTTP::sessionStart();

if(isset($_POST['username'])){

	$username = $_POST['username'];
	$macid = $_POST['macid'];
	$text = $_POST['text'];

	$connectHC = Db::instance('ConnectDb');
	$clientid = $connectHC->row("SELECT clientid FROM `wifiserver` WHERE macid='$macid'");

	if(Gateway::isOnline($clientid['clientid'])){  //判断设备是否在线

		if(!isset($_SESSION['login'])){

			echo "103";

		}else{

			Gateway::sendToClient($clientid['clientid'], $text);
			echo "101";

		}
		
	}else{

		echo "102";

	}
}else{

	echo "对不起，您访问的页面不存在！:(";
	HTTP::end();
}

?>