<?php	
		
	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Db{
	
		var $connections = array();
	
		 function FURY_Db(){
		 	
		 	// Use default connections and save them
		 	$this->core =& load_class('Core');
		 	
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
		 				show_error("Problem connecting to default database, please check your credentials over before trying again!".mysql_error());
		 			}
		 		
		 		}else{
		 			show_error("It looks like you want to auto connect but have not supplied all the database credentials, please do so and try again.");
		 		}
		 	
		 	}
		 	
		 }
		 
		 function selectDb($database){	
		 	if(!$db = mysql_select_db($database)){
		 		show_error("Unable to select database: $database");
		 		return false;
		 	}
		 	return true;
		 }
		 
		 function query($query){
		 	$this->current_query = mysql_query($query) or die(mysql_error());
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
		 // ! Insert a record to the database   
		 // =========== 
		 
		function insert($table, $assoc_arr, $ret = false){
		    foreach($assoc_arr as $k=>$v)
			    $assoc_arr[$k] = $this->_mes($v);
			
			    $insertstr="INSERT INTO `".$table."`";
			    
			    $insertstr.=" (`". implode("`,`", array_keys($assoc_arr)) ."`) VALUES" ;
			    $insertstr.=" (". implode(",", array_values($assoc_arr)) .");" ;
			    
			    $q = $this->query($insertstr);
		   	
		   	if($ret){
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
		// ! Get updates to the player.
		// =========== 	
		
		function getuser($id,$fields='*',$use_reg_table=false){
			if($id && $fields){
				$char_table = $this->core->get_config_item('character_table');
				$user_table = $this->core->get_config_item('user_table');
				
				if($use_reg_table){
					return $this->query("select $fields from $user_table where id='$id'")->as_assoc();
				}else{
					return $this->query("select $fields from $char_table where userid='$id'")->as_assoc();
				}
			}
			return false;
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
		
	
	}
		