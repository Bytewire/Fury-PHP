<?php	
	
	if(!function_exists('profilelink')){
		function profilelink($base,$id,$username){
			return anchor($base.'profile/player/'.$id,$username,array("class"=>"bold no_decor"));
		}
	}