<?php
		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\DatabaseExtension;
		
		class ObstacleRoom extends Room{	
		
		
			//$clear: whether or not the obstacle has been cleared. 
			private $obstacle;
			private $clear; 
			
			function __construct($obstacle, DatabaseExtension $db, $id){
				$this->obstacle = $obstacle;
				$this->ID = $id;
				$this->db = $db;
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
				}
				
				$random = rand(1, 2);
				if($random == 1){
					$this->item = new Item($this->db);
				}
			}
			
			function getObstacle(){
				return $this->obstacle;
			}
			
			function clearObstacle($itemName){
				$result = "No obstacle to clear.";
				if($this->clear == false){
					$effect = $this->db -> getItemUseResult($itemName, $this->obstacle);
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