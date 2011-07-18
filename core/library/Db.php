<?php	
		
	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Db{
	
		var $connections = array();
	
		 function FURY_Db(){
		 	
		 	// Use default connections and save them
		 	$this->core =& load_class('Core');
		 	$this->FURY =& get_instance();
			$this->FURY->load->library('Logging');
		 	$this->FURY->load->library('Validation');
		 	
		 	# Auto connect if told too
		 	if($this->core->get_config_item('auto_db_connect')){
		 		$this->connectDefault();
		 	}
		 	
		 }
		 
		 function connectDefault(){
		 
		 	if($default = $this->core->get_config_item('default','database')){
		 			 	
		 		if($default['user'] && $default['password'] && $default['host']){
		 	
		 			$this->connections['default'] = mysql_connect($default['host'],$default['user'],$default['password']);
		 			if($this->connections['default']){
		 				
		 				# Select the database.
		 				$this->selectDb($default['database']);
		 				
		 			}else{
		 				
		 				# Log the error.
		 				$this->FURY->logging->mysql_log("Problem connecting to default database",mysql_error());
		 			
		 			}
		 		
		 		}else{
		 			
		 				# Log the error.
		 				$this->FURY->logging->mysql_log("Database credentials missing.",mysql_error());

		 		}
		 	
		 	}
		 	
		 }
		 
		 function selectDb($database){	
		 	if(!$db = mysql_select_db($database)){
		 	
 				# Log the error.
 				$this->FURY->logging->mysql_log("Unable to select database: $database.",mysql_error());
		 		return false;
		 	}
		 	return true;
		 }
		 
		 function query($query){
		 	$this->current_query = mysql_query($query);
		 	if(mysql_errno()){
		 		# Log this error.
		 		$this->FURY->logging->mysql_log($query,mysql_error());
		 	}
		 	
		 	return $this;
		 }
		 
		 function num_rows(){
		 	return mysql_num_rows($this->current_query);
		 }
		 
		 function row(){
		 	return mysql_fetch_assoc($this->current_query);
		 }
		 
		 function rows(){
		 	$array = array();
		 	while($r = mysql_fetch_assoc($this->current_query)){
		 		$array[] = $r;
		 	}
		 	return $array;
		 }
		 
		 function count($table,$field='*',$where){
		 	$query = $this->query("SELECT COUNT($field) as total FROM $table WHERE $where")->as_assoc();
		 	return $query['total']; 
		 }
		 
		 # Returns the mysql_object as an object
		 function as_object(){
		 	return mysql_fetch_object($this->current_query);
		 }
		 
		 # Returns the mysql_object as an associative array
		 function as_assoc(){
		 	return mysql_fetch_assoc($this->current_query);
		 }
		 
		 # Returns the objects as an array
		 function as_array(){
		 	return mysql_fetch_array($this->current_query);
		 }
		 
		 # Returns a single value 
		 function get(){
		 	$assoc = mysql_fetch_assoc($this->current_query);
		 	if(is_array($assoc)){
			 	foreach($assoc as $k=>$v){
			 		return $v;
			 	}
		 	}
		 }
		 
		// =========== 
		// ! Due to my love of this phrase I can't do without it.  
		// ===========  
		
		function getwhere($select_fields,$table,$field,$var){
			return $this->query("select $select_fields from $table where $field='{$var}'")->as_assoc();
		}
		
		 
		// =========== 
		// ! delete a record from the db   
		// =========== 
		 
		function delete($table,$where){
		
			$finalstr = $this->_handleWhere($where);
			
			if(count($where)>0){
							
				$this->query("delete from $table where $finalstr");
			}	
	
		}
		
		// =========== 
		// ! Get all data  
		// =========== 
		
		function getalldata($table){
			return $this->query("select * from $table")->rows();
		}
		
		// =========== 
		// ! Get all data where  
		// =========== 
		
		function getalldatawhere($table,$field,$var){
			return $this->query("select * from $table where $field='$var'")->as_assoc();
		}
		
		// =========== 
		// ! delete a record from the fixed to one where   
		// =========== 
		 
		function deletewhere($table,$field,$value){
			$this->query("delete from $table where $field='{$value}'");
		}
		 
		// =========== 
		// ! update a table going with an array of fields at the end  
		// =========== 
		
		function updatetabledata($table,$fields,$field,$value){
			$this->query("update $table set $fields where $field='{$value}'");
		}
	
		// =========== 
		// ! update a table going with an array of fields at the end  
		// =========== 
		
		function updatetabledatawhere($table,$fields,$where){
			$finalstr = $this->_handleWhere($where);
			if(count($where)>0){
				$this->query("update $table set $fields where $finalstr");
			}
		}		
		 
		 // =========== 
		 // ! Insert a record to the database   
		 // =========== 
		 
		function insert($table, $assoc_arr, $ret = false){
		    foreach($assoc_arr as $k=>$v)
			    $assoc_arr[$k] = $this->_mes($v);
			
			    $insertstr="INSERT INTO `".$table."`";
			    
			    $insertstr.=" (`". implode("`,`", array_keys($assoc_arr)) ."`) VALUES" ;
			    $insertstr.=" (". implode(",", array_values($assoc_arr)) .");" ;
			    
			    $q = $this->query($insertstr);
		   	
		   	if($ret && $q){
		   		return mysql_insert_id();
		   	}
		        
		}
		
		// =========== 
		// ! Insert record to the database with delay   
		// =========== 
			
		function insert_delayed($table, $assoc_arr){
		    foreach($assoc_arr as $k=>$v)
		        $assoc_arr[$k] = $this->_mes($v);
		
		    $insertstr="INSERT DELAYED INTO `".$table."`";
		    $insertstr.=" (`". implode("`,`", array_keys($assoc_arr)) ."`) VALUES" ;
		    $insertstr.=" (". implode(",", array_values($assoc_arr)) .");" ;
		    
		    $this->query($insertstr);
		}
		
		
		// =========== 
		// ! Get updates to the player.
		// =========== 	
		
		function updatedata($id,$fields){
			if($id && $fields){
				$char_table = $this->core->get_config_item('character_table');
				$this->query("update $char_table set $fields where id='$id'");
				return true;
			}
			return false;
		}
		
		// =========== 
		// ! Get timers
		// =========== 
		
		function getTimer($id,$fields='*'){
			if($id && $fields){
				return $this->query("select $fields from character_timers where userid='$id'")->as_assoc();
			}
		}			
		
		// =========== 
		// ! Get timers
		// ===========
		
		function updateTimer($id,$fields){
			if($id && $fields){
			
				/* Check they have a row */
				if(!$this->query("select id from character_timers where userid='$id'")->as_assoc()):
					$this->insert("character_timers",array(
						"userid" => $id
					));
				endif;
				
				if(is_array($fields)){
					$sql = array();
					foreach($fields as $k=>$v):
						$sql[]="$k='{$v}'";
					endforeach;
					$fields = implode(",",$sql);
				}
			
				$this->query("update character_timers set $fields where userid='$id'");
			}
		}		
		
		// =========== 
		// ! Get updates to the player.
		// =========== 	
		
		function getuser($id,$fields='*',$use_reg_table=false){
			if($id && $fields){
			
				if(is_numeric($id)):
					$ext = "id='$id'";
				else:
					$ext = "username='$id'";
				endif;
			
				$char_table = $this->core->get_config_item('character_table');
				$user_table = $this->core->get_config_item('user_table');
				
				if($use_reg_table){
					return $this->query("select $fields from $user_table where $ext")->as_assoc();
				}else{
					return $this->query("select $fields from $char_table where $ext")->as_assoc();
				}
			}
			return false;
		}
		

		// =========== 
		// ! Get all player details.
		// =========== 			
		
		function getuserinfo($id,$fields='*'){
			$char_table = $this->core->get_config_item('character_table');
			$user_table = $this->core->get_config_item('user_table');


			return $this->query("SELECT $fields
			FROM $char_table c
			INNER JOIN $user_table u
			ON c.userid=u.id
			WHERE c.id='$id'")->as_assoc();
		}

		// =========== 
		// ! Detect whether or not global magicquotes are available   
		// =========== 		 
		
		function _mes($value){
		
			// Stripslashes
			if (get_magic_quotes_gpc()){
			  $value = stripslashes($value);
			}
			// Quote if not a number
			if (!is_numeric($value)){
			  $value = "'" . mysql_real_escape_string($value) . "'";
			}
			
			return $value;
		
		}
		
		function _handleWhere($where){
			$str = array();
			if(is_array($where)){
				foreach($where as $k=>$v):
					$where[$k] =  $this->_mes($v);
					$str[]=$k."='{$where[$k]}'";
				endforeach;
				
				return $finalstr = implode(" AND ",$str);
			}
		}
		
		function build_number($amount){
			if($this->FURY->validation->is_numeric($amount)){
				if($amount >= 0){
					$text = "+ '".$amount."'";
				} else {
					$minus = split("-",$amount);
					$text = "- '".$minus[1]."'";
				}
			}
			return $text;	
		}
	
	}
		