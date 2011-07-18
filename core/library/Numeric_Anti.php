<?php

	// =========== 
	// ! Numeric anti is a self built anti script which will serve the sole purpose of serving up anti scripts.  
	// =========== 
	
	
	class FURY_Numeric_Anti{
	
		var $base_url = '';
		var $numeric_anti = '';
		
		var $image;
		var $image_height = 150;
		var $image_width = 150;
		
		function FURY_Numeric_Anti(){
			
			$this->FURY =& get_instance();

	        // Initialize session or attach to existing
	        if ( session_id() == '' ) { // no session has been started yet, which is needed for validation
	            if (trim($this->session_name) != '') {
	                session_name($this->session_name); // set session name if provided
	            }
	            session_start();
	        }

			$this->FURY->load->library('Logging');			
			
			$this->FURY->config->load('antiscript',TRUE);
			
			$this->base_url = $this->FURY->core->get_config_item('base_url');
			
			$params = $this->FURY->core->get_config_item('antiscript');
			
			$this->initialize($params);		
			
			
							
			
		}
	
		
		/**
		 * Initialize Preferences
		 *
		 * @access	public
		 * @param	array	initialization parameters
		 * @return	void
		 */	
	    function initialize($params)
		{	
	        $this->_clear();
			if (count($params) > 0)
	        {
	            foreach ($params as $key => $value)
	            {	
	                if (isset($this->$key))
	                {
	                    $this->$key = $value;
	                }
	            }
	        }
	        
		}
		
		/**
		*
		* load up the html for the numeric anti script
		*/
		
		function number_get_html(){
			
			/* first of all create a code */
			
			$this->_createCode();

			$this->image = imagecreatetruecolor($this->image_width, $this->image_height);
			
			imagettftext($this->tmpimg, $font_size, 0, $x, $y, $this->gdtextcolor, $this->ttf_file, $this->code);
		
		}
	
		
	    /**
	     * Create a code and save to the session
	     *
	     * @access private
	     * @since 1.0.1
	     *
	     */
	    function _createCode()
	    {
	        $this->code = false;
	        			
			$numbers = array();
			for($i=0;$i<$this->numeric_anti['length'];$i++):
				$numbers[] = mt_rand(1,9);
			endfor;
			
			$this->code = $numbers;
			
			/* save to session */
			$this->_saveCode();	
	    }		
		
		
		
		/**
		*
		* save the code to the session
		*/
		
		function _saveCode(){
				
			if($this->code):
				$this->FURY->session->_set("numeric_anti",implode("",$this->code));
			endif;
			
		}
	
		/**
		*
		* _clear all previously set class elements to allow for chaining. 
		*/
		
		function _clear(){

			$this->anti_type = '';
			$this->anti_failed_attempts = 0;
			$this->anti_jail_time = 0;
			$this->recaptcha = '';
	
		}			
	
	}