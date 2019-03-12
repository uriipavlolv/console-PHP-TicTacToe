<?php

Class Game extends Field
{
	function __construct() {
		
		parent::__construct();
		
	}
	
	private function restart() {
		
		$this->resetField();

		return;		
	}
	
	private function exitGame() {
		
		exit;
	}
	
	private function about() {
		
		$message = (file_get_contents('about.txt'));
		
		$this->addMessage($message);
		
		return;
	}
	
	private function render() {
		
		$nextMove = $this->nextMove;
	
		$rawField = $this->cells;
		
		$message = $this->message;
		
		$size = $this->size;
		
		include 'classes/view.php';
		
		$this->message = '';
		
		return;
	}
	
	private function addMessage(string $message) {
		
		$this->message .= "\n{$message}";
		
		return;
	}
	
	private function isEndGame ():bool {
		
		$rawField = $this->cells;
		
		$size = $this->size;
		
		
		//check free cells for move
		if (!in_array(" ", $rawField)) {
			
			$this->showFinal(false);
			
			return true;
		}
		
		$patterns['O'] = str_pad("", $size, "O");
		
		$patterns['X'] = str_pad("", $size, "X");
			
		
		//check diagonals
		
		//left to right diagonal
		$row = [];
		
		for ($i = 1; $i <= $size; $i++) {
			
			$row[] = $rawField[($i + (($i - 1) * $size))];
		}
		
		$currentDiagonal = implode("", $row);
		
		if (in_array($currentDiagonal, $patterns)) {
			
			$this->showFinal(true);
			
			return true;
		}
		
		//right to left diagonal
		$row = [];
		
		for ($i = $size; $i >= 1; $i--) {
			
			$row[] = $rawField[($i + (($size - $i) * $size))];
		}
		
		$currentDiagonal = implode("", $row);
		
		if (in_array($currentDiagonal, $patterns)) {
			
			$this->showFinal(true);
			
			return true;
		}
		
		
		//check rows
		for ($i = 1; $i <= $size; $i++) {
			
			$row = [];
			
			for ($k = 0; $k < $size; $k++) {
				
				$row[] = $rawField[($i + $k * $size)];
			}
			
			$currentRow = implode("", $row);
			
			if (in_array($currentRow, $patterns)) {
				
				$this->showFinal(true);
				
				return true;
			}
		}
		
		
		//check columns
		for ($i = 1; $i < $size * $size; $i+=($size)) {
			
			$column = implode("", array_splice($rawField, 0, $size));
			
			if (in_array($column, $patterns)) {
				
				$this->showFinal(true);
				
				return true;
			}
		}
		
		return false;
	}
	
	private function showFinal(bool $hasWinner) {
		
			if ($hasWinner) {
				
				$this->addMessage("##################################################");
				$this->addMessage("##################################################");
				$this->addMessage("##                                              ##");
				$this->addMessage("## Yo-ho-ho, we have a winner! Congratulations! ##");
				$this->addMessage("## Enter 'new' to start new game.               ##");
				$this->addMessage("##                                              ##");
				$this->addMessage("##################################################");
				$this->addMessage("##################################################");
				
			} else 	{
				
				$this->addMessage("##################################################");
				$this->addMessage("##################################################");
				$this->addMessage("##                                              ##");
				$this->addMessage("##   There are no more moves available.         ##");
				$this->addMessage("##   Restart the game with the 'new' command.   ##");
				$this->addMessage("##                                              ##");
				$this->addMessage("##################################################");
				$this->addMessage("##################################################");
			}
		
		return;
	}
	
	private function move(string $cellId):bool {
		
		if (!array_key_exists($cellId, $this->cells)) {
			
			$this->addMessage("Cell index {$cellId} does not exist. Repeat input.");
			
			return false;
		}
		
		if ($this->cells[$cellId] !== " ") {
			
			$this->addMessage("Cell index {$cellId} is already taken. Repeat input.");
			
			return false;
		}
		
		$this->cells[$cellId] = $this->nextMove;
		
		$this->addMessage("Symbol '{$this->nextMove}' placed in cell #{$cellId}");
		
		$this->nextMove = ($this->nextMove == "X") ? "O" : "X";
		
		$this->saveField();
		
		return true;
	}
	
	public function start() {
		
		$isEndGame = $this->isEndGame(); // true/false
		
		$this->render();
		
		echo "\n\nYour choise: ";
	
		$userChoice = strtolower(trim(fgets(STDIN)));
		
		if (!preg_match('/new|about|\d/', $userChoice)) {
			
			$this->addMessage("{$userChoice} is not allowed command! Try again.");
		}

		if ($userChoice === "exit") $this->exitGame();
		
		if ($userChoice === "new") $this->restart();
		
		if ($userChoice === "about") $this->about();
		
		if (!$isEndGame & is_numeric($userChoice)) {
			
			$this->move($userChoice);
		}
		
		$this->start();
	}
}