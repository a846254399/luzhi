<?php 
/**
 * @author lukez
 */
namespace luzhi\datatype;

/**
 * 数组
 */
class Arr{

	/**
	 * 是否为非重复数组（值都是唯一）
	 */
	public static function isRepeat($data)
	{
		$tmp = [];
		foreach ($data as $val) {
			if (!$val) {
				continue;
			}
			if (isset($tmp[$val])) {
				return true;	
			}	
			$tmp[$val] = true;
		}
		return false;
	}


	/**
	 * 对值进行替换
	 * @param  array $data 
	 * @param  all $replace
	 * @return $data
	 */
	public static function valueRepalce($data,$replace)
	{
		foreach ($data as $k => $val) {
			$data[$k] = $replace;
		}
		return $data;
	}


	public static function merge($data,$data2)
	{
		foreach ($data2 as $k => $val) {
			// 索引数组
			if (is_numeric($k)) {
				array_push($data,$val);
			}else{
				$data[$k] = $val;
			}
		}
		return $data;
	}


	




















}

















 ?>