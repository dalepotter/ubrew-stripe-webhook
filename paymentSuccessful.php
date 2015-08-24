<?php
// Turn on error messages (for debugging)
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

// Set path to the Stripe dependencies
// This file uses 'composer' automatically load in required classes based on the location 
// that it has installed them to.  If installing the Stripe library manually (for example, 
// via FTP) replace the following line with: require 'path-to-Stripe.php';
require 'vendor/autoload.php';

// Include the Stripe config file
require 'configStripe.php';

// Set Stripe API key
\Stripe\Stripe::setApiKey(stripeApiKey); // 'stripeApiKey' is defined in configStripe.php

// Read current number of memberships
$numberMembershipsGreenwich = file_get_contents('greenwichNumber.txt');

// Process the data from the webhook
$body = @file_get_contents('php://input');
$eventJson = json_decode($body);

// Store transaction data in 'data.txt' (This overwrites any existing data in the file)
file_put_contents('data.txt', $body);

// Increment the number of memberships for Greenwich
$newNumberMembershipsGreenwich = $numberMembershipsGreenwich + 1;

// Write this new number to the file
file_put_contents('greenwichNumber.txt', $newNumberMembershipsGreenwich);
?>

