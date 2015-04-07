<?php
		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\DatabaseExtension;
		
		class QuestionRoom extends Room{	
			
			private $question; 
			private $answer;
			
			function __construct(){
				
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
				
				$this->answer = "0";
				for($j=0;$j<3;$j++){
					if($this->question["answer"][($j)] == $this->question["correct_answer"]){
						$this->answer = $j;
					}
				}
				
			}
			
			function getQuestion(){
				return $this->question;
			}
			
			function getAnswer(){
				return $this->answer;
			}
			
			function getItem(){
				if(isset($this->item)){
					return $this->item;
				}
				return 0;
			}
			
			function takeItem(){
				$result=0;
				if($this->item){
					$result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			function welcomePlayer(){
				$result = "welcome to a QuestionRoom. this room's question is: ".$this->question["question"]."<br>";
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
				if(isset($this->neighbours[$direction])){
					return $this->neighbours[$direction];
				}
				return null;
			}
			
			function registrateNeigbour(Room $room, $direction){
				$this->neighbours[$direction] = $room;
				if(isset($this->question["answer"][$direction])){
					$this->question["answer"][] = $this->question["answer"][$direction];
					$this->question["answer"][$direction] = null;
				}
			}
			
			
			
		}	
			
?>