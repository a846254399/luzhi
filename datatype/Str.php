<?php 
/**
 * @author lukez
 */
namespace luzhi\datatype;

/**
 * 字符串
 */
class Str
{

	const LETTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

	const UPPER = 1;
	const LOWER = 0;


	/**
	 * 获取26个字母数组集
	 * @param  int $mode 大写还是小写
	 * @return array
	 */
	public static function getLettersArray($mode = static::UPPER)
	{
		if ($mode === static::LOWER) {
			$letters = strtolower(static::LETTERS);
		}else{
			$letters = static::LETTERS;
		}
		return str_split($letters); 
	}

	//带命名空间的类 转为只有类名
	public static function getClassName( $class ){
		$data = explode("\\",$class);
		return array_pop($data);
	}







}