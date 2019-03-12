<?php

Class Field
{
	protected $nextMove, $size, $cells = [];
	
	function __construct() {
		
		if (is_file('field.json') & !empty(file('field.json'))) {
			
			$size = include('settings.php') ; //loadGame
			
			$this->size = $size;
			
			$this->getField();

			if (count($this->cells) != $size * $size) $this->resetField(); //if changed settings
			
		} else $this->newField($this->size); //newGame
	}
	
	protected function resetField() {
		
		$this->nextMove = "X";
		
		$size = include('settings.php') ; //loadGame
			
		$this->size = $size;
		
		$this->cells = array_fill(1, $this->size * $this->size, " ");
		
		$this->saveField();
	}
	
	
	protected function saveField() {
	
		$out['nextMove'] = $this->nextMove;
		
		$out['size'] = $this->size;
		
		$out['cells'] = $this->cells;
		
		file_put_contents('field.json', json_encode($out));
	}
	
	
	private function getField() {
		
		$data = json_decode(file_get_contents('field.json'), true);
		
		$this->nextMove = $data['nextMove'];
		
		$this->cells = $data['cells'];
		
		$this->size = $data['size'];
	}
}
