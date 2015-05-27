<?php

	namespace Game\Builder; 
	use Game\Room\IntroRoom;
	
	class IntroRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $itemId, $new, $unlockedDoors, $questionHintorWhatever){
			$creation = new IntroRoom($id, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>