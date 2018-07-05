<?php
/**
 * @author lukez 
 */
namespace luzhi\mvc;

use luzhi\base\Object;

class View extends Object
{

	protected $var = [];

	/**
	 * 传递变量
	 */
	public function assign($var = []){
		if (is_array($var)) {
			$this->var = array_merge($this->var,$var);
		}else{
			$this->var[$var] = $var;
		}
	}
	
	/**
	 * 显示页面
	 */
	public function display($html){

		$file = APP_PATH.'/view/'.$html;

		if (is_file($file)) {
			foreach ($this->var as $k => $v) {
				$$k = $v;
			}
			require($file);	
		}else{
			throw new \Exception("view：{$file} is no exists",1);
		}	
	}


}
