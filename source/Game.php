<?php

	namespace Game;
	use Game\DatabaseExtension;
	use Game\CommandProcessor;
	use game\Player\Player;
	
	class Game{
		
		private $id;
		private $player;
		private $building;
		private $currentRoom;
		
		
		function __construct(DatabaseExtension $db){
			$this->db = $db;
			$this->player = new Player();
			$this->building = new Building($this->player, $this->db);
			$this->currentRoom = new IntroRoom(1, $this->db);
			$this->currentRoom->welcomePlayer();
		}
		
		function __wakeup(){
			//reconstruct current room, game data, Player, items, doors
		}
		
		function newTurn($command){
			//data new Room
			$this->processCommand($command);
			//current room is next room
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
			return 'Invalid command.';
		}
		
		function movePlayer(){
			//TODO code
		}
		
		function reconnect(Connection $conn){
			$this->db->reconnect($conn);
		}
		
		function showHelp(){
			return 'help';
		}
	}

?>