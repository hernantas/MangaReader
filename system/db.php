<?php
	class DB {
		private $host;
		private $username;
		private $password;
		private $db_name;
		
		public $Connection;
		public $DB;
		
		function __construct($db_name, $host = 'localhost', $username = 'root', $password = '') {
			$this->host = $host;
			$this->username = $username;
			$this->password = $password;
			$this->db_name = $db_name;
			
			Debug::write("Trying connect to database");
			$this->Connection = mysql_connect($this->host, $this->username, $this->password);
			if (!$this->Connection) {
				Debug::write(mysql_error());
			}
			$this->DB = mysql_select_db($db_name, $this->Connection);
			if (!$this->DB) {
				Debug::write(mysql_error());
			}
			Debug::write("Successfully connect to database");
		}
		
		public function close() {
			mysql_close($this->Connection);	
		}
		
		public function Query($sql) {
			if (!$this->Connection) Debug::write("Mysql Connection has not been made");
			Debug::write("Run a Query: ".$sql);
			$query = mysql_query($sql, $this->Connection);
			if (!$query) {
				Debug::write(mysql_error() . "(Syntax: " . $sql . ")<br />");	
			} else
				Debug::write("Successfully run a query");
			return $query;
		}
		public function QueryNum($sql) {
			if (!$this->Connection) Debug::write("Mysql Connection has not been made");
			return mysql_num_rows($this->Query($sql));	
		}
	}
	
	// Default Database Declaration
	$db = new DB($CFG['DB_Name'],$CFG['DB_Host'],$CFG['DB_UserName'],$CFG['DB_Password']);
?>