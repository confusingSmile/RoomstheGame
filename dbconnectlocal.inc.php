<?php
	//Setting up a connection
	$mysqli = new mysqli("localhost","root","root","roomsthegame");
	
	
	if (mysqli_connect_errno()) { 
		printf("Connect failed: %s\n", mysqli_connect_error()); 
		exit(); 
	} 
	
	/*
	//If the database server cannot be connected to, stop. 
	if (!$link) {
		die("Gebruikersnaam of wacthwoord fout : " . mysql_error());
	}
	
	//If the database itself cannot be connected to, stop. 
	$db_selected = mysql_select_db("models", $link);
	if (!$db_selected) {
		die ("Kan geen verbinding maken met de database: " . mysql_error());
	}
	*/
?>