<?php
// Build an array of data
// Data from the file is converted to an integer - just to be sure we're dealing with whole numbers!
$arr = array( 'greenwich' => intval(file_get_contents('greenwichNumber.txt')), 
			  'dalston' => intval(file_get_contents('dalstonNumber.txt'))
			);

// Set headers for the output.
// These are needed for the AJAX request to work properly
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Print the output
// Give the JSON object a name, so that the jQuery knows what to expect when running the AJAX command
echo "UBrewMembershipStatus(" . json_encode($arr) . ")";

?>
