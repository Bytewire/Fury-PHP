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

		function firstDayOfLastMonth(){
			$firstDay = strtotime("first day of last month");
			$theDate = date("F Y",$firstDay);
			$finalDate = strtotime($theDate);
			return $finalDate;
		}
		
		function lastDayOfLastMonth(){
			$firstDay = strtotime("first day of this month");
			$theDate = date("F Y",$firstDay);
			$finalDate = (strtotime($theDate))-1;
			return $finalDate;
		}
		
		function getStat($stat,$playerid,$type=false){
			/** Type Options
			
				0 - Total
				1 - Last Week (time - 7 days)
				2 - Current Week
				3 - Last Month
				4 - This Month
			**/
		
			if($this->FURY->validation->checkdata($stat,5)){
				$checkstat = $this->FURY->db->query("SELECT * FROM character_stat_types WHERE stat = '$stat'")->row();
				if($checkstat){
					if($this->FURY->validation->is_numeric($playerid)){
						$checkuser = $this->FURY->db->query("SELECT * FROM characters WHERE id = '$playerid'")->row();
						if($checkuser){	
							if($this->FURY->validation->is_numeric($type) || empty($type)){
								if(empty($type)){ $type = 0; }
								$time = time();
								switch ($type){
									case 0:
										$current_total = $this->FURY->db->query("SELECT SUM(value) as total FROM character_stats WHERE userid = '$playerid' AND stat = '$stat'")->get();
										$remaining_total = $this->FURY->db->query("SELECT SUM($stat) as total FROM character_stats_monthly WHERE userid = '$playerid'")->get();
										$total = $current_total+$remaining_total;
										break;
									case 1:
										$startTime = time()-604800;
										$total = $this->FURY->db->query("SELECT SUM(value) as total FROM character_stats WHERE userid = '$playerid' AND stat = '$stat' AND date >= '$startTime' AND date <= '$time'")->get();
										break;
									case 2:
										$startTime = strtotime('Last Monday');
										$total = $this->FURY->db->query("SELECT SUM(value) as total FROM character_stats WHERE userid = '$playerid' AND stat = '$stat' AND date >= '$startTime' AND date <= '$time'")->get();
										break;
									case 3:
										$startTime = firstDayOfLastMonth();
										$endTime = lastDayOfLastMonth();
										$total = $this->FURY->db->query("SELECT $stat as total FROM character_stats_monthly WHERE userid = '$playerid' AND start_month >= '$startTime' AND end_month <= '$endTime'")->get();
										break;
									case 4:
										$total = $this->FURY->db->query("SELECT SUM(value) as total FROM character_stats WHERE userid = '$playerid' AND stat = '$stat'")->get();									
										break;
								}
							}
							return $total;
						}
					}
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