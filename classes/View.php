<?php
$oneCellLength = strlen(max(array_keys($rawField)));

$shortLineLength = $size * 4 - 1;
	
$longLineLength = ($oneCellLength + 2) * $size + $size - 1;

$horisontalLine = "\t";
$horisontalLine .= str_pad("", $shortLineLength, "-");
$horisontalLine .= "\t ";
$horisontalLine .= str_pad("", $longLineLength, "-");
$horisontalLine .= "\n";

for ($row = 1; $row <= $size; $row++) {

	$line = "\t ";
	
	$values = $keys = array();
	
	for ($col = 1; $col <= $size; $col++) {
		
		$key = array_key_first($rawField);
		
		$keys[] = str_pad($key, $oneCellLength, " ", STR_PAD_LEFT);
		
		$values[] = $rawField[$key];
		
		unset ($rawField[$key]);
	}

	$line .= implode(" | ", $values);
	$line .= " \t  ";
	$line .= implode(" | ", $keys);
	$line .= " \n";
	
	$lines[] = $line;
}

$currentField = implode($horisontalLine, $lines);


//clear screen win/linux
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	
	popen('cls', 'w');
	
} else system("clear");


//print Field
echo "Next move player: {$nextMove}\n\n";

print_r ($currentField);

//print Available commands
echo <<<EOD

----------------------------------------------------------
Commands:

Enter the cell number in which to place the character '{$nextMove}'.

Enter "new" to restart the game.
Enter "about" to view help.
Enter "exit" to end the game.

----------------------------------------------------------
EOD;

if (!empty($message)) echo $message;