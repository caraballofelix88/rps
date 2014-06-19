<?php 


	abstract class RPS
	{
		const Paper = 0;
		const Rock = 1;
		const Scissors = 2;

		function toStr($selection)
		{
			if($selection == 0) //paper
				return "'Paper'";

			if($selection == 1) //rock
				return "'Rock'";  

			if($selection == 2) //scissors
				return "'Scissors'";	
		}		


		//returns 1 if p1 wins, 2 if p2 wins, 0 if tie
		function winner($p1Entry,$p2Entry)
		{

			if($p1Entry === $p2Entry)
				return 0;

			if(($p1Entry+1)%3 === $p2Entry)
			{
				return 1;
			}
			else return 2;
		}

	}


	

function rps()
{
	session_start();
	include $_SERVER["DOCUMENT_ROOT"] . '/rps/db.inc.php';
	include $_SERVER["DOCUMENT_ROOT"] . '/rps/entry.php';

	if(!isset($_SESSION['entrylist']))
		$_SESSION['entrylist'] = array();
	if(!isset($_SESSION['playerScore']))
		$_SESSION['playerScore'] = 0;
	if(!isset($_SESSION['cpuScore']))
		$_SESSION['cpuScore'] = 0;
	if(!isset($_SESSION['turn']))
		$_SESSION['turn'] = 0;



	$playerScore = $_SESSION['playerScore'];
	$cpuScore = $_SESSION['cpuScore'];

	$cpuChoice = rand(0,2);
	

	$playerChoice = 0;
	$r_result = isset($_POST['rock'])? isset($_POST['rock']) : NULL;
	$p_result = isset($_POST['paper'])? isset($_POST['paper']) : NULL;
	$s_result = isset($_POST['scissors'])? isset($_POST['scissors']) : NULL;


	if($r_result == 1)
	{
		$playerChoice = RPS::Rock;
	}
	else if($p_result == 1)
	{
		$playerChoice = RPS::Paper;
	}
	else if($s_result == 1)
	{
		$playerChoice = RPS::Scissors;
	}
	else
	{
		
	}


	echo '<p>Player selected ', RPS::toStr($playerChoice), '</p>';
	echo '<p>CPU selected ', RPS::toStr($cpuChoice), '</p>';

		if(RPS::winner($playerChoice,$cpuChoice) === 1)
		{
			$_SESSION['playerScore'] = $_SESSION['playerScore'] + 1;
			$_SESSION['turn'] = $_SESSION['turn'] + 1;
			$playerScore = $_SESSION['playerScore'];
			echo '<h2> Player wins! Current Score: ', $playerScore, '-', $cpuScore, '</h2>';
		}
		else if(RPS::winner($playerChoice,$cpuChoice) === 2)
		{
			$_SESSION['cpuScore'] = $_SESSION['cpuScore'] + 1;
			$_SESSION['turn'] = $_SESSION['turn'] + 1;
			$cpuScore = $_SESSION['cpuScore'];
			echo '<h2> CPU wins! Current Score: ', $playerScore, '-', $cpuScore, '</h2>';
		}
		else
		{
			$_SESSION['turn'] = $_SESSION['turn'] + 1;
			echo '<h2> Tie game. Current Score: ', $playerScore, '-', $cpuScore, '</h2>';
		}


	$entry = new Entry(RPS::toStr($playerChoice),RPS::toStr($cpuChoice),RPS::winner($playerChoice,$cpuChoice),$_SESSION['turn']);
	array_push($_SESSION['entrylist'], $entry);


	$sesh_id = "'" . session_id() . "'";
	$insert_sql = 'INSERT INTO rpsentries (id, playerSelection, cpuSelection, winner, sessionTurn, session_id) VALUES (NULL,' . 
		($entry->playerSelect) . ',' . 
		($entry->cpuSelect) . ',' . 
		($entry->winner) . ',' .
		($entry->sessionTurn)  . ',' .
		($sesh_id) . ');';
	$pdo->exec($insert_sql);
}



if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'rps' : rps();break;
        }
    }
?>