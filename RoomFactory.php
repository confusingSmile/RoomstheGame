<?php
	class RoomFactory{
	
	
		function RoomFactory(){
			
		}
		
		function createRoom($generatedItems){
			$creation = "";
			$creation = new QuestionRoom(1);
			
			return $creation;
		}
		
		function fillRoom(&$room){
			$filledRoom = $room;
			//make new Item(); 
			$item = new Item();
			//room-> additem function
			return $filledRoom;
		}
	}
?>