<?php
/**
 * @author lukez 
 */
namespace luzhi\validators\rules;

use luzhi\validators\Rule;
/**
 * 数字验证
 */
class Numeric extends Rules{

	public $error = '属性非数字';

	public function verifyCourse()
	{	
		// 不存在不验证
		if($this->value === null){
			return true;
		}
		return is_numeric($this->value);
	}
}

