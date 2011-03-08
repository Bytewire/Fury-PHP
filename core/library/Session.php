<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');	

	// =========== 
	// ! The sole purpose of this script is to congregate the stuff we are going to be 
	// doing in and around sessions   
	// There will always per script be quite a bit of session based work.
	// This aims to make it easier.
	// =========== 

	class FURY_Session{
	
	const SESSION_STARTED 	= TRUE;
	const SESSION_ENDED 	= FALSE;
	var $displaydata_key 		= 'flash';
	
	private $sessionState = self::SESSION_ENDED;
	
		function __construct(){
			
			// Start the session
			$this->sessionState = @session_start();
			
					
			// Delete session displaydata marked old.
	  	 	$this->_displaydata_sweep();

			// Mark all new displaydata old (data will be deleted before next request)
		   	$this->_displaydata_mark();
		
		}
		
		// =========== 
		// ! Session write
		// Writes an array of keys and vals to the session array.   
		// =========== 
		
		function _sess_write($array){
			if(is_array($array)):
				foreach($array as $key=>$val):
					$_SESSION[$key] = $val;
				endforeach;
			endif;
		}
		
		// =========== 
		// ! Set a session part   
		// =========== 
		
		function _set($newdata = array(), $newval = ''){
			if (is_string($newdata)){
				$newdata = array($newdata => $newval);
			}
	
			if (count($newdata) > 0){
				foreach ($newdata as $key => $val){
					$_SESSION[$key] = $val;
				}
			}
	
		}
		
		// =========== 
		// ! Get a session part.   
		// =========== 
		
		function _get($var){
			return (!isset($_SESSION[$var])) ? FALSE : $_SESSION[$var];		
		}
		
		// =========== 
		// ! Get whole session array   
		// =========== 
		
		function _get_session_array(){
			return (!isset($_SESSION)) ? FALSE : $_SESSION;		
		}
		
		// =========== 
		// ! Check if something is set   
		// =========== 
		
	    function _isset( $name ){
	        return (isset($_SESSION[$name])) ? TRUE : FALSE;
	    }
	   	
	   	// =========== 
	   	// ! Unset a part of the session array
	   	// =========== 
	   
	    function _unset( $name ){	    
	        if(isset($_SESSION[$name])):
	        	unset( $_SESSION[$name] );
	        endif;
	    }	
	    
	    // =========== 
	    // ! Does exactly what it says on the tin and destroys a session   
	    // =========== 
	    
	    function _destroy(){
	    	if($this->sessionState == self::SESSION_STARTED){
				$this->sessionState = !session_destroy();
	            unset( $_SESSION );	    
            }
	    }
	    
	    // =========== 
	    // ! Set the display data, the point of doing this is to 
	    // keep session data for one page only.
	    // Can be passed an array or a key and value.
	    // =========== 
		function set_displaydata($newdata = array(), $newval = ''){
		
			if (is_string($newdata)){
				$newdata = array($newdata => $newval);
			}
	
			if (count($newdata) > 0){
				foreach ($newdata as $key => $val):
					$displaydata_key = $this->displaydata_key.':new:'.$key;
					$this->_set($displaydata_key, $val);
				endforeach;
			}
		}
		
		// =========== 
		// ! Make sure that we use this function to try and conserve the displaydata   
		// =========== 
		
		function keep_displaydata($key){
			// 'old' flashdata gets removed.  Here we mark all
			// flashdata as 'new' to preserve it from _flashdata_sweep()
			// Note the function will return FALSE if the $key
			// provided cannot be found
			$old_displaydata_key = $this->displaydata.':old:'.$key;
			$value = $this->_get($old_displaydata_key);
	
			$new_displaydata_key = $this->displaydata_key.':new:'.$key;
			$this->_set($new_displaydata_key, $value);
		}
		
		// =========== 
		// ! Use display data to fetch the displaydata by the key   
		// =========== 
		
		function displaydata($key){
			$displaydata_key = $this->displaydata_key.':old:'.$key;
			return $this->_get($displaydata_key);
		}
		
		// =========== 
		// ! Fetch all the display data   
		// =========== 
		
		function displaydata_all(){
			if($userdata = $this->_get_session_array()){
			$ret_array = array();
				foreach ($userdata as $key => $value):
					if (strpos($key, ':old:')){
						$ret_array[$key] = $value;
					}
				endforeach;	
			}	
			return (!isset($ret_array)) ? FALSE : $ret_array;
		}
		
		// =========== 
		// ! Mark session display_data for deletion on the next run
		// =========== 
		
		function _displaydata_mark(){
			$userdata = $this->_get_session_array();
			foreach ($userdata as $name => $value):
				$parts = explode(':new:', $name);
				if (is_array($parts) && count($parts) === 2){
					$new_name = $this->displaydata_key.':old:'.$parts[1];
					$this->_set($new_name, $value);
					$this->_unset($name);
				}
			endforeach;
		}
		
		// =========== 
		// ! Cleanse old display_data session data after being marked as old  
		// =========== 		


		function _displaydata_sweep(){
			$userdata = $this->_get_session_array();
						
			foreach ($userdata as $key => $value):
				if (strpos($key, ':old:')){
					$this->_unset($key);
				}
			endforeach;
		}
	
		
	
	}