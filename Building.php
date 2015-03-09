<?php

	class Building{
		
		/*
		*	player: the player
		*	generatedItems: items that the player can obtain or has obtained
		*/
		var $player;
		var $generatedItems;
		
		function Building($player){
			$this->player = $player; 
		}
		
		function addGeneratedItem($item){
			$this->generatedItems[] = $item;
		}
		
		function createRoom($direction){
			$output = "";
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
			$creation = "";
			$db = new DatabaseExtension();
			if($db->getObstaclesClearedByItems($this->generatedItems)[0] != "error"){
				$creation = new ObstacleRoom(new Obstacle($this->generatedItems));
			}else {
				$creation = new HintRoom();
			}
			$correctExit = false;
			$lastRoom = get_class($this->player->getCurrentRoom());
			
			
			if(($lastRoom == "QuestionRoom" || $lastRoom == "HintRoom") && $this->player->getCurrentRoom()->getAnswer() == $direction){
				$correctExit = true;
				$output = "correct";
			}
			
			switch($random){
				
			}
			
			
			//create the room
			//$creation = new HintRoom();
			//make the next room know the way back here
			$creation->registrateNeigbour($this->player->getCurrentRoom(), (($direction + 2)%4));
			//keeping track of generated items
					$generatingItem = $creation->getItem();
					if($generatingItem != null){
						$this->addGeneratedItem($generatingItem->getItemName());
					}
			//make this room know the next room
					$this->player->getCurrentRoom()->registrateNeigbour($creation, $direction);
			
			
			
			return $output;
		}
	}

?>