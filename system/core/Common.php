<?php if (!defined("BASEPATH")) exit("NO DIRECT SCRIPT ACCESS ALLOWED");

/**
 * This function for load the class in 'class name dot php' file
 *
 * @param Class name
 * @param Directory of file, either in app path or system path
 * @param Prefix of class, if class using prefix for example SF_Core
 * @return Class object
 */	
if (!function_exists("load_class"))
{
	function &load_class($class_name, $directory="core", $prefix = "SF_")
	{
		static $_classes = array();
		
		// If class exist then return it right away
		if (array_key_exists($class_name, $_classes)) 
			return $_classes[$class_name];
		
		$name = FALSE;
		
		// Search the file in application and system path
		foreach (array(BASEPATH, APPPATH) as $path)
		{
			if (file_exists($path.$directory.'/'.$class_name.".php"))
			{
				$name = $prefix.$class_name;
				
				if (class_exists($class_name) === FALSE)
					require ($path.$directory.'/'.$class_name.".php");
				
				break;
			}
		}
		
		if ($name === FALSE) 
			exit("Unable to locate spesific class: '".$class_name.".php'");
		
		// Index the loaded class
		is_loaded($class_name);
		
		$_classes[$class_name] = new $name();
		return $_classes[$class_name];
	}
}
/**
 * Keep track of all class loaded
 *
 * @param string : class name
 * @return array list of class name
 * 
 */
if (!function_exists("is_loaded"))
{
	function is_loaded($class_name='')
	{
		static $_is_loaded = array();
		
		if ($class_name == '')
			return $_is_loaded;
		
		$_is_loaded[strtolower($class_name)] = $class_name;
		return $_is_loaded;
	}
}
/**
 * utility function to help display if page is not found and not handled in
 * system code
 *
 * @return void
 */
if (!function_exists("not_found"))
{
	function not_found()
	{
		exit("<div style=\"background:#FAA;border:1px solid #F00;padding:10px 15px;\"><h1>404 not found</h1>Your requested page is not found. Please contact administrator of this website</div>");
	}
}
?>