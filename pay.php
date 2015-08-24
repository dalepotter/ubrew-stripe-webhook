<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);


// Set path to the Stripe dependencies
// This file uses 'composer' automatically load in required classes based on the location 
// that it has installed them to.  If installing the Stripe library manually (for example, 
// via FTP) replace the following line with: require 'path-to-Stripe.php';
require 'vendor/autoload.php';

// Include the config file
require 'configStripe.php';

// If this page has received a data from the form, process it
if ($_POST) {
    // Set Stripe API key
    \Stripe\Stripe::setApiKey(stripeApiKey); // 'stripeApiKey' is defined in configStripe.php
  
    // Set default values
    $error = '';
    $success = '';
print_r($_POST);
    // Attempt to process the payment, based on the information sent to this page
    try {
      if (!isset($_POST['cardNumber'])){
          throw new Exception("The Stripe Token was not generated correctly");
      } 

          // Make the API call to create the payment
          \Stripe\Charge::create(array("amount" => 1000,
                                      "currency" => "gbp",
                                      "source" => array(
                                                        "number" => $_POST['cardNumber'],
                                                        "exp_month" => 8,
                                                        "exp_year" => 2016,
                                                        "cvc" => "314"
                                                       ),
                                      "description" => "UBrew membership Greenwich",
                                      ));
          $success = 'Your payment was successful.';
      }
    catch (Exception $e) {
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
        <h1>Charge $10 with Stripe</h1>
        <!-- to display errors returned by createToken -->
        <span class="payment-errors"><?= $error ?></span>
        <span class="payment-success"><?= $success ?></span>
        <form action="<?= $_SERVER['PHP_SELF']  ?>" method="post" id="payment-form">
            <div class="form-row">
                <label>Card Number</label>
                <input type="text" size="20" autocomplete="off" name="cardNumber" class="card-number" />
            </div>
            <div class="form-row">
                <label>CVC</label>
                <input type="text" size="4" autocomplete="off" class="card-cvc" />
            </div>
            <div class="form-row">
                <label>Expiration (MM/YYYY)</label>
                <input type="text" size="2" class="card-expiry-month"/>
                <span> / </span>
                <input type="text" size="4" class="card-expiry-year"/>
            </div>
            <button type="submit" class="submit-button">Submit Payment</button>
        </form>
    </body>
</html>
