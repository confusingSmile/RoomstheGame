<?php
	
	namespace Game;
	
	class Obstacle{
		
		private  $obstacleName;
		private  $obstacleId;
		private  $obstacleText;
		
		function __construct($generatedItems, DatabaseExtension $db){
			//ask the database what the maximum is for a random obstacleId 
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