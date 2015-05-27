<?php
		
		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\DatabaseExtension;
		
		class LockedDoorRoom extends Room{	
			
			
			function __construct($id, DatabaseExtension $db, $thisRoomIsNew = true, $itemId = null, $questionHintorWhatever = null, 
								 $unlockedDoors = null){
				$this->id = $id;
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door(true);
				}
				if($unlockedDoors){
					
					foreach($unlockedDoors as $doorNumber){
						$this->getDoor($doorNumber)->unblock();
					}
				}
				
				if($thisRoomIsNew){
					
					$random = rand(1, 2);
					if($random == 1){
						$this->item = new Item($db);
					}
					
				} else if($itemId){
					$this->item = new Item($db, $itemId);
				}
			}
			
			function getId(){
				return $this->id;
			}
			
			function getItem(){
				if(isset($this->item)){
					return $this->item;
				}
				return 0;
			}
			
			function reconstruct($room_id, $unlockedDoors, $itemId, $questionHintorWhatever, $db){
				
			}
			
			function getNextRoom($direction){
				return 'HintRoom';
			}
			
			function getQuestionHintOrWhatever(){
				return null;
			}
			
			function takeItem(){
				$result=0;
				if($this->item){
					$result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			function welcomePlayer(){
				return "welcome to a LockedDoorRoom.";
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				if(isset($this->neighbours[$direction])){
					return $this->neighbours[$direction];
				}
				return null;
			}
			
			function getDoor($direction){
				return $this->doors[$direction];
			}
			
			function registrateNeigbour(Room $room, $direction){
				$this->neighbours[$direction] = $room;
			}
			
			
		}	
			
?>