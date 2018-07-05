<?php
/**
 * @author lukez 
 */
namespace luzhi\application;

use luzhi\base\Object;

class Config extends Object{

	//保存所有配置
	public static $config = [];

	//配置文件路径
	public static $path;

	//配置文件
	public static $files;

	public function __construct(){}

	public function init(){

		// if (is_array($this->files)) {
		// 	foreach ($this->files as $file) {
		// 		$this->loadFileConfig( $this->path.$file );
		// 	}
		// }else if( is_string($this->files) ){
		// 	$this->loadFileConfig( $this->path.$this->files );
		// }else{
		// 	throw new Exception('非法参数',1);
		// }

	}

	//加载目录下所有文件
	public static function loadDirConfig(){
		if (is_dir(static::$path)) {
			$dir = opendir(static::$path);
			while ($file = readdir($dir)) {
				if ($file != '.' && $file != '..') {
					static::loadFileConfig( $file );
				}
			}
			closedir($dir);
		}
	}

	//加载文件
	protected static function loadFileConfig( $file ){

        if( !file_exists($file) ){
        	$file = static::$path.'/'.$file;
        }
        try{
            $config = include $file;
        }catch(\Exception $e){
            throw $e;
        }
        if (is_array($config)) {
        	static::$config = array_merge(static::$config , $config);
        }
	}


	public static function get($key = '*'){
		if ($key == '*') {
			return static::$config;
		}
		$config = static::$config;
		return array_key_exists($key, $config)
			? $config[$key]
			: NULL;
	}

	public static function set($key,$value){
		static::$config[$key] = $value;;
	}





















}
