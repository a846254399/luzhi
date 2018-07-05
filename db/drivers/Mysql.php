<?php
/**
 * TODO::代码属于残留 不符合本lib
 * Created by sublime.
 * User: lukez
 * Date: 16/7/25
 */


namespace lib\db\drivers;

use PDO;
use lib\Config;
use lib\String;
use lib\db\driver\PDODriver;

class Mysql{

	protected $db = '';

	protected $table = '';
	//主键
	protected $pk = '';
	protected $field = [];
	protected $selectField = '*';
	
	//预处理语句
	protected $option = [];
	//预处理值
	protected $execute = [];
	
	/**
	* 自动连接数据库
	* 连接成功,将数据库对象赋值给$db
	* 调用getTable获取表名 赋予$this->table
	* 调用parseField获取表信息，将全部列名赋予$this->field,将主键列名赋予$this->pk
	*/
	public function __construct( $classname ){
		$this->getDB();
		$this->getTable( $classname );
		$this->parseFields();
	}

	public function getDB(){
		$config = Config::get('mysql');
		$this->db = PDODriver::open( $config );
		$this->db->query('set names '.$config['charset']);
	}

	//利用调用类名得到数据库表名
	public function getTable( $class ){
		$className = String::getClassName( $class );
		$this->table = strtolower(substr($className,0,-5));
	}

	//分析数据表， 得到表的基本信息
	public function parseFields(){
		//选择表语句
		$sql = 'desc '.$this->table;
		$tableinfo = $this->db->query($sql);
		$data = $tableinfo->fetchAll(PDO::FETCH_ASSOC);
		//获取列名
		foreach ($data as $k => $v) {
			//获取主键列名
			if ($v['Key'] == 'PRI') {
				$this->pk = $v['Field'];
			}
			if ($this->pk != $v['Field']) {
				$this->field[$k] = $v['Field'];
			}		
		}
	}
	/**
	 * 自定义sql
	 * str $sql 预处理sql
	 * arr $value 预处理值
	 * 语句对象 or false
	 */
	public function mExecute($sql,$value){
		$so = $this->db->prepare($sql);
		if ($so->execute($value)) {
			return $so;
		}else{
			return false;
		}
	}
	/**
	 * 需要查询的列
	 * @param  $field 需要查询的列 支持字符串及数组
	 * @return 赋值查询字段 并返回本对象
	 */
	public function field($field){
		if (is_array($field)) {
			$str = '';
			foreach ($field as $value) {
				$str .= $value.',';
			}
			$str = trim($str,',');
		}else if(is_string($field)){
			$str = $field;
		}else{
			$str = '*';
		}
		$this->selectField = $str;
		return $this;
	}
	/**
	 * 添加table join条件
	 * @param  str $v sql语句 
	 * @return 赋值tablejoin 并返回处理过的本对象
	 */
	public function leftJoin($table,$where){
		if (!empty($v)) {
			$this->option[0] = ' '.$v;
			return $this;
		}
	}
	/**
	 * 添加where条件
	 * @param  str $v 预处理值 
	 * @param  str $k 预处理键 空则默认主键
	 * @param  str $ys 运算符空则默认= 
	 * @return 赋值where 并返回处理过的本对象
	 */
	public function where($v,$k='',$ys=''){
		$k = empty($k)? $this->pk : $k;
		$ys = empty($ys)? '=' : $ys;
		if (isset($v)) {
			$this->option[1] = ' where '.$k.' '.$ys.' '.'?';
			$this->execute[0] = $v;
			return $this;
		}
	}
	/**
	 * 添加group条件
	 * @param  str $v 预处理值 
	 * @return 赋值group 并返回处理过的本对象
	 */
	public function group($v){
		if (isset($v)) {
			$this->option[2] = ' group by '.$v;
			return $this;
		}
	}
	/**
	 * 添加having条件
	 * @param  str $v 预处理值 
	 * @param  str $k 预处理键 空则默认主键
	 * @param  str $ys 运算符 空则默认= 
	 * @return 赋值having 并返回处理过的本对象
	 */
	public function having($v,$k='',$ys=''){
		$k = empty($k)? $this->pk : $k;
		$ys = empty($ys)? '=' : $ys;
		if (isset($v)) {
			$this->option[3] = ' having '.$k.$ys.'?';
			$this->execute[1] = $v;
			return $this;
		}
	}
	/**
	 * 添加order条件
	 * @param  str $v 预处理值 
	 * @param  str $px 排序方式 
	 * @return 赋值order 并返回处理过的本对象
	 */
	public function order($v,$px='asc'){
		if (isset($v)) {
			$this->option[4] = ' order by '.$v.' '.$px;
			return $this;
		}
	}
	/**
	 * 添加limit条件
	 * @param  str $start limit开始预处理值 
	 * @param  str $stop limit结束预处理值 
	 * @return 赋值limit 并返回处理过的本对象
	 */
	public function limit($start,$stop){
		if (isset($start)&&isset($stop)){
			$this->option[5] = " limit $start,$stop";
			return $this;
		}
	}
	/**
	 * 查询单条数据 底层
	 * @param   str $sql  待查询的sql语句
	 * @return 一维数组,无结果或失败返回false 
	 */
	public function getRow($sql){
		if (!empty($this->execute)) {
			ksort($this->execute);
			$value = array_values($this->execute);
		}else{
			$value = [];
		}
		$so = $this->db->prepare($sql);
		if ($so->execute($value)) {
			$data = $so->fetch(PDO::FETCH_ASSOC);
			$this->option = [];
			$this->op = '*';
			return $data;
		}else{
			$this->option = [];
			$this->op = '*';
			return false;
		}
	}
	/**
	 * 查询多条数据 底层
	 * @param  str $sql  待查询的sql语句
	 * @return 二维数组,无结果或失败返回false 
	 */
	public function getAll($sql){
		if (!empty($this->execute)) {
			ksort($this->execute);
			$value = array_values($this->execute);
		}else{
			$value = [];
		}
		$so = $this->db->prepare($sql);
		if ($so->execute($value)) {
			$data = $so->fetchAll(PDO::FETCH_ASSOC);
			$this->option = [];
			$this->op = '*';
			return $data;
		}else{
			$this->option = [];
			$this->op = '*';
			return false;
		}
	}	
	/**
	* 查询单条数据单个结果 底层
	* @param str $sql 待查询的sql语句
	* @return str 成功,返回结果,无结果或失败返回false 
	*/
	public function getOne($sql){
		if (!empty($this->execute)) {
			ksort($this->execute);
			$value = array_values($this->execute);
		}else{
			$value = [];
		}
		$so = $this->db->prepare($sql);
		if ($so->execute($value)) {
			$result = $so->fetch()[0];
			$this->option = [];
			$this->op = '*';
			return $result;
		}else{
			$this->option = [];
			$this->op = '*';
			return false;
		}
	}
	/**
	 * 查询数据 sql语句拼接 根据参数调用方法
	 * @param str $why 查询方法
	 * @return $data 查询结果集数组
	 */
	public function select($why){
		$sql = "select $this->op from $this->table";
		if (!empty($this->option)) {
			ksort($this->option);
			foreach ($this->option as $key => $value) {
				$sql .= $value;
			}
		}
		if ($why == 'all') {
			return $this->getAll($sql);	
		}else if ($why == 'row') {
			return $this->getRow($sql);
		}else if ($why == 'one') {
			return $this->getOne($sql);	
		}	
	}
	/**
	* 插入数据操作 底层
	* @param str $sql 预处理添加语句
	* @param arr $data 插入的数据
	* @return 成功返回插入的主键,否则返回错误信息; 
	*/
	public function insert($sql,$value=[]){
		$so = $this->db->prepare($sql);
		if ($so->execute($value)) {
			return $this->db->lastInsertId();
		}else{
			return $this->db->errorInfo();
		}
	}
	/**
	* 插入数据操作 sql语句拼接 调用insert()方法  注意因为使用预处理，插入值不能为运算
	* @param arr $data 插入的数据
	* @return 成功返回插入的主键,否则返回错误信息; 
	*/
	public function add($data=[]){
		$key = '';
		$value = '';
		$values = [];
		foreach ($data as $k => $v) {
				$key .= $k.',';
				$value .= '?,';
				$values[] .= $v;
			}
		$sql = 'insert into '. $this->table .'('.rtrim($key,',').') values ('.rtrim($value,',').')';
		return $this->insert($sql,$values);
	}
	/**
	* 删除数据操作 底层 
	* @param str $sql 预处理where条件的键
	* @param arr $value 预处理where条件的值 
	* @return 成功返回影响的行数,否则返回错误信息; 
	*/
	public function delete($sql,$value=[]){
		$so = $this->db->prepare($sql);
		if ($so->execute($value)) {
			return $so->rowCount();
		}else{
			return $this->db->errorInfo();
		}
	}
	/**
	* 删除数据操作 sql语句拼接 调用delete()方法
	* @param  str $k 预处理where条件的键 空则默认主键
	* @param  str $v 预处理where条件的值 
	* @return 成功返回影响的行数,否则返回错误信息;
	*/
	public function del($v,$k=''){
		$k = empty($k)? $this->pk : $k;
		$sql = 'delete from '.$this->table.' where '.$k.' = ?';
		return $this->delete($sql,[$v]);
	}
	/**
	* 修改数据操作 底层
	* @param str $sql 预处理where条件的键
	* @param arr $value 预处理where条件的值 
	* @return 成功返回影响的行数,否则返回错误信息; 
	*/
	public function update($sql,$value=[]){
		$so = $this->db->prepare($sql);
		if ($so->execute($value)) {
			return $so->rowCount();
		}else{
			return $this->db->errorInfo();
		}
	}
	/**
	* 修改数据操作 sql语句拼接 调用update()方法 注意因为使用预处理，插入值不能为运算
	* @param  arr $data 修改的数据
	* @param  str $v 预处理where条件的值 
	* @param  str $k 预处理where条件的键 空则默认主键
	* @return 成功返回影响的行数,否则返回错误信息; 
	*/
	public function upd($data=[],$v,$k=''){
		$key = empty($k)? $this->pk : $k;
		$values = '';
		$arr = [];
		foreach ($data as $k => $z) {
				$values .= $k.'=?,';
				$arr[] .= $z;
			}
		$sql = "update $this->table set ".rtrim($values,',').' where '.$key.'=?';
		$arr[] = $v;
		return $this->update($sql,$arr);
	}


}
