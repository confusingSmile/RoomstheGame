<?php
	class Door{
		
		var $blocked;
		
		function Door($blocked = false){
			$this->blocked = $blocked;
			
		}
		
		//"unlocks" the Door if it's locked. 
		function unblock(){
			$result = "The door won't open.";
			if($this->blocked == true){
				$this->blocked = false;
				$result = "The door slowly opens...";
			} else {
				$result = "The door is already open.";
			}
			
			return $result;
		}
		
		function getBlocked(){
			return $this->blocked;
		}
	}
?>