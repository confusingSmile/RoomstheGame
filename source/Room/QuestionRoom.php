<?php
		namespace Game\Room;
		use Game\Room\Room;
		use Game\Item;
		use Game\DatabaseExtension;
		
		class QuestionRoom extends Room{	
			
			private $question; 
			private $answer;
			
			function __construct($id, DatabaseExtension $db, $thisRoomIsNew = true, $itemId = null, $questionHintorWhatever = null, 
								 $unlockedDoors = null){
				
				$this->id = $id;
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
				}
				if($unlockedDoors){
					
					foreach($unlockedDoors as $doorNumber){
						$this->getDoor($doorNumber)->unblock();
					}
				}
				
				if($thisRoomIsNew){
					
					$random = rand(1, 2);
					if($random == 1){
						$this->item = new Item($db);
					}
					
				} else if($itemId){
					$this->item = new Item($db, $itemId);
				}
				
				if(!$questionHintorWhatever){
					$this->question = $db->getQuestion(); 
					shuffle($this->question["answer"]); 
					
					$this->answer = "0";
					for($j=0;$j<3;$j++){
						if($this->question["answer"][($j)] == $this->question["correct_answer"]){
							$this->answer = $j;
						}
					}
				} else{ 
					$questionParts = explode($questionHintorWhatever, 'b.b');
					$this->question["answer"][0] = $questionParts[0];
					$this->question["answer"][1] = $questionParts[1];
					$this->question["answer"][2] = $questionParts[2];
					$this->question["correct_answer"] = $questionParts[3];
					$this->answer = $questionParts[4];
					$this->question["question"] = $questionParts[5];
				} 
			}
			
			function getId(){
				return $this->id;
			}
			
			function getQuestionHintOrWhatever(){
				$questionsString = $this->question["answer"][0].'b.b'.$this->question["answer"][1].'b.b'.$this->question["answer"][2].'b.b'.
				$this->question["correct_answer"].'b.b'.$this->answer.'b.b'.$this->question["question"];
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