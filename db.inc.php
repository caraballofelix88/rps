<?php


	//fill these in
	$host = "";
	$root = "";
	$password = "";//
	$db = "";



	//creates database and table if it doesnt already exist
	try
	{
		$create = new PDO('mysql:host=localhost', $root, $password);
		$create->exec("CREATE DATABASE IF NOT EXISTS rps;");
		$create->exec("CREATE TABLE IF NOT EXISTS rps.rpsentries(
			id INT(11) AUTO_INCREMENT PRIMARY KEY,
			playerSelection INT(11) NOT NULL,
			cpuSelection INT(11) NOT NULL,
			winner INT(11) NOT NULL,
			sessionTurn INT(11) NOT NULL,
			session_id VARCHAR(40) NOT NULL,
			sessionNum INT(11) NOT NULL);");


		$pdo = new PDO('mysql:host=localhost;dbname='. $db, $root, $password);
		$pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		$pdo->exec('SET NAMES "utf8"');
	}
	catch(PDOException $e)
	{
		$error = 'Unable to connect to database server.';
		include 'error.html.php';
		exit();
	}

	?>