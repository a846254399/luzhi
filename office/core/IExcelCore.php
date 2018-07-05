<?php 
/**
 * @author lukez
 */
namespace luzhi\office\core;

/**
 * ExcelCore接口
 */
interface IExcelCore{

	public function setContent($contents);

	public function create($location);

}