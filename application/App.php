<?php
/**
 * @author lukez 
 */
namespace luzhi\application;

//APP
class App{

	public static function start(){

		//定义一些常量
		self::setConst();

		//类自动加载
		spl_autoload_register('self::autoLoad');

		//错误收集
		Error::gatherError();

		//读取配置文件
		self::loadConfig();

		//获取路由 运行网站
		self::run();

	}

	public static function setConst(){

		define('LIB_PATH',__DIR__);
		define('LIB_PATH_NAME',basename(__DIR__));
		define('LIB_NAMESPACE','luzhi');

		define('_PUBLIC_',ROOT.'/public');
		define('_VIEW_',APP_PATH.'/view');
		
		define('APP_PATH_NAME',basename(APP_PATH));

		// print_r(APP_PATH_NAME);exit;
	}

	/**
	 * 自动加载类
	 */
	public static function autoLoad( $classname ){

		switch ( explode('\\', $classname)[0] ) {
			case LIB_NAMESPACE:
				//luzhi就是框架内部namespace
				$file = LIB_PATH.str_replace(LIB_NAMESPACE,'',$classname).'.php';			
				break;	
			case APP_PATH_NAME:
				//项目路径和namespace对应
				$file = dirname(APP_PATH).'/'.$classname.'.php';			
				break;
			default:
				//默认从根目录路径对应namespace
				$file = ROOT.'/'.$classname.'.php';
				break;
		}
		if(is_file($file)){
			require($file);
		}else{
			throw new \Exception("No Class Is ".$classname, 1);
		}
	}

	public static function loadConfig(){
		
		$configDir = [
			LIB_PATH.'/config',
			ROOT.'/config',
			APP_PATH.'/config',
		];

		foreach ($configDir as $dir) {
			Config::$path = $dir;
			Config::loadDirConfig();
		}
	}


	public static function run(){

		$routeType = Config::get('routeType');
		$route = Route::$routeType();

		$className = $route['controller'];
		$method = $route['method'].'Action';

		$class = '\\'.APP_PATH_NAME."\controllers\\$className";

		// echo $class;exit;

		$controller = new $class();

		if (method_exists($controller,$method)) {
			$controller->$method();
		}else{
			throw new \Exception("No Method Is ".$method, 1);
		}
		
	}


















}





