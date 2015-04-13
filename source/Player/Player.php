<?php


	namespace Game\Player;

	use Game\Room\IntroRoom;
	use Game\Building;
	use Game\DatabaseExtension;
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
		private $db;
	
		function __construct(DatabaseExtension $db){
			$this->db = $db;
			$this->hunger = 300;
			$this->currentRoom = new IntroRoom($this->db);
			$this->currentRoom->welcomePlayer();
			$this->building = new Building($this, $this->db);
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
			if(get_class($this->currentRoom) == 'Game\Room\LockedDoorRoom'){	
				$firstUnlockThisRoom = true; 
				for($i=0;$i<4;$i++){
					$j = $i%4;
					if($this->currentRoom->getDoor($j)->getBlocked() == false){
						$firstUnlockThisRoom = false;
					}
				}
				if($firstUnlockThisRoom){
					$this->doorsUnlocked ++;
				}
				return $this->currentRoom->getDoor($direction)->unblock();
			}
			return 'Nothing to unlock.';
		}
		
		function obtainItem(){
			if($this->currentRoom->getItem()){
				$pickedUpItem = $this->currentRoom->getItem();
				
				if(!(in_array($pickedUpItem, $this->gatheredItems)) ){
					$this->gatheredItems[] = $pickedUpItem;
					$this->currentRoom->takeItem();
					return 'Obtained a(n)' . $pickedUpItem->getItemName(). '.';
				} else {
					return 'You already have this item: ' . $pickedUpItem->getItemName() . '.';
				}
				
			}
			
			return 'There is no item.';
		}
		
		function searchRoomForItem(){
			if($this->currentRoom->getItem() != null){
				return 'There seems to be something here.';
			}
			return 'No items in this room.';
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
				if($itemName === $gatheredItem->getItemName() && get_class($this->currentRoom) === 'Game\Room\ObstacleRoom'){
					return $this->currentRoom->clearObstacle($itemName);
				}
			}
			
			return 'Nothing happened...';
		}
		
		
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		function travel($direction){
			if(!$this->currentRoom->getNeighbour($direction)){	
				if(!$this->currentRoom->getDoor($direction)->getBlocked()){
					//create the room
					$buildingOutput = $this->building->createRoom($direction);
					//actually enter the next room, which costs hunger, so subtract 1 hunger;
					$this->currentRoom = $this->currentRoom->getNeighbour($direction);
					$this->hunger --;
					
					return $buildingOutput.'\n'.$this->currentRoom->welcomePlayer();
				}else{
					return  'The door won\'t open.';
				}
			}else{
				//the player is moving back into a room that has already generated. 
				$this->currentRoom = $this->currentRoom->getNeighbour($direction);
				return $this->currentRoom->welcomePlayer();
			}
			
		}
		
		function gameOver(){
			//maybe send something to the CommandProcessor
		}
		
		
		
	}
?>