<?php
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once "../../../components/config.php";

// Ensure user_id and room_id are set
if (isset($_SESSION['id']) && isset($_SESSION['room_id'])) {
    $user_id = $_SESSION['id'];
    $room_id = $_SESSION['room_id'];
} else {
    die("User ID or Room ID is not set in session.");
}

// Sanitize the search term
$searchTerm = isset($_POST['searchTerm']) ? mysqli_real_escape_string($conn, $_POST['searchTerm']) : '';

// Construct the SQL query using prepared statements
$sql = "SELECT * FROM `chat_users` WHERE NOT User_ID = ? AND Room_ID=? AND Name LIKE ? ";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Error preparing statement: " . $conn->error);
}

// Bind the parameters and execute the query
$searchTerm = '%' . $searchTerm . '%';
$stmt->bind_param("sss", $user_id,$room_id, $searchTerm);
$stmt->execute();
$query = $stmt->get_result();

// Process the query results
$output = "";
if ($query->num_rows > 0) {
    // Pass $query to data.php
    include_once "data.php";
} else {
    $output .= 'No user found related to your search term';
}

$stmt->close();
$conn->close();

echo $output;
?>
