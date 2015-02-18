<?php
	class Item{
		//itemID: an integer indicating which item it is (not unique outside the database).
		//itemName: the name of the Item
		//itemIcon: the icon to display in the interface
		var $itemID;
		var $itemName;
		var $itemIcon;
		
		function Item($itemID = -1){
			$this->itemID = $itemID;
			$db = new DatabaseExtension();
			if($itemID == -1){
				$max = $db->getMaxItemID();
				$this->itemID = rand(1, $max);
			}
			$this->itemName = $db->getItemName($itemID);
			$this->itemIcon = $db->getItemIcon($itemID);
		}
		
		function getItemName(){
			return $this->itemName;
		}
		
		function getIcon(){
			return $this->itemIcon;
		}
	}
?>