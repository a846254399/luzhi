<?php 
/**
 * @author lukez
 */
namespace luzhi\office\core;

/**
 * PHPExcelCore
 */
class PHPExcelCore implements IExcelCore{

	/**
	 * 依赖核心
	 * @var obj
	 */
	public $core;

	/**
	 * 偏移
	 * @var integer
	 */
	public $offset = 0;


	public $currentLine = 1;


	public function __construct()
	{
		$this->core = new \PHPExcel();
	}

	/**
	 * 获取excel中标题字母序列
	 * @param  int $num 
	 * @return array list
	 */
	protected function getTitleLetter($num)
	{	
		$letters = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');

		if ($num <= count($letters)) 
		{
			return array_slice($letters,0,$num);
		}



		// foreach ($letters as $val) {
		// 	$count++;




		// }


	}

	public function setContent($content)
	{
		if (!is_array($content)) 
		{
			throw new \Exception("Excel Error : 内容不能为非数组");
		}
		// 总列数
		$cols = count($content);
		$letters = $this->getTitleLetter($cols);

		// 循环中计数器
		$count = 0;
		foreach ($content as $k => $val) 
		{	
			// 自动宽度
			$this->core->getActiveSheet()->getColumnDimension($letters[$count])->setAutoSize(true);
			$this->core->getActiveSheet()->setCellValue( $letters[$count].$this->currentLine, $content[$k] );
			$count++;
		}

		$this->currentLine++;
	}


	public function initRules()
	{
		$this->core->setActiveSheetIndex($this->offset);
	}

	public function create($location)
	{
		$this->initRules();
		$objWriter = new \PHPExcel_Writer_Excel5($this->core);
		$objWriter->save($location);
	}








}