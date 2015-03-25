<?php
		class ObstacleRoom extends Room{	
		
		
			//$clear: whether or not the obstacle has been cleared. 
			private $obstacle;
			private $clear; 
			
			function ObstacleRoom($obstacle){
				$this->obstacle = $obstacle;
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
				}
				
				$random = rand(1, 2);
				if($random == 1){
					$this->item = new Item();
				}
			}
			
			function getObstacle(){
				return $this->obstacle;
			}
			
			function clearObstacle($itemName){
				$result = "No obstacle to clear.";
				if($this->clear == false){
					$db = new DatabaseExtension();
					$effect = $db -> getItemUseResult($itemName, $this->obstacle);
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
				if($this->item){
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