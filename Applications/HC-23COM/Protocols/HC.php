<?php
namespace Protocols;

class HC{

	/**
     * 协议版本：V1
     * @var json
     */
    const PROTOCOL_V1 = '{"HeadSign":4, "Protocol":2, "MessageLength":2, "TargetAddr":12, "StartAddr":12, "Model":1, "Class":1, "Left":1, "AT":1}';
	
	/**
	 * 判断数据包长度
	 * @param  string $buffer
	 * @return int
	 */
	public static function input($buffer){

		$headsign = strpos($buffer, '++HC');
		if(false === $headsign)
		{
			return 0;
		}

		$endsign = strpos($buffer, 'HC'."\r");
		if(false === $endsign)
		{
			return 0;
		}

		return $endsign+4;
	}

	/**
	 * HC协议封包
	 * @param  string $buffer
	 * @return string
	 */
	public static function encode($buffer){

		return $buffer;

	}

	/**
	 * HC协议拆包
	 * @param  string $buffer
	 * @return array
	 */
	public static function decode($buffer){

		$data = array();
		// 判断协议版本
		switch(substr($buffer, 4, 2))
		{
			// 协议版本：V1
			case "\x01\x01":
				$protocol = json_decode(self::PROTOCOL_V1);
		    	foreach ($protocol as $key => $value) {
		    		$newmessage = &$buffer;
		    		$data[$key] = substr($buffer, 0, $value);
		    		$newmessage = substr($buffer, $value);

		    	}
		    	$data['Param'] = substr($buffer, 0, -5);
		    break;

		    // case "":
		    // defalut:
		}
		return $data;

    }
}

?>