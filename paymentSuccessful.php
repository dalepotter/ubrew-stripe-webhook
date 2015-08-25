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

// For extra security, retrieve from the Stripe API
$event_id = $eventJson['id'];
$event = \Stripe\Event::retrieve($event_id);

// Store transaction data in 'data.txt' (This overwrites any existing data in the file)
file_put_contents('data.txt', $body);
file_put_contents('data1.txt', $eventJson['id']);

// Increment the number of memberships for either Greenwich or Dalston depending on the description
  if (find_in_string('Greenwich', $event->data->object['description'])){
     $numberMembershipsGreenwich = file_get_contents('greenwichNumber.txt');
     $newNumberMembershipsGreenwich = $numberMembershipsGreenwich + 1;
     file_put_contents('greenwichNumber.txt', $newNumberMembershipsGreenwich);
  } elseif (find_in_string('Dalston', $event->data->object['description'])){
     $numberMembershipsDalston = file_get_contents('dalstonNumber.txt');
     $newNumberMembershipsDalston = $numberMembershipsDalston + 1;
     file_put_contents('dalstonNumber.txt', $newNumberMembershipsDalston);
  }

?>

