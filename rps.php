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


		function stringifyA($entryList)
		{
			$result = "";
			foreach($entryList as $entry)
			{
				$result = $result . $entry->playerSelection . $entry->cpuSelection . ',';
			}
			return $result;
		}

		function stringifyB($entryList)
		{
			$result = "";
			foreach($entryList as $entry)
			{
				//echo $entry['playerSelection'] . $entry['cpuSelection'] . ',';
				$result  = $result . $entry['playerSelection'] . $entry['cpuSelection'] . ',';
			}
			return $result;
		}

		//entrylist will be the individual history 
		function history_guess($entryList, $pdo)
		{
			$rockGuess = 0;
			$paperGuess = 0;
			$scissorsGuess = 0;

			$prepQuery = ("SELECT * FROM rpsentries WHERE session_id = :sesh_id AND sessionNum = :sesh_num");
			$hbo = $pdo->prepare($prepQuery);

			foreach($pdo->query("SELECT DISTINCT session_id FROM rpsentries")as $sesh_id)
			{
				foreach($pdo->query("SELECT sessionNum FROM rpsentries WHERE session_id = '$sesh_id[session_id]'")->fetchAll() as $sesh_num)
				{
					 $hbo->execute(array(':sesh_id' => $sesh_id['session_id'], ':sesh_num' => $sesh_num['sessionNum']));
					 $comp_entryList = $hbo->fetchAll();

					// //compare entry lists here
					$depth = (count($entryList) < 3) ? count($entryList) : 3;
					$subarr = array_slice($entryList, -$depth, $depth);

					$comp_subarr = $comp_entryList;


					$str = RPS::stringifyA($subarr);
					$comp_str = RPS::stringifyB($comp_subarr);
						
					$matches = array();

					preg_match(( '/'. $str . '\d/'), $comp_str, $matches);

					foreach($matches as $match)
					{
						$guess = substr($match,-1);

						switch($guess)
						{
							case RPS::Rock:
								$rockGuess++;
								break;
							case RPS::Scissors:
								$scissorsGuess++;
								break;
							case RPS::Paper:
								$paperGuess++;
								break;	
						}
					}

				}
			}


			echo 'rock: '. $rockGuess. '<br>';
			echo 'paper: '. $paperGuess.'<br>';
			echo 'scissors' . $scissorsGuess;

			if($rockGuess >= $paperGuess && $rockGuess >= $scissorsGuess)
				return RPS::Paper;

			if($paperGuess >= $rockGuess && $paperGuess >= $scissorsGuess)
				return RPS::Scissors;

			if($scissorsGuess >= $paperGuess && $scissorsGuess >= $rockGuess)
				return RPS::Rock;



			return max($rockGuess,$paperGuess);
		}

	}
	

function rps()
{
	include_once $_SERVER["DOCUMENT_ROOT"] . '/rps/db.inc.php';
	include_once $_SERVER["DOCUMENT_ROOT"] . '/rps/entry.php';
	session_start();

	if(!isset($_SESSION['entrylist']))
		$_SESSION['entrylist'] = array();
	if(!isset($_SESSION['playerScore']))
		$_SESSION['playerScore'] = 0;
	if(!isset($_SESSION['cpuScore']))
		$_SESSION['cpuScore'] = 0;
	if(!isset($_SESSION['turn']))
		$_SESSION['turn'] = 0;
	if(!isset($_SESSION['sessionNum']))
		$_SESSION['sessionNum'] = 1;




	$playerScore = $_SESSION['playerScore'];
	$cpuScore = $_SESSION['cpuScore'];

//	$cpuChoice = rand(0,2);
	$cpuChoice = RPS::history_guess($_SESSION['entrylist'], $pdo);
	

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


	$entry = new Entry($playerChoice,$cpuChoice,RPS::winner($playerChoice,$cpuChoice),$_SESSION['turn']);
	array_push($_SESSION['entrylist'], $entry);


	$sesh_id = "'" . session_id() . "'";
	$insert_sql = 'INSERT INTO rpsentries (id, playerSelection, cpuSelection, winner, sessionTurn, session_id, sessionNum) VALUES (NULL,' . 
		($entry->playerSelection) . ',' . 
		($entry->cpuSelection) . ',' . 
		($entry->winner) . ',' .
		($entry->sessionTurn)  . ',' .
		($sesh_id) . ',' . 
		$_SESSION['sessionNum'] . ');';
	$pdo->exec($insert_sql);
}



if(isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch($action) {
        case 'rps' : rps();break;
        }
    }
?>