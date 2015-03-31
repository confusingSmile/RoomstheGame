<?php
	class CommandProcessor{
		
		
		
		function CommandProcessor(){
			
		}
		
		function processCommand($command, Player $player){
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
					return $player->travel(0);
					break;
				case 'left':
					return $player->travel(1);
					break;
				case 'up':
					return $player->travel(2);
					break;
				case 'right':
					return $player->travel(3);
					break;
				case 'search':
					return $player->searchRoomForItem();
					break;
				case 'pick':
					if(isset($command[1])){
						if($command[1] == 'up'){
							return $player->obtainItem();
						}
					}
					break;
				case 'use':
					if(isset($command[1])){
						return $player->useItem($command[1]);
					}	
					break;
				case 'unlock':
					if(isset($command[1])){
						switch($command[1]){
							case 'down':
								return $player->unlockKeyDoor(0);
								break;
							case 'left':
								return $player->unlockKeyDoor(1);
								break;
							case 'up':
								return $player->unlockKeyDoor(2);
								break;
							case 'right':
								return $player->unlockKeyDoor(3);
								break;
						}
					}
					break;
			
			}
			return 'Invalid command.';
		}
		
		
		function showHelp(){
			return 'help';
		}
		
		
	}
?>
