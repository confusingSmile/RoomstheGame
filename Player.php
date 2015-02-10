<?php
	class Player{
	
		/*
		*	hunger: integer starting at 300, indicating how not-hungry the player is. 
		*		If it reaches 0, the player "dies".
		*	currentRoom: the Room that the player is visiting.
		*	activeItemEffects: utility items' effects that are currently active
		*	gatheredItems: items the player can use
		*	generatedItems: items that the player can obtain or has obtained
		*	doorsUnlocked: the amount of keyDoors the player has unlocked
		*/
		var $hunger;
		var $currentRoom;
		var $activeItemEffects;
		var $gatheredItems;
		var $generatedItems;
		var $doorsUnlocked;
	
		function Player(){
			$hunger = 300;
			$this->currentRoom = new IntroRoom();
		}
		
		function getGatheredItems(){
			return $gatheredItems;
		}
		
		function getCurrentRoom(){
			return $this->currentRoom;
		}
		
		function getCurrentItemEffects(){
			return $activeItemEffects;
		}
		
		//integer indicating the amount of keyDoors the player has unlocked
		function doorsUnlocked(){
			return $doorsUnlocked;
		}
		
		function addGeneratedItem($item){
			$generatedItems[] = $item;
		}
		
		function unlockKeyDoor(){
			$output = "Nothing to unlock.";
			if($this->currentRoom->getClass() == LockedDoorRoom){
				if($this->currentRoom->exitBlocked == true){
					$output = "The door slowly opens...";
				} 
				
			}
			return $output;
		}
		
		function obtainItem(){
			$result = 0;
			if($this->currentRoom->getItem() != null){
				$gatheredItems[] = $this->currentRoom->getItem();
				//statement to remove the item from the non-gathered list
				$this->currentRoom->takeItem();
				$result = 1;
			}
			return $result;
		}
		
		/*uses an item
		*	item: the Item the player wants to use
		*	room: the Room the player wants to use the item in
		*/
		function useItem($item, $room){
			$result = 0;
			//db query about effect of using that item in that room. 1 for works, 0 for does nothing, -1 for game over
			//statement to remove the item from the item list
			return $result;
			
		}
		
		
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		function travel($direction){
			$output = "";
			//if true the player is moving back into a room that has already generated. 
			if($this->currentRoom->getNeighbour($direction) == null){	
				if($this->currentRoom->exitBlocked() == false){
					$nextRoom = //RoomFactory will make a new Room
					//keeping track of generated items
					$generatingItem = $nextRoom->getItem();
					if($generatingItem != 0){
						addGeneratedItem($generatingItem);
					} 
					$this->currentRoom -> registrateNeigbour($nextRoom, $direction);
					$this->currentRoom = $this->currentRoom->getNeighbour($direction);
					$this->currentRoom->generateNeighbours();
					$output =  $this->currentRoom->welcomePlayer;
				}else{
					$output =  "The door won't open.";
				}
			}else{
				$this->currentRoom = $this->currentRoom->getNeighbour($direction);
				$output =  $this->currentRoom->welcomePlayer;
			}
			
			return $output;
		}
		
		function gameOver(){
			//maybe send something to the CommandProcessor
		}
		
	}
?>