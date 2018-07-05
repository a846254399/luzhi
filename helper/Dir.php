<?php 
/**
 * @author lukez
 */
namespace luzhi\helper;

/**
 * 目录
 */
class Dir
{

	/**
	 *  递归删除目录内所有文件
	 * @param  string $dir 目录
	 * @return true
	 */
	public static function rmAll($dir)
	{
		if (is_dir($dir) && $dh = opendir($dir)){
		    while( false !== ($file = readdir($dh)) ){
		      	if ($file == '.' || $file == '..') {
		      		continue;
		      	}
		      	$filePath = $dir.'/'.$file;		
		      	if (is_dir($filePath)) {
		      		static::rmAll($filePath);
		      	}else{	 
			      	unlink($filePath);
		      	}
		      	@rmdir($filePath);
		    }
		    closedir($dh);
		}
		@rmdir($dir);
		return true;
	}







}