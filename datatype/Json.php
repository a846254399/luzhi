<?php 
/**
 * @author lukez
 */
namespace luzhi\datatype;

/**
 * Json
 */
class Json
{

	public static function encode($data)
	{
		$str = json_encode($data,JSON_UNESCAPED_UNICODE);
		$str = str_replace("\/", "/",$str);
		$str = str_replace('\"','"',$str);
		return $str;
	}



}