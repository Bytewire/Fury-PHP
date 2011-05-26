<?php


	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	
	class FURY_Messaging{
	
	private $primary_table;
	private $attached_table;
	private $notification_table;
	
		function FURY_Messaging(){
		
			$this->core =& load_class('Core');
			$this->FURY =& get_instance();
			$this->FURY->load->library('db');
			$this->FURY->load->library('logging');
			
			$this->primary_table = $this->core->get_config_item('primary_table','messaging');
			$this->attached_table = $this->core->get_config_item('attached_table','messaging');
			$this->notification_table = $this->core->get_config_item('table','notifications');
			
		}
		
		function notification($playerid,$title='',$type='',$text=''){
			if(!$playerid){
				die('Attempt to make a notification with no playerid present, problem.');
			}
			$this->FURY->db->insert($this->notification_table,array(
				"userid"	=> $playerid,
				"title"		=> $title,
				"type"		=> $type,
				"text" 		=> $text,
				"time" 		=> time()
			));
		}
	
		function new_inbox($receivers,$creator,$mid='',$seq='',$msg){
			if(!$creator){
				$this->FURY->logging->log_this("Tried to send a message without using a creator id.",$creator);
			}
			
			$msg_id = $this->FURY->db->insert($this->primary_table,array(
				"mid"	=> $mid,
				"seq"	=> $seq,
				"created_on"	=> time(),
				"created_on_ip"	=> $_SERVER['REMOTE_ADDR'],
				"created_by" 	=> $creator,
				"body" 	=> $msg
			),true);	
			
			// Now handle the attached recipients.
			
			if(is_array($receivers)){
				foreach($receivers as $k=>$v):
					$uid = isset($v['uid']) ? $v['uid'] : $v['id'];
					$this->FURY->db->insert($this->attached_table,array(
						"mid" 	=> $msg_id,
						"seq"	=> $seq,
						"uid"	=> $uid, 
						"status"	=> $uid  == $creator ? 'A' : 'N'
					));					
				endforeach;
			}else{
				// single insert
			}
				
		}
	
	}