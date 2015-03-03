<?php
		class QuestionRoom extends Room{	
			
			var $question;
			
			function QuestionRoom(){
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
				}
				$random = rand(1, 2);
				if($random == 1){
					$this->item = new Item();
				}
				$db = new DatabaseExtension();
				$this->question = $db->getQuestion(); 
				shuffle($this->question["answer"]); 
				
			}
			
			function getQuestion(){
				return $this->question;
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
				$result = "welcome to a QuestionRoom. this room's question is: ".$this->question["question"]."\n";
				$directions = array("down: ", "left: ", "up: ", "right: ");
				for($i=0;$i<4;$i++){
					if(isset($this->question["answer"][$i]) && $this->question["answer"][$i] != null){
						$result .= $directions[$i]."".$this->question["answer"][$i].", ";
					}
				}
				$result = rtrim($result, ", ");
				return $result.".";
			}
			
			function getDoor($direction){
				return $this->doors[$direction];
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
				if(isset($this->question["answer"][$direction])){
					$this->question["answer"][] = $this->question["answer"][$direction];
					$this->question["answer"][$direction] = null;
				}
			}
			
			
			
		}	
			
?>