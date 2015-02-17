the player starts in a room 
		   starts with 300 points. 
with every new Room the player enters, the player loses a point  
	this does indeed mean, moving back to any generated room does NOT cost any points
	Also, every room has 4 doors, 1 of which is the one you entered from

There are 6 types of rooms in this game, 
The types of Rooms are:
-HintRoom
	idea is that it gives a hint as to which door will take you further, while other doors will take you back (NYI) 
-IntroRoom
	Here the player gets an introduction to the game. This is the first room the player enters, and the first one only
-ItemRoom
	and despite being an "ItemRoom" currently all rooms can generate with an item inside 
-LockedDoorRoom
	in this room the player will need to use a key before he can go anywhere but back
-ObstacleRoom
	in this room the player will need to use an item fit to clear the obstacle in this room. (NYI)
	possibly the most difficult room to implement
-QuestionRoom
	Here the player will be asked a question(NYI), which he will need to answer correctly

Along the way the player can check for items, and pick up items if there are any

Goal of the game: to open 10 locked doors before running out of points

method mechanics:
when the player moves the game:
-checks if he is moving into a new room or into an old room
	if the player moves into an old Room:
		the player will move back without any issues
		the room will "welcome" the player, printing its welcome message
	if the player moves into a new Room:
		the game checks if the door is locked
				if the door is not locked:
					a new Room is being generated (NYI) 
					that Room will get the current Room assigned as being its neighbour
					the current Room will get the next Room assigned as its neighbour (now they know about each other) 					
					the player will move on to the next Room 
					the room will "welcome" the player, printing its welcome message
				if the door is locked:
					it will say "The door won't open."










Game mechanics:
Hunger: you start with 300 points of "hunger", and every new Room you enter, you lose one point of hunger, 
	until you reach 0. Then it is Game Over.

Room/Obstacle generating mechanics: to be decided. current plan is to have them 
	generate as long as there is possibility to generate the required items, and have the items be more likely to generate when 
	a room requiring it has generated

Room physics: If you go left to one room, you can go right to go back to the previous room, but no matter how many times you go left,
	you won't be back in that first room, you have to start going right again until you're back. 

Questions: Not well-thought out yet. plan is to have 1 door going to a "better" room, than the other doors. 
Hints: similar to questions, except they don't end with a "?" 



Commands:
/?, /help: should bring up some help text like in most command-line interfaces
/left, /right, /up, /down: move left right up and down respectively
/use: use an item
/unlock (subject to change) use a key, may be merged with /use, becoming /use key
/search searches the room for items
/pick up <item name>  pick up an item




methods and when they're used: 
Player:
	getGeneratedItems() used by RoomFactory and Item to generate objects appropriate to the player's progression
	getGatheredItems() used by...wait do I need this? I can use $this when calling useItem() 
	useItem() used by the game when the /use command is given
	travel() used by the game when the /left, /right etc. commands are issued
	getCurrentRoom() used in travel() 
	getCurrentItemEffects() not used yet
	getDoorsUnlocked() currently unused but will be used  by the game to display progression
	unlockKeyDoor() used when the /unlock (/use item key)  command is issued
	getHunger() used by the game to display hunger
	obtainItem() used when the /pick up  command is used
	
