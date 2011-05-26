<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');

	// =========== 
	// ! Class To literally throw errors at users when they come through.
	
	// =========== 
	
	class FURY_Exceptions{
	
		function FURY_Exceptions(){
		 	$this->FURY =& get_instance();
			
		}
		
		function show_error($title,$text,$type,$code){
			
			$this->FURY->load->view('error');
		
		}
		
		function show_404(){
		
			echo '<h1>You reached a 404! Doh!</h1>';
		
		}
	
	}