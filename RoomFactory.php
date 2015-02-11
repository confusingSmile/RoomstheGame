<?php
	class RoomFactory{
	
	
		function RoomFactory(){
			
		}
		
		function createRoom($generatedItems){
			$creation = "";
			$creation = new QuestionRoom();
			
			return $creation;
		}
	}
?>