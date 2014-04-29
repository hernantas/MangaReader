<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
class SF_query
{
	private $con = NULL;
	private $pointer = 0;
	public $length = 0;
	private $data = array();
	
	function __construct($con, $sql)
	{
		load_class("log")->write("Do SQL: ".$sql);
		$this->con = $con;
		$result = mysqli_query($this->con, $sql);
		
		if ($result === FALSE) 
			load_class("log")->error("Syntax sql error: ".$sql);
		
		$this->length = @mysqli_num_rows($result);
		
		if ($this->length > 0)
		{
			$i = 0;
			while ($data = mysqli_fetch_array($result)) 
			{
				$this->data[$i] = $data;
				$i++;
			}	
		}
		
		@mysqli_free_result($result);
		load_class("log")->write("SQL succes");
	}
	
	public function seek($num)
	{
		if ($num < $this->length)
			return $this->data[$num];
		else
			return FALSE;
	}
	
	public function is_last()
	{
		if ($this->pointer == $this->length)
			return TRUE;
		else
			return FALSE;
	}
	
	public function restart()
	{
		$this->pointer = 0;
	}
	
	public function get_data()
	{
		return $this->data;
	}
	
	public function get_next()
	{
		$data = $this->seek($this->pointer);
		$this->pointer++;
		return $data;
	}
}
?>