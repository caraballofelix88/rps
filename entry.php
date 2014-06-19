<?php
	class Entry
	{
		public $cpuSelect;
		public $playerSelect;
		public $winner;
		public $sessionTurn;

		function __construct($_playerSelect,$_cpuSelect,$_winner,$_sessionTurn)
		{
			$this->cpuSelect = $_cpuSelect;
			$this->playerSelect = $_playerSelect;
			$this->winner = $_winner;
			$this->sessionTurn = $_sessionTurn;
		}
	}
?>