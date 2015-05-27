<?php

	namespace Game;
	use Game\DatabaseExtension;
	use Game\CommandProcessor;
	use Game\Player\Player;
	use Game\Room\IntroRoom;
	use Doctrine\DBAL\Connection;
	
	class Game{
		
		private $id;
		private $player;
		private $building;
		private $currentRoom;
		
		
		function __construct(DatabaseExtension $db, $id, $playerName){
			$this->id = $id;
			$this->db = $db;
			$this->player = new Player($playerName);
			$this->building = new Building($this->db);
			$this->currentRoom = new IntroRoom(1, $this->db); 
			if(!($this->db->retreiveGameData() )){
				$this->db->insertGame($this->id, $playerName);
			} else {
				$gameData = $this->db->retreiveGameData();
				$currentRoomId = $gameData['current_room_id'];
				$this->currentRoom = $this->db->getRoomFromDatabase($currentRoomId, $this->id);
				$hungerLost = 300 - $gameData['current_hunger'];
				$this-player->becomeHungrier($hungerLost);
				$this->player->overwriteDoorsUnlocked($gameData['current_doors_unlocked']);
				$this->player->overwriteItemsGathered($gameData['items_gathered']);
				$this->building->overwriteItemsGenerated($gameData['items_generated']);
			}
			
			
			if($this->currentRoom->getItem()){
				$this->db->saveIntroRoom($this->currentRoom->getItem()->getId(), $this->id);
			} else {
				$this->db->saveIntroRoom(null, $this->id);
			}
			
			$this->db->saveGame($this->id, $this->currentRoom->getID(), $this->player->getHunger(), $this->player->getDoorsUnlocked(), 
								$this->player->getGatheredItems(), $this->building->getGeneratedItems());
			
		}
		
		function __wakeup(){
			if($this->player->getHunger() < 1){
				//game over 
			} 
			if($this->player->getDoorsUnlocked() > 9){
				//victory
			}
		}
		
		function getWelcomeMessage(){
			return $this->currentRoom->welcomePlayer();
		}
		
		function getGatheredItems(){
			return $this->players->getGatheredItems();
		}
		
		function getDoorsUnlocked(){
			return $this->player->getDoorsUnlocked();
		}
		
		function newTurn($command){
			//$currentRoom is there to remember what the current Room was before moving
			$turnOutput = '';
			$roomIdBeforeTraveling = $this->currentRoom->getId();
			$turnOutput .= $this->processCommand($command);
			if($this->currentRoom->getId() != roomIdBeforeTraveling){
				$player->becomeHungrier(1);
			}
			$turnOutPut .= '<br>'.$this->getWelcomeMessage;
		}
		
		function processCommand($command){
			//the intention was for commands to start with '/', but for now it will be optional 
			//Anyway, the '/' is pretty useless. (just for style/tradition?) 
			$command = ltrim($command, '/');
			$command = ltrim($command, '<br>');
			strip_tags($command);
			$command = explode(' ', $command);
			switch($command[0]){
				case '':
					//do nothing. Error message already is the default. 
					break;
				case '?':
					return $this->showHelp();
					break;
				case 'help':
					return $this->showHelp();
					break;
				case 'down': 
					return $this->movePlayer(0);
					break;
				case 'left':
					return $player->movePlayer(1);
					break;
				case 'up':
					return $player->movePlayer(2);
					break;
				case 'right':
					return $this->player->movePlayer(3);
					break;
				case 'search':
					return $this->player->searchRoomForItem($this->currentRoom);
					break;
				case 'pick':
					if(isset($command[1])){
						if($command[1] == 'up'){
							return $this->player->obtainItem($this->currentRoom);
						}
					}
					break;
				case 'use':
					if(isset($command[1])){
						return $this->player->useItem($command[1], ($this->currentRoom instanceof Game\Room\ObstacleRoom));
					}	
					break;
				case 'unlock':
					if(isset($command[1])){
						switch($command[1]){
							case 'down':
								return $this->player->unlockKeyDoor($this->currentRoom, 0);
								break;
							case 'left':
								return $this->player->unlockKeyDoor($this->currentRoom, 1);
								break;
							case 'up':
								return $this->player->unlockKeyDoor($this->currentRoom, 2);
								break;
							case 'right':
								return $this->player->unlockKeyDoor($this->currentRoom, 3);
								break;
						}
					}
					break;
			
			}
			return 'Invalid command.<br>';
		}
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		function movePlayer($direction){
			//asks what type of Room would be next when going this direction. 
			$nextRoomType = $this->currentRoom->getNextRoom($direction);
			$potentialNextRoom = $this->building->createRoom($nextRoomType, $this->id);
			if(!$this->currentRoom->getDoor($direction)->getBlocked()){
				$destination = $this->building->getRoomRebuilt($this->currentRoom, $direction, $this->id);
				if($destination){
					$this->currentRoom = $destination;
				} else {
					$this->currentRoom = $potentialNextRoom;
					$this->db->saveRoom($this->currentRoom, $potentialNextRoom, $direction, $this->id);
				}
				
				return $this->getWelcomeMessage();
			}else{
				return  'The door won\'t open.<br>';
			}
		}
		
		function reconnect(Connection $conn){
			$this->db->reconnect($conn);
		}
		
		function showHelp(){
			return 'help';
		}
	}

?>