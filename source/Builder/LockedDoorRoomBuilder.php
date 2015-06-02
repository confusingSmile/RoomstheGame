<?php

	namespace Game\Builder; 
	use Game\Room\LockedDoorRoom;
	
	class LockedDoorRoomBuilder{
		
		function __construct(){
			
		}
		
		function createRoom($id, $gameId, $db, $new = true, $questionHintorWhatever = null, $itemId = null, $unlockedDoors = null){
			$creation = new LockedDoorRoom($id, $db, $new, $itemId, $questionHintorWhatever, $unlockedDoors); 
			return $creation; 
		}
		
	}

?>