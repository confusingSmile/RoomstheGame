<?php
	class CommandProcessor{
		
		
		
		function CommandProcessor(){
			
		}
		
		function processCommand($command, $player){
			$output = "Invalid command.";
			//the intention was for commands to start with "/", but for now it will be optional 
			//Anyway, the "/" is pretty useless. (just for style/tradition?) 
			$command = ltrim($command, "/");
			$command = ltrim($command, "<br>");
			strip_tags($command);
			$command = explode(" ", $command);
			switch($command[0]){
				case "":
					$output="";
					break;
				case "?":
					$output = $this->showHelp();
					break;
				case "help":
					$output = $this->showHelp();
					break;
				case "down":
					$output = $player->travel(0);
					break;
				case "left":
					$output = $player->travel(1);
					break;
				case "up":
					$output = $player->travel(2);
					break;
				case "right":
					$output = $player->travel(3);
					break;
				case "search":
					$output = $player->searchRoomForItem();
					break;
				case "pick":
					if(isset($command[1])){
						if($command[1] == "up"){
							$output = $player->obtainItem();
						}
					}
					break;
				case "use":
					$output="";
					if(isset($command[1])){
						$output = $player->useItem($command[1]);
					}	
					break;
				case "unlock":
					if(isset($command[1])){
						switch($command[1]){
							case "down":
								$output = $player->unlockKeyDoor(0);
								break;
							case "left":
								$output = $player->unlockKeyDoor(1);
								break;
							case "up":
								$output = $player->unlockKeyDoor(2);
								break;
							case "right":
								$output = $player->unlockKeyDoor(3);
								break;
						}
					}
					break;
			
			}
			return $output;
		}
		
		
		function showHelp(){
			return "help";
		}
		
		
	}
?>
