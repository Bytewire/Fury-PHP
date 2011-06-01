<?php	
	
	if(!function_exists('profilelink')){
		function profilelink($base=false,$id,$username){
			if(!$base){
				$FURY =& get_instance();
				$base = $FURY->core->get_config_item('base_url');
			}
			return anchor($base.'profile/player/'.$id,$username,array("class"=>"bold no_decor"));
		}
	}
	
	if(!function_exists('avatar')){
		function avatar($avatar){
		
			$no_avatar = false;
		
			$FURY =& get_instance();
			$base = $FURY->core->get_config_item('base_url');
			$div = '<div class="profilePicTable">';
			
			// Choose the avatar to show.
			if($avatar){	
				$path = $FURY->core->get_config_item('relative_path').'assets/images/avatars/'.$avatar; 
				if(!file_exists($path)){
					$no_avatar = true;	
				}
			}else{
				$no_avatar = true;	
			}
			
			if($no_avatar){
				$path = $base.$FURY->core->get_config_item('blank_profile');
			}
			
			
			$div.= '<img src="'.$path.'">';
			$div.= '</div>';
			return $div;
		}
	}