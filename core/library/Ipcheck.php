<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Ipcheck{
	
		var $offense_limit = 0;
		var $ip_violate_message = '';
		var $dupe_limit = 0;
		var $violation_timer = 0;
		var $jail_violation_reason = '';
		
		var $base_url = '';		
		var $breached = 0;
			
		function FURY_IP_Check(){
			
			$this->FURY =& get_instance();

			$this->FURY->load->library('Logging');			
			
			$this->FURY->config->load('ip_check',TRUE);
			
			$this->base_url = $this->FURY->core->get_config_item('base_url');
			
			$params = $this->FURY->core->get_config_item('ip_check');
			
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
		* The first is a simple check to see if a user passed 
		* has the same current ip as the ip we exract for you.
		*/
		
		function simple($user,$other_user){
			
			if($you_ip = $this->_get_ip_address()):
						
			if($you_ip==$other_user['current_ip']):
			
				$this->logging->user_log(sprintf(gettext("Userid: %s has been caught on the same IP as %s, after a 'simple' ip check."),$user['id'],$other_user['id']),$user['id']);			
								
				$parent_ip = $this->_flag_ip($you_ip);

				$this->_flag_users(array($user,$other_user),$parent_ip);
				
				/* we must now check to see if this is something which has been re_offended often */
				
				$this->_add_offense($user);
								
				throw new Exception($this->ip_violate_message);
				
			endif;
			
			endif;
			
			return false;
		
		}
		
		
		/**
		*
		* A more complex check to look through a whole bunch of shit. 
		*/
		
		function complex($user,$users){
		
			/* grab the actioners ip address, with ability to get through headers */
			
			if($you_ip = $this->_get_ip_address()):
				
				$problem_user = array();
				
				foreach($users as $k=>$v):
				
					if($v['current_ip']==$you_ip):
					
						$problem_user[] = $v;
					
					endif;
				
				endforeach;
				
				
				if(count($problem_user)>0):
					
					$parent_ip = $this->_flag_ip($you_ip);

					$this->_flag_users($user,$parent_ip);
					
					$this->_add_offense($user);
					
					$problem_ids = array();
					
					foreach($problem_user as $k=>$v):
						
						$this->_flag_users($v,$parent_ip);
						$problem_ids = $v['id'];
					
					endforeach;
					
					$this->logging->user_log(sprintf(gettext("Userid: %s has been caught on the same IP as %s, after a 'complex' ip check."),$user['id'],implode(", ",$problem_ids)),$user['id']);						
					
					throw new Exception($this->ip_violate_message);
				
				endif;
			
			endif;
			
			return false;
		
		}
		
		
		
		/**
		*
		* add offenses breached counter column. 
		*/
		
		function _add_offense($user){
		
			if(!$this->FURY->db->query("select * from dupe_flagged_offences where userid='{$user['id']}'")->as_assoc()):
			
				$this->FURY->db->insert("dupe_flagged_offences",array(
					"userid" => $user['id'],
					"offence_count" => 1
				));
			
			else:
			
				$this->FURY->db->updatetabledatawhere("dupe_flagged_offences","offence_count=offence_count+'1'",array("userid"=>$user['id']));
			
			endif;
			
		
		}
		
		
		/**
		*
		* test if this user has broken the rules offences_breached 
		*/
		
		
		function _offences_breached($id){
		
			$offences_row = $this->FURY->db->getwhere("*","dupe_flagged_offences","userid",$id);
			
			if($offences_row['offence_count'] % $this->offense_limit == 0):
			
				/* log this event */
				
				$this->logging->user_log(sprintf(gettext("Userid: %s has been thrown into federal jail for exceeding the number of I.P check offenses allowed"),$id),$id);		
			
				/* time for federal jail */
				
				$this->FURY->load->model('sentance');
				
				/* right what we now need to do is find the amount of times divisible. */
				
				$jail_time = round($this->violation_timer * floor(($offences_row['offence_count']/$this->offense_limit)),0);
				
				$this->FURY->sentance->Jail($id,$this->violation_timer,$this->jail_violation_reason,true,true);
				
				header("Location: ".$this->base_url."/city/jail");exit;
				
			endif;
		
		}
		
		
		
		/**
		*
		* Flag users up 
		*/
		
		
		function _flag_users($users,$parent_ip=false){
		
			if($parent_ip):
			
				if(is_array($users) && count($users)>0):
				
					foreach($users as $k=>$v):
						$this->FURY->db->insert("dupe_flagged_ip_users",array(
							"userid" => $v['id'],
							"page" => $_SERVER['REQUEST_URI'],
							"time" => time(),
							"ip_id" => $parent_ip
						));
					endforeach;
				
				else:
					
					if($users):
					
						$this->FURY->db->insert("dupe_flagged_ip_users",array(
							"userid" => $users['id'],
							"page" => $_SERVER['REQUEST_URI'],
							"time" => time(),
							"ip_id" => $parent_ip
						));					
					
					endif;
				
				endif;
			
			endif;
		
		}
	
	
					
		/**
		*
		* Flag an ip 
		*/
		
		function _flag_ip($ip){
		
			if(!$ip = $this->FURY->db->query("SELECT * FROM dupe_flagged_ip WHERE ip_address='$ip'")->as_assoc()):
				$ret_id = $this->FURY->db->insert("dupe_flagged_ip",array("ip_address"=>$ip,"last_flagged"=>time()),true);
			else:
				$time = time();
				$this->FURY->db->updatetabledatawhere("dupe_flagged_ip","last_flagged='$time'","ip_address",$ip);
				$ret_id = $ip['id'];
			endif;
		
			return $ret_id;
		
		}
	
	
		/**
		*
		* On a single user against other users, we must fetch the current user. 
		*/
		
		function _get_ip_address() {
		    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key):
		        if (array_key_exists($key, $_SERVER) === true):
		            foreach (explode(',', $_SERVER[$key]) as $ip):
		                if (filter_var($ip, FILTER_VALIDATE_IP) !== false):
		                    return $ip;
		                endif;
		            endforeach;
		        endif;
		    endforeach;
		   	return false;
		}		
				
	
	
		/**
		*
		* _clear all previously set class elements to allow for chaining. 
		*/
		
		function _clear(){

			$this->offense_limit = 0;
			$this->ip_violate_message = '';
			$this->dupe_limit = 0;
			$this->violation_timer = 0;
			$this->jail_violation_reason = '';
					
			$this->breached = 0;		
	
		}
	
	
	}
	
	
	
	
?>