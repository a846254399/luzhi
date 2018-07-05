<?php
/**
 * @author lukez 
 */
namespace luzhi\validators\rules;

use luzhi\validators\Rule;

/**
 * 回调验证
 */
class Callback extends Rule{

	public $error = '回调验证失败';

	public function verifyCourse()
	{	
		if (!isset($this->option['callback'])) {
			throw new \Exception("Valid Error : Callback is not define");
		}

		$class = $this->option['callback'][0];
		$method = $this->option['callback'][1];
		
		return call_user_func([$class,$method]);
	}
}
