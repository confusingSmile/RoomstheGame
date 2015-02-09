<?php
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	require("DatabaseExtension.php");
	$db = new DatabaseExtension();
	if($db->authenticate($username, $password) == true){
		
	}
?>