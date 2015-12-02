<?php
namespace Config;

class Db
{
	// MYSQL配置
	public static $ConnectDb = array(
		'host'		 => '127.0.0.1',
		'port'		 => 3306,
		'user'		 => 'root',
		'password'	 => 'a8508212',
		'dbname'	 => 'HC',
		'charset'	 => 'utf8'
	);
	// REDIS配置
	public static $ConnectRedis = array(
		'host'		=> '127.0.0.1',
		'port'		=> '6380'
	);
}
?>
