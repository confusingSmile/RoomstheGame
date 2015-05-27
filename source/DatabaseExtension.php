<?php

	namespace Game;
	use Doctrine\Common\ClassLoader;
	use Doctrine\DBAL\Connection;
	use Game\Room\Room;
	use Game\Room\QuestionRoom;
	use Game\Room\HintRoom;
	use Game\Room\IntroRoom;
	use Game\Room\ObstacleRoom;
	use Game\Room\LockedDoorRoom;
	
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
		
		function obstacleRoomPossible(){
			$result = "";
			$sql = "SELECT count(*) 'count'
				    FROM rooms
					WHERE item_id is not null"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				if ($row['count'] > 0){
					return true;
				}				
			}
			
			return false;
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
		
		function saveIntroRoom($introRoomItemId, $gameId){
			$sql = "INSERT INTO rooms (room_id, item_id, unlocked_doors, game_id, question_or_hint, room_type) 
					VALUES (1, :itemId, '0, 1, 2, 3', :gameId, null, 'Game\Room\IntroRoom')"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('itemId', $introRoomItemId);
			$stmt->bindValue('gameId', $gameId);
			$stmt->execute();
		}
		
		function saveRoom(Room $parent, Room $room, $direction, $gameId){ 
		
			//small preparation in order to know the unlocked doors 
			$unlockedDoors = '';
			for($i=0; $i<4; $i++){
				if(!$room->getDoor($i)->getBlocked()){
					$unlockedDoors .= $i.', ';	
				}
			}
			$unlockedDoors = rtrim($unlockedDoors, ', ');
			
			
			
		
			$sql = "INSERT INTO rooms (room_id, item_id, unlocked_doors, game_id, question_or_hint, room_type) 
					VALUES (:roomId, :itemId, ':doorsUnlocked', :gameId, :questionOrHint, 'Game\Room\IntroRoom')"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('roomId', $room->getId());
			$stmt->bindValue('itemId', $room->getItem()->getId());
			$stmt->bindValue('doorsUnlocked', $unlockedDoors);
			$stmt->bindValue('gameId', $gameId);
			$stmt->bindValue(':questionOrHint', $room->getQuestionHintOrWhatever());
			$stmt->execute();
			
			$sql = "INSERT INTO neighbours (room_id, game_id, direction, neighbour_id)
					VALUES (:roomId, :gameId, :direction, :neighbourId)";
			$stmt = $this->conn->prepare($sql);
			$stmt->bindValue('roomId', $room->getId());
			$stmt->bindValue('gameId', $gameId);
			$stmt->bindValue('direction', $direction);
			$stmt->bindValue('neighbourId', $parent->getId());
			$stmt->execute();
		}
		
		function getRoomFromDatabase($roomId, $gameId){
			$result = '';
			$sql = "SELECT * FROM rooms WHERE room_id = :roomId AND game_id = :gameId"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('roomId', $roomId);
			$stmt->bindValue('gameId', $gameId);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {  
				foreach(array_keys($row) as $index){
					$result[$index] = $row[$index];
				}
			}
			
			return $result;
		}
		
		
		function getNeighbour($roomId, $direction, $gameId){
			$queryResult = null;
			$sql = "SELECT neighbour_id FROM rooms WHERE room_id = :roomId AND direction = :direction AND game_id = :gameId"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('roomId', $roomId);
			$stmt->bindValue('direction', $direction);
			$stmt->bindValue('gameId', $gameId);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {  
				$queryResult = $row['neighbour_id'];
			}
			
			if($queryResult){
				$result = $this->getRoomFromDatabase($queryResult, $gameId);
			} else {
				return null;
			}
			
			return $result;
		}
		
		function generateGameId(){
			$minimum = '';
			$maximum = '';
			$sql = "SELECT min(game_id) 'minimum', max(game_id) 'maximum'
					   FROM games"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$minimum = $row['minimum'];
				$maximum = $row['maximum'];
			} 
			
			if($minimum > 1){
				return $minimum - 1;
			}
			
			return $maximum + 1;
		}
		
		function retreiveGameData($gameId){
			$result = '';
			$sql = "SELECT * FROM save_data WHERE game_id = :gameId"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('gameId', $gameId);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {  
				foreach(array_keys($row) as $index){
					$result[$index] = $row[$index];
				}
			}
			
			return $result;
		}
		
		function retreiveGameList($username){
			$result = array();
			$sql = "SELECT game_id FROM games WHERE username = :username"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('username', $username);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {                              
				$result[] = $row['game_id'];	
			} 
			 
			
			return $result;
		}
		
		function saveGame($gameId, $currentRoomId, $currentHunger, $currentDoorsUnlocked, $itemsGathered, $itemsGenerated){
			$gatheredItems = '';
			$generatedItems = '';
			foreach($itemsGathered as $item){
				$gatheredItems .= $item->getId().', ';
			}
			$gatheredItems = rtrim($gatheredItems, ', ');
			
			foreach($itemsGenerated as $item){
				$generatedItems .= $item->getId().', ';
			}
			$generatedItems = rtrim($generatedItems, ', ');
			
			$sql = "INSERT INTO save_data (game_id, current_room_id, current_hunger, current_doors_unlocked, items_gathered, items_generated) 
					VALUES (:gameId, :currentRoomId, :currentHunger, :currentDoorsUnlocked, :itemsGathered, :itemsGenerated)"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('gameId', $gameId);
			$stmt->bindValue('currentRoomId', $currentRoomId);
			$stmt->bindValue('currentHunger', $currentHunger);
			$stmt->bindValue('currentDoorsUnlocked', $currentDoorsUnlocked);
			$stmt->bindValue('itemsGathered', $gatheredItems);
			$stmt->bindValue('itemsGenerated', $generatedItems);
			$stmt->execute();
		}
			
		function insertGame($gameId, $username, $gameName = "New game 1"){
			$sql = "INSERT INTO games (game_id, username, game_name) 
					VALUES (:gameId, :username, :game_name)"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('gameId', $gameId);
			$stmt->bindValue('username', $username);
			$stmt->bindValue('game_name', $gameName);
			$stmt->execute();
		}
			
		function deleteGame($gameId){
			$sql = "DELETE FROM games WHERE game_id = :gameId"; 
			$stmt = $this->conn->prepare($sql); 
			$stmt->bindValue('gameId', $gameId);
			$stmt->execute();
		}
	}
?>