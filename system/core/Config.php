<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
class SF_Config
{
	private $_config = array();
	
	public function load_config($filename)
	{
		if (file_exists(APPPATH.'config/'.$filename.'.ini'))
		{
			$f = fopen(APPPATH.'config/'.$filename.'.ini', "r");
			$cur_set = 'none';
			while (($line = fgets($f)) !== false) {
				if ($line[0]=='#')
					continue;
				elseif ($line[0]=='[')
					$cur_set = substr($line, 1, strlen($line)-4);
				else {
					$conf = explode("=", $line);
					$this->_config[$cur_set][$conf[0]]=trim($conf[1]);
				}
			}
		}
		else
			load_class('log')->error("Can't load configuration: ".$file.".ini");
	}
	public function get($config_name, $group='none')
	{
		// If config name is empty, then return all value in the group
		if ($config_name=='')
			return $this->_config;
		elseif (array_key_exists($group, $this->_config))
		{
			if (array_key_exists($config_name, $this->_config[$group]))
			{
				return $this->_config[$group][$config_name];
			}
			else
				load_class('log')->error("No configuration in group '".$group."' with name '".$config_name."'");	
		}
		else {
			load_class('log')->error("No configuration group '".$group."'");	
		}
		return FALSE;
	}
}
?>