the player starts in a room with 300 points. 
with every new Room the player enters, the player loses a point (NYI) 
	this does indeed mean, moving back to any generated room does NOT cost any points

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
	if the player moves into an old Room:
		the game checks if the door is locked
				if the door is not locked:
					a new Room is being generated (NYI) 
					that Room will get neighbours assigned (currently a mess) 
					the player will move on to the next Room
					the room will "welcome" the player, printing its welcome message
				if the door is locked:
					it will say "The door won't open."











Room/Obstacle generating rules: to be decided