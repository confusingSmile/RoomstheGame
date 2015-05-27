<?php
	
	namespace Game\Builder; 
	use Game\Room\ObstacleRoom;
	
	class ObstacleRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $itemId, $new, $unlockedDoors, $questionHintorWhatever){
			$obstacle = new Obstacle(array(), $db, $questionHintorWhatever);
			$creation = new ObstacleRoom($id, $obstacle, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>