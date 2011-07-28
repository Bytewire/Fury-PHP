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
	private $mysql_db;
	
		function Fury_Logging(){
		
			// lets do some stuff with this shit
			
			$this->core =& load_class('Core');
			//$this->session =& load_class('session');
			$this->FURY =& get_instance();
			$this->FURY->load->library('db');
			
			foreach($this->core->get_config_item('logging') as $k=>$v):
				
				$this->log_files[$k] = $v;
				
			endforeach;
			
			$this->error_db = $this->core->get_config_item('error_db','logging');
			$this->user_logs_db = $this->core->get_config_item('user_logs_db','logging');
			$this->event_db = $this->core->get_config_item('event_db','logging');
			$this->mysql_db = $this->core->get_config_item('mysql_db','logging');
			$this->mail_db = $this->core->get_config_item('mail_db','logging');
			
		
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
						
			$txt_append = $this->_append($txt,$userid);
			
			error_log($txt_append,3,$this->log_files['user_log']);
			
			$this->log_to_db(array(
				"ip"	=>	$this->ip,
				"time" => time(),
				"userid" => $userid,
				"txt"	=> $txt,
				"uri"	=>	$this->http_host
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
				"ip"	=>	$this->ip,
				"time" => time(),
				"userid" => $userid,
				"txt"	=> $txt,
				"uri"	=>	$this->http_host
			),"event");
					
		}
		
		function mysql_log($query,$error){
		
			if(!$this->log_files['mysql_log']){
				$this->core =& load_class('Core');
				foreach($this->core->get_config_item('logging') as $k=>$v):
					$this->log_files[$k] = $v;
				endforeach;				
			}
			
			// Record the log into a new file.
			
			$userid = $this->FURY->session->_get('id');
			
			if(!isset($userid)){
				$userid = 0;
			}
			
			$txt = $error.' | '.$query;
			
			$txt_append = $this->_append($txt,$userid);
			
			error_log($txt_append,3,$this->log_files['mysql_log']);
			
			$this->log_to_db(array(
				"userid"	=>	$userid,
				"query_used" => $query,
				"mysql_error" => $error,
				"time"	=> time(),
				"uri"	=>	$this->http_host,
				"ip" => $this->ip
			),"mysql");
					
		}
		
		function mail_log($txt,$userid){
		
			if(!$this->log_files['mysql_log']){
				$this->core =& load_class('Core');
				foreach($this->core->get_config_item('logging') as $k=>$v):
					$this->log_files[$k] = $v;
				endforeach;				
			}
			
			// Record the log into a new file.
			
			$userid = $this->FURY->session->_get('id');
			
			if(!isset($userid)){
				$userid = 0;
			}
						
			$txt_append = $this->_append($txt,$userid);
			
			error_log($txt_append,3,$this->log_files['mail_log']);
			
			$this->log_to_db(array(
				"userid"	=>	$userid,
				"txt" => $txt,
				"time"	=> time(),
				"uri"	=>	$this->http_host,
				"ip" => $this->ip
			),"mail");
					
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
									$this->FURY->db->insert($this->error_db,$params);
								}
							}
							break;
				case "mysql":
							if($this->mysql_db){
								if(count($params)>5){
									$this->FURY->db->insert($this->mysql_db,$params);
								}
							}
							break;
				case "mail": 
							if($this->mail_db){
								if(count($params)>5){
									$this->FURY->db->insert($this->mail_db,$params);
								}
							}
							break;
			}

		}
		
		function _append($txt,$userid){
		
			$date = date('d.m.Y h:i:s',time()); 
			$this->ip = $_SERVER['REMOTE_ADDR'];
			$this->http_host = $_SERVER['REQUEST_URI'];
			
			$txt = $date.' | '.$this->ip.' | userid: '.$userid.' | '.$txt.' | uri: '.$this->http_host."\n";
			
			return $txt;

		}
	
	}