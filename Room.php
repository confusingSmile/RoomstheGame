<?php
	abstract class Room{
		
		
		/*
		*	neighbours: an array of the four Rooms next to this Room. 
		*	item: an Item, if present; null if there are not Items
		*	foodPresent: boolean indicating whether or not food is present in this room
		*	exitBlocked: boolean indicating whether or not the player is trapped in this room
		*/
		var $neighbours;
		var $item;
		var $exitBlocked = false;
		
		
		abstract function getItem();
		
		abstract function takeItem();
		
		abstract function getExitBlocked();
		
		abstract function registrateNeigbour($room, $direction);
		
		//executes a script; normally some flavour text, or the introduction to the game. 
		abstract function welcomePlayer();
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		abstract function getNeighbour($direction);
		
		
		
	}
?>