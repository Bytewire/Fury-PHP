<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');	

	// =========== 
	// ! This is a file to help validate and fetch inputs used in fury. 
	// It will contain a whole bunch of stuff around inputs
	// =========== 
	
	class FURY_Input{
	
		function FURY_Input(){
			
		}
		
		// =========== 
		// ! Return something from the get array   
		// =========== 
		
		function get($index = '', $xss_clean = FALSE){
			return $this->_fetch_from_array($_GET, $index, $xss_clean);
		}		
	
		// =========== 
		// ! Return something from the post array   
		// =========== 
		
		function post($index = '', $xss_clean = FALSE){
			return $this->_fetch_from_array($_POST, $index, $xss_clean);
		}
		
		// =========== 
		// ! Get from either   
		// =========== 
		
		function get_post($index = '', $xss_clean = FALSE){
			if ( ! isset($_POST[$index]) ){
				return $this->get($index, $xss_clean);
			}else{
				return $this->post($index, $xss_clean);
			}
		}
		
		// =========== 
		// ! Return all of the post or get array.    
		// =========== 		
		
		function ret_array($type){
			switch($type){
				case "post": return $_POST;break;
				case "get": return $_GET;break;
				case "session": return $_SESSION;break;
				case "cookie": return $_COOKIE;break;
			}
		}
		
		// =========== 
		// ! Fetch from the server array   
		// =========== 
		
		function server($index = '', $xss_clean = FALSE){
			return $this->_fetch_from_array($_SERVER, $index, $xss_clean);
		}	
		
		// =========== 
		// ! Fetch from the cookie array    
		// =========== 	
		
		function cookie($index = '', $xss_clean = FALSE){
			return $this->_fetch_from_array($_COOKIE, $index, $xss_clean);
		}		
		
		// =========== 
		// ! Fetch from an array function   
		// =========== 
		
		function _fetch_from_array(&$array, $index = '', $xss_clean = FALSE){
			if ( ! isset($array[$index])){
				return FALSE;
			}
	
			if ($xss_clean === TRUE){
				return $this->xss_clean($array[$index]);
			}
	
			return $array[$index];
		}
	
	
	}
	