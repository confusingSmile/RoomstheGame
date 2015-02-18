<?php
		class ObstacleRoom extends Room{	
		
			var $obstacle;
			
			function ObstacleRoom(){
				$this->obstacle = new Obstacle();
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door(true);
				}
				
				$random = rand(1, 2);
				if($random == 1){
					$this->item = new Item();
				}
			}
			
			function getObstacle(){
				return $this->obstacle->getObstacleName();
			}
			
			function clearObstacle($itemName, &$room){
				$result = "No obstacle to clear.";
				$db = new DatabaseExtension();
				$result = $db -> getItemUseResult($itemName, $room);
				return $result;
			}
			
			
			function getItem(){
				$result=null;
				if($this->item != null){
					$result = $this->item;
				}
				return $result;
			}
			
			function takeItem(){
				$result=0;
				if($this->item != null){
					$this->result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			function welcomePlayer(){
				$output="";
				$output = $this->Obstacle->getObstacleText();
				return $output; 
				
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				$output = null;
				if(isset($this->neighbours[$direction])){
					$output = $this->neighbours[$direction];
				}
				return $output;
			}
			
			function getDoor($direction){
				return $this->doors[$direction];
			}
			
			function registrateNeigbour(&$room, $direction){
				$this->neighbours[$direction] = $room;
			}
			
		}	
			
?>