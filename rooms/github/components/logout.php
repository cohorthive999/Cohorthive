<?php
session_start();
require_once "../../../components/config.php";
// Clear GitHub OAuth session data
unset($_SESSION['github_token']);
// Redirect to the index page or any other page you want after logout
header('Location: ../index.php');
exit;
?>