<html>
	<head>
		<link rel="stylesheet" type="text/css" href="csslayout.css" />
		<script language=javascript>
		//keeping a textarea scrolled down doesn't seem to be working yet
			document.getElementById("commandTextField").scrollTop = document.getElementById("commandTextField").scrollHeight;
		</script>
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
				//include("Building.php");
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
				include("RoomFactory.php");
				include("Player.php");
				include("QuestionRoom.php");
				
				//starting the game
				$player = "";
				$hunger = "Hunger: ";
				if(!isset($_SESSION['player'])){
					
					$player = new Player();
					$output = $player->getCurrentRoom()->welcomePlayer();
					$_SESSION['player'] = serialize($player);
					$_SESSION['output'] = $output;
					
				} else {
					
					$player = unserialize($_SESSION['player']);
					$command = $_POST['input'];
					
					if(isset($_POST['input'])){
						
						$command = $_POST['input'];
						
					} else {
						
						$command = "";
						
					}
					
					$commandProcessor = new CommandProcessor();
					$output = $_SESSION['output']."\n".$commandProcessor->processCommand($command, $player);
					$_SESSION['output'] = $output;
					$_SESSION['player'] = serialize($player);
					$hunger = "Hunger: ".$player->getHunger();
				}
				
				
				echo "<div id=\"commandIn\">
						  <div id=\"headsUpDisplay\">
							".$hunger."
						  </div>
							  <form action=\"index.php\" method=\"post\">
								  <center><textarea cols=\"100\" rows=\"20\">".$output."</textarea><br><br>
								  <input type=\"text\" id=\"commandTextField\" name=\"input\" value=\"\">
								  <input type=\"submit\" value=\"OK\"></center><br>
							  </form>
					  </div>";
			
			
			} else {
					
				echo "<form action=\"login.php\" method=\"post\">
						  <input type=\"text\" name=\"username\" value=\"\">
						  <input type=\"text\" name=\"password\" value=\"\">
						  <input type=\"submit\" value=\"Log in\">
					  </form>";
				
			}
		
			
		?>
		
		
	</body>
</html>
