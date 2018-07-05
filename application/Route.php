<?php
/**
 * @author lukez 
 */
namespace luzhi\application;

use luzhi\base\Object;

class Route extends Object{

	//get模式
	public static function get(){

		$route = [];
			
		$controlKey = Config::get('controlKey');
		$methodKey = Config::get('methodKey');

		$route['controller'] = isset($_GET[$controlKey])?ucfirst($_GET[$controlKey]):Config::get('defaultControl');

		$route['method'] = isset($_GET[$methodKey])?$_GET[$methodKey]:Config::get('defaultMethod');

		return $route;
	}

	//pathinfo 模式
	public static function pathInfo(){

		$controller = Config::get('defaultControl');
		$method = Config::get('defaultMethod');
	
		if ( $_SERVER['REQUEST_URI'] !== '/') {
			$path = trim($_SERVER['REQUEST_URI'],'/');
			if ($pos = strpos($path,'?')) {
				$path = substr($path,0,$pos);  
			}
			
			$pathinfo = explode('/',$path);
			
			$controller = isset($pathinfo[0])?ucfirst($pathinfo[0]):$controller;
			$method = isset($pathinfo[1])?$pathinfo[1]:$method;
		}
		return ['controller'=>$controller,'method'=>$method];
	}




}
