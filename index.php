<?php
	
	// =========== 
	// ! Version   
	// =========== 
	
	define('FURY_VERSION',	'1.0.0');	
	
	// =========== 
	// ! Development environment globals   
	// ===========
	
	// Decide whether in development mode or not.
	
	// =========== 
	// ! Use the test variables to decide on config settings.
	// Create better more seamless approach to local and remote coding. 
	// Could also be changed to be anything you like but localhost works well for us here.  
	// =========== 
			
	# Set some defines and call our bootstrap
	if($_SERVER['HTTP_HOST']){
    switch($_SERVER['HTTP_HOST']){
        case "localhost:8888": 
            define('DEVELOPMENT_ENVIRONMENT' , true);
            define('EXT', '.php');
            define('DS', DIRECTORY_SEPARATOR);
            define('ROOT', dirname(dirname(__FILE__)).'/boardwalk/');
            define('SYS', 'core/');
            define('APPPATH','application');
            break;
        default: 
            define('DEVELOPMENT_ENVIRONMENT' , false);
            define('EXT', '.php');
            define('DS', DIRECTORY_SEPARATOR);
            define('ROOT', dirname(dirname(__FILE__)).'/http/');
            define('SYS', 'core/');
            define('APPPATH','application');
    }	
	}else{
        define('DEVELOPMENT_ENVIRONMENT' , true);
        define('EXT', '.php');
        define('DS', DIRECTORY_SEPARATOR);
        define('ROOT', dirname(dirname(__FILE__)).'/boardwalk/');
        define('SYS', 'core/');	
        define('APPPATH','application');
	}
	# True error reporting should be set here, to find problems right from the route.	
	
	if (DEVELOPMENT_ENVIRONMENT == TRUE) {
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
	} else {
		error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		//ini_set('display_errors','Off');
		ini_set('log_errors', 'On');
		ini_set('error_log', ROOT.DS.'tmp'.DS.'logs'.DS.'error.log');
	}		
	
	# Require the bootstrap file		 
	
	//require_once (ROOT . 'core' .DS. 'bootstrap.php');
	
