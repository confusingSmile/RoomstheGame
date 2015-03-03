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
			$randomPH = rand(1,2);
			$creation = "";
			
			if($randomPH == 2){
				$creation = new HintRoom();
			} else if($randomPH==1){
				$creation = new QuestionRoom();
			} else {
				$creation = new IntroRoom();
			}
			
			
			switch($random){
				
			}
			
			if(get_class($this->player->getCurrentRoom()) == "QuestionRoom"){
				$output = ($this->player->getCurrentRoom()->getQuestion()["answer"][$direction] == $this->player->getCurrentRoom()->getQuestion()["correct_answer"]);
			} else if(get_class($this->player->getCurrentRoom()) == "HintRoom"){
				$output = ($this->player->getCurrentRoom()->getAnswer() == $direction);
			}
			
			
			//create the room
			//$creation = new HintRoom();
			//make the next room know the way back here
			$creation->registrateNeigbour($this->player->getCurrentRoom(), (($direction + 2)%4));
			//keeping track of generated items
					$generatingItem = $creation->getItem();
					if($generatingItem != null){
						$this->addGeneratedItem($generatingItem);
					}
			//make this room know the next room
					$this->player->getCurrentRoom()->registrateNeigbour($creation, $direction);
			
			
			
			return $output;
		}
	}

?>