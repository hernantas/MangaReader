<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");

class SF_Router
{
	// Use the folder when url is http://localhost/folder/index.php
	public $folder = "folder";
	
	// WIP
	private $route = array();
	
	// Parameter will be formated depends on url
	// example url format: http://mywebsite.com/controller_class/function/
	// parameter1/parameter2/parameter3/etc
	// example 2 url format: http://mywebsite.com/controller_class/parameter1/
	// parameter2/parameter3/etc
	// example 3 url format: http://mywebsite.com/method_name/parameter1/
	// parameter2/parameter3/etc
	// In this system, it use format 2 and the default called method is index()
	
	private $parameter;
	
	public function host_url()
	{
		$s = $_SERVER;
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
	    $sp = strtolower($s['SERVER_PROTOCOL']);
	    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	    $port = $s['SERVER_PORT'];
	    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? 
	    			'' : 
	    			':'.$port;
	    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? 
	    			$s['HTTP_X_FORWARDED_HOST'] : 
	    			isset($s['HTTP_HOST']) ? 
	    				$s['HTTP_HOST'] : 
	    				$s['SERVER_NAME'];
	    return $protocol . '://' . $host . $port;
	}
	public function home_url()
	{
		return $this->host_url().'/'.$this->folder;
	}
	public function full_url()
	{
		$s = $_SERVER;
		$ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on') ? true:false;
	    $sp = strtolower($s['SERVER_PROTOCOL']);
	    $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
	    $port = $s['SERVER_PORT'];
	    $port = ((!$ssl && $port=='80') || ($ssl && $port=='443')) ? 
	    			'' : 
	    			':'.$port;
	    $host = isset($s['HTTP_X_FORWARDED_HOST']) ? 
		    		$s['HTTP_X_FORWARDED_HOST'] : 
		    		isset($s['HTTP_HOST']) ? 
			    		$s['HTTP_HOST'] : 
			    		$s['SERVER_NAME'];
	    return $protocol . '://' . $host . $port . $s['REQUEST_URI'];
	}
	// WIP
	public function add_route($key, $val)
	{
		$this->route[$key] = $val;
	}
	public function extract_url($url)
	{
		$trim = $this->host_url().'/'.$this->folder;
		$param = "";
		if (substr($url, 0, strlen($trim)) == $trim)
			$param = substr($url, strlen($trim));
		$param = trim($param, "/");
		$this->parameter = explode("/", $param);
		
		$loader = &load_class("loader");
		// Handle if no url param
		if (!$this->parameter[0]) $this->parameter[0] = "home";
		$param_max = count($this->parameter)-1;
		
		if (strpos($this->parameter[$param_max],'?') !== false) {
			$str = explode("?", $this->parameter[$param_max]);
			$this->parameter[$param_max] = $str[0];
		}
		
		$loader->controller($this->parameter[0])->_run();
	}
	
	public function _get_param()
	{
		return $this->parameter;
	}
}
?>