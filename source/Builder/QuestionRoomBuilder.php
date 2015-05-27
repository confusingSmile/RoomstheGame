<?php

	namespace Game\Builder; 
	use Game\Room\QuestionRoom;
	
	class QuestionRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $itemId, $new, $unlockedDoors, $questionHintorWhatever){
			$creation = new QuestionRoom($id, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>