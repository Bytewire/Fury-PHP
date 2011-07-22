<?php	
	
	if(!function_exists('profilelink')){
		function profilelink($base=false,$id,$username=false){
			if(!$base){
				$FURY =& get_instance();
				$base = $FURY->core->get_config_item('base_url');
			}
			if(!$username || $username==''){
				$FURY =& get_instance();
				$user = $FURY->db->getuser($id,"username");
				$username = $user['username'];
			}
			
			return anchor($base.'profile/player/'.$id,$username,array("class"=>"bold no_decor profile_link"));
		}
	}
	
	if(!function_exists('crewLink')){
		function crewlink($base=false,$id,$crewname=false){
			if(!$base){
				$FURY =& get_instance();
				$base = $FURY->core->get_config_item('base_url');
			}	
			if(!$crewname || $crewname==''){
				$FURY =& get_instance();
				$crewname = $FURY->db->query("SELECT crewname FROM crews WHERE id = '$id'")->get();
			}

			return anchor($base.'profile/crew/'.$id,$crewname,array("class"=>"bold no_decor"));
		}
	}
	
	if(!function_exists('avatar')){
		function avatar($avatar){
		
			$no_avatar = false;
			$FURY =& get_instance();
			$config_vars = $FURY->core->get_config_item_array(array(
				"base_url",
				"default_theme"
			));
			
			if(isset($config_vars['default_theme']) && $config_vars['default_theme']!=''):
				$FURY->config->load($config_vars['default_theme'],TRUE);
				$theme_vars = $FURY->core->get_config_item_array(array(
					"blank_avatar"
				),$config_vars['default_theme']);
			endif;
			
			$base = $config_vars['base_url'];
			
			// Choose the avatar to show.
			if($avatar){				
				
				if(!file_exists(ASSETS_PATH.'images'.DS.'avatars'.$avatar)){
					$no_avatar = true;	
				}
				
			}else{
				$no_avatar = true;	
			}
			
			if($no_avatar===TRUE){
				$path = $base.'assets'.DS.'images'.DS.$theme_vars['blank_avatar'];
			}
			
			
			$div = '<img src="'.$path.'">';
			
			return $div;
		}
	}