<?php 
/**
 * @author lukez
 */
namespace luzhi\datatype;

/**
 * Class Floats 
 * 操作小数
 */
class Floats
{
	/**
	 * 保留小数点后N位
	 */
	public static function toFixed($float,$length = 2)
	{
		return sprintf( '%.'.$length.'f',$float);
	}

	



}