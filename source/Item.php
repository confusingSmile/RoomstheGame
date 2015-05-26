<?php

	namespace Game;
	class Item{
		//itemID: an integer indicating which item it is (not unique outside the database).
		//itemName: the name of the Item
		//itemIcon: the icon to display in the interface
		private $itemID;
		private $itemName;
		private $itemIcon;
		
		function __construct(DatabaseExtension $db, $itemID = -1){
			$this->itemID = $itemID;
			if($itemID == -1){
				$max = $db->getMaxItemID();
				$this->itemID = rand(1, $max);
			} 
			
			$this->itemName = $db->getItemName($this->itemID);
			$this->itemIcon = $db->getItemIcon($this->itemID);
		}
		
		function getItemName(){
			return $this->itemName;
		}
		
		function getIcon(){
			return $this->itemIcon;
		}
		
		function getId(){
			return $this->itemID;
		}
	}
?>