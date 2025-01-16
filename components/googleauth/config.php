<?php

//start session on web page
session_start();

//config.php

//Include Google Client Library for PHP autoload file
require_once '../../vendor/autoload.php';

//Make object of Google API Client for call Google API
$google_client = new Google_Client();

//Set the OAuth 2.0 Client ID
$google_client->setClientId('1084406674986-gjs3o9kf841se56qni4mke5sfhddf1fe.apps.googleusercontent.com');

//Set the OAuth 2.0 Client Secret key
$google_client->setClientSecret('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');

//Set the OAuth 2.0 Redirect URI
$google_client->setRedirectUri('http://localhost/CohortHive/index.php');

// to get the email and profile 
$google_client->addScope('email');

$google_client->addScope('profile');

?>