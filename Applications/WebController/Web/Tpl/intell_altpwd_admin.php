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
$oldpwd = $_POST['oldpwd'];
$newpwd = $_POST['newpwd'];

$connectHC = Db::instance('ConnectDb');
$result = $connectHC->row("SELECT password FROM `wxadmin` WHERE username='$username'");
if(!$result || $result['password'] !== $oldpwd){ // 如果没有注册或者原密码不正确

	echo "error";
	HTTP::end();
}else{

	$connectHC = Db::instance('ConnectDb');
	$result = $connectHC->query("UPDATE `wxadmin` SET `password` = '$newpwd' WHERE username='$username'");
	echo "ok";
}

?>