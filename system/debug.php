<?php
	// Debugger Class
	
	class Debug {
		public static $enabled = false;
		public static $maxfilesize = 3145728; // in B
		public static $maxfilecount = 3; // Maximum file count
		
		static function init() {
			
		}
		
		static function write($msg) {
			if (self::$enabled) {
				$filename = "debug";
				$bJustAdded = false;
				
				if (file_exists(dirname(__FILE__)."/../log/".$filename.".0.txt")) {
					if (filesize(dirname(__FILE__)."/../log/".$filename.".0.txt") > self::$maxfilesize) {
						for ($log_num=(self::$maxfilecount)-1;$log_num>=0;$log_num--) {
							if (file_exists(dirname(__FILE__)."/../log/".$filename.".".$log_num.".txt")) {
								rename((dirname(__FILE__)."/../log/".$filename.".".$log_num.".txt"), (dirname(__FILE__)."/../log/".$filename.".".($log_num+1).".txt"));
								$bJustAdded = true;
							}
						}
					}
				}
				else
				{
					$bJustAdded = true;
				}
				
				
				$fp = fopen(addslashes(dirname(__FILE__))."/../log/debug.0.txt", "a+");
				
				if ($bJustAdded)
				{
					fwrite($fp, "[".date("d/M/Y - H:i:s")."] ==============================================================\n");
					fwrite($fp, "[".date("d/M/Y - H:i:s")."] ==================== Initizalize Debugger ====================\n");
					fwrite($fp, "[".date("d/M/Y - H:i:s")."] ==============================================================\n");
					/*
					self::write("PHP Version: ".phpversion());
					self::write("Loaded PHP ini: ".php_ini_loaded_file());
					self::write("PHP SAPI Name: ".php_sapi_name());
					*/
					foreach($_SERVER as $key => $value) {
						fwrite($fp, "[".date("d/M/Y - H:i:s")."] ".$key.": ".$value."\n");
					}
					fwrite($fp, "[".date("d/M/Y - H:i:s")."] ==============================================================\n");
					fwrite($fp, "[".date("d/M/Y - H:i:s")."] ==================== Debugger Ready ====================\n");
					fwrite($fp, "[".date("d/M/Y - H:i:s")."] ==============================================================\n\n");
				}
				
				fwrite($fp, "[".date("d/M/Y - H:i:s")."] ".$msg . "\n");
				fclose($fp);
			}
		}
		static function warning($msg) {
			self::write("Warning: ".$msg);
		}
		
		static function error($msg) {
			self::write("Error: ".$msg);
		}
		
		static function clear() {
			if (self::$enabled) {
				$fp = fopen(addslashes(dirname(__FILE__))."/../../log/debug.txt", "w");
				fclose($fp);
			}
		}
	}
?>