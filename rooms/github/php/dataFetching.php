<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once "../../components/config.php";
require_once "../components/UserAuthorization.php";

// Assuming room_id is stored in session
$room_id = $_SESSION['room_id'];

// Initialize array to hold the GitHub repositories data
$github_repos = [];

// Fetch GitHub repositories data with user names joined from users table
$stmt = $conn->prepare("SELECT gr.ID, gr.Room_ID, gr.UserName,gr.RepoName, u.Name AS NameofUser 
                       FROM githubrepos gr
                       INNER JOIN users u ON gr.Owner_ID = u.ID
                       WHERE gr.Room_ID = ?");
$stmt->bind_param("s", $room_id);
$stmt->execute();
$result = $stmt->get_result();

// Iterate through the results and store each row in $github_repos array
while ($row = $result->fetch_assoc()) {
    $github_repos[] = $row;
}
$stmt->close();
$conn->close();

// Now $github_repos should contain all GitHub repository data with owner names
?>
