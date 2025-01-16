<?php 
// $timelineEntries = [];
// //To fetch Timeline
// // SQL query to fetch data from timeline and users tables
// $roomId = $_SESSION['room_id'];
// $timeline_sql ="SELECT ID,Title,Deadline,Details FROM `timeline` WHERE Room_ID = ? ORDER BY ID";
// $timeline_stmt = $conn->prepare($timeline_sql);
// $timeline_stmt->bind_param("s", $roomId);
// $timeline_stmt->execute();
// $timeline_result = $timeline_stmt->get_result();
// if($timeline_result->num_rows>0) {
//    $timelineEntries=$timeline_result->fetch_assoc();
// }
// $timeline_stmt->close();
// $conn->close();

$timelineEntries = [];

// To fetch Timeline
$roomId = $_SESSION['room_id'];

// Prepare the SQL query to fetch data from the timeline table
$timeline_sql ="SELECT 
        timeline.ID,
        timeline.Title,
        timeline.Deadline,
        timeline.Details,
        users.Name AS Owner_name
    FROM 
        `timeline`
    JOIN 
        `users` ON timeline.Owner_ID = users.ID
    WHERE 
        timeline.Room_ID = ?
    ORDER BY
        timeline.ID ASC
";

$timeline_stmt = $conn->prepare($timeline_sql);
$timeline_stmt->bind_param("s", $roomId);
$timeline_stmt->execute();
$timeline_result = $timeline_stmt->get_result();

// Check if the query returned any rows
if ($timeline_result && $timeline_result->num_rows > 0) {
    // Fetch all the results into an associative array
    $timelineEntries = $timeline_result->fetch_all(MYSQLI_ASSOC);
}
$timeline_stmt->close();

$conn->close();
?>