<?php
	class RoomFactory{
	
	
		function RoomFactory(){
			
		}
		//Not sure what to call the 2nd parameter
		function createRoom($generatedItems, $additionalInfo = ""){
			$creation = "";
			//balance:
			/*
			
			*/
			$random = rand(0, 99);
			switch($random){
				
			}
			$creation = new QuestionRoom(1);
			
			return $creation;
		}
		
	}
?>