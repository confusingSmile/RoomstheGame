<?php
	class DatabaseExtension{
		
		var $connection;
			
		function DatabaseExtension(){
			
		}
		
		function getQuestion($dificulty){
			$chosenQuestion["error"] = "404 question not found";
			$result = "";
			//open connection
			$this->connect();
			
			$query = "SELECT * 
					  FROM questions
					  WHERE difficulty='".$dificulty."';"; 
			//execute multi query 
			$mysqli = $this->connection;
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result["question"][] = $row["question"];
							$result["correct_answer"][] = $row["correct_answer"];
							$result["wrong_answer1"][] = $row["wrong_answer1"];
							$result["wrong_answer2"][] = $row["wrong_answer2"];
							
						}             
					$queryResult->close();         
					}         
					    
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			//TODO
			$chosenQuestion["question"] = $result["question"][$randomNumber];
			$chosenQuestion["correct_answer"] = $result["correct_answer"][$randomNumber];
			$chosenQuestion["wrong_answer1"] = $result["wrong_answer1"][$randomNumber];
			$chosenQuestion["wrong_answer2"] = $result["wrong_answer2"][$randomNumber];
			return $chosenQuestion;
		}
		
		//consider merging with question
		function getHint(){
			$hint = "";
			$maxHintNumber = $this->getMaxHintNumber();
			$hintNumber = rand(1, $maxHintNumber);
			include("dbconnectlocal.inc.php");
			$query = "SELECT hint_text
					  FROM hints
					  WHERE hint_number = '".$hintNumber."'"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$hint = $row["hint_text"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			}
			//close connection  
			$mysqli->close();
			return $hint;
		}
		
		function getFlavourText($criterium){
			$flavourText = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT text 
					  FROM flavour_text
					  WHERE displayed_when=''".$criterium.""; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$flavourText = $row["text"];            
						}             
					$queryResult->close();         
					}         
					    
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $flavourText;
		}
		
		function authenticate($username, $password){
			$result = false;
			$correctPassword = "";
			$username = addslashes($username);
			$password = addslashes($password);
			$password = md5($password);
			$this->connect();
			$query = "SELECT password
					  FROM users
					  WHERE username = '".$username."'"; 
			//execute multi query  
			
			$mysqli = $this->connection;
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$correctPassword = $row["password"]; 
						
						} 
						
					$queryResult->close();        
					}
				
					  
					if($password == $correctPassword){
						$result = true;		
					}					
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		function getItemIcon($itemNumber){
			$result = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT item_icon
					   FROM items
					   WHERE item_id = '".$itemNumber."'"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result = $row["item_icon"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		
		function getItemName($itemNumber){
			$result = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT item_name
					   FROM items
					   WHERE item_id = '".$itemNumber."'"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result = $row["item_name"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		function getItemUseResult($itemName, &$room){
			$itemUseResult = 0;
			$obstacleType = $room->getClass();
			if($obstacleType == "ObstacleRoom"){
				$obstacleType = $room->getObstacle()->getObstacleName();
			}
			$itemName = addslashes($itemName);
			include("dbconnectlocal.inc.php");
			$query = "SELECT result
					  FROM item_use
					  WHERE item_name = '".$itemName."' 
							AND obstacle_type = '".$obstacleType."'"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$itemUseResult = $row["result"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			//making sure no invalid values are being returned
			if($itemUseResult != 1 && $itemUseResult != 0 && $itemUseResult != -1){
				$itemUseResult = 0;
			}
			return $itemUseResult;
		}
		
		function getMaxItemID(){
			$result = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT max(item_id) 'item_id'
					   FROM items"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result = $row["item_id"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		function getMaxObstacleID(){
			$result = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT max(obstacle_id) 'obstacle_id'
					   FROM obstacle"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result = $row["item_id"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		function getObstacleName($obstacleId){
			$result = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT obstacle_name
					   FROM obstacle
					   WHERE obstacle_id ='".$obstacleId."'"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result = $row["obstacle_name"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		function getObstacleText($obstacleId){
			$result = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT obstacle_text
					   FROM obstacle
					   WHERE obstacle_id ='".$obstacleId."'"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result = $row["obstacle_text"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		function getMaxHintNumber(){
			$result = "";
			include("dbconnectlocal.inc.php");
			$query = "SELECT max(hint_number) 'hint_number'
					   FROM hints"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$result = $row["item_id"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			//close connection  
			$mysqli->close();
			return $result;
		}
		
		function connect(){
			include("dbconnectlocal.inc.php");
			$this->connection = $mysqli;
		}
	}
?>