<?php
/**
 * @author lukez 
 */
namespace luzhi\remote;

class Git
{		
	/**
	 * git工作目录
	 * @var null
	 */
	public $workPath = null;

	/**
	 * git命令
	 * @var string
	 */
	public $bin = 'git';


	public $error = null;

	public $output = null;


	public function __construct($config)
	{
		$this->bin = isset($config['bin']) ? $config['bin'] : $this->bin;

		if (!isset($config['workPath'])) {
			throw new Exception("Git error: Param workPath is required", 1);
		}
		$this->workPath = $config['workPath'];	
	}


	/**
	 * 执行命令 底层 
	 * 成功输出会赋值$this->output 错误会赋值$this->error
	 * @param  string $cmd 
	 * @return bool
	 */
	private function execute($cmd)
	{	
		$descriptorspec = [
			1 => ['pipe', 'w'],
			2 => ['pipe', 'w'],
		];
		$pipes = [];

		$process = proc_open($cmd, $descriptorspec, $pipes, $this->workPath,null);

		if (is_resource($process)) {

			$this->output = stream_get_contents($pipes[1]);
			$this->error = stream_get_contents($pipes[2]);

			foreach ($pipes as $pipe) {
				fclose($pipe);
			}

			$status = trim(proc_close($process));

			if (!$status){
				return true;
			}	
		}
		return false;
	}

	/**
	 * 执行命令 内部调用
	 * @param  string $cmd 
	 * @return message | false
	 */
	public function run($cmd)
	{
		return $this->execute($this->bin.' '.$cmd);
	}


	public function status()
	{
		return $this->run('status');
	}

	public function init()
	{
		return $this->run('init');
	}

	public function cloneFrom($url)
	{
		return $this->run('clone '.$url);
	}

	public function add($path)
	{
		return $this->run('add '.$path);
	}

	public function commit($message,$all = true)
	{	
		$mode = $all ? '-a' : '-v';
		return $this->run('commit '.$mode.' -m '.escapeshellarg($message));
	}

	public function addRemote($url,$name)
	{
		return $this->run("git remote add $name $url");
	}

	public function pull($remote,$branch = 'master')
	{
		return $this->run("pull $remote $branch");
	}

	public function push($remote,$branch = 'master')
	{
		return $this->run("push $remote $branch");
	}


}