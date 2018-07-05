<?php
/**
 * @author lukez 
 */
namespace luzhi\validators\rules;

use luzhi\validators\Rule;

/**
 * 长度验证
 */
class Length extends Rules{

	public $error = 'Character length limits';

	public function verifyCourse()
	{	
		// 不存在不验证
		if($this->value === null){
			return true;
		}
		$len = mb_strlen($this->value,'UTF8');
		if (isset($this->option['min']) && $len < $this->option['min']) {
			return false;
		}
		if (isset($this->option['max']) && $len > $this->option['max']) {
			return false;
		}
		return true;
	}
}
