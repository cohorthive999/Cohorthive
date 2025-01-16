<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once "../../components/config.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit;
}

$stmt=$conn->prepare("UPDATE `chat_users` SET Status='Active Now' WHERE User_ID=?");
$stmt->bind_param("s",$_SESSION['id']);
$stmt->execute();
$stmt->close();


// Prepare and execute the SQL statement to fetch user details
$stmt = $conn->prepare("SELECT Name, Status FROM `chat_users` WHERE User_ID = ?");
if ($stmt === false) {
  die("Error preparing statement: " . $conn->error);
}
$stmt->bind_param("s", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

// Check if any rows were returned
if ($result->num_rows > 0) {
  // Fetch user data
  $user = $result->fetch_assoc();
  // Now you can use $user['Name'] and $user['Status'] as needed
} else {
  echo "User data not found.";
}

$stmt->close();
$conn->close();
?>
