<html>
	<head>
		<link rel="stylesheet" type="text/css" href="csslayout.css" />
		<title>Rooms: the game</title>
	</head>
	<body>
		<?php
		
		require('vendor/autoload.php');
		include('config/config_db_local.php');
		use Doctrine\DBAL\Configuration;
		use Doctrine\DBAL\DriverManager;
		use Game\Game;
		use Game\Room\QuestionRoom;
		use Game\Room\HintRoom;
		use Game\Room\IntroRoom;
		use Game\Room\ObstacleRoom;
		use Game\Room\LockedDoorRoom;
		use Game\Player\Player;
		use Game\CommandProcessor;
		use Game\DatabaseExtension;
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
		$output = '';
		
		//starting the game
		$player = '';
		$name = $_SESSION['user'];
		$hunger = 'Hunger: ';
		$progress = 'Progress: ';
		$items = null;
		if(!isset($_SESSION['game'])){
			
			$conn = DriverManager::getConnection($connectionParams, new Configuration());
			$db = new DatabaseExtension($conn);
			$id = $db->generateGameId($name);
			$game = new Game($db, $id, $name);
			$output = $game->getWelcomeMessage();
			$_SESSION['game'] = serialize($game);
			$_SESSION['output'] = $output;
			
		} else {
			
			$game = unserialize($_SESSION['game']);
			
			$conn = DriverManager::getConnection($connectionParams, new Configuration());
			$game->reconnect($conn);
			
			if(isset($_POST['input'])){
				
				$command = $_POST['input'];
				
			} else {
				
				$command = "";
				
			}
			
			$output = $game->processCommand($command).'<br>'.$_SESSION['output'];
			//for some reason '\n' needs to be specified in this function. 
			$output = ltrim($output, "\n"); //TODO validate nessicity
			$_SESSION['output'] = $output;
			$_SESSION['game'] = serialize($game);
			$hunger = 'Hunger: '.$game->getHunger();
			$items = $game->getGatheredItems();
			if($game->getDoorsUnlocked() != null){
				$progress = 'Progress: '.$game->getDoorsUnlocked().'/10';
			} else {
				$progress = 'Progress: 0/10';
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
