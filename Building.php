<?php

	class Building{
		
		var $player;
		
		function Building($player){
			$this->player = $player; 
		}
		
		function createRoom($direction){
			$creation = "";
			//rules: 
			/*
				QuestionRoom -> correct -> LockedDoorRoom
				QuestionRoom -> wrong -> QuestionRoom
				ObstacleRoom -> QuestionRoom
				HintRoom -> wrong -> HintRoom 
				HintRoom -> correct -> 25% chance on ObstacleRoom 
				LockedDoorRoom -> HintRoom 
				
				
			*/
			//balance:
			/*
			
			*/
			$random = rand(0, 99);
			switch($random){
				
			}
			$creation = new LockedDoorRoom();
			
			return $creation;
		}
	}

?>