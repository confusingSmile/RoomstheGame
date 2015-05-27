<?php
	namespace Game;
	use Game\Player\Player;
	use Game\Room\IntroRoom;
	use Game\Room\HintRoom;
	use Game\Room\LockedDoorRoom;
	use Game\Room\ObstacleRoom;
	use Game\Room\QuestionRoom;
	use Game\DatabaseExtension;
	class Building{
		
		/*
		*	player: the player
		*	generatedItems: items present in this game of RoomsTheGame. 
		*/
		private $player;
		private $generatedItems;
		private $db;
		
		function __construct(Player $player, DatabaseExtension $db){
			$this->player = $player; 
			$this->db = $db;
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
			
			if($this->db->getObstaclesClearedByItems($this->generatedItems)[0] != 'error'){
				$obstacleRoomPossible = true;
			}
			
			$lastRoom = get_class($this->player->getCurrentRoom());
			$correctExit = false;
			
			if(($lastRoom == 'Game\Room\QuestionRoom' || $lastRoom == 'Game\Room\HintRoom') && $this->player->getCurrentRoom()->getAnswer() == $direction){
				$correctExit = true;
				$output = 'correct';
			}
			
			//create the Room, based on the previous Room and how that Room has been handled by the Player. 
			switch($lastRoom){
				case 'Game\Room\HintRoom':
					if($correctExit == true && $random < 25 && $obstacleRoomPossible == true){
						$creation = new ObstacleRoom(new Obstacle($this->generatedItems, $this->db));
					} else {
						$creation = new hintRoom($this->db);
					}
					break;
				case 'Game\Room\IntroRoom':
				case 'Game\Room\LockedDoorRoom':
					$creation = new HintRoom($this->db);
					break;
				case 'Game\Room\ObstacleRoom':
					$creation = new QuestionRoom($this->db);
					break;
				case 'Game\Room\QuestionRoom':
					if($correctExit == true){
						$creation = new LockedDoorRoom($this->db);
					} else if($random < 60){
						$creation = new HintRoom($this->db);
					} else {
						$creation = new QuestionRoom($this->db);
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