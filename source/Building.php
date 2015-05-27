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
		*	lastRoom: the last room so far
		*	generatedItems: items present in this game of RoomsTheGame. 
		*/
		private $lastRoom;
		private $generatedItems = array();
		private $nextRoomId;
		private $db;
		
		function __construct(DatabaseExtension $db){
			$this->db = $db;
			$this->nextRoomId = 2;
		}
		
		function addGeneratedItem(Item $item){
			$this->generatedItems[] = $item;
		}
		
		function getGeneratedItems(){
			return $this->generatedItems;
		}
		
		function getRoomRebuilt($roomId, $direction, $gameId){
			$dataToBuildFrom = $this->db->getNeighbour($roomId, $direction, $gameId);
			
			
		}
		
		function createRoom($roomType, $gameId){
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
			
			if(($lastRoom == 'Game\Room\QuestionRoom' || $lastRoom == 'Game\Room\HintRoom') && $this->player->getCurrentRoom()->getAnswer() == $direction){
				$correctExit = true;
				$output = 'correct<br>';
			}
			
			//create the Room, based on the previous Room and how that Room has been handled by the Player. 
			switch($lastRoom){
				case 'Game\Room\HintRoom':
					if($correctExit == true && $random < 25 && $obstacleRoomPossible == true){
						$creation = new ObstacleRoom($this->nextRoomId, new Obstacle($this->generatedItems, $this->db));
					} else {
						$creation = new hintRoom($this->nextRoomId, $this->db);
					}
					break;
				case 'Game\Room\IntroRoom':
				case 'Game\Room\LockedDoorRoom':
					$creation = new HintRoom($this->nextRoomId, $this->db);
					break;
				case 'Game\Room\ObstacleRoom':
					$creation = new QuestionRoom($this->nextRoomId, $this->db);
					break;
				case 'Game\Room\QuestionRoom':
					if($correctExit == true){
						$creation = new LockedDoorRoom($this->nextRoomId, $this->db);
					} else if($random < 60){
						$creation = new HintRoom($this->nextRoomId, $this->db);
					} else {
						$creation = new QuestionRoom($this->nextRoomId, $this->db);
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