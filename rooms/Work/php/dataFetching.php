<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../../components/config.php";
require_once "../components/UserAuthorization.php";

// Assuming room_id is stored in session
$room_id = $_SESSION['room_id'];

// Initialize arrays to hold the documents, sheets, and slides
$documents=[];

// Fetch all documents, sheets, and slides of that room
$stmt = $conn->prepare("SELECT * FROM `documents` WHERE Room_ID = ?");
$stmt->bind_param("s", $room_id);
$stmt->execute();
$result = $stmt->get_result();

// Iterate through the results and separate them into their respective arrays(no neen now,)
while ($row = $result->fetch_assoc()) {
    $documents[] = $row;
}
$stmt->close();
$conn->close();

?>

