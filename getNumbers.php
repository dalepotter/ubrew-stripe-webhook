<?php
$arr = array('greenwich' => intval(file_get_contents('greenwichNumber.txt')), 'dalston' => intval(file_get_contents('dalstonNumber.txt')));
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
echo "UBrewMembershipStatus(" . json_encode($arr) . ")";
?>
