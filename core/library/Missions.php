<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Missions{
	
	private $fields = array();
	private $user = array();
	private $current_field;
	var $failed = array();
	var $errors = 0;
	var $asset_url;
	
		function Fury_Missions(){
		 	$this->core =& load_class('Core');
		 	$this->FURY =& get_instance();
		 	$this->FURY->load->library('Validation');
		 	$this->FURY->load->library('db');
			
			// This core.
			$this->asset_url = $this->core->get_config_item('assets_url');
		
		}	
		
		function interpretRequirements($req,$playerid){
				
			if(isset($req)){
				
				foreach($req as $k=>$v):
					
					
					$this->filterRequirement($k);
					
					
				endforeach;
				
				// First of all we just set up some fields to retrieve.
				
				$fields_to_fetch = implode(",",$this->fields);
				
				$this->user = $this->FURY->db->query("select $fields_to_fetch from characters where id='$playerid'")->as_assoc();
				
				// Then we loop again.
				
				foreach($req as $k=>$v):
					
					$this->setRequirement($k);
					
					if(!$this->challengeRequirement($k,$v)){
						
						// The player has failed to meet a requirement so we must fail and record what it was.
						$this->failed_requirements[] = $this->failedRequirement($k,$v);
						
					}
					
				endforeach;
			
			}
		
		}
		
		function filterRequirement($key){
			
			if(isset($key)){
		
				switch($key){
					
					case "l": 
							// We know this is a level interpretter. So initially we create an array of fields to collect.
							$this->fields[] = "level";
							break;
							
				}
			
			}
		
		}
		
		function setRequirement($key){
			
			
			if(isset($key)){
			
				switch($key){
					case "l": 
							// We know this is a level conversion so lets make this into something.
							$this->current_field = "level";
							break;
				}
			
			}
			
		}
		
		function challengeRequirement($k,$v){
		
			if($this->user[$this->current_field]>=$v){
				
				return true;
				
			}
			
			return false;
		
		}
		
		function failedRequirement($k,$v){
			
			$this->errors++;
			$this->failed[$k] = 'You need to be '.$v.' but are only '.$this->user[$this->current_field];
			
		}
		
		function outputRequirements($req,$plid){
		
			$str = '';
		
			foreach($req as $k=>$v):
				
				$str.= $v.'&nbsp;';
				$str.='<img src="'.$this->fetchImage($k).'">';
				$str.=$this->fetchWords($k);
				
			endforeach;
			
			return $str;
			
		}
		
		function outputRewards($reward,$plid){
			
			if(isset($reward)){
			
				$str = '';
			
				foreach($reward as $k=>$v):
					
					$str.='<img src="'.$this->fetchRewardImage($k).'">';
					$str.=$this->fetchRewardText($k,$v);
					
				endforeach;
			
				return $str;
			
			}else{
				echo 'NOPE';
			}
						
			return false;
					
		}
		
		function fetchImage($k){
		
			switch($k){
				case "l": 
						// Level Image
						$img = $this->asset_url.'images/exp.png';
						
			}
			
			return $img;
		
		}
		
		function fetchWords($k){
			
			switch($this->current_field){
				case "l": 
						// Level text
						
						break;
			}
		
		}
		
		function fetchRewardImage($k){

			switch($k){
				case "c": 
						// Level Image
						$img = $this->asset_url.'images/dollar_bill.png';
						
			}
			
			return $img;

		}

		function fetchRewardText($k,$v){
			
			switch($k){
				case "c": 
						// Level Image
						$text = sprintf(gettext("x %s"),number_format($v));
						
			}
			
			return $text;			
			
		}
		
		function takeMission($number,$id){
			
			if(is_numeric($number)){
				if($this->FURY->db->query("select * from missions where id='$number'")->as_assoc()){
				
					// Set the user up with this mission as their current.
					if(!$this->FURY->db->query("select * from user_missions where userid='{$id}'")->as_assoc()){
						
						$this->FURY->db->insert("user_missions",array(
							"userid" => $id,
							"mission_id" => $number,
							"mission_step" => 1
						));
						
					}else{
						
						$this->FURY->db->query("update user_missions set mission_id='$number',mission_step='1' where userid='{$id}'");
						
					}
					
					return true;
					
				}
			}
			
			return false;
			
		}
	
	}