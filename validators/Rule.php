<?php
/**
 * @author lukez 
 */
namespace luzhi\validators;

/**
 * 验证规则 抽象类
 */
abstract class Rule{


	use Errors;

	/**
	 * 需验证字段名
	 * @var 
	 */
	public $label;

	/**
	 * 需验证字段值
	 * @var 
	 */
	public $value;

	/**
	 * 其它配置 
	 * @var 
	 */
	public $option = [];

	/**
	 * 默认error
	 */
	protected $error = 'valid error';

	// 设置验证字段
	public function setValidField($label,$value)
	{
		$this->label = $label;
		$this->value = $value;
	}

	public function setOption($option)
	{
		$this->option = array_merge($this->option,$option);
	}

	// 可重写
	public function getDefaultError(){
		return $this->error;
	}

	protected function verifyFail()
	{
		$error = isset($this->option['error']) ? $this->option['error'] : $this->getDefaultError();
		$this->addError($error);
	}

	// 验证过程
	abstract function verifyCourse();


	public function verify()
	{
		if (!$this->verifyCourse()) {
			$this->verifyFail();
			return false;
		}
		return true;
	}



}
