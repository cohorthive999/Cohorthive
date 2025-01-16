<?php
session_start();
require 'config.php';
require_once "../../../components/config.php";

if (isset($_GET['code'])) {
    $code = $_GET['code'];
    $tokenUrl = "https://github.com/login/oauth/access_token";
    $postData = [
        'client_id' => GITHUB_CLIENT_ID,
        'client_secret' => GITHUB_CLIENT_SECRET,
        'code' => $code,
        'redirect_uri' => GITHUB_REDIRECT_URI,
    ];

    $ch = curl_init($tokenUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    if (isset($data['access_token'])) {
        $_SESSION['github_token'] = $data['access_token'];            
        header('Location: ../index.php');
        exit;
    } else {
        echo "Error: Unable to obtain access token.";
    }
} 
else {
    echo "Error: No code parameter found.";
}
?>
