<?php
	class Entry
	{
		public $cpuSelection;
		public $playerSelection;
		public $winner;
		public $sessionTurn;

		function __construct($_playerSelect,$_cpuSelect,$_winner,$_sessionTurn)
		{
			$this->cpuSelection = $_cpuSelect;
			$this->playerSelection = $_playerSelect;
			$this->winner = $_winner;
			$this->sessionTurn = $_sessionTurn;
		}
	}
?>