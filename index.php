<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beatbox GG</title>
    <style>
        /* Styles for Google Sign-In button */
        .google-signin-btn {
            display: inline-block;
            background: #357ae8;
            color: #fff;
            border-radius: 4px;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
        }

        .google-signin-btn:hover {
            background: skyblue;
        }
    </style>
</head>
<body>
  <?php
require_once 'vendor/autoload.php';

// Set up your Stripe API key
\Stripe\Stripe::setApiKey('');

// init configuration
$clientID = '';
$clientSecret = '';
$redirectUri = 'http://localhost/gg/indexx.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;
                      echo "<br><center> <h1> welcome, " . $name. "</h1> </center>";
  // Now check if the user is subscribed to your Stripe service
  try {
    // Retrieve the customer object from Stripe using the email address
    $customers = \Stripe\Customer::all(['email' => $email]);
    
    // Check if any customer matches the email address
    if (!empty($customers->data)) {
        foreach ($customers->data as $customer) {
            // Retrieve the customer's subscriptions
            $subscriptions = \Stripe\Subscription::all(['customer' => $customer->id]);
            
            // Check if the customer has an active subscription
            foreach ($subscriptions->data as $subscription) {
                if ($subscription->status === 'active') {
                    // User is subscribed, do something
                    echo "<br><center> <h1> You are subscribed!</h1> </center>";
                    // You can break out of the loops if you only need to check for one subscription
                    break 2;
                }
            }
        }
    } else {
        // No customer found with the given email address
        echo "<br><center> <h1>Free Tier</h1> </center>";
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Handle any errors from Stripe API requests
    echo "Error: " . $e->getMessage();
}
} else {?>
<center>
    <h1>Beatbox GG!</h1>
    <img src="google.JPG" width="5%" height="5%">
    <a class="google-signin-btn" href="<?php echo $client->createAuthUrl(); ?>">Sign in with Google</a><br>
<script async
  src="https://js.stripe.com/v3/buy-button.js">
</script>

<stripe-buy-button
  buy-button-id="buy_btn_1OnZFcDrTnlyAO2LEHFeclCb"
  publishable-key="pk_live_51MeyrHDrTnlyAO2LD172wTVpRmwuZNaC2NnPKCDlVRlJdv2o6DbeT0V1VR28jPxkIOKGUhs1NWREGxTjQTaIXAbl00Jshl6vvY"
>
</stripe-buy-button></center>
</body>
<?php } ?>

</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
