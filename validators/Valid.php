<?php
/**
 * @author lukez 
 */
namespace luzhi\validators\rules;

use luzhi\common\Errors;
use luzhi\common\Attributes;

/**
 * 验证
 */
class Valid
{

	use Errors;

	use Attributes;

	public $ruleMaps = [
		'required'=>'\Home\Support\Validators\Rules\Required',
		'numeric'=>'\Home\Support\Validators\Rules\Numeric',
		'in'=>'\Home\Support\Validators\Rules\In',
		'callback'=>'\Home\Support\Validators\Rules\Callback',
		'length'=>'\Home\Support\Validators\Rules\Length',
	];

	/**
	 * 规则
	 * [ ['*label','*rule',['option'=>'option',...]] ];
	 * @var array
	 */
	public $rules = [];


	public function verify()
	{
		foreach ($this->rules as $rule) 
		{	
			// 参数缺失
			if (count($rule) < 2) 
			{
				throw new \Exception('Valid Error : Valid parameter deletion');
			}
			$label = $rule[0];
			$type = $rule[1];

			// 不存在该验证类型
			if (!isset($this->ruleMaps[$type]))
			{
				throw new \Exception('Valid Error : ValidType : '.$type.' does not exist');
			}

			// 配置验证器
			$verifier = new $this->ruleMaps[$type];
			if ($this->hasAttribute($label)) 
			{	
				// 配置验证字段名和值
				$verifier->setValidField($label,$this->getAttribute($label));
			}else{
				$verifier->setValidField($label,null);
			}
			if (isset($rule[2])) 
			{
				// 配置其它属性
				$verifier->setOption($rule[2]);
			}

			// 验证
			if(!$verifier->verify())
			{
				$this->addError($verifier->getError());
				return false;	
			}
		}	
		return true;
	}

}