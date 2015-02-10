<?php
		class LockedDoorRoom extends Room{	
			
			
			function LockedDoorRoom($number){
				$exitBlocked = true;
			}
			
			function getFoodPresent(){
				return $foodPresent;
			}
			
			function takeFood(){
				$result = 0;
				if($foodPresent == true){
					$foodPresent = false;
					$result = 1;
				}
				return $result;
			}
			
			function addFood(){
				$foodPresent = true;
			}
			
			function getItem(){
				$result=0;
				if($item != null){
					$result = $item;
				}
				return $result;
			}
			
			function takeItem(){
				$result=0;
				if($item != null){
					$result = $item;
					$item = null;
				}
				return $result;
			}
			
			function welcomePlayer(){
				return "welcome to a LockedDoorRoom.";
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				return $neighbours[$direction];
			}
			
			function getExitBlocked(){
				return $exitBlocked;
			}
			
			function registrateNeigbour($room, $direction){
				$neighboours[$direction] = $room;
			}
			
			function unlock(){
				$exitBlocked = false;
			}
		}	
			
?>