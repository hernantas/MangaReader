<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");
	
class SF_Controller
{
	public $param;
	
	public function home()
	{
		
	}
	public function index()
	{
		$this->seek_method();
	}
	
	// This method is too tell the controller to call method based on the
	// url, example http://mywebsite.com/class_name/method_name/ rather
	// than calling index no matter method_name. method_name can't be index,
	// because the default method to be called is index
	public function seek_method()
	{
		if (!isset($this->param[1]))
		{
			$this->home();
		}
		elseif ($this->param[1] == 'index')
		{
			$data['view'] = '404';
			$this->loader->view('home',$data);
		} 
		elseif (method_exists($this, $this->param[1]))
		{
			$method = $this->param[1];
			$this->$method();
		}
		else
		{
			$data['view'] = '404';
			$this->loader->view('home',$data);
		}
	}
	
	public function seek_param($param_value)
	{
		for ($i=0;$i<count($this->param);$i++)
		{
			if ($this->param[$i] == $param_value)
				return $i;
		}
		return FALSE;
	}
	
	public function param_value($param_name, $default_return = FALSE)
	{
		for ($i=0;$i<count($this->param);$i++)
		{
			if ($this->param[$i] == $param_name)
				if (isset($this->param[$i+1]))
					return $this->param[$i+1];
		}
		return $default_return;
	}
	
	public function _run()
	{
		// Assign all loaded class as one big object
		$class = is_loaded();
		foreach($class as $key=>$val) 
		{
			$this->$key = &load_class($val);
		}
		
		$this->param = $this->router->_get_param();
		
		$this->index();
	}
	public function &load()
	{
		return $this->loader;
	}
}
?>