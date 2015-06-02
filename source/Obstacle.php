<?php
	
	namespace Game;
	
	class Obstacle{
		
		private  $obstacleName;
		private  $obstacleId;
		private  $obstacleText;
		
		function __construct($gameId, DatabaseExtension $db, $obstacleId = null){
			
			if(!$obstacleId){ 
				$possibleObstacleIds = $db->getObstaclesClearedByItems($gameId);
				$this->obstacleId = $possibleObstacleIds[array_rand($possibleObstacleIds)];
			} else {
				$this->obstacleId = $obstacleId;
			}
			
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