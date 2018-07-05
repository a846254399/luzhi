<?php 
/**
 * @author lukez
 */
namespace luzhi\office;

/**
 * Excel
 */

Class Excel{


	/**
	 * 底层支持
	 * @var obj
	 */
	public $engine;


	public $fileName;


	public $title = [];


	public $titleCols = 0;


	public $contents = [];


	public $fields = [];


	public $engineMaps = [
		'PhpExcel'=>'\Home\Support\Office\PHPExcelCore'
	];


	public function __call($name,$value)
	{
		$this->engine->$name = $value;
	}


	public function __construct($type = 'PhpExcel')
	{
		$this->engine = new $this->engineMaps[$type];
		$this->fileName = $this->getFileName();
		$this->_init();
	}

	public function getFileName()
	{
		return $this->fileName == null ? date('Y-m-d h.i.s') : $this->fileName;
	}

	public function _init(){}


	public function getTitle()
	{
		return $this->title;
	}

	public function setTitle()
	{
		$title = $this->getTitle();
		$this->titleCols = count($title);
		$this->engine->setContent($title);
		return $this;
	}

	public function fieldDispose($field,$value){
		return $field;
	}

	// 内容过滤 摘掉无必要属性 以及 属性值再处理
	public function contentFilter($content)
	{
		if (empty($this->fields)) {
			return $content;	
		}
		$tmp = array_flip($this->fields);
		foreach ($tmp as $k => $val) 
		{
			$tmp[$k] = isset($content[$k]) ? $this->fieldDispose($k,$content[$k]) : '';
		}
		return $tmp;
	}


	public function getContents()
	{
		return $this->contents;
	}

	public function setContents()
	{	
		$contents = $this->getContents();

		if (!is_array($contents)) 
		{
			throw new \Exception("Excel Error : 内容列表不能为非数组");
		}
		foreach ($contents as $val) {
			// 保证字段相符
			$val = $this->contentFilter($val);
			// 与title列数同步
			$val = array_slice($val,0,$this->titleCols);
			$this->engine->setContent($val);
		}
		return $this;
	}

	public function setHeader()
	{
		ob_end_clean();
		header("Pragma: public");
    	header("Expires: 0");
    	header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    	header("Content-Type:application/force-download");
    	header("Content-Type:application/vnd.ms-execl");
    	header("Content-Type:application/octet-stream");
    	header("Content-Type:application/download");

    	//多浏览器下兼容中文标题
	    $timestamp = time();
	    $encoded_filename = urlencode($this->fileName);
	    $ua = $_SERVER["HTTP_USER_AGENT"];
	    if (preg_match("/MSIE/", $ua)) {
	        header('Content-Disposition: attachment; filename="' . $encoded_filename . '.xls"');
	    } else if (preg_match("/Firefox/", $ua)) {
	        header('Content-Disposition: attachment; filename*="utf8\'\'' .$this->fileName. '.xls"');
	    } else {
	        header('Content-Disposition: attachment; filename="' .$this->fileName. '.xls"');
	    }
	    header("Content-Transfer-Encoding:binary");
	    return $this;
	}

	public function download()
	{
		$this->setHeader();
		$this->engine->create('php://output');
	}












}