<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Admin{
	
		function FURY_Admin(){
			$this->FURY =& get_instance();
			$this->FURY->load->library('Db');
		}
		
		function ban_Player($table,$field,$length,$on,$value,$banfield,$reason){
			
			$totaltime = time()+$length;
			$this->FURY->db->query("UPDATE $table SET $field = '$totaltime', $banfield = '$reason' WHERE $on = '$value'");
		
		}
		
		function ip_Ban($playerid){
			
			$currentIp = $this->FURY->db->query("SELECT current_ip FROM characters WHERE id = '$playerid'")->get();
			$exists = $this->FURY->db->query("SELECT * FROM ip_bans WHERE ipaddress = '$currentIp'")->row();
			if(!$exists){
				$fields['ipaddress']=$currentIp;
				$this->FURY->db->insert('ip_bans',$fields);				
			}
			
		}
		
		function unBan($username){
			
			$thisuser = $this->FURY->db->query("SELECT c.username, c.userid, u.ban_time FROM characters c INNER JOIN users u ON c.userid = u.id WHERE c.username = '$username'")->row();
			if($thisuser){
				if($thisuser['ban_time'] > time()){
					
					$this->FURY->db->query("UPDATE users SET ban_time = '0' WHERE id = '{$thisuser['userid']}'");
					$this->data['success'] = sprintf(gettext("You removed %s's ban, they can now login again."),$thisuser['username']);
					
				} else {
					$this->data['fail'] = gettext("This user is not banned!");
				}
			} else {
				$this->data['fail'] = gettext("No users found with that username.");
			}
			
			return $this->data;
		}
		
	}