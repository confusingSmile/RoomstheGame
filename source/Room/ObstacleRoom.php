<?php
		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\Obstacle;
		use Game\DatabaseExtension;
		
		class ObstacleRoom extends Room{	
		
		
			//$clear: whether or not the obstacle has been cleared. 
			private $obstacle;
			private $clear; 
			
			function __construct($id, Obstacle $obstacle, DatabaseExtension $db, $thisRoomIsNew = true, $itemId = null, $questionHintorWhatever = null, 
								 $unlockedDoors = null){
				$this->id = $id;
				$this->obstacle = $obstacle;
				$this->db = $db;
				$this->clear = false;
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
			
			function getObstacle(){
				return $this->obstacle;
			}
			
			function getId(){
				return $this->id;
			}
			
			function clearObstacle($itemName){
				$result = "No obstacle to clear.";
				if($this->clear == false){
					$effect = $this->db->getItemUseResult($itemName, $this->obstacle);
					if($effect == 2){
							$result = "That...may not have been a good idea. Now you're Game Over.";

							
					} else if($effect == 1){
						$result = "Obstacle cleared";
						for($i=0;$i<4;$i++){
							$uselessVariable = $this->doors[$i]->unblock();
						}
						$this->clear = true;
					}
				}
				
				return $result;
			}
			
			
			function getItem(){
				if(isset($this->item)){
					return $this->item;
				}
				return 0;
			}
			
			function reconstruct($room_id, $unlockedDoors, $itemId, $questionHintorWhatever, $db){
				
			}
			
			function getNextRoom($direction, $gameId){
				return 'QuestionRoom';
			}
			
			function getQuestionHintOrWhatever(){
				return $this->obstacle->getObstacleId();
			}
			
			function takeItem(){
				$result=0;
				if($this->item){
					$this->result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			function welcomePlayer(){
				$output="".$this->obstacle->getObstacleText()."<--".$this->obstacle->getObstacleId()."";
				return $output; 
				
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