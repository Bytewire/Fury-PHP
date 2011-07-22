<?php
   /**
    *
    * Allow models to use other models
    *
    * This is a substitute for the inability to load models
    * inside of other models in CodeIgniter.  Call it like
    * this:
    *
    * $salaries = model_load_model('salary');
    * ...
    * $salary = $salaries->get_salary($employee_id);
    *
    * @param string $model_name The name of the model that is to be loaded
    *
    * @return object The requested model object
    *
    */
        
   function model_load_model($model_name)
   {
      $FURY =& get_instance();
      $FURY->load->model($model_name);
      return $FURY->$model_name;
   }
   
   
   /**
   *
   * Check to see if the current uri is the correct one 
   */
   
   function is_active_page($value){
   		$FURY =& get_instance();
   		
   		$string_to_match = explode(" ",$value);
   		$string_to_match = strtolower($string_to_match[0]);
   		
   		$FURY->uri->_fetch_uri_string();
   		$current_root = $FURY->uri->segment(1);
   		   		
   		if($current_root == $string_to_match):
   			
   			return true;
   		
   		endif;
   		
   		return false;
   }
   
   /**
   *
   * Allow all of the websites to take advantage of the shit now. 
   */
   
   function get_template_part($part,$array){
   	
		$FURY =& get_instance();
		$theme = $FURY->core->get_config_item('default_theme');
		$fallback_theme = $FURY->core->get_config_item('fallback_theme');
		
		
		/* set a base path */
		
		$base_path = ROOT.APPPATH.DS.'view'.DS.'themes'.DS;
		
		$loaded = false;
		
		if($theme):
		
			/* attempt to use the themes part. */
			if(file_exists($base_path.$theme.DS.$part.EXT)):
				
				$loaded = true;
				
				extract($array);
				include_once($base_path.$theme.DS.$part.EXT);
				
			endif;
		
		endif;
		
		if(!$loaded):
			
			if($fallback_theme):
			
				/* attempt to fallback to the default theme */
				if(file_exists($base_path.$fallback_theme.DS.$part.EXT)):
				
					extract($array);
					include_once($base_path.$fallback_theme.DS.$part.EXT);					
				
				endif;
			
			endif;
		
		endif;
		
   }
   
   
   /**
   *
   * get_image - will default to looking inside the 
   * theme folder unless otherwise specified. 
   */
   
   function get_image($image,$site_wide=false){
   
   		$FURY =& get_instance();
		$config_vars = $FURY->core->get_config_item_array(array("default_theme","assets_url"));
		
		$loaded = false;
		 
		if(!$site_wide):
		
			$base_path = ASSETS_PATH.'themes'.DS;
					
			if(isset($config_vars['default_theme'])):
			
				if(file_exists($base_path.$config_vars['default_theme'].DS.'images'.DS.$image)):
					
					$loaded = true;
					
					return '<img src="'.$config_vars['assets_url'].'themes'.DS.$config_vars['default_theme'].DS.'images'.DS.$image.'">';
					
				endif;
				
			endif;
		
		endif;
		
		if(!$loaded || $site_wide===TRUE):
		
			if(file_exists(ASSETS_PATH.'images'.DS.$image)):
				return '<img src="'.$config_vars['assets_url'].DS.'images'.DS.$image.'">';
			endif;
			
		endif;	
   	
   }
