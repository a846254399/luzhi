<?php 
/**
 * @author lukez
 */
namespace luzhi\helper;

/**
 * 文件
 */
class File
{

	/**
	 * 检测文件是否存在该目录中
	 * @param  string  $fileName 文件名 需要有后缀名
	 * @param  string  $dir      完整路径
	 * @return boolean         
	 * TODO::中文不能正确检测
	 */
	public static function isExists($fileName,$dir)
	{
		if (is_dir($dir) && $dh = opendir($dir)){
		    while( false !== ($file = readdir($dh)) ){
		      	if ($file == '.' || $file == '..') {
		      		continue;
		      	}
		      	$filePath = $dir.'/'.$file;
		      	if (is_dir($filePath)) {
		      		if(static::isExists($fileName,$filePath)){
		      			return true;
		      		}
		      	}else{	      		
			      	if ($fileName == $file) {
			      		return true;
			      	}
		      	}
		    }
		    closedir($dh);
		}
		return false;
	}

	/**
	 * 获取没有后缀名的文件名
	 * @return string
	 */
	public function getNameNoSuffix($fileName)
	{
		return substr($fileName,0,strpos($fileName,'.'));
	}



}
