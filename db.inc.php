<?php

	$host = "localhost";
	$root = "";
	$password = "";//

	$db = "rps";

	try
	{
		$create = new PDO('mysql:host=localhost', $root, $password);
		$create->exec("CREATE DATABASE IF NOT EXISTS rps;");
		$create->exec("CREATE TABLE IF NOT EXISTS rps.rpsentries(
			id INT(11) AUTO_INCREMENT PRIMARY KEY,
			playerSelection VARCHAR(10) NOT NULL,
			cpuSelection VARCHAR(10) NOT NULL,
			winner INT(11) NOT NULL,
			sessionTurn INT(11) NOT NULL,
			session_id VARCHAR(40) NOT NULL);");





		$pdo = new PDO('mysql:host=localhost;dbname=rps', $root, $password);
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