<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');

	// =========== 
	// ! Class To literally throw errors at users when they come through.
	
	// =========== 
	
	class FURY_Exceptions{
	
		var $ob_level;	
	
		function FURY_Exceptions(){
			$this->ob_level = ob_get_level();
		}

		
		/**
		 * 404 Page Not Found Handler
		 *
		 * @access	private
		 * @param	string
		 * @return	string
		 */
		function show_404($page = '', $log_error = TRUE)
		{
			$heading = "404 Page Not Found";
			$message = "The page you requested was not found.";
	
			// By default we log this, but allow a dev to skip it
			if ($log_error)
			{
				//log_message('error', '404 Page Not Found --> '.$page);
			}
	
			echo $this->show_error($heading, $message, 'error_404', 404);
			exit;
		}	
		
		// --------------------------------------------------------------------
	
		/**
		 * General Error Page
		 *
		 * This function takes an error message as input
		 * (either as a string or an array) and displays
		 * it using the specified template.
		 *
		 * @access	private
		 * @param	string	the heading
		 * @param	string	the message
		 * @param	string	the template name
		 * @return	string
		 */
		function show_error($heading, $message, $template = 'error_general', $status_code = 500)
		{
			set_status_header($status_code);
	
			$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';
	
			if (ob_get_level() > $this->ob_level + 1)
			{
				ob_end_flush();
			}
			ob_start();
			include(APPPATH.DS.'errors/'.$template.EXT);
			$buffer = ob_get_contents();
			ob_end_clean();
			return $buffer;
		}	
		

		/**
		 * Native PHP error handler
		 *
		 * @access	private
		 * @param	string	the error severity
		 * @param	string	the error string
		 * @param	string	the error filepath
		 * @param	string	the error line number
		 * @return	string
		 */
		function show_php_error($severity, $message, $filepath, $line)
		{
			$severity = ( ! isset($this->levels[$severity])) ? $severity : $this->levels[$severity];
	
			$filepath = str_replace("\\", "/", $filepath);
	
			// For safety reasons we do not show the full file path
			if (FALSE !== strpos($filepath, '/'))
			{
				$x = explode('/', $filepath);
				$filepath = $x[count($x)-2].'/'.end($x);
			}
	
			if (ob_get_level() > $this->ob_level + 1)
			{
				ob_end_flush();
			}
			ob_start();
			include(APPPATH.DS.'errors'.DS.'error_php'.EXT);
			$buffer = ob_get_contents();
			ob_end_clean();
			echo $buffer;
		}				
		

	}