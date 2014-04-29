<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");

/**
 * Snow Flake Benchmark Class
 * 
 * Use to do a benchmarking in php script like mark two point in the code and
 * calculate time difference between them. Memory used to some point can be used
 * as well
 *
 * @package Core
 */
class SF_Benchmark
{
	/**
	 * 
	 */
	private $mark = array();
	
	
	public function start($mark_name='')
	{
		$this->mark[$mark_name]  = microtime(true);
	}
	
	public function end($mark_name='')
	{
		$endTime = microtime(true);
		$result = $endTime - $this->mark[$mark_name];
		load_class("log")->write("Script took ".$result."s to complete");
		return $result;
	}
	
	public function get_memory()
	{
		return memory_get_peak_usage();
	}
	public function get_real_memory()
	{
		return memory_get_peak_usage(true);
	}
	public function get_peak_memory()
	{
		return '{memory_usage}';
	}
}

?>