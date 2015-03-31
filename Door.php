<?php
	class Door{
		
		private $blocked;
		
		function Door($blocked = false){
			$this->blocked = $blocked;
		}
		
		//"unlocks" the Door if it's locked. 
		function unblock(){
			if($this->blocked){
				$this->blocked = false;
				return 'The door slowly opens...';
			} else {
				return 'The door is already open.';
			}
			
			return 'The door won\'t open.';
		}
		
		function getBlocked(){
			return $this->blocked;
		}
	}
?>