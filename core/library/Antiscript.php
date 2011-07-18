<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Antiscript{
		
		var $params = array();
		private $recaptcha = '';
		
		function FURY_Antiscript(){
			
			$this->FURY =& get_instance();

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
		*  
		*/
		
		function load_anti($action,$param=false){
		
			if(!$param):
				$file = $this->params['anti_type'];
			elseif($param):
				$file = $param;
			endif;
			
			$name = $file;
			
			$this->FURY->load->library($name);
			
			if($name=='recaptcha'):
			
				return $this->FURY->$name->$action();
			
			elseif($name=='securimage'):
			
				//Change some settings
				$this->FURY->securimage->image_width = 250;
				$this->FURY->securimage->image_height = 250;
				$this->FURY->securimage->perturbation = 0.9;
				$this->FURY->securimage->code_length = 1;
				$this->FURY->securimage->image_bg_color = new Securimage_Color("#d4b689");
				$this->FURY->securimage->use_transparent_text = true;
				$this->FURY->securimage->charset = '23456789';
				$this->FURY->securimage->text_transparency_percentage = 55; // 100 = completely transparent
				$this->FURY->securimage->num_lines = 25;
				$this->FURY->securimage->image_signature = '';
				$this->FURY->securimage->text_color = new Securimage_Color("#ff4f00");
				$this->FURY->securimage->line_color = new Securimage_Color("#552206");
				
				return $this->FURY->securimage->show(''); // alternate use:  $img->show('/path/to/background_image.jpg');
			
			
			endif;
		
		}
		
		
		
		/**
		*
		* if anti needs to be shown, show it. 
		*/
		
		function show_anti($id){
			
			$ch_timers = $this->FURY->db->query("SELECT * FROM character_timers WHERE userid='$id'")->as_assoc();
			if($ch_timers['anti_script_show']<time()):
				
				$this->FURY->uri->_fetch_uri_string();
				$uri_string = $this->FURY->uri->uri_string();
			
				$this->FURY->db->query("UPDATE character_misc SET anti_referer='{$uri_string}' WHERE userid='{$id}'");
				header("Location: ".$this->base_url."antiscript");exit;
			endif;
			
			return false;
			
		}
		
		
		/**
		*
		* check anti 
		*/
		
		function check_anti($action,$param=false){

			if(!$param):
				$file = $this->anti_type;
			elseif($param):
				$file = $param;
			endif;
			
			$name = $file;
						
			$this->FURY->load->library($name);
			return $this->FURY->$name->$action();		
		
		}
		
		/**
		*
		* _clear all previously set class elements to allow for chaining. 
		*/
		
		function _clear(){

			$this->anti_type = '';
			$this->anti_failed_attempts = 0;
			$this->anti_jail_time = 0;
			$this->re_captcha = '';
	
		}
		
		
	
	}