<?php
	class DatabaseExtension{
		
		var $connection;
			
		function DatabaseExtension(){
			//TODO fix database queries and array keys, also add $mysqli = $this->connection;
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
			$max = count($result["question"]);
			$randomNumber = (rand(1, $max) - 1);
			$chosenQuestion["question"] = $result["question"][$randomNumber];
			$chosenQuestion["correct_answer"] = $result["correct_answer"][$randomNumber];
			$chosenQuestion["wrong_answer1"] = $result["wrong_answer1"][$randomNumber];
			$chosenQuestion["wrong_answer2"] = $result["wrong_answer2"][$randomNumber];
			return $chosenQuestion;
		}
		
		//consider merging with question
		function getHint(){
			include("dbconnectlocal.inc.php");
			$hint = "";
			$max = 1;
			$query = "SELECT max(hint_number)
					  FROM hints"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
						while ($row = $queryResult->fetch_assoc()) {                              
							$max = $row["hint_number"];            
						}             
					$queryResult->close();         
					}         
					     
				} 
				while ($mysqli->next_result()); 
			
			} 
			$hintNumber = rand(1, $max);
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
		
		//TODO check if this one is being used at all
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
		
		function getItemUseResult($itemName, $room){
			$itemUseResult = 0;
			$obstacleType = $room->getClass();
			if($obstacleType == "ObstacleRoom"){
				$obstacleType = $room->getObstacle();
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
			return $itemUseResult;
		}
		
		function connect(){
			include("dbconnectlocal.inc.php");
			$this->connection = $mysqli;
		}
	}
?>