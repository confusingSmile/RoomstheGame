<?php

	namespace Game;
	use Game\DatabaseExtension;
	use Game\CommandProcessor;
	use Game\Player\Player;
	use Game\Room\IntroRoom;
	use Doctrine\DBAL\Connection;
	
	class Game{
		
		private $id;
		private $db;
		private $player;
		private $building;
		private $currentRoom;
		
		
		function __construct(DatabaseExtension $db, $id, $playerName){
			$this->id = $id;
			$this->db = $db;
			$this->player = new Player($playerName);
			$this->building = new Building($this->db);
			$this->currentRoom = new IntroRoom(1, $this->db); 
			if(!($this->db->retreiveGameData($id) )){
				$this->db->insertGame($this->id, $playerName);
			} else {
				$gameData = $this->db->retreiveGameData($this->id);
				$currentRoomId = $gameData['current_room_id'];
				$this->currentRoom = $this->db->getRoomFromDatabase($currentRoomId, $this->id);
				$hungerLost = 300 - $gameData['current_hunger'];
				$this->player->becomeHungrier($hungerLost);
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
		
		function getId(){
			return $this->id;
		}
		
		function getGatheredItems(){
			return $this->player->getGatheredItems();
		}
		
		function getHunger(){
			return $this->player->getHunger();
		}
		
		function getDoorsUnlocked(){
			return $this->player->getDoorsUnlocked();
		}
		
		function newTurn($command){
			//this method is mainly for processing a command and saving the game. 
			$turnOutput = $this->processCommand($command);
			$this->db->saveGame($this->id, $this->currentRoom->getID(), $this->player->getHunger(), $this->player->getDoorsUnlocked(), 
								$this->player->getGatheredItems(), $this->building->getGeneratedItems());
			
			return $turnOutput;
		}
		
		function processCommand($command){
			//the intention was for commands to start with '/', but for now it will be optional 
			//Anyway, the '/' is pretty useless. (just for style/tradition?) 
			$command = ltrim($command, '/');
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
					return $this->movePlayer(1);
					break;
				case 'up':
					return $this->movePlayer(2);
					break;
				case 'right':
					return $this->movePlayer(3);
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
						return $this->player->useItem($command[1], ($this->currentRoom));
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
			return 'Invalid command.'.$command[0].'<br>';
		}
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		function movePlayer($direction){
			//asks what type of Room would be next when going this direction. 
			$nextRoomType = $this->currentRoom->getNextRoom($direction, $this->id);
			$cloneCurrentRoom = clone($this->currentRoom);
			$destination = $this->db->getNeighbour($this->currentRoom->getId(), $direction, $this->id);
			
			//if moving back to previous rooms
			if($destination){
				
				$this->currentRoom = $destination;
				return $this->getWelcomeMessage();
				
			}else{
				if(!$this->currentRoom->getDoor($direction)->getBlocked()){
					
					$this->currentRoom = $this->building->createRoom($nextRoomType, $this->id);
					$this->db->saveRoom($cloneCurrentRoom, $this->currentRoom, $direction, $this->id);
					$this->player->becomeHungrier(1);
					return $this->getWelcomeMessage();
				} else {
					return  'The door won\'t open.<br>';
				}
				
			}
			
			return 'error. This statemen shouldn\'t be reached';
		}
		
		function reconnect(Connection $conn){
			$this->db->reconnect($conn);
		}
		
		function showHelp(){
			return 'help';
		}
	}

?>