<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Inventory{
	
		function Fury_Inventory(){
		 	$this->core =& load_class('Core');
		 	$this->FURY =& get_instance();
		 	$this->FURY->load->library('Validation');
		 	$this->FURY->load->library('db');
		}
		
		function additem($id,$itemid,$quantity=1){
			
			if(isset($id) && isset($itemid)){
				
				if(!$this->FURY->db->query("select * from inventory where userid='$id' and itemid='$itemid'")->as_assoc()){
				
					//$iteminfo = $this->fetchItemInfo($itemid);
				
					$this->FURY->db->insert("inventory",array(
						"userid" => $id,
						"itemid" => $itemid,
						"quantity" => $quantity
					));
				
				}else{
					
					$this->FURY->db->updatetabledatawhere("inventory","quantity=quantity+'$quantity'",array(
						"userid" => $id,
						"itemid" => $itemid
					));
					
				}
			
			}
			
		}
		
		function removeitem($id,$itemid,$quantity=1){
			if(isset($id) && isset($itemid)){
				$row = $this->FURY->db->query("select * from inventory where itemid='$itemid' and userid='$id'")->as_assoc();
				if($row['quantity']>$quantity){
				
					$this->FURY->db->updatetabledatawhere("inventory","quantity=quantity-'$quantity'",array(
						"userid" => $id,
						"itemid" => $itemid
					));
					
				}elseif($quantity==$row['quantity']){
					$this->FURY->db->delete("inventory",array(
						"itemid" => $itemid,
						"userid" => $id
					));
				}			
			}
		}
		
		function fetchItemInfo($itemid){
			if(isset($itemid) && $this->FURY->validation->is_numeric($itemid)){
				return $this->FURY->db->query("select * from items where id='$itemid'")->as_assoc();
			}
			return false;
		}
		
	}