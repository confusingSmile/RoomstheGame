<?php
		class ObstacleRoom extends Room{	
		
			var $obstacle;
			
			function ObstacleRoom(){
				$this->obstacle = new obstacle();
				$this->exitBlocked = true;
			}
			
			function getObstacle(){
				return $this->obstacle->getObstacleName();
			}
			
			
			function getItem(){
				$result=0;
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
				return "welcome to an ObstacleRoom.";
				//something stating what the obstacle entails. 
				
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				$output = null;
				if(isset($this->neighbours[$direction])){
					$output = $this->neighbours[$direction];
				}
				return $output;
			}
			
			function getExitBlocked(){
				return $this->exitBlocked;
			}
			
			function registrateNeigbour(&$room, $direction){
				$this->neighbours[$direction] = $room;
			}
			
			function generateNeighbours(){
			
			}
		}	
			
?>