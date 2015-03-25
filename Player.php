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
		private $hunger;
		private $currentRoom;
		private $gatheredItems = array();
		private $doorsUnlocked;
		private $building;
	
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
		
		/**
		 * Returns a list of gathered items
		 *
		 * @return array
		 */
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
								
				if(!(in_array($pickedUpItem, $this->gatheredItems)) ){
					$this->gatheredItems[] = $pickedUpItem;
					$result = 'Obtained a(n)' . $pickedUpItem->getItemName(). '.';
				} else {
					$result = 'You already have this item: ' . $pickedUpItem->getItemName() . '.';
				}
				
				$this->currentRoom->takeItem();
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
		
		/**
		 * uses an item
		 *
		 * @param string $itemName the Item the player wants to use
		 *
		 * @return string for the textarea in index.php
		 */
		function useItem($itemName){		
			if (empty($this->gatheredItems)) {
				return 'You don\'t have any items...';
			}
			
			foreach($this->gatheredItems as $gatheredItem){
				if($itemName === $gatheredItem->getItemName() && get_class($this->currentRoom) === "ObstacleRoom"){
					return $this->currentRoom->clearObstacle($itemName);
				}
			}
			
			return 'Nothing happened...';
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