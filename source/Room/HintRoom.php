<?php
		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\DatabaseExtension;
		
		class HintRoom extends Room{
			
			private $hint;
			private $answer;
			//because for getNextRoom I will need the database. 
			private $db;
			
			
			function __construct($id, DatabaseExtension $db, $thisRoomIsNew = true, $itemId = null, $questionHintorWhatever = null, 
								 $unlockedDoors = null){
				$this->id = $id;
				$this->db = $db;
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
				
				if(!$questionHintorWhatever){
					$hintData = $this->db->getHint();
					$this->hint = $hintData["text"];
					$this->answer = $hintData["answer"];
				} else{
					$hintParts = explode($questionHintorWhatever, 'b.b');
					$this->hint = $hintParts[0];
					$this->answer = $hintParts[1]; 
				}				
				
			}
			
			function getQuestionHintOrWhatever(){
				return $this->hint.'b.b'.$this->answer;
			}
			
			function getId(){
				return $this->id;
			}
			
			function hintToString(){
				$hintString = $this->hint.', '.$this->answer;
				return $hintString;
			}
			
			function getAnswer(){
				return $this->answer;
			}
			
			function takeItem(){
				$result=0;
				if($this->item){
					$result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			function reconstruct($room_id, $unlockedDoors, $itemId, $questionHintorWhatever, $db){
				
			}
			
			function getNextRoom($direction){
				$obstacleRoomPossible = $this->db->obstacleRoomPossible();
				$random = rand(0, 99);
				
				if($random > 24 || $obstacleRoomPossible != true || $direction != $this->answer){
					return 'HintRoom';
				} 
				
				return 'ObstacleRoom';
				
			}
			
			function getItem(){
				if(isset($this->item)){
					return $this->item;
				}
				return 0;
			}
			
			function welcomePlayer(){
				return "welcome to a HintRoom.".$this->hint;
			}
			
			function getDoor($direction){
				return $this->doors[$direction];
			}
			
			function registrateNeigbour(Room $room, $direction){
				$this->neighbours[$direction] = $room;
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				if(isset($this->neighbours[$direction])){
					return $this->neighbours[$direction];
				}
				return null;
			}
			
		}	
			
?>