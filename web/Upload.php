<?php
/**
 * @author lukez 
 */
namespace luzhi\web;

use luzhi\common\Errors;
use luzhi\base\Object;

class Upload extends Object{

	use ErrorTrait;

	const INVALID_PARAM = 1;
	const NO_FILE_SEND = 2;
	const FILESIZE_LIMIT = 3;
	const NO_TYPE = 4;
	const NO_SIZE = 5;
	const UNKNOWN = 6;
	const MOVE_ERROR = 7;

	/**
	 * 允许类型
	 */
	public $allowType = [];

	protected $allType = [
        'jpg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
	];

	/**
	 * 上传目录
	 */
	public $path = './resource/img/uploads/';

	/**
	 * 最大长度 单位m
	 */
	public $maxSize;

	/**
	 * 文件name值
	 */
	public $name;

	/**
	 * 文件类型
	 */
	public $fileExt;

	/**
	 * 上传结果
	 */
	public $result;


	public function __construct($name)
	{
		parent::__construct();
		$this->name = $name;
	}

	/**
	* 检测文件类型 true or false
	*/
	public function checkType()
	{
		if (empty($this->allowType)) {
			$this->fileExt = substr(strrchr($_FILES[$this->name]['name'],'.'),1);
			return true;
		}
		$filter = [];
		foreach ($this->allowType as $type) {
			if (isset($this->allType[$type])) {
				$filter[$type] = $this->allType[$type];
			}
		}

	    $finfo = new \finfo(FILEINFO_MIME_TYPE);
	    if($this->fileExt = array_search(
	    	$finfo->file($_FILES[$this->name]['tmp_name']),
	    	$filter,
	        true
	    )){
	    	return true;
	    }
	    $this->error = static::NO_TYPE;
	    return false;
	}

	/**
	* 检测文件大小 true or false
	*/
	public function checkSize()
	{
		if (!is_numeric($this->maxSize)) {
			return true;
		}
		if($_FILES[$this->name]['size']/1024/1024 <= $this->maxSize){
			return true;
		}
		$this->error = static::NO_SIZE;
		return false;
	}

	public function checkError()
	{
		if (!isset($_FILES[$this->name]['error']) || is_array($_FILES[$this->name]['error']))
		{
			$this->error = static::INVALID_PARAM;
			return false;
        }
	    switch ($_FILES[$this->name]['error']) {
	        case UPLOAD_ERR_OK:
	        	return true;
	            break;
	        case UPLOAD_ERR_NO_FILE:
	            $this->error = static::NO_FILE_SEND;
	        case UPLOAD_ERR_INI_SIZE:
	        case UPLOAD_ERR_FORM_SIZE:
	            $this->error = static::FILESIZE_LIMIT;
	        default:
	            $this->error = static::UNKNOWN;
	    }
	    return false;
	}

	public function move()
	{
		$path = $this->path;
		$name = sha1_file($_FILES[$this->name]['tmp_name']).'.'.$this->fileExt;

		if (!is_dir($path)) {
			mkdir($path);
		}
	    if (move_uploaded_file($_FILES[$this->name]['tmp_name'],$path.$name)) {
	        $this->result = ['status'=>'ok','path'=>$path.$name];
	        return true;
	    }
	    $this->error = static::MOVE_ERROR;
	    return false;
	}


	public function run()
	{
		if (!$this->checkError()) {
			return false;
		}
		if (!$this->checkType()) {
			return false;
		}
		if (!$this->checkSize()) {
			return false;
		}
		if (!$this->move()) {
			return false;
		}
		return true;
	}

}