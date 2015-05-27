<?php

	namespace Game\Builder; 
	use Game\Room\HintRoom;
	
	class HintRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $new = true, $itemId = null, $unlockedDoors = null, $questionHintorWhatever = null){
			$creation = new HintRoom($id, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>