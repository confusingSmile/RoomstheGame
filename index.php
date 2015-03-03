<html>
	<head>
		<link rel="stylesheet" type="text/css" href="csslayout.css" />
	</head>
	<body>
	
		<?php
			session_start();
			if(isset($_SESSION['user'])){
				//avoiding the "undefined index" error by making sure $output is set.
				if(!(isset($output))){
					$output = "";
				}else{
					//well this looks useless, but this will be the introductory story
					//the IntroRoom welcoming message is more of a guide to the game
					$output = "";
				}
				//importing the nessecary classes
				include("Building.php");
				include("CommandProcessor.php");
				include("DatabaseExtension.php");
				include("Door.php");
				include("Room.php");
				include("HintRoom.php");
				include("IntroRoom.php");
				include("Item.php");
				include("LockedDoorRoom.php");
				include("Obstacle.php");
				include("ObstacleRoom.php");
				include("Player.php");
				include("QuestionRoom.php");
				
				//starting the game
				$player = "";
				$hunger = "Hunger: ";
				$progress = "Progress: 0";
				if(!isset($_SESSION['player'])){
					
					$player = new Player();
					$output = $player->getCurrentRoom()->welcomePlayer();
					$_SESSION['player'] = serialize($player);
					$_SESSION['output'] = $output;
					
				} else {
					
					$player = unserialize($_SESSION['player']);
					
					if(isset($_POST['input'])){
						
						$command = $_POST['input'];
						
					} else {
						
						$command = "";
						
					}
					
					$commandProcessor = new CommandProcessor();
					$output = $commandProcessor->processCommand($command, $player)."\n".$_SESSION['output'];
					$output = ltrim($output);
					$_SESSION['output'] = $output;
					$_SESSION['player'] = serialize($player);
					$hunger = "Hunger: ".$player->getHunger();
					$progress = "Progress: ".$player->getDoorsUnlocked()."/10";
				}
				
				
				echo "<div id=\"commandIn\">
						  <div id=\"headsUpDisplay\">
							<div id=\"logout\">
								<a href=\"logout.php\">Exit Game</a>
							</div>
							".$hunger."
							
							<br>".$progress."
						  </div>
							  <form action=\"index.php\" method=\"post\">
								  <center><textarea cols=\"100\" rows=\"20\" readonly>".$output."</textarea><br><br>
								  <input type=\"text\" id=\"commandTextField\" name=\"input\" value=\"\">
								  <input type=\"submit\" value=\"OK\"></center><br>
							  </form>
					  </div>";
			
			
			} else {
					
				echo "<form action=\"login.php\" method=\"post\">
						  <input type=\"text\" name=\"username\" value=\"lol\">
						  <input type=\"password\" name=\"password\" value=\"lol\">
						  <input type=\"submit\" value=\"Log in\">
					  </form>";
				
			}
		
			
		?>
		
		
	</body>
</html>
