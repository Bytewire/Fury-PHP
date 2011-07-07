<?php

	if ( ! defined('ROOT')) exit('No direct script access allowed');
	
	class FURY_Inventory{
	
		function Fury_Inventory(){
		 	$this->core =& load_class('Core');
		 	$this->FURY =& get_instance();
		 	$this->FURY->load->library('Validation');
		 	$this->FURY->load->library('db');
		}
		
		
		/**
		*
		* Add item to players inventory 
		*/
		
		
		function additem($id,$itemid,$quantity=1){
			
			if(isset($id) && isset($itemid)):
				
				if(!$this->FURY->db->query("select * from inventory where userid='$id' and itemid='$itemid'")->as_assoc()):
				
					$iteminfo = $this->fetchItemInfo($itemid);
									
					$this->FURY->db->insert("inventory",array(
						"userid" => $id,
						"itemid" => $itemid,
						"quantity" => $quantity
					));
				
				else:
					
					$this->FURY->db->updatetabledatawhere("inventory","quantity=quantity+'$quantity'",array(
						"userid" => $id,
						"itemid" => $itemid
					));
					
				endif;
			
			endif;
			
		}
		
		
		/**
		*
		* Remove item from players inventory 
		*/
		
		
		function removeitem($id,$itemid,$quantity=1){
			if(isset($id) && isset($itemid)):
			
				$row = $this->FURY->db->query("select * from inventory where itemid='$itemid' and userid='$id'")->as_assoc();
				if($row['quantity']>$quantity):
				
					$this->FURY->db->updatetabledatawhere("inventory","quantity=quantity-'$quantity'",array(
						"userid" => $id,
						"itemid" => $itemid
					));
					
				elseif($quantity==$row['quantity']):
				
					$this->FURY->db->delete("inventory",array(
						"itemid" => $itemid,
						"userid" => $id
					));
				
				endif;
							
			endif;
		}
		
		
		/**
		*
		* Fetchs all items by a selected type from an inventory 
		*/
		
		function fetchItemsByType($type,$userid){
			
			if(isset($type) && isset($userid)):
			
				return $this->FURY->db->query("
					SELECT inventory.*,it.name,it.type FROM inventory 
					INNER JOIN items it 
					ON it.id=inventory.itemid 
					WHERE it.type='$type'  
					AND inventory.userid='$userid'
					ORDER BY inventory.quantity DESC
					")->rows();
			
			endif;
			
		}
		
		
		/**
		*
		* Fetchs a inventory row where itemid= & userid= 
		*/
		
		
		function fetchInventory($itemid,$playerid){
		
			if(isset($itemid) 
			&& $this->FURY->validation->is_numeric($itemid) 
			&& isset($playerid) 
			&& $this->FURY->validation->is_numeric($playerid)
			):
			
				return $this->FURY->db->query("
				SELECT inventory.*,items.name FROM inventory 
				INNER JOIN items 
				ON inventory.itemid=items.id 
				WHERE inventory.itemid='$itemid' 
				AND inventory.userid='$playerid'")->as_assoc();
			
			endif;
			
			return false;
		}
		
		
		/**
		*
		* Fetch info for an item, joining on its category 
		*/
		
		
		function fetchItemInfo($itemid){
		
			if(isset($itemid) && $this->FURY->validation->is_numeric($itemid)):
			
				return $this->FURY->db->query("SELECT items.*,item_categories.name as cat_name FROM items
				 LEFT JOIN item_categories 
				 ON item_categories.id=items.type
				 WHERE items.id='$itemid'")->as_assoc();
				 
			endif;
			
			return false;
		}
		
		
		/**
		*
		* Verify the item is a valid one 
		*/
		
		
		function verifyItem($itemid){
			if(isset($itemid) 
			&& $this->FURY->validation->is_numeric($itemid)
			):
			
				return $this->FURY->db->query("SELECT * FROM items WHERE id='$itemid'")->as_assoc();
				
			endif;
			
			return false;
		}
		
		
		/**
		*
		* Test whether or not the player has the item 
		*/
		
		function hasItem($itemid,$playerid){
		
			if(isset($itemid) 
			&& $this->FURY->validation->is_numeric($itemid) 
			&& isset($playerid) 
			&& $this->FURY->validation->is_numeric($playerid)):
			
				return $this->FURY->db->query("SELECT * FROM inventory 
				WHERE userid='$playerid' 
				AND itemid='$itemid'")->as_assoc();
			
			endif;
			return false;
		}
		
		
		/**
		*
		* Get the quantity of the item 
		*/
		
		function fetchQuants($itemid,$playerid){
			if(isset($itemid) 
			&& $this->FURY->validation->is_numeric($itemid) 
			&& isset($playerid) 
			&& $this->FURY->validation->is_numeric($playerid)):
			
				return $this->FURY->db->query("select quantity from inventory where userid='$playerid' and itemid='$itemid'")->get();
			
			endif;
			return false;
		}
		
	}