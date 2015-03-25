<html>
	<head>
		<link rel="stylesheet" type="text/css" href="csslayout.css" />
		<title>Rooms: the game</title>
	</head>
	<body>
		<?php
		session_start();
		
		if(!isset($_SESSION['user'])){
			?>
			<form action="login.php" method="post">
				<input type="text" name="username" value="lol">
				<input type="password" name="password" value="lol">
				<input type="submit" value="Log in">
			</form>
			<?php
			exit;
		}
	
		//resetting the output. 
		$output = "";
		
		//importing the nessecary classes
		include("Building.php");
		include("CommandProcessor.php");
		include("DatabaseExtension.php");
		include("Door.php");
		include("Item.php");
		include("Obstacle.php");
		include("Player.php");
		
		//include rooms
		include("room/Room.php");
		include("room/HintRoom.php");
		include("room/IntroRoom.php");
		include("room/LockedDoorRoom.php");
		include("room/ObstacleRoom.php");
		include("room/QuestionRoom.php");
		
		//starting the game
		$player = "";
		$hunger = "Hunger: ";
		$progress = "Progress: ";
		$items = null;
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
			$output = $commandProcessor->processCommand($command, $player)."<br>".$_SESSION['output'];
			$output = ltrim($output);
			$_SESSION['output'] = $output;
			$_SESSION['player'] = serialize($player);
			$hunger = "Hunger: ".$player->getHunger();
			$items = $player->getGatheredItems();
			if($player->getDoorsUnlocked() != null){
				$progress = "Progress: ".$player->getDoorsUnlocked()."/10";
			} else {
				$progress = "Progress: 0/10";
			}
			
		}
		
		?>
		<div id="container">
			<div id="headsUpDisplay">
				<div id="status">
					<div id="logout">
						<a href="logout.php">Exit Game</a>
					</div>
					<?php echo $hunger; ?>
					
					<br><?php echo $progress; ?>
				</div>
				<div id="items">
					<?php 
					if($items){
						foreach($items as $gatheredItem){
							?>
							<div class="item">
								<img src="images/<?php echo $gatheredItem->getItemName(); ?>.jpg" 
									title="<?php echo $gatheredItem->getItemName(); ?>" alt="<?php echo $gatheredItem->getItemName(); ?>"
									width="43px" height="33px">
								</img>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
			
			<div id="outputArea">
				<?php echo $output; ?>
			</div>
			
			<div id="commandIn">
			  <form action="index.php" method="post" name="gameWindow">  
				  <br><br>
				  <input type="text" id="commandTextField" name="input" value="">
				  <input type="submit" value="OK"></center><br>
			  </form>
			</div>
		</div>
	</body>
</html>
