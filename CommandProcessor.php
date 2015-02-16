<?php
	class CommandProcessor{
		
		
		
		function CommandProcessor(){
			
		}
		
		function processCommand($command, $player){
			$output = "Invalid command.";
			$command = ltrim($command, "/");
			strip_tags($command);
			$command = explode(" ", $command);
			switch($command[0]){
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
					// player search $output = ;
					break;
				case "use":
					//output = not really a command
					//doing something with whatever comes after use (expecting item name) 
					break;
				case "unlock":
					$output = $player->unlockKeyDoor();
					break;
			
			}
			return $output;
		}
		
		
		function showHelp(){
			return "help";
		}
		
		
	}
?>
