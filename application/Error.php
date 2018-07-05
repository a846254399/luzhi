<?php
/**
 * @author lukez 
 */
namespace luzhi\application;

use luzhi\base\Object;

class Error extends Object{

	public function __construct(){
		error_reporting(0);
	}

	public static function gatherError(){
		$error = new Error;
		set_error_handler([$error,'errorHandler']);
		set_exception_handler([$error,'exception']);
		register_shutdown_function([$error,'lastError']);
	}

	public function lastError(){
		$err = error_get_last();
		if($err){
			if ($err['type'] != 2 || $err['type'] != 8) {
				$this->errorDispose($err['type'],$err['message'],$err['file'],$err['line']);
			}
		}
	}

	public function errorHandler($code,$message,$file,$line){
		if ($code != 2 || $code != 8) {
			$this->errorDispose($code,$message,$file,$line);
		}	
	}

	public function exception($ex){
		$code = $ex->getCode();
		$message = $ex->getMessage();
		$file = $ex->getFile();
		$line = $ex->getline();
		$this->errorDispose($code,$message,$file,$line);
	}


	public function createErrorHtml($code,$message,$file,$line){

// 		$html = <<<EOF
// 	<div style='text-align: center;'>
// 		<h2 style='color:red;'>错误</h2>
// 		<table style='width: 80%;  display: inline-block;'>
// 			<tr style='background-color:rgb(240,240,240);height:40px;'><th>信息</th><td>{$message}</td></tr>
// 			<tr style='background-color:rgb(230,230,230);height:40px;'><th>文件</th><td>{$file}</td></tr>
// 			<tr style='background-color:rgb(240,240,240);height:40px;'><th>行数</th><td>{$line}</td></tr>
// 		</table>
// 	</div>
// EOF;	
		$css = file_get_contents(LIB_PATH.'/assets/css/layui.css');
		include(LIB_PATH.'/assets/error.html');
		exit();
		// echo $html;
	}



	//统一错误处理 取决于DEBUG 常量
	public function errorDispose($code,$message,$file,$line){
		if (defined('DEBUG')) {
			$this->createErrorHtml($code,$message,$file,$line);
		}else{
			$this->errorLog($code,$message,$file,$line);
			require(ROOT.'/resource/html/500.html');
			exit();
		}
	}


	public function errorLog($code,$message,$file,$line){
		$error_msg = '日期：'.date('Y-m-d h:i:s')."\r\n";
		$error_msg .= '错误代码：'.$code."\r\n";
		$error_msg .= '错误信息：'.$message."\r\n";
		$error_msg .= '错误文件：'.$file."\r\n";
		$error_msg .= '错误行数：'.$line."\r\n";
		error_log($error_msg."\r\n",3,ROOT.'/resource/log/errorlog.txt');
	}
}	


 ?>