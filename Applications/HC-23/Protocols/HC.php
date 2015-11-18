<?php
namespace Protocols;

class HC{

	/**
	 * 验证数据包长度是否正确
	 * @param  string $str
	 * @param  int $strlen
	 * @param  int $headbegin
	 * @return int
	 */
	private static function regmessage($str, $strlen, $headbegin){
		// 协议中数据包长度
		$datalenght = self::datalenght(substr($str, $headbegin+6, 2));
		// 按照数据包长度去找协议尾
		$endsign = substr($str, $datalenght-4, 4);
		// 判断数据包长度是否符合协议
		if($endsign === "\x48\x43\x0D\x0A")
		{
			return $datalenght;
		}
		// 最后一次出现数据尾的位置
		$lastendsign = strrpos($str, "\x48\x43\x0D\x0A");
		// 数据包长度小于实际长度
		if($endsign !== false && $datalenght < ($lastendsign+4))
		{
			$headbegin = strpos($str, '++HC', $headbegin+4);
			// 后面找不到'++HC'
			if($headbegin === false)
			{
				return $strlen;
			}
			// 在协议尾后面找到第二个'++HC'
			if($headbegin >= ($lastendsign+4))
			{
				return $headbegin;
			}
			// 递归验证数据包
			return self::regmessage($str,$strlen,$headbegin);
		}
		return 0;
	}
	/**
	 * 没有找到'++HC'情况下,最优解决
	 * @param  string $str
	 * @return int $strlen
	 * @return int
	 */
	private static function findhead($str, $strlen){
		if($a = strrpos($str, '++H'))
  		{
    		return $a;
  		}
  		if($b = strrpos($str, '++'))
  		{
    		return $b;
  		}
  		if($c = strrpos($str, '+'))
  		{
    		return $c;
  		}
  		return $strlen;
	}
	/**
	 * 16进制字符转换为10进制数字
	 * @param  string $str
	 * @return int | bool
	 */
	private static function datalenght($str){
  		
  		if($str)
  		{
  			$height = dechex(ord($str{0}));
  			$low = dechex(ord($str{1}));
  			return hexdec($height.$low);
  		}
  		return false;
	}
	/**
	 * 判断数据包长度
	 * @param  string $buffer
	 * @return int
	 */
	public static function input($buffer){

		// 数据包总长度
		$bufferlenght = strlen($buffer);
		// '+'开头,数据包大于4位
		if($bufferlenght >= 4)
		{
			// 第一次出现'++HC'位置
			$headsignall = strpos($buffer,'++HC');
			if($headsignall === 0 && $bufferlenght >= 8)
			{

				return self::regmessage($buffer, $bufferlenght, 0);

			}
			// 没有找到协议头
			if($headsignall === false)
			{
				return self::findhead($buffer, $bufferlenght);
			}
			// 协议头前面数据
			if($headsignall)
			{
				return $headsignall;
			}
		}
		return 0;		
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
	 * @return string
	 */
	public static function decode($buffer){

		return $buffer;
    }
}
?>