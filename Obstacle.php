<?php
	class Obstacle{
		
		var $obstacleName;
		var $obstacleId;
		
		function Obstacle(){
			//ask the database what the maximum is for a random obstacleId 
			//$maxObstacleId=;
			//$obstacleId = rand(1, $maxObstacleId);
			//$obstacleName=->getObstacleName($obstacleId);
		}
		
		function getObstacleName(){
			return $obstacleName;
		}
	}
?>