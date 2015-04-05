<?php
	class Application {
		private $list = array();
		
		public function add($key, $file, $title=NULL) {
			$this->list[$key] = array("file"=>$file, "title"=>$title);
			Debug::write("Add an Application key:[" . $key . "] file:[".$file . "] title:[".$title."]");
		}
		
		public function exists($key) {
			if (array_key_exists($key, $this->list)) return true;
			return false;
		}
		
		public function get($key) {
			if (!$this->exists($key)) return false;
			return $this->list[$key];
		}
		public function getFile($key) {
			if (!$this->exists($key)) return false;
			return $this->list[$key]["file"];	
		}
		public function getTitle($key) {
			if (!$this->exists($key)) return false;
			return $this->list[$key]["title"];	
		}
	}
	
	$app = new Application();
?>