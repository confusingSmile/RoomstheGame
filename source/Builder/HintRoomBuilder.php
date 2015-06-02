<?php

	namespace Game\Builder; 
	use Game\Room\HintRoom;
	
	class HintRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $new = true, $questionHintorWhatever = null, $itemId = null, $unlockedDoors = null){
			$creation = new HintRoom($id, $db, $new, $questionHintorWhatever, $itemId, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>