<?php 
/**
 * @author lukez 
 */
namespace luzhi\base;

class Object
{
	public function __construct()
	{
		$this->init();
	}

	public function init(){}

	/**
     * @return mixed
     */
    public static function className()
    {
        return get_called_class();
    }

}
