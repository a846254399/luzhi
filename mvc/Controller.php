<?php
/**
 * @author lukez 
 */
namespace luzhi\mvc;

use luzhi\base\Object;

class Controller extends Object
{
	
	public $view;

	public function init()
	{
		$this->view = new View();
	}

	public function view($file,$data = [])
	{
		$this->view->assign($data);
		$this->view->display($file.'.html');
	}



	
}




