<?php 
require_once dirname(__DIR__).'/redisData.php';
require_once dirname(dirname(dirname(dirname(__DIR__)))).'/Workerman/Autoloader.php';
\Workerman\Autoloader::setRootPath(__DIR__.'/../../');
use \GatewayWorker\Lib\Db;
use \Workerman\Protocols\Http;

$username = $_POST['username'];
$macid = $_POST['macid'];

if(!isset($username)){

	echo "对不起，你访问的网页不存在！:(";
  	HTTP::end();
}


$connectHC = Db::instance('ConnectDb');
$getmacidlist = $connectHC->row("SELECT macid FROM `wxserver` WHERE openid='$username'");
$getmacidlist = substr_replace($getmacidlist['macid'], "", strpos($getmacidlist['macid'], $macid), 13);
$result = $connectHC->query("UPDATE `wxserver` SET `macid` = '$getmacidlist' WHERE openid='$username'");
if($result == 1){

	echo "ok";
}else{

	echo "error";
}


?>