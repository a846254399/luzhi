<?php 
/**
 * @author lukez
 */
namespace luzhi\db\depend;

use PDO;

class PDODriver{

	protected static $db;

	protected function __construct(){}

	public static function open( $config ){
		if (!static::$db) {
			try {
				$dsn = $config['dbtype'].':host='.$config['host'].';dbname='.$config['dbname'];
				$db = new PDO($dsn, $config['user'], $config['pwd']);
				return static::$db = $db;
			} catch (Exception $e) {
				throw new Exception("DB config error", 1);	
			}
		}else{
			return static::$db;
		}
	}

}