<?php
		//if (session_status() != PHP_SESSION_NONE) {
			session_start();
			$_SESSION['entrylist'] = array();
			$_SESSION['playerScore'] = 0;
			$_SESSION['cpuScore'] = 0;
			$_SESSION['turn'] = 0;
			$_SESSION['sessionNum'] = $_SESSION['sessionNum'] + 1;
		//}
?>