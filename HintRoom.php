<?php
		class HintRoom extends Room{	
			
			var $hint;
			//TODO getHint
			
			function HintRoom(){
				for($i=0;$i<4;$i++){
					$this->doors[$i] = new Door();
				}
				
				$random = rand(1, 2);
				if($random == 1){
					$this->item = new Item();
				}
				
				$db = new DatabaseExtension();
				$this->hint = $db->getHint();
			}
			
			
			
			function takeItem(){
				$result=0;
				if($this->item != null){
					$result = $this->item;
					$this->item = null;
				}
				return $result;
			}
			
			
			function getItem(){
				$result=0;
				if($this->item != null){
					$result = $this->item;
				}
				return $result;
			}
			
			function welcomePlayer(){
				return "welcome to a HintRoom.\n".$this->hint;
			}
			
			function getDoor($direction){
				return $this->doors[$direction];
			}
			
			function registrateNeigbour(&$room, $direction){
				$this->neighbours[$direction] = $room;
			}
			
			//direction is an integer ranging from 0-3, 0 being south, 1 being west, 2 being north and 3 being east
			function getNeighbour($direction){
				$output = null;
				if(isset($this->neighbours[$direction])){
					$output = $this->neighbours[$direction];
				}
				return $output;
			}
			
			
		}	
			
?>