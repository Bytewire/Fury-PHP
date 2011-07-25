<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Time_Convert{
	
		function FURY_Time_Convert(){
			$this->FURY =& get_instance();
		}
		
		//Library of time functions
		
		function remaining_time($timestamp,$skiptime = false){
			if(!$skiptime){
				$remaining = $timestamp-time();
			} else {
				$remaining = $timestamp;
			}
			return $remaining;
		}
		
		//Standard Time e.g. 1 hour, 10 minutes and 15 seconds
		function standard_time_left($timestamp,$skiptime = false){
			$seconds = $this->remaining_time($timestamp,$skiptime);  
			$integer = $seconds;  
			
			$minutes = 0;
			$hours = 0;
			$days = 0;
			$weeks = 0;
			$return = '';
			
			if($seconds > 0) {
				if($seconds/60 >=1){  
	  				$minutes=floor($seconds/60);  
					if($minutes/60 >= 1){ # Hours  
						$hours=floor($minutes/60);  
						if($hours/24 >= 1){ #days  
							$days=floor($hours/24);  
							if($days/7 >=1){ #weeks  
								$weeks=floor($days/7);  
								if($weeks>=2) $return="$weeks weeks";
								else $return="$weeks week";  
								} #end of weeks  
								$days=$days-(floor($days/7))*7;  
								if($weeks>=1 && $days >=1) $return="$return, ";  
								if($days >=2) $return="$return $days days"; 
								if($days ==1) $return="$return $days day"; 
							} #end of days 
							$hours=$hours-(floor($hours/24))*24;  
							if($days>=1 && $hours >=1) $return="$return, ";  
							if($hours >=2) $return="$return $hours hours";
							if($hours ==1) $return="$return $hours hour"; 
						} #end of Hours 
						$minutes=$minutes-(floor($minutes/60))*60;  
						if($hours>=1 && $minutes >=1) $return="$return, ";  
						if($minutes >=2) $return="$return $minutes minutes"; 
						if($minutes ==1) $return="$return $minutes minute"; 
					} #end of minutes  
					$seconds=$integer-(floor($integer/60))*60;  
					if($minutes>=1 && $seconds >=1) $return="$return and ";  
					if($seconds >=2) $return="$return $seconds seconds"; 
					if($seconds ==1) $return="$return $seconds second"; 
	  			} else{
	  				$return = "Expired";
	  			}
		     return $return;  
		} 
		
		//Short Time e.g. 1 H 10 M 15 S
		function short_time_left($timestamp,$skiptime = false){  
			$seconds = $this->remaining_time($timestamp,$skiptime);  
			$integer = $seconds;  
			
			$minutes = 0;
			$hours = 0;
			$days = 0;
			$weeks = 0;
			$return = '';
			
			if($seconds > 0) {
				if($seconds/60 >=1){  
	  				$minutes=floor($seconds/60);  
					if($minutes/60 >= 1){ # Hours  
						$hours=floor($minutes/60);  
						if($hours/24 >= 1){ #days  
							$days=floor($hours/24);  
							if($days/7 >=1){ #weeks  
								$weeks=floor($days/7);  
								if($weeks>=2) $return="$weeks W";
								else $return="$weeks W";  
								} #end of weeks  
								$days=$days-(floor($days/7))*7;  
								if($weeks>=1 && $days >=1) $return="$return ";  
								if($days >=2) $return="$return $days D"; 
								if($days ==1) $return="$return $days D"; 
							} #end of days 
							$hours=$hours-(floor($hours/24))*24;  
							if($days>=1 && $hours >=1) $return="$return ";  
							if($hours >=2) $return="$return $hours H";
							if($hours ==1) $return="$return $hours H"; 
						} #end of Hours 
						$minutes=$minutes-(floor($minutes/60))*60;  
						if($hours>=1 && $minutes >=1) $return="$return ";  
						if($minutes >=2) $return="$return $minutes M"; 
						if($minutes ==1) $return="$return $minutes M"; 
					} #end of minutes  
					$seconds=$integer-(floor($integer/60))*60;  
					if($minutes>=1 && $seconds >=1) $return="$return ";  
					if($seconds >=2) $return="$return $seconds S"; 
					if($seconds ==1) $return="$return $seconds S"; 
	  			} else {
	  				$return = "Expired";
	  			}
		     return $return;  
		}
		 		
		//Date and Time e.g. 10:33 10 June 2011
		function date_time($timestamp){
			return date("H:i d F Y",$timestamp);
		}
		
		//Standard Date e.g. 10 June 2011
		function the_date($timestamp){
			return date("d F Y",$timestamp);
		}
		
		//Short Date e.g. 10/06/2011
		function short_date($timestamp){
			return date("d/m/Y",$timestamp);
		}
		
		// Convert date to timestamp
		function get_timestamp($date){
			return strtotime($date);
		}
		
		/**
		*
		* Check time is less than time. 
		*/
		
		function checktime($time,$error_elements=false){
			
			if($time-time()<0):
				return true;
			endif;
			
			if($error_elements):
				
				if(!is_array($error_elements)):
					$name = $error_elements;
				elseif(is_array($error_elements)):
					$name = $error_elements['name'];
				endif;
				
				throw new Exception(sprintf(gettext("You must wait another %s before attempting another %s."),$this->standard_time_left($time),$name));
				
			endif;
			
			return false;
			
		}
		
	}