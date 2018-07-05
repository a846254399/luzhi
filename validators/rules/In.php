<?php
/**
 * @author lukez 
 */
namespace luzhi\validators\rules;

use luzhi\validators\Rule;

/**
 * 范围验证
 */
class In extends Rules{

	public $error = '属性不在范围内';

	public function verifyCourse()
	{	
		if (!isset($this->option['scope'])) {
			throw new \Exception("Valid Error : Scope is not define");
		}
		// 不存在不验证
		if($this->value === null){
			return true;
		}
		return in_array($this->value,$this->option['scope']);
	}
}
