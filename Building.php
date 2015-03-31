<?php

	class Building{
		
		/*
		*	player: the player
		*	generatedItems: items present in this game of RoomsTheGame. 
		*/
		private $player;
		private $generatedItems;
		
		function Building(Player $player){
			$this->player = $player; 
		}
		
		function addGeneratedItem(Item $item){
			$this->generatedItems[] = $item;
		}
		
		function createRoom($direction){
			//rules: 
			/*
				QuestionRoom -> correct -> LockedDoorRoom
				QuestionRoom -> wrong -> 60% Hint-, 40% QuestionRoom
				ObstacleRoom -> QuestionRoom
				HintRoom -> wrong -> HintRoom 
				HintRoom -> correct -> 25% chance on ObstacleRoom 
				LockedDoorRoom -> HintRoom 
				
				
			*/
			//balance: Maybe later...
			/*
			
			*/
			$output = '';
			//a random number, used for chance-based events (in this case: which Room to generate) 
			$random = rand(0, 99);
			$creation = '';
			$obstacleRoomPossible = false; 
			$db = new DatabaseExtension();
			
			if($db->getObstaclesClearedByItems($this->generatedItems)[0] != 'error'){
				$obstacleRoomPossible = true;
			}
			
			$lastRoom = get_class($this->player->getCurrentRoom());
			$correctExit = false;
			
			if(($lastRoom == 'QuestionRoom' || $lastRoom == 'HintRoom') && $this->player->getCurrentRoom()->getAnswer() == $direction){
				$correctExit = true;
				$output = 'correct';
			}
			
			//create the Room, based on the previous Room and how that Room has been handled by the Player. 
			switch($lastRoom){
				case 'HintRoom':
					if($correctExit == true && $random < 25 && $obstacleRoomPossible == true){
						$creation = new ObstacleRoom(new Obstacle($this->generatedItems));
					} else {
						$creation = new hintRoom();
					}
					break;
				case 'IntroRoom':
					$creation = new HintRoom();
					break;
				case 'LockedDoorRoom':
					$creation = new HintRoom();
					break;
				case 'ObstacleRoom':
					$creation = new QuestionRoom();
					break;
				case 'QuestionRoom':
					if($correctExit == true){
						$creation = new LockedDoorRoom();
					} else if($random < 60){
						$creation = new HintRoom();
					} else {
						$creation = new QuestionRoom();
					}
					break;
			}
			
			//make the next room know the way back here
			$creation->registrateNeigbour($this->player->getCurrentRoom(), (($direction + 2)%4));
			//keeping track of generated items
					$generatingItem = $creation->getItem();
					if($generatingItem){
						$this->addGeneratedItem($generatingItem);
					}
			//make this room know the next room
					$this->player->getCurrentRoom()->registrateNeigbour($creation, $direction);
			
			
			
			return $output;
		}
	}

?>