<?php 
use \Workerman\Worker;
use \Workerman\WebServer;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;
use \Workerman\Autoloader;

// 自动加载类
require_once __DIR__ . '/../../Workerman/Autoloader.php';
Autoloader::setRootPath(__DIR__);

/**
 * WIFI模块的透传进程
 */
$gateway_com = new Gateway("tcp://0.0.0.0:50000");
// 进程名称
$gateway_com->name = 'HC-23COMGateway';
// 进程数
$gateway_com->count = 1;
// 本机ip，分布式部署时使用内网ip
$gateway_com->lanIp = '127.0.0.1';
// 内部通讯起始端口
$gateway->startPort = 50001;
// // 心跳间隔
// $gateway->pingInterval = 2;
// // 心跳不响应次数
// $gateway->pingNotResponseLimit = 0;
// // 心跳数据
// $gateway->pingData = '222';
//
/* 
// 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
$gateway->onConnect = function($connection)
{
    $connection->onWebSocketConnect = function($connection , $http_header)
    {
        // 可以在这里判断连接来源是否合法，不合法就关掉连接
        // $_SERVER['HTTP_ORIGIN']标识来自哪个站点的页面发起的websocket链接
        if($_SERVER['HTTP_ORIGIN'] != 'http://kedou.workerman.net')
        {
            $connection->close();
        }
        // onWebSocketConnect 里面$_GET $_SERVER是可用的
        // var_dump($_GET, $_SERVER);
    };
}; 
*/
// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

