<?php
		class IntroRoom extends Room{	
			
			function IntroRoom(){
				
			}
			
			
			function takeItem(){
				$result=0;
				if($item != null){
					$result = $item;
					$item = null;
				}
				return $result;
			}
			
			
			function getItem(){
				$result=0;
				if($item != null){
					$result = $item;
				}
				return $result;
			}
			
			function welcomePlayer(){
				return "welcome to an IntroRoom.";
				
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
			
			
		}	
			
?>