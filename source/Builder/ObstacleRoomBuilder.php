<?php
	
	namespace Game\Builder; 
	use Game\Room\ObstacleRoom;
	use Game\Obstacle;
	
	class ObstacleRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $new = true, $questionHintorWhatever = null, $itemId = null, $unlockedDoors = null){
			$obstacle = new Obstacle($gameId, $db, $questionHintorWhatever);
			$creation = new ObstacleRoom($id, $obstacle, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>