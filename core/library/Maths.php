<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Maths{
	
		function FURY_Maths(){
			$this->FURY =& get_instance();
		}
		
		//Library of math functions
		function progressiveIncrease($base,$limit,$increase,$single=false){
			
			$increase = 100+$increase;
				
			$result[1] = $base;
			for($i=2;$i<=$limit;$i++){
				$base = (($base/100)*$increase);
				$base = floor($base/1000)*1000;
				$result[$i] = $base;
			}		
			
			if(!$single){
				echo "<pre>";
				print_r($result);
				echo "</pre>";
			} else {
				return $result[$single];
			}		
		}
		
	}