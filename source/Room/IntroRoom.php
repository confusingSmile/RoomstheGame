<?php

		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\DatabaseExtension;
		
		class IntroRoom extends Room{	
			
			function __construct($id, DatabaseExtension $db, $thisRoomIsNew = true, $itemId = null, $questionHintorWhatever = null, 
								 $unlockedDoors = null){
				$this->id = $id;
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
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
			
			function takeItem(){
				$result=0;
				if($this->item){
					$result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			function getNextRoom($direction, $gameId){
				return 'HintRoom';
			}
			
			function getItem(){
				if(isset($this->item)){
					return $this->item;
				}
				return 0;
			}
			
			function getQuestionHintOrWhatever(){
				return null;
			}
			
			function welcomePlayer(){
				return "welcome to an IntroRoom.";
				
			}
			
			function getDoor($direction){
				return $this->doors[$direction];
			}
			
			
		}	
			
?>