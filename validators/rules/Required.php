<?php
/**
 * @author lukez 
 */
namespace luzhi\validators\rules;

use luzhi\validators\Rule;

/**
 * 唯一验证
 */
class Required extends Rules{

	public function getDefaultError()
	{
		return $this->label.'属性不存在';
	}

	public function verifyCourse()
	{
		return $this->value !== null && $this->value !== '';
	}

}

