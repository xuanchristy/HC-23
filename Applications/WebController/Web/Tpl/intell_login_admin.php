<?php
require_once dirname(dirname(dirname(dirname(__DIR__)))).'/Workerman/Autoloader.php';
\Workerman\Autoloader::setRootPath(__DIR__.'/../../');
use \Workerman\Protocols\Http;
use \GatewayWorker\Lib\Db;

if(isset($_POST['username'])){

	$username = $_POST['username'];
	$password = $_POST['password'];
	$connectHC = Db::instance('ConnectDb');
	$result = $connectHC->row("SELECT password FROM `wxadmin` WHERE username='$username'");
	if($result['password'] == $password){

		echo "ok";
		HTTP::sessionStart();
		$_SESSION['login'] = 1;
		HTTP::sessionWriteClose();
	
	}else{

		echo "error";
	}
}else{

	echo "对不起，您访问的页面不存在！:(";
}

// MYSQL code...


?>