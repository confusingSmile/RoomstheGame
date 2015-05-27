<?php
	namespace Game;
	use Game\Player\Player;
	use Game\Building;
	use Game\CommandProcessor;
	use Doctrine\DBAL\Connection;
	
	class Game{
		
		/**
		*	$db: the database
		*	$player: the player
		*/
		private $player;
		private $building; 
		
		function __construct($db, $player){
			$this->building = new Building($player, $this->db, $this);
			$this->Player->setBuilding($this->building);
		}
		
		function registratePlayer($player){
			$this->player = $player;
		}
		
		function __wakeup(){
			if($this->player->getHunger() < 1){
				over();
			} 
			if($this->player->getDoorsUnlocked() > 9){
				wonTheGame();
			}
		}
		
		function loadGame($username, Connection $conn){
			//request game
			//reconstruct game
			
		} 
		
		
		function turn($playerInput){
			return $this->commandProcessor->processCommand($playerInput, $this->player);
		}
		
		function over(){
			//over, Game over. 
			
		} 
		function wonTheGame(){
			
		}
	}

?>