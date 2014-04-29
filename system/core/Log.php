<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");

class SF_Log
{	
	private $_fopen = NULL;
	
	public function enable($state = true)
	{
		if ($state)
		{
			$filename = date("m.d.y");
			$this->_fopen = fopen(APPPATH."logs/".$filename.".log", "a+");
			$this->write("================== Initalize ==================");
			//foreach ($_SERVER as $key => $value) 
			//{
			//	$this->write($key.': '.$value);
			//}
		}
		else
			if ($this->_fopen) fclose($this->_fopen);
	}
	public function write($message)
	{
		if ($this->_fopen) fwrite($this->_fopen, $message."\n");
	}
	public function error($message, $exit=true)
	{
		if ($this->_fopen) fwrite($this->_fopen, "Error: ".$message."\n");
		if ($exit) exit("<b>Error:</b> ".$message);
	}
}
	
?>