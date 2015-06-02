<?php

	namespace Game\Builder; 
	use Game\Room\IntroRoom;
	
	class IntroRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $new = true, $questionHintorWhatever = null, $itemId = null, $unlockedDoors = null){
			$creation = new IntroRoom($id, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>