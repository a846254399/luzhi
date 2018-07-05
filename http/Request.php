<?php 
/**
 * @author lukez
 */
namespace luzhi\http;

use luzhi\Object;

class Request extends Object
{

	const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * 输入流元数据
     */
    protected $raw;

	/**
	 * 字段过滤
	 */               
	public static function filtration($data)
	{
		if (is_array($data)) {
			foreach ($data as $k => $v) {
				$data[$k] = static::filtration($v);
			}
		}else{
			$data = strip_tags(trim($data));
		}
		return $data;
	}	

	/**
	 * get方式的字段
	 */
	public static function get($name)
	{
		return isset($_GET[$name])
			? static::filtration($_GET[$name])
			: null;
	}

	/**
	 * post方式的字段
	 */
	public static function post($name)
	{
		return isset($_POST[$name])
			? static::filtration($_POST[$name])
			: null;
	}

	/**
	 * 获取所有 get or post
	 */
	public static function all($type = static::METHOD_POST)
	{
		$data = strtoupper($type) === static::METHOD_POST
			? $_POST
			: $_GET;
		return static::filtration($data);
	}

	/**
	 * 获取元数据
	 */
	public static function getRaw()
	{
		if ($this->raw === null) {
            $this->raw = file_get_contents('php://input');
        }
        return $this->raw;
	}

	/**
	 * 获取当前客户的的ip
	 */
	public static function getIp() {
		static $realip = NULL;
		if ($realip !== NULL) {
			return $realip;
		} if (getenv('HTTP_X_FORWARDED_FOR')) {
			$realip = getenv('HTTP_X_FORWARDED_FOR');
		} elseif (getenv('HTTP_CLIENT_IP')) {
			$realip = getenv('HTTP_CLIENT_IP');
		} else {
			$realip = getenv('REMOTE_ADDR');
		} 
		return $realip;
	}

	public static function isPost()
	{
		return $_SERVER['REQUEST_METHOD'] === static::METHOD_POST;
	}

	public static function isGet()
	{
		return $_SERVER['REQUEST_METHOD'] === static::METHOD_GET;
	}

	public static function isAjax()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' );
	}

}


