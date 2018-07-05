<?php 
/**
 * @author lukez 
 */
namespace luzhi\common;

/**
 * errors
 */

trait Errors{

	protected $errors = [];

	public function hasError()
	{
		return empty($this->errors);
	}

	public function addError($message)
	{
		array_push($this->errors,$message);
	}

	public function getError()
	{
		return array_pop($this->errors);
	}

}