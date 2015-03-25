<?php
	class Obstacle{
		
		private  $obstacleName;
		private  $obstacleId;
		private  $obstacleText;
		
		function Obstacle($generatedItems){
			//ask the database what the maximum is for a random obstacleId 
			$db = new DatabaseExtension();
			$possibleObstacleIds = $db->getObstaclesClearedByItems($generatedItems);
			$this->obstacleId = $possibleObstacleIds[array_rand($possibleObstacleIds)];
			$this->obstacleName = $db->getObstacleName($this->obstacleId);
			$this->obstacleText = $db->getObstacleText($this->obstacleId);
			
		}
		
		function getObstacleName(){
			return $this->obstacleName;
		}
		
		function getObstacleText(){
			return $this->obstacleText;
		}
		
		function getObstacleId(){
			return $this->obstacleId;
		}
	}
?>