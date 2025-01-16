<?php
session_start(); // Start the session

require_once "config.php";

$sql = "UPDATE `chat_users` SET Status='Offline' WHERE User_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['id']);
$stmt->execute();
$stmt->close();


// $stmt = $conn->prepare("DELETE FROM `userbytoken` WHERE User_ID=?");
// $stmt->bind_param("s",$_SESSION['id']);
// $stmt->execute();

//unsetting cookie
setcookie('remember_me_cookie_cohorthive', '', time() - 30 * 24 * 60 * 60, '/');

// Destroy the session
session_destroy();

// Redirect to the homepage or login page
header("Location: ../index.php");
exit;
?>
