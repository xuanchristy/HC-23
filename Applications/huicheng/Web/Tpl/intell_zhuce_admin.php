<?php 
require_once dirname(dirname(dirname(dirname(__DIR__)))).'/Workerman/Autoloader.php';
\Workerman\Autoloader::setRootPath(__DIR__.'/../../');
use \GatewayWorker\Lib\Db;
use \Workerman\Protocols\Http;

if(!isset($_POST['username'])){

	echo "对不起，您访问的页面不存在！:(";
	HTTP::end();
}

	$username = $_POST['username'];
	$password = $_POST['password'];
	$connectHC = Db::instance('ConnectDb');
	$result = $connectHC->row("SELECT username FROM `wxadmin` WHERE username='$username'");
	
	if($result){ // 用户已注册

		echo "error";
	
	}else{

		$connectHC->query("INSERT INTO `wxserver` (`openid`) VALUES ('$username')");
		$connectHC->query("INSERT INTO `wxadmin` (`username`,`password`) VALUES ( '$username', '$password')");
		echo "ok";
	}

?>