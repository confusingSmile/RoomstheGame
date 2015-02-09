<?php
	
	//Setting up a connection
	$mysqli = new mysqli("localhost", "deb43619_walter", "sakana","roomsthegame");
	
	
	if (mysqli_connect_errno()) { 
		printf("Connect failed: %s\n", mysqli_connect_error()); 
		exit(); 
	} 
	
	/*
	//Setting up a connection
	$link = mysql_connect("localhost", "deb43619_walter", "sakana");
	
	//If the database server cannot be connected to, stop. 
	if (!$link) {
		die("Gebruikersnaam of wacthwoord fout : " . mysql_error());
	}
	
	//If the database itself cannot be connected to, stop. 
	$db_selected = mysql_select_db("deb43619_walter", $link);
	if (!$db_selected) {
		die ("Kan geen verbinding maken met de database: " . mysql_error());
	}
	*/
?>