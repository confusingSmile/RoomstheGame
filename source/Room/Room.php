<?php

	namespace Game\Room;
	abstract class Room{
		
		
		/*
		*	neighbours: an array of the four Rooms next to this Room. 
		*	item: an Item, if present; null if there are no Items
		*	doors: the doors in this Room
		*/
		private $neighbours;
		private $item;
		private $doors;
		private $id;
		
		
		abstract function getItem();
		
		abstract function takeItem();
		
		abstract function getDoor($direction);
		
		abstract function getId();
		
		abstract function registrateNeigbour(Room $room, $direction);
		
		abstract function getNextRoom($direction);
		
		abstract function reconstruct($room_id, $unlockedDoors, $itemId, $questionHintorWhatever, $db);
		
		//OCP, am I doing it right? 
		abstract function getQuestionHintOrWhatever(); 
		
		//executes a script; normally some flavour text, or the introduction to the game. 
		abstract function welcomePlayer();
		
		//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
		abstract function getNeighbour($direction);
		
		
		
	}
?>