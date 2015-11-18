<?php
require_once dirname(__DIR__).'/redisData.php';
use \Workerman\Protocols\Http;

$username = $_POST['username'];
$macid = $_POST['macid'];
$newmacname = $_POST['newmacname'];

if(!isset($username)){

	echo "对不起，你访问的网页不存在！:(";
  	HTTP::end();
}


redisData::hSet($username,$macid,$newmacname);
$result = redisData::hGet($username,$macid);
if($result == $newmacname){

	echo "ok";
}else{

	echo "抱歉！遇到未知错误";
}




?>