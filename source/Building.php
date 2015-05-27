<?php
	namespace Game;
	use Game\Player\Player;
	use Game\Room\IntroRoom;
	use Game\Room\HintRoom;
	use Game\Room\LockedDoorRoom;
	use Game\Room\ObstacleRoom;
	use Game\Room\QuestionRoom;
	use Game\DatabaseExtension;
	use Game\Builder\IntroRoomBuilder;
	use Game\Builder\HintRoomBuilder;
	use Game\Builder\LockedDoorRoomBuilder;
	use Game\Builder\ObstacleRoomBuilder;
	use Game\Builder\QuestionRoomBuilder;
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
		
		//used when loading the game
		function overwriteItemsGenerated($newItems){
			$this->generatedItems = $newItems;
		}
		
		function createRoom($roomType, $gameId){
			//roomType is the classname without namespace indications
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
			$creation = '';
			$roomType = 'Game\\Builder\\'.$roomType.'Builder';
			$builder = new $roomType(); 
			echo 'new $roomType being made';
			$creation = $builder->createRoom($this->nextRoomId, $gameId, $this->db);
			$this->nextRoomId ++;
			
			
			return $creation;
		}
	}

?>