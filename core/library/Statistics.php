<?php

	class FURY_Statistics{
	
		function FURY_Statistics(){
			
		}
		
		function playerStats(){
			
		}
		
		function gameStats(){
			
		}
		
		function site_stats($stat_data=array(),$stat_value=''){
			if (is_string($stat_data)){	
				$stat_data = array($stat_data => $stat_value);
			}
			
			if(count($stat_data)>0){
				foreach($stat_data){
					//blah blah
				}
			}
		}
	
	}
	
	function _set($newdata = array(), $newval = ''){
			if (is_string($newdata)){
				$newdata = array($newdata => $newval);
			}