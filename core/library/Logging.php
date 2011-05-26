<?php

	
	if ( ! defined('ROOT')) exit('No direct script access allowed');

	// =========== 
	// ! Class To literally through errors at users as to why things didn't work as expected!   
	// =========== 
	
	class Fury_Logging{
	
	private $log_files = array();
	private $error_db;
	private $user_logs_db;
	private $event_db;
	
		function Fury_Logging(){
		
			// lets do some stuff with this shit
			
			$this->core =& load_class('Core');
			$this->FURY =& get_instance();
			$this->FURY->load->library('db');
			
			foreach($this->core->get_config_item('logging') as $k=>$v):
				
				$this->log_files[$k] = $v;
				
			endforeach;
			
			$this->error_db = $this->core->get_config_item('error_db','logging');
			$this->user_logs_db = $this->core->get_config_item('user_logs_db','logging');
			$this->event_db = $this->core->get_config_item('event_db','logging');
		
		}
		
		function log_this($txt,$userid){
				
			if(!$this->log_files['main_log']){
				$this->core =& load_class('Core');
				foreach($this->core->get_config_item('logging') as $k=>$v):
					$this->log_files[$k] = $v;
				endforeach;				
			}
			
			// Record the log into a new file.
			
			$txt_append = $this->_append($txt,$userid);
			
			error_log($txt_append,3,$this->log_files['main_log']);
			
			$this->log_to_db(array(
				"ip"	=>	$ip,
				"time" => time(),
				"userid" => $userid,
				"txt"	=> $txt,
				"uri"	=>	$http_host
			),"error");
			
		}
		
		function user_log($txt,$userid){
			
			if(!$this->log_files['user_log']){
				$this->core =& load_class('Core');
				foreach($this->core->get_config_item('logging') as $k=>$v):
					$this->log_files[$k] = $v;
				endforeach;				
			}
			
			// Record the log into a new file.
			
			$date = date('d.m.Y h:i:s',time()); 
			$ip = $_SERVER['REMOTE_ADDR'];
			$http_host = $_SERVER['REQUEST_URI'];
						
			$txt_append = $this->_append($txt,$userid);
			
			error_log($txt_append,3,$this->log_files['user_log']);
			
			$this->log_to_db(array(
				"ip"	=>	$ip,
				"time" => time(),
				"userid" => $userid,
				"txt"	=> $txt,
				"uri"	=>	$http_host
			),"user");			
							
		}
		
		function event_log($txt,$userid){
		
			if(!$this->log_files['event_log']){
				$this->core =& load_class('Core');
				foreach($this->core->get_config_item('logging') as $k=>$v):
					$this->log_files[$k] = $v;
				endforeach;				
			}
			
			// Record the log into a new file.
			
			$txt_append = $this->_append($txt,$userid);
			
			error_log($txt_append,3,$this->log_files['event_log']);
			
			$this->log_to_db(array(
				"ip"	=>	$ip,
				"time" => time(),
				"userid" => $userid,
				"txt"	=> $txt,
				"uri"	=>	$http_host
			),"event");
					
		}
		
		function log_to_db($params,$type){
		
			switch($type){
				case "event": 
							if($this->event_db){
								if(count($params)>=3){
									$this->FURY->db->insert($this->event_db,$params);
								}
							}
							break;
				case "user":
							if($this->error_db){
								if(count($params)>=3){
									$this->FURY->db->insert($this->user_logs_db,$params);
								}
							}
							break;
				case "error":
							if($this->error_db){
								if(count($params)>=5){
									$this->FURY->db->insert_delayed($this->error_db,$params);
								}
							}
							break;
			}

		}
		
		function _append($txt,$userid){
		
			$date = date('d.m.Y h:i:s',time()); 
			$ip = $_SERVER['REMOTE_ADDR'];
			$http_host = $_SERVER['REQUEST_URI'];
			
			$txt = $date.' | '.$ip.' | userid: '.$userid.' | '.$txt.' | uri: '.$http_host."\n";
			
			return $txt;

		}
	
	}