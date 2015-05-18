<?php

	namespace Game;
	use Doctrine\Common\ClassLoader;
	use Doctrine\DBAL\Connection;
	
	class DatabaseExtension{
		
		private $conn;
		
		/**
		* @param a connection with the database
		*/
		function __construct(Connection $conn){
			$this->conn = $conn;
		}
		
		function __sleep(){
			return array();
		} 
		
		/**
		* Re-initiates $conn, the variable containing the connection with the database .  
		*
		* @param a connection with the database
		*/
		function reconnect(Connection $conn){
			$this->conn = $conn;
		}
		
		/**
		* Returns a random question 
		* 
		* @return array 
		*/
		function getQuestion(){
			$chosenQuestion["error"] = "404 question not found";
			$result = "";
			//open connection
			$sql = "SELECT * 
					FROM questions;"; 
					
			$stmt = $this->conn->prepare($sql); 
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {
				$result["question"][] = $row["question"];
				$result["correct_answer"][] = $row["correct_answer"];
				$result["wrong_answer1"][] = $row["wrong_answer1"];
				$result["wrong_answer2"][] = $row["wrong_answer2"];
			}
			
			
			$randomNumber = 0; 
			$randomNumber = (rand(1, (count($result["question"]))) - 1); 
			
			
			$chosenQuestion["question"] = $result["question"][$randomNumber];
			$chosenQuestion["correct_answer"] = $result["correct_answer"][$randomNumber];
			$chosenQuestion["answer"][] = $result["correct_answer"][$randomNumber];
			$chosenQuestion["answer"][] = $result["wrong_answer1"][$randomNumber];
			$chosenQuestion["answer"][] = $result["wrong_answer2"][$randomNumber];
			return $chosenQuestion;
		}
		
		/**
		* Returns a random hint. 
		* 
		* @return array 
		*/		
		function getHint(){
			$hint = "";
			$maxHintNumber = $this->getMaxHintNumber();
			$hintNumber = rand(1, $maxHintNumber);
			$sql = "SELECT hint_text, hint_answer
					  FROM hints
					  WHERE hint_number = '".$hintNumber."'"; 
					  
			$stmt = $this->conn->prepare($sql); 
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$hint["text"] = $row["hint_text"];
				$hint["answer"] = $row["hint_answer"];
				
			} 
			
			return $hint;
		}
		
		/**
		* Checks if the username matches the password. 
		*
		* @return boolean 
		*/ 	
		function authenticate($username, $password){
			$result = false;
			$correctPassword = "";
			$password = md5($password);
			$sql = "SELECT password
					  FROM users
					  WHERE username = :username"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('username', $username);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$correctPassword = $row['password'];	
			} 
			
			if($correctPassword == $password){
				$result = true; 
			}
			return $result;
		}
		
		/**
		* Returns the path to the item's icon. (NYI) 
		* 
		* @param itemNumber: the number of the item 
		* of which we need the path 
		*/		
		function getItemIcon($itemNumber){
			$result = "";
			$sql = "SELECT item_icon
					   FROM items
					   WHERE item_id = :itemNumber"; 
			
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('itemNumber', $itemNumber);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['item_icon'];	
			}
			
			return $result;
		}
		
		/**
		* Returns the name of an item. 
		* 
		* @param the number of the item we need the name of. 
		* 
		* @return the item's name. 
		*/
		function getItemName($itemNumber){
			$result = "No item with number".$itemNumber;
			$sql = "SELECT item_name
					   FROM items
					   WHERE item_id = :itemNumber"; 
			
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('itemNumber', $itemNumber);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['item_name'];	
			}
			
			return $result;
		}
		
		/**
		* returns the ID of an Item. 
		* 
		* @param the name of the item we need the ID of. 
		*/
		function getItemId($itemName){
			$result = "No item with name".$itemName;
			$sql = "SELECT item_id
					   FROM items
					   WHERE item_name = :itemName"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('itemName', $itemName);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['item_id'];	
			}
			
			return $result;
		}
		
		//returns the result of using an item in a certain situation. 
		function getItemUseResult($itemName, Obstacle $obstacle){
			$itemUseResult = 0;
			$obstacleId = $obstacle->getObstacleId();
		
			$itemId = $this->getItemId($itemName);
			$sql = "SELECT result
					  FROM item_use
					  WHERE item_id = :itemId 
							AND obstacle_id = :obstacleId"; 
			//execute multi query  
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('itemId', $itemId);
			$stmt->bindValue('obstacleId', $obstacleId);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['result'];	
			} 
			 
			
			//making sure no invalid values are being returned
			
			if($itemUseResult != 2 && $itemUseResult != 1 && $itemUseResult != 0 && $itemUseResult != -1){
				$itemUseResult = 0;
			}
			return $itemUseResult;
		}
		
		//returns the highest itemID currently in use. 
		function getMaxItemID(){
			$result = "";
			$sql = "SELECT max(item_id) 'item_id'
					   FROM items"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['item_id'];	
			}
			
			return $result;
		}
		
		//returns the IDs of Obstacles that can be cleared by any of the currently generated items. 
		function getObstaclesClearedByItems($generatedItems){
			for($i = 0;$i < count($generatedItems); $i++){
				$sql = "SELECT obstacle_id
						  FROM item_use, items
						  WHERE item_use.item_id = items.item_id
							AND items.item_name = :itemName
							AND result = '1'"; 
				$stmt = $this->conn->prepare($sql); 
				$stmt->bindValue('itemName', $generatedItems[$i]->getItemName());
				$stmt->execute();
				
				while ($row = $stmt->fetch()) {                              
					$result[] = $row['obstacle_id'];	
				}
			}
			
			 
			
			if(!(isset($result[0]))){
				$result[0] = "error";
			}
			return $result;
		}
		
		//returns the name of an Obstacle based on its ID 
		function getObstacleName($obstacleId){
			$result = "";
			$sql = "SELECT obstacle_name
					   FROM obstacle
					   WHERE obstacle_id = :obstacleId"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('obstacleId', $obstacleId);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['obstacle_name'];	
			} 
			 
			
			return $result;
		}
		
		//Still needs to be implemented. 
		function getObstacleText($obstacleId){
			$result = "";
			$sql = "SELECT obstacle_text
					   FROM obstacle
					   WHERE obstacle_id = :obstacleId"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('obstacleId', $obstacleId);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['obstacle_text'];	
			} 
			 
			
			return $result;
		}
		
		//returns the highest hint number currently in use 
		function getMaxHintNumber(){
			$result = "";
			$sql = "SELECT max(hint_number) 'hint_number'
					   FROM hints"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result = $row['hint_number'];	
			} 
			 
			
			return $result;
		}
		
	}
?>