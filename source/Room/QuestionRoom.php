<?php
		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\DatabaseExtension;
		
		class QuestionRoom extends Room{	
			
			private $question; 
			private $answer;
			
			function __construct($id, DatabaseExtension $db){
				
				$this->id = $id;
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
				}
				
				$random = rand(1, 2);
				if($random == 1){
					$this->item = new Item($db);
				}
				
				$this->question = $db->getQuestion(); 
				shuffle($this->question["answer"]); 
				
				$this->answer = "0";
				for($j=0;$j<3;$j++){
					if($this->question["answer"][($j)] == $this->question["correct_answer"]){
						$this->answer = $j;
					}
				}
				
			}
			
			function getId(){
				return $this->id;
			}
			
			function getQuestionHintOrWhatever(){
				return $this->question;
			}
			
			function questionToString(){
				$questionsString = $this->question["answer"][0].', '.$this->question["answer"][1].', '.$this->question["answer"][2].', '.
				$this->question["correct_answer"].', '.$this->answer;
				return $questionString;
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
			
			function reconstruct($room_id, $unlockedDoors, $itemId, $questionHintorWhatever, $db){
				$this->id = $id;
				
				for($i=0; $i<4; $i++){
					$this->getDoor($i)->block();
				}
				$unlockedDoors = explode($unlockedDoors, ', ');
				foreach($unlockedDoors as $doorNumber){
					$this->getDoor($doorNumber)->unblock();
				}
				
				$questionParts = explode($questionHintorWhatever, ', ');
				
				$this->item = new Item($db, $itemId);
				
				
			}
			
			function getNextRoom($direction){
				if($direction === $this->anwer){
					return 'LockedDoorRoom';
				}
				
				$random = rand(0, 99);
				if($random < 60){
					return 'HintRoom';
				}
				
				return 'QuestionRoom';
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