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

// Require helper functions
require 'functions.php';

// Set Stripe API key
\Stripe\Stripe::setApiKey(stripeApiKey); // 'stripeApiKey' is defined in configStripe.php

// Process the data from the webhook
$body = @file_get_contents('php://input');
$body = preg_replace('#\[\s+#', '[', $body);
$eventJson = json_decode(utf8_encode($body), true);

// If there is no data in $body, send this user away
// This probably isn't essential, but is probably a good idea for security!
if (is_null($body)){
	echo "Nothing to see here!";
	exit();
}

// For extra security, retrieve from the Stripe API
$event_id = $eventJson['id'];
$event = \Stripe\Event::retrieve($event_id);

// Store transaction data in 'data.txt' (This overwrites any existing data in the file)
file_put_contents('data.txt', $body);

// Increment the number of memberships for either Greenwich or Dalston depending on the description
  if (find_in_string('Greenwich', $event->data->object['description'])){
     // Read the current number of memberships for Greenwich
     $numberMembershipsGreenwich = intval(file_get_contents('greenwichNumber.txt'));
     
     // Increment the number of memberships for Greenwich by 1
     $newNumberMembershipsGreenwich = $numberMembershipsGreenwich + 1;

     // Rewrite the new number to a file
     file_put_contents('greenwichNumber.txt', $newNumberMembershipsGreenwich);

  } elseif (find_in_string('Dalston', $event->data->object['description'])){
     // Read the current number of memberships for Dalston
     $numberMembershipsDalston = intval(file_get_contents('dalstonNumber.txt'));
     
	 // Increment the number of memberships for Dalston by 1
     $newNumberMembershipsDalston = $numberMembershipsDalston + 1;

     // Rewrite the new number to a file
     file_put_contents('dalstonNumber.txt', $newNumberMembershipsDalston);
  }

?>

