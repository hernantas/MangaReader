<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");

require_once (BASEPATH."library/query.php");

class SF_DB
{
	private $host;
	private $username;
	private $password;
	private $db_name;
	
	private $_con = NULL;
	
	function __construct()
	{
		$config = &load_class('config');
		$this->host = $config->get('host','db');
		$this->username = $config->get('username','db');
		$this->password = $config->get('password','db');
		$this->db_name = $config->get('db_name','db');
		
		$this->_con = mysqli_connect($this->host, 
									 $this->username,
									 $this->password);
		if (!$this->_con) 
			load_class("log")->error("Unable to connect to database server");
		
		$select = mysqli_select_db($this->_con, $this->db_name);
		if (!$select) 
			load_class("log")->error("Unable to select database, database 
														name:".$this->db_name);
	}
	
	public function query($sql)
	{
		return new SF_query($this->_con, $sql);
	}
}
?>