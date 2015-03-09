<?php
	class DatabaseExtension{
		
		var $connection;
			
		function DatabaseExtension(){
			
		}
		
		function getQuestion(){
			$chosenQuestion["error"] = "404 question not found";
			$result = "";
			//open connection
			include("dbconnectlocal.inc.php");
			
			$query = "SELECT * 
					  FROM questions;"; 
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
			
			$randomNumber = 0; 
			$randomNumber = (rand(1, (count($result["question"]))) - 1); 
			
			
			$chosenQuestion["question"] = $result["question"][$randomNumber];
			$chosenQuestion["correct_answer"] = $result["correct_answer"][$randomNumber];
			$chosenQuestion["answer"][] = $result["correct_answer"][$randomNumber];
			$chosenQuestion["answer"][] = $result["wrong_answer1"][$randomNumber];
			$chosenQuestion["answer"][] = $result["wrong_answer2"][$randomNumber];
			return $chosenQuestion;
		}
		
		//consider merging with question
		function getHint(){
			$hint = "";
			$maxHintNumber = $this->getMaxHintNumber();
			$hintNumber = rand(1, $maxHintNumber);
			include("dbconnectlocal.inc.php");
			$query = "SELECT hint_text, hint_answer
					  FROM hints
					  WHERE hint_number = '".$hintNumber."'"; 
			//execute multi query  
			if ($mysqli->multi_query($query)) {
				do {         
				
						
					//store result set 
					if ($queryResult = $mysqli->use_result()) {             
							while ($row = $queryResult->fetch_assoc()) {                              
							$hint["text"] = $row["hint_text"];
							$hint["answer"] = $row["hint_answer"];
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
			include("dbconnectlocal.inc.php");
			$query = "SELECT password
					  FROM users
					  WHERE username = '".$username."'"; 
			//execute multi query  
			
			
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
			$result = "No item with number".$itemNumber;
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
		
		function getItemId($itemName){
			$result = "No item with name".$itemName;
			include("dbconnectlocal.inc.php");
			$query = "SELECT item_id
					   FROM items
					   WHERE item_name = '".$itemName."'"; 
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
		
		function getItemUseResult($itemName, &$obstacle){
			$itemUseResult = 0;
			$obstacleId = $obstacle->getObstacleId();
		
			$itemName = addslashes($itemName);
			$itemId = $this->getItemId($itemName);
			include("dbconnectlocal.inc.php");
			$query = "SELECT result
					  FROM item_use
					  WHERE item_id = '".$itemId."' 
							AND obstacle_id = '".$obstacleId."'"; 
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
			
			if($itemUseResult != 2 && $itemUseResult != 1 && $itemUseResult != 0 && $itemUseResult != -1){
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
		
		function getObstaclesClearedByItems($generatedItems){
			include("dbconnectlocal.inc.php");
			for($i=0;$i < count($generatedItems); $i++){
				$query = "SELECT obstacle_id
						  FROM item_use, items
						  WHERE item_use.item_id = items.item_id
							AND items.item_name = '".$generatedItems[$i]."'
							AND result = '1'"; 
				//execute multi query  
				if ($mysqli->multi_query($query)) {
					do {         
					
						//store result set 
						if ($queryResult = $mysqli->use_result()) {             
							while ($row = $queryResult->fetch_assoc()) {                              
								$result[] = $row["obstacle_id"];            
							}             
						$queryResult->close();         
						}         
							 
					} 
					while ($mysqli->next_result()); 
				
				} 
			}
			
			//close connection  
			$mysqli->close();
			if(!(isset($result[0]))){
				$result[0] = "error";
			}
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
							$result = $row["hint_number"];            
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
		
	}
?>