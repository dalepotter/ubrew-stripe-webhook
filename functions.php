<?php
// Search for a string within a variable.  Return true if found, or false if not. Function is not case sensitive
function find_in_string($search_term, $string){
	$search_term = strtolower($search_term);
	$string = strtolower($string);
	if( is_numeric(strpos($string, $search_term)) ){
		return true;
	} else {
		return false;
	}
// end find_in_string function
}

?>
