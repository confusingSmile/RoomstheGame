<?php
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	require('DatabaseExtension.php');
	$db = new DatabaseExtension();
	if($db->authenticate($username, $password)){
		session_start();
		$_SESSION['user'] = $username;
		header('Location: index.php');
	}else{
		echo "<a href=\"index.php\">Try again.</a>";
	}
?>