<?php
	

function stats()
{
	session_start();
	include $_SERVER["DOCUMENT_ROOT"] . '/rps/db.inc.php';
	$array = $pdo->query("SELECT * FROM rpsentries")->fetchAll();

	$totalGames = count($array);
	$totalWins = 0;
	$userGames = 0;
	$userWins = 0;

	foreach ($array as $entry)
	{	
		if($entry['session_id'] == session_id())
		{
			if($entry['winner'] == 1)
			{
				$userWins = $userWins + 1;
				$totalWins = $totalWins + 1;
			}

			$userGames = $userGames + 1;
		}
		else
		{
			if($entry['winner'] == 1)
			{
				$totalWins = $totalWins + 1;
			}
		}
	}


	//total games ever played
	$winrate = ($totalGames > 0) ? floatval($totalWins / $totalGames) : 0;
	$userWinrate = ($userGames > 0) ? floatval($userWins / $userGames) : 0;
	$winrate = sprintf("%0.2f",$winrate);
	$userWinrate = sprintf("%0.2f",$userWinrate);


	echo "<div style='background-color: #EEE0EE;'>";
	echo "<h2>Total:</h2>";
	echo "<p>games: $totalGames</p>";
	echo "<p>total won games: $totalWins</p>";
	echo "<p>total winrate: $winrate</p>";
	echo "</div>";

	echo "<div style='background-color: #EEEEE0;'>";
	echo "<h2>Yours:</h2>";
	echo "<p>games: $userGames</p>";
	echo "<p>your won games: $userWins</p>";
	echo "<p>your winrate: $userWinrate</p>";
	echo "</div>";



}

	if(isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
    switch($action) {
        case 'stats' : stats();break;
        }
    }
?>