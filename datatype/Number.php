<?php 
/**
 * @author lukez
 */
namespace luzhi\datatype;

/**
 * Class Math 
 * 数学操作
 */
class Math
{
	/**
	 * 求平均值
	 */
	public static function avg($data){
		if (empty($data)) {
			return 0;
		}
		$sum = 0;
		foreach ($data as $key => $value) {
			$sum += $value;
		}
		return $sum/count($data);
	}
}