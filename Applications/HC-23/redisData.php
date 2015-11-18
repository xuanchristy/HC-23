<?php 
class redisData{

	//连接redis数据库
    private static function connectRedis()
    {

        $redis = new Redis();
        $redis->connect('127.0.0.1', '6380');
        return $redis;
    }

    //设置对应OpenId的哈希表
    public static function hSet($client_openid, $key, $value)
    {
        
        self::connectRedis()->hSet($client_openid, $key, $value);
    }

    //得到对应OpenId的哈希表
    public static function hGet($client_openid, $key)
    {

        $getvalue = self::connectRedis()->hGet($client_openid, $key);
        return $getvalue;
    }

    //存储键<=>值对

   	public static function Set($key,$value)
   	{

   		self::connectRedis()->set($key,$value);
   	}

   	//得到access_token
   	public static function Get($key)
   	{

   		$getvalue = self::connectRedis()->get($key);
   		return $getvalue;
   	}

    // 得到h哈希表中的键/值对个数
    public static function hLen($h)
    {

      $getvalue = self::connectRedis()->hLen($h);
      return $getvalue;
    }

    // 得到h哈希表中的所有键名
    public static function hKeys($h)
    {

      $getvalue = self::connectRedis()->hKeys($h);
      return $getvalue;
    }

    // 得到h哈希表中所有的键/值
    public static function hGetAll($h)
    {

      $getvalue = self::connectRedis()->hGetAll($h);
      return $getvalue;
    }
    
    // 删除h哈希表中的key对应的值
    public static function hDel($h,$key)
    {

      $getvalue = self::connectRedis()->hDel($h,$key);
      return $getvalue;
    }

    public static function hMGet($h, $array)
    {

      $getvalue = self::connectRedis()->hMGet($h, $array);
      return $getvalue;
    }

    public static function allarray($a, $b)
    {

      $allarray = array();
      foreach ($a as $key => $value) {
        $allarray[$value] = $b[$key];
      }

      return $allarray;
    }
}
?>