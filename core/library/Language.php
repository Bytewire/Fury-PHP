<?php

	class FURY_Language{
		
		var $locale;
		
		function __construct(){	
			
			$this->core =& load_class("Core");
			$this->checkLanguage();
				
		}
		
		function checkLanguage(){
			
			if(isset($_COOKIE['locale'])){
				if(in_array($_COOKIE['locale'],$this->core->get_config_item('supported_languages'))){
					// if allowed language set it.
					$locale = $_COOKIE['locale'];
					$this->setPageDefaults($locale);
				}else{
					//revert to default
					$this->setDefault();
				}
			}else{
				$this->setDefault();
			}
			
		} 
				
		function setDefault(){
			
			$locale = $this->core->get_config_item('default_language');
			
			// Set a cookie to record this.
			setcookie("locale", $locale, time()+(86400*365) , "/boardwalk/", $this->core->get_config_item('local_url'));
			
			$this->setPageDefaults($locale);
			
		}
		
		function setLanguage($locale){
			
			// override old cookie and set new one.
			if(in_array($locale,$this->core->get_config_items('supported_languages'))){
				
				// Overwrite cookie.
				setcookie("locale", $locale, (time()+(86400*365)), "/boardwalk/", $this->core->get_config_item('local_url'));
				
				$this->setPageDefaults();
			}	
			
		}
		
		function setPageDefaults($locale){	
			putenv("LC_ALL=$locale");
			setlocale(LC_ALL, $locale);
			bindtextdomain("messages", $this->core->get_config_item('language_directory'));
			textdomain("messages");
		}
		
		function destroyCookie(){
			
		}
		
			
	}