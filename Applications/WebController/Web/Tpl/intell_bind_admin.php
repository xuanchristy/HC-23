<?php
require_once dirname(dirname(dirname(dirname(__DIR__)))).'/Workerman/Autoloader.php';
require_once dirname(__DIR__).'/redisData.php';
\Workerman\Autoloader::setRootPath(__DIR__.'/../../');
use \Workerman\Protocols\Http;
use \GatewayWorker\Lib\Db;

if(!isset($_POST['username'])){

	echo "对不起，您访问的页面不存在！:(";
	HTTP::end();
}

$username = $_POST['username'];
$macid = $_POST['macid'];
$macname = $_POST['macname'];

// username<=>macid 存入MYSQL中
$connectHC = Db::instance('ConnectDb');
$getmacid = $connectHC->row("SELECT macid FROM `wxserver` WHERE openid='$username'");
// 查找是否重复绑定macid
$getmacidnum = strpos($getmacid['macid'],$macid."/");

if(!$getmacidnum && $getmacidnum !== 0){  // 第一次绑定

	// macid<=>macname 存入redis中
	redisData::hSet($username, $macid, $macname);
	$macid = $getmacid['macid'].$macid."/";
	$result = $connectHC->query("UPDATE `wxserver` SET `macid` = '$macid' WHERE openid='$username'");
	if($result == 1){  //更新数据库macid成功

		echo "101";
	}else{  //更新数据库macid失败

		echo "102";
	}
}else{  // 重复绑定

	echo "001";
}


?>