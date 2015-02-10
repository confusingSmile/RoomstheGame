<?php
		class QuestionRoom extends Room{	
			
			var $question;
			var $nextDificulty;
			
			function QuestionRoom(){
				$db = new DatabaseExtension();
				//nextDifficulty may become an inner class maybe...? 
				$nextDificulty = "easy";
				$question = $db->getQuestion("easy");
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
				return "welcome to a QuestionRoom.";
				//$this->askQuestion();
			}
			
			function getExitBlocked(){
				return $exitBlocked;
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				return $neighbours[$direction];
			}
			
			function registrateNeigbour($room, $direction){
				$neighboours[$direction] = $room;
			}
			
			
		}	
			
?>