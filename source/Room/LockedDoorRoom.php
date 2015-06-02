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
					
					$unlockedDoors = explode(', ', $unlockedDoors);
					
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
			
			function getNextRoom($direction, $gameId){
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
			
			function getDoor($direction){
				return $this->doors[$direction];
			}
			
			
		}	
			
?>