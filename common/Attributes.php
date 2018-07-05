<?php 
/**
 * @author lukez 
 */
namespace luzhi\common;

/**
 * 属性
 */
trait Attributes{

	/**
	 * attr list
	 * @var array
	 */
	public $attributes = [];

	/**
	 * set attr
	 * @param string or array $label 
	 * @param string $value
	 */
	public function setAttributes($data)
	{
		if (is_array($data)) 
		{
			foreach ($data as $k => $v) 
			{
				$this->setAttribute($k,$v);
			}
		}
	}

	public function setAttribute($label,$value)
	{
		if (property_exists($this,'allow'))
		{
			if (!is_array($this->allow)) {
				throw new \Exception("SetAttribute Error : Allow should be an array");				
			}	
			if (!in_array($label,$this->allow)) {
				return;
			}
		}
		if ($value !== null) {
			$this->attributes[$label] = $value;
		}
	}

	/**
	 * attr is exists ?
	 * @param  string  $label 
	 * @return boolean
	 */
	public function hasAttribute($label)
	{
		return isset($this->attributes[$label]);
	}

	/**
	 * get attr
	 * @param  string $label 
	 * @return string or null
	 */
	public function getAttribute($label)
	{
		if ($this->hasAttribute($label))
		{
			return $this->attributes[$label];
		}
		return null;
	}



}