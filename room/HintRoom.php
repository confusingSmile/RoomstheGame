<?php
		class HintRoom extends Room{	
			
			private $hint;
			private $answer;
			//TODO getHint
			
			function HintRoom(){
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
				}
				
				$random = rand(1, 2);
				if($random == 1){
					$this->item = new Item();
				}
				
				$this->prepareHint();			
				
			}
			
			function getHint(){
				return $this->hint;
			}
			
			function getAnswer(){
				return $this->answer;
			}
			
			function takeItem(){
				$result=0;
				if($this->item){
					$result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			
			function getItem(){
				if(isset($this->item)){
					return $this->item;
				}
				return 0;
			}
			
			function welcomePlayer(){
				return "welcome to a HintRoom.".$this->hint;
			}
			
			function getDoor($direction){
				return $this->doors[$direction];
			}
			
			function registrateNeigbour(Room $room, $direction){
				$this->neighbours[$direction] = $room;
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				if(isset($this->neighbours[$direction])){
					return $this->neighbours[$direction];
				}
				return null;
			}
			
			function prepareHint(){
				$db = new DatabaseExtension();
				$hintData = $db->getHint();
				$this->hint = $hintData["text"];
				$this->answer = $hintData["answer"];
			}
			
		}	
			
?>