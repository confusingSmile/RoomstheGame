<?php
	class Obstacle{
		
		var $obstacleName;
		var $obstacleId;
		var $obstacleText;
		
		function Obstacle(){
			//ask the database what the maximum is for a random obstacleId 
			$db = new DatabaseExtension();
			$maxObstacleId=1;
			$this->obstacleId = rand(1, $maxObstacleId);
			$this->obstacleName = $db->getObstacleName($obstacleId);
			$this->obstacleText = $db->getObstacleText($obstacleId);
			
		}
		
		function getObstacleName(){
			return $this->obstacleName;
		}
		
		function getObstacleText(){
			return $this-obstacleText;
		}
	}
?>