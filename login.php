<?php
	
	require('vendor/autoload.php');
	include('config/config_db_local.php');
	use Doctrine\DBAL\Configuration;
	use Game\DatabaseExtension;
	use Doctrine\DBAL\DriverManager;

	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$conn = DriverManager::getConnection($connectionParams, new Configuration());
	$db = new DatabaseExtension($conn);
	if($db->authenticate($username, $password)){
		session_start();
		$_SESSION['user'] = $username;
		header('Location: index.php');
	}else{
		echo "<a href=\"index.php\">Try again.</a>";
	}
?>