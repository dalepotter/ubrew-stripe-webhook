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

// Set default success and error messages
$error = '';
$success = '';

// If this page has received card data from the form, process it
if ($_POST) {
    // Set Stripe API key
    \Stripe\Stripe::setApiKey(stripeApiKey); // 'stripeApiKey' is defined in configStripe.php

    // Print all form data (for debugging)
    print_r($_POST);
    
    // Attempt to process the payment, based on the information sent to this page
    try {
      if (!isset($_POST['cardNumber'])){
          throw new Exception("The Stripe Token was not generated correctly");
      } 

      // Make the API call to create the payment
      \Stripe\Charge::create(array("amount" => 1000,  // £10.00
                                   "currency" => "gbp",
                                   "source" => array(
                                                      "number" => $_POST['cardNumber'],
                                                      "exp_month" => $_POST['cardExpiryMonth'],
                                                      "exp_year" => $_POST['cardExpiryYear'],
                                                      "cvc" => $_POST['cardCcv']
                                                     ),
                                   "description" => "UBrew Membership (" . $_POST['location'] . ")",
                                   ));
          // Set new success message
          $success = 'Your payment was successful.';
      }
      catch (Exception $e) {
          // If this brace is entered, there has been an error!
          // Set error message to be the error returned
          $error = $e->getMessage();
      }
}

// Output HTML for the payment form
?>

<!DOCTYPE html>
<html lang="en">
    <head>

    </head>
    <body>
        <h1>Sign-up to UBrew</h1>
        <!-- to display errors returned by createToken -->
        <span class="payment-errors"><?= $error ?></span>
        <span class="payment-success"><?= $success ?></span>
        <form action="<?= $_SERVER['PHP_SELF']  ?>" method="post" id="payment-form">
            <div class="form-row">
                <label>Card Number</label>
                <input type="text" size="20" autocomplete="off" name="cardNumber" class="card-number" value="4242424242424242" />
            </div>
            <div class="form-row">
                <label>CVC</label>
                <input type="text" size="4" autocomplete="off" name="cardCcv" class="card-cvc" value="123" />
            </div>
            <div class="form-row">
                <label>Expiration (MM/YYYY)</label>
                <input type="text" size="2" name= "cardExpiryMonth" class="card-expiry-month" value="01" />
                <span> / </span>
                <input type="text" size="4" name= "CardExpiryYear" class="card-expiry-year" value="2016" />
            </div>
            <div class="form-row">
                <label>Which location?</label>
                <input type="radio" name="location" value="Greenwich">Greenwich
                <input type="radio" name="location" value="Dalston">Dalston
            </div>
            <button type="submit" class="submit-button">Join us!</button>
        </form>
    </body>
</html>
