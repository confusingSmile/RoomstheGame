<?php

	namespace Game\Builder; 
	use Game\Room\HintRoom;
	
	class HintRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $itemId, $new, $unlockedDoors, $questionHintorWhatever){
			$creation = new HintRoomBuilder($id, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>