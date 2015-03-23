<?php
	class Player{
	
		/*
		*	hunger: integer starting at 300, indicating how not-hungry the player is. 
		*		If it reaches 0, the player "dies".
		*	currentRoom: the Room that the player is visiting.
		*	gatheredItems: items the player can use
		*	generatedItems: items that the player can obtain or has obtained
		*	doorsUnlocked: the amount of keyDoors the player has unlocked
		*	building: the place with the Rooms. The Player and the Room should know each other (right?) 
		*/
		var $hunger;
		var $currentRoom;
		var $gatheredItems;
		var $doorsUnlocked;
		var $building;
	
		function Player(){
			$this->hunger = 300;
			$this->currentRoom = new IntroRoom();
			//doesn't do anything yet
			$this->currentRoom->welcomePlayer();
			$this->building = new Building($this);
		}
		
		function getHunger(){
			return $this->hunger;
		}
		
		function getGatheredItems(){
			return $this->gatheredItems;
		}
		
		function getCurrentRoom(){
			return $this->currentRoom;
		}
		
		//integer indicating the amount of keyDoors the player has unlocked
		function getDoorsUnlocked(){
			return $this->doorsUnlocked;
		}
		
		//unlocks the Door if it's locked. 
		function unlockKeyDoor($direction){
			$output = "Nothing to unlock.";
			if(get_class($this->currentRoom) == "LockedDoorRoom"){
				$output = $this->currentRoom->getDoor($direction)->unblock();	
					$firstUnlockThisRoom = true; 
					for($i=($direction+1);$i<($direction+3);$i++){
						$j = $i%4;
						if($this->currentRoom->getDoor($j)->getBlocked() == false){
							$firstUnlockThisRoom = false;
						}
					}
					if($firstUnlockThisRoom == true){
						$this->doorsUnlocked ++;
					}
			}
			return $output;
		}
		
		function obtainItem(){
			$result = "There is no item.";
			if($this->currentRoom->getItem() != null){
				$pickedUpItem = $this->currentRoom->getItem();
				$this->gatheredItems[] = $pickedUpItem;
				$this->currentRoom->takeItem();
				$result = "Obtained a(n)".$pickedUpItem->getItemName().".";
			}
			return $result;
		}
		
		function searchRoomForItem(){
			$result = "No items in this room.";
			if($this->currentRoom->getItem() != null){
				$result = "There seems to be something here.";
			}
			return $result;
		}
		
		/*uses an item
		*	item: the Item the player wants to use
		*	@return: String for the textarea in index.php
		*/
		function useItem($itemName){
			$result = "Nothing happened...";
			$effect = 0;
			
			//check if the player HAS the item:
			$itemGathered = false; 
			for($i = 0; $i < count($this->gatheredItems); $i++){
				if($itemName == $this->gatheredItems[$i]->getItemName()){
					$itemGathered = true; 
				}
			}
			if($this->gatheredItems != null){
				if(!($itemGathered == true)){
					$result = "You don't have that item... (".$itemName.")";
				} else {
					$roomType = get_class($this->currentRoom);
					if($roomType == "ObstacleRoom"){
						$result = $this->currentRoom->clearObstacle($itemName);
						
					} 
				}
			} else {
				$result = "You don't have any items...";
			}
			
			
			return $result;
			
		}
		
		
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		function travel($direction){
			$output = "";
			$buildingOutput = "";
			//if true the player is moving back into a room that has already generated. 
			if($this->currentRoom->getNeighbour($direction) == null){	
				if($this->currentRoom->getDoor($direction)->getBlocked() == false){
					//create the room
					$buildingOutput = $this->building->createRoom($direction);
					//actually enter the next room, which costs hunger, so subtract 1 hunger;
					$this->currentRoom = $this->currentRoom->getNeighbour($direction);
					$this->hunger --;
					
					$output =  $this->currentRoom->welcomePlayer();
				}else{
					$output =  "The door won't open.";
				}
			}else{
				$this->currentRoom = $this->currentRoom->getNeighbour($direction);
				$output = $this->currentRoom->welcomePlayer();
			}
			
			return $buildingOutput."\n".$output;
		}
		
		function gameOver(){
			//maybe send something to the CommandProcessor
		}
		
		
		
	}
?>