<?php

	# This is the file which is responsible for routing out all of our requests.
	
	# Lets have our nice friendly config file do some work.
	
	require_once(ROOT . 'application' .DS . 'config' . DS . 'config' .EXT);	
	
	// At this point we want to be able to break the site.
	
	if($config['sitedown']['enabled']){
		if(file_exists($config['relative_path'].'sitedown.txt')){
			die("The site is currently experiencing some technical difficulties");
		}			
	}
	
	
/**
* Determines if the current version of PHP is greater then the supplied value
*
* Since there are a few places where we conditionally test for PHP > 5
* we'll set a static variable.
*
* @access	public
* @param	string
* @return	bool	TRUE if the current version is $version or higher
*/
	function is_php($version = '5.0.0')
	{
		static $_is_php;
		$version = (string)$version;

		if ( ! isset($_is_php[$version]))
		{
			$_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0) ? FALSE : TRUE;
		}

		return $_is_php[$version];
	}	
	
	/**
	* Exception Handler
	*
	* This is the custom exception handler that is declaired at the top
	* of Codeigniter.php.  The main reason we use this is to permit
	* PHP errors to be logged in our own log files since the user may
	* not have access to server logs. Since this function
	* effectively intercepts PHP errors, however, we also need
	* to display errors based on the current error_reporting level.
	* We do that with the use of a PHP error template.
	*
	* @access	private
	* @return	void
	*/
		function _exception_handler($severity, $message, $filepath, $line)
		{
		
			 // We don't bother with "strict" notices since they tend to fill up
			 // the log file with excess information that isn't normally very helpful.
			 // For example, if you are running PHP 5 and you use version 4 style
			 // class functions (without prefixes like "public", "private", etc.)
			 // you'll get notices telling you that these have been deprecated.
			if ($severity == E_STRICT)
			{
				return;
			}
	
			$_error =& load_class('Exceptions');
			$_thecore =& load_class('Core');
				
			// Should we display the error? We'll get the current error_reporting
			// level and add its bits with the severity bits to find out.
			if (($severity & error_reporting()) == $severity)
			{
				$_error->show_php_error($severity, $message, $filepath, $line);
			}
	
			// Should we log the error?  No?  We're done...
			if ($_thecore->get_config_item('log_threshold') == 0)
			{
				return;
			}
	
			$_error->log_exception($severity, $message, $filepath, $line);
		}
	
	/*
	 * ------------------------------------------------------
	 *  Define a custom error handler so we can log PHP errors
	 * ------------------------------------------------------
	 */
		set_error_handler('_exception_handler');
	
		if ( ! is_php('5.3'))
		{
			@set_magic_quotes_runtime(0); // Kill magic quotes
		}	
	
	
	// ------------------------------------------------------------------------

	/**
	 * Set HTTP Status Header
	 *
	 * @access	public
	 * @param	int		the status code
	 * @param	string
	 * @return	void
	 */
		function set_status_header($code = 200, $text = '')
		{
			$stati = array(
								200	=> 'OK',
								201	=> 'Created',
								202	=> 'Accepted',
								203	=> 'Non-Authoritative Information',
								204	=> 'No Content',
								205	=> 'Reset Content',
								206	=> 'Partial Content',
	
								300	=> 'Multiple Choices',
								301	=> 'Moved Permanently',
								302	=> 'Found',
								304	=> 'Not Modified',
								305	=> 'Use Proxy',
								307	=> 'Temporary Redirect',
	
								400	=> 'Bad Request',
								401	=> 'Unauthorized',
								403	=> 'Forbidden',
								404	=> 'Not Found',
								405	=> 'Method Not Allowed',
								406	=> 'Not Acceptable',
								407	=> 'Proxy Authentication Required',
								408	=> 'Request Timeout',
								409	=> 'Conflict',
								410	=> 'Gone',
								411	=> 'Length Required',
								412	=> 'Precondition Failed',
								413	=> 'Request Entity Too Large',
								414	=> 'Request-URI Too Long',
								415	=> 'Unsupported Media Type',
								416	=> 'Requested Range Not Satisfiable',
								417	=> 'Expectation Failed',
	
								500	=> 'Internal Server Error',
								501	=> 'Not Implemented',
								502	=> 'Bad Gateway',
								503	=> 'Service Unavailable',
								504	=> 'Gateway Timeout',
								505	=> 'HTTP Version Not Supported'
							);
	
			if ($code == '' OR ! is_numeric($code))
			{
				show_error('Status codes must be numeric', 500);
			}
	
			if (isset($stati[$code]) AND $text == '')
			{
				$text = $stati[$code];
			}
	
			if ($text == '')
			{
				show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
			}
	
			$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;
	
			if (substr(php_sapi_name(), 0, 3) == 'cgi')
			{
				header("Status: {$code} {$text}", TRUE);
			}
			elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
			{
				header($server_protocol." {$code} {$text}", TRUE, $code);
			}
			else
			{
				header("HTTP/1.1 {$code} {$text}", TRUE, $code);
			}
		}
	
	// --------------------------------------------------------------------


	// =========== 
	// ! Show some errors out of our framework   
	// =========== 
	
	function show_error($message, $status_code = 500, $heading = 'An Error Was Encountered')
	{
		$_error =& load_class('Exceptions');
		echo $_error->show_error($heading, $message, 'error_general', $status_code);
		exit;
	}

	
	// =========== 
	// ! Show 404 through exceptions controller   
	// =========== 
	
	function show_404($page = '',$log_error = TRUE){
		$_error =& load_class('Exceptions', 'core');
		$_error->show_404($page, $log_error);
		
		if($log_error):
		
		endif;
		
		exit;
	}	
	
	// =========== 
	// ! Fetch config file   
	// =========== 
	
	function &get_config(){
	
		global $config;
	
		static $main_conf;
		if(! isset($main_conf)){
		
			if ( ! isset($config) OR ! is_array($config)){
				exit('Your config file does not appear to be formatted correctly.');
			}
			
			$main_conf[0] = & $config;

		}
		return $main_conf[0];	
	}
	
	// =========== 
	// ! Class register
	// * This function acts as a singleton.  If the requested class does not
	// * exist it is instantiated and set to a static variable.  If it has
	// * previously been instantiated the variable is returned.  
	// =========== 

	function &load_class($class, $instantiate = TRUE){
		static $objects = array();
	
		// Does the class exist?  If so, we're done...
		if (isset($objects[$class])){
			return $objects[$class];
		}
	
		// Load the native file from the libraries core.

		if (file_exists(ROOT . 'core' . DS .'library'. DS . $class . EXT)){
			require(ROOT . 'core' . DS .'library'. DS . $class . EXT);
			$is_subclass = FALSE;
		}else{
			require(ROOT . 'core' . DS .'library' . DS . $class . EXT);
			$is_subclass = FALSE;
		}
	
		if ($instantiate == FALSE){
			$objects[$class] = TRUE;
			return $objects[$class];
		}
	
		$name = ($class != 'Controller') ? 'FURY_'.$class : $class;
	
		$objects[$class] =& instantiate_class(new $name());
		return $objects[$class];
	}
	
	// =========== 
	// ! Instantiate a class
	// * Returns a class object by reference use by load_class()   
	// =========== 

	function &instantiate_class(&$class_object){
		return $class_object;
	}	

	// =========== 
	// ! Load in some default class's
	// * Router (used for simply routing the requests)
	// * URI (used for helping with the URI division and returning certain segments of a given URI   
	// =========== 
		
	require(ROOT.'core'.DS.'fury5.php');

	$ROUTE =& load_class('Router');
	$URI =& load_class('URI');
	$CF =& load_class("Core");
	$OUT =& load_class("Output");
	//$THEMES =& load_class('Templating');
	
	
	# Now lets get the base controller in there.
	
	load_class('Controller', FALSE);
		
	// Load the local application controller
	// Note: The Router class automatically validates the controller path.  If this include fails it 
	// means that the default controller in the Routes.php file is not resolving to something valid.
		
	if (!file_exists( APP_PATH . 'controller' .DS. $ROUTE->fetch_directory().$ROUTE->fetch_class().EXT)){
		
		show_404("Controller was missing");
		
	}
	
	# Lets include the correct controller
		
	include(APP_PATH . 'controller' . DS .$ROUTE->fetch_directory().$ROUTE->fetch_class().EXT);
	
	# Now lets perform the requested actions.
	
	$class  = $ROUTE->fetch_class();
	$method = $ROUTE->fetch_method();

	
	if ( ! class_exists($class)
		OR $method == 'controller'
		OR strncmp($method, '_', 1) == 0
		OR in_array(strtolower($method), array_map('strtolower', get_class_methods('Controller')))
		)
	{	
		show_404("{$class}/{$method}");
	}
	
	$FURY = new $class();
	
	// Is there a "remap" function?
	if (method_exists($FURY, '_remap')){
		$FURY->_remap($method);
	}else{
		// is_callable() returns TRUE on some versions of PHP 5 for private and protected
		// methods, so we'll use this workaround for consistent behavior
		if ( ! in_array(strtolower($method), array_map('strtolower', get_class_methods($FURY))))
		{
			
			# Okay so we know the URI might not be valid but 
			# lets check if the index is expecting extra params
			show_404("{$class}/{$method}");
					
			
		}

		// Call the requested method.
		
		// Any URI segments present (besides the class/function) will be passed to the method for convenience
		call_user_func_array(array(&$FURY, $method), array_slice($URI->rsegments, 2));
		
	}
	
	# Throw the final output to the browser.
	
	$OUT->_display();
	
	