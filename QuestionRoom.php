<?php
		class QuestionRoom extends Room{	
			
			var $question;
			var $nextDificulty;
			
			//$dificulty: "easy" "medium" "hard"
			function QuestionRoom($dificulty){
				//$db = new DatabaseExtension();
				//nextDifficulty may become an inner class maybe...? 
				$nextDificulty = "easy";
				//$question = $db->getQuestion("easy");
			}
			
			
			
			function getItem(){
				$result=0;
				if($this->item != null){
					$result = $this->item;
				}
				return $result;
			}
			
			function takeItem(){
				$result=0;
				if($this->item != null){
					$result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			function welcomePlayer(){
				return "welcome to a QuestionRoom.";
				//$this->askQuestion();
			}
			
			function getExitBlocked(){
				return $this->exitBlocked;
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				$output = null;
				if(isset($this->neighbours[$direction])){
					$output = $this->neighbours[$direction];
				}
				return $output;
			}
			
			function registrateNeigbour(&$room, $direction){
				$this->neighbours[$direction] = $room;
			}
			
			
		}	
			
?>