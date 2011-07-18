<?php

	class FURY_Statistics{
	
		function FURY_Statistics(){
		 	$this->core =& load_class('Core');
		 	$this->FURY =& get_instance();
		 	$this->FURY->load->library('Validation');
		 	$this->FURY->load->library('db');
		}
		
		function user($stat_data=array(),$stat_value='',$userid=false){
			if(is_string($stat_data)){    
            	$stat_data = array($stat_data => $stat_value);
            }
            if(!$this->FURY->validation->is_numeric($userid)){
            	$userid = $this->FURY->playerid;
            } else {
            	if($this->FURY->db->query("SELECT * FROM characters WHERE id = '$userid'")->row()){
            		$userid = $userid;
            	} else {
            		$userid = $this->FURY->playerid;
            	}
            }
           
			$start = (mktime(0, 0, 0, date('n'), date('j'))+1);
			$end = (mktime(0, 0, 0, date('n'), date('j')+1)-1);
           			
			if(count($stat_data)>0){
				foreach($stat_data as $k => $v){
					$check = $this->FURY->db->query("SELECT * FROM character_stats WHERE date >= '$start' AND date <= '$end' AND userid = '$userid' AND stat = '$k'")->row();
					if(!$check){
						$fields['userid']=$userid;
						$fields['date']=time();
						$fields['stat']=$k;
						$this->FURY->db->insert("character_stats",$fields);
						$rowid = mysql_insert_id();
						
						//Check in types table
						$types_check = $this->FURY->db->query("SELECT * FROM character_stat_types WHERE stat = '$k'")->row();
						if(!$types_check){
							$field['stat']=$k;
							$this->FURY->db->insert("character_stat_types",$field);
							$this->FURY->db->query("ALTER TABLE character_stats_monthly ADD $k INT(12) NOT NULL DEFAULT 0;");
						}
					} else {
						$rowid = $check['id'];
					} 
					
					$sql = 'value = value '.$this->FURY->db->build_number($v);
					$this->FURY->db->query("UPDATE character_stats SET $sql WHERE id = '$rowid'");
				}
			}
		}
	
		function site($stat_data=array(),$stat_value=''){
			if(is_string($stat_data)){    
            	$stat_data = array($stat_data => $stat_value);
            }
           
			$start = (mktime(0, 0, 0, date('n'), date('j'))+1);
			$end = (mktime(0, 0, 0, date('n'), date('j')+1)-1);
           
			$check = $this->FURY->db->query("SELECT * FROM site_stats WHERE date >= '$start' AND date <= '$end'")->row();
			if(!$check){
				$fields['date']=time();
				$this->FURY->db->insert("site_stats",$fields);
				$rowid = mysql_insert_id();
			} else {
				$rowid = $check['id'];
			} 
			
			if(count($stat_data)>0){
				foreach($stat_data as $k => $v){
					$check = $this->FURY->db->query("SELECT * FROM site_stats WHERE date >= '$start' AND date <= '$end' AND stat = '$k'")->row();
					if(!$check){
						$fields['date']=time();
						$fields['stat']=$k;
						$this->FURY->db->insert("site_stats",$fields);
						$rowid = mysql_insert_id();
						
						//Check in types table
						$types_check = $this->FURY->db->query("SELECT * FROM site_stat_types WHERE stat = '$k'")->row();
						if(!$types_check){
							$field['stat']=$k;
							$this->FURY->db->insert("site_stat_types",$field);
							$this->FURY->db->query("ALTER TABLE site_stats_monthly ADD $k INT(12) NOT NULL DEFAULT 0;");
						}
					} else {
						$rowid = $check['id'];
					} 
					
					$sql = 'value = value '.$this->FURY->db->build_number($v);
					$this->FURY->db->query("UPDATE site_stats SET $sql WHERE id = '$rowid'");
				}
			}
		}       	
	}