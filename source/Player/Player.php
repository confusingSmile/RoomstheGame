<?php


	namespace Game\Player;

	use Game\Room\IntroRoom;
	use Game\Building;
	use Game\DatabaseExtension;
	use Doctrine\DBAL\Connection;
	class Player{
	
		/*
		*	hunger: integer starting at 300, indicating how not-hungry the player is. 
		*		If it reaches 0, the player "dies".
		*	name: the username of the current user 
		*	currentRoom: the Room that the player is visiting.
		*	gatheredItems: items the player can use
		*	generatedItems: items that the player can obtain or has obtained
		*	doorsUnlocked: the amount of keyDoors the player has unlocked
		*	building: the place with the Rooms. The Player and the Room should know each other (right?) 
		*/
		private $hunger;
		private $name; 
		private $gatheredItems = array();
		private $doorsUnlocked;
	
		function __construct($name){
			$this->name = $name;
			$this->hunger = 300;
		}
		
		function getHunger(){
			return $this->hunger;
		}
		
		function getName(){
			return $this->name;
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
		function unlockKeyDoor(Room $currentRoom, $direction){
			if(get_class($currentRoom) == 'Game\Room\LockedDoorRoom'){	
				$firstUnlockThisRoom = true; 
				for($i=0;$i<4;$i++){
					$j = $i%4;
					if($currentRoom->getDoor($j)->getBlocked() == false){
						$firstUnlockThisRoom = false;
					}
				}
				if($firstUnlockThisRoom){
					$this->doorsUnlocked ++;
				}
				return $currentRoom->getDoor($direction)->unblock();
			}
			return 'Nothing to unlock.';
		}
		
		function obtainItem(Room $currentRoom){
			if($currentRoom->getItem()){
				$pickedUpItem = $currentRoom->getItem();
				
				if(!(in_array($pickedUpItem, $this->gatheredItems)) ){
					$this->gatheredItems[] = $pickedUpItem;
					$currentRoom->takeItem();
					return 'Obtained a(n)' . $pickedUpItem->getItemName(). '.';
				} else {
					return 'You already have this item: ' . $pickedUpItem->getItemName() . '.';
				}
				
			}
			
			return 'There is no item.';
		}
		
		function searchRoomForItem(Room $room){
			if($room->getItem() != null){
				return 'There seems to be something here.';
			}
			return 'No items in this room.';
		}
		
		/**
		 * uses an item
		 *
		 * @param string $itemName the Item the player wants to use
		 * @param obstacleRoom boolean wether or not this happens in an obstacleRoom, as opposed to other rooms
		 *
		 * @return string for the textarea in index.php
		 */
		function useItem($itemName, $obstacleRoom){		
			if (empty($this->gatheredItems)) {
				return 'You don\'t have any items...';
			}
			
			foreach($this->gatheredItems as $gatheredItem){
				if($itemName === $gatheredItem->getItemName() && $obstacleRoom == true){
					return $room->clearObstacle($itemName);
				}
			}
			
			return 'Nothing happened...';
		}
		
		
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		function travel(Room $roomOfOrigin, Room $destinationRoom){	
			if(!$roomOfOrigin->getDoor($direction)->getBlocked()){
				//create the room
				//actually enter the next room, which costs hunger, so subtract 1 hunger;
				$this->currentRoom = $this->currentRoom->getNeighbour($direction);
				$this->hunger --;
				
				return $destinationRoom->welcomePlayer();
			}else{
				return  'The door won\'t open.';
			}
			
			
		}
		
		
		
		
		
	}
?>