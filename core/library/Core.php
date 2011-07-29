<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');

	// =========== 
	// ! To avoid confusion this is literally a class to dev up and 
	// * retrieve config settings and return them as and how needed.   
	// =========== 
	
	class FURY_Core{
	
		function FURY_Core(){
		
			$this->config =& get_config();
		
		}
		
		// =========== 
		// ! Fetch a config item from the config file   
		// =========== 
		
		function get_config_item($item=array(),$index=''){
		
			
			if ($index == ''){	
				if ( ! isset($this->config[$item])){
					return FALSE;
				}
	
				$pref = $this->config[$item];
			}else{
				if ( ! isset($this->config[$index])){
					return FALSE;
				}
	
				if ( ! isset($this->config[$index][$item])){
					return FALSE;
				}
	
				$pref = $this->config[$index][$item];
			}	
				
			
			return $pref;
				
		}
		

		// =========== 
		// ! Fetch a config item array  
		// =========== 
		
		
		function get_config_item_array($items=array(),$overriding_index=false){
		
		
		
			if(is_array($items)){
				if(count($items)>0){
				
					$pref = array();
					
					foreach($items as $v){
					
						if($v==''){
							$pref[$v] = '';
						}else{
						
							if(is_array($v)){
																																					
								if(isset($v['index']) && $v['index']!=''){
																		
									$pref[$v['index']][$v['item']] = (isset($this->config[$v['index']][$v['item']])) ?  $this->config[$v['index']][$v['item']] : '';					
								
								}else{
																		
									$pref[$v] = (isset($this->config[$v])) ? $this->config[$v] : '';
									
								}
							
							}else{
								
								if(isset($overriding_index) && $overriding_index!=''):
								
									$pref[$v] = (isset($this->config[$overriding_index][$v])) ? $this->config[$overriding_index][$v] : '';
							
								else:
								
									$pref[$v] = (isset($this->config[$v])) ? $this->config[$v] : '';
								
								endif;
							
							}
						
						}
					
					}
				
				}
			}
		
			
			return $pref;
		
		}

		
		// =========== 
		// ! Return the site url based on defined set of params in the config   
		// =========== 
		
		function site_url($uri = ''){
		
			if (is_array($uri)){
				$uri = implode('/', $uri);
			}
	
			if ($uri == ''){
				return $this->slash_item('base_url').$this->get_config_item('index_page');
			}else{
				$suffix = ($this->get_config_item('url_suffix') == FALSE) ? '' : $this->get_config_item('url_suffix');
				return $this->slash_item('base_url').$this->slash_item('index_page').trim($uri, '/').$suffix; 
			}
		}
		
		// =========== 
		// ! Add a slash after the end of a parameter given   
		// =========== 
		
		function slash_item($item){
			if ( ! isset($this->config[$item])){
				return FALSE;
			}
	
			$pref = $this->config[$item];
	
			if ($pref != '' && substr($pref, -1) != '/'){	
				$pref .= '/';
			}
	
			return $pref;
		}		
				
	}