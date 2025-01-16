<?php 
session_start();
if (isset($_SESSION['id'])) {
    require_once "../../../components/config.php";
    $outgoing_id = $_SESSION['id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $output = "";

    // Use a prepared statement for the SQL query
    $sql = "SELECT messages.*, chat_users.Name 
            FROM `messages` 
            LEFT JOIN `chat_users` ON chat_users.User_ID = messages.Outgoing_ID
             WHERE (
             (Outgoing_ID = ? AND Incomming_ID = ?) 
               OR (Outgoing_ID = ? AND Incomming_ID = ?)
              ) 
             AND (messages.Room_ID =chat_users.Room_ID)
            ORDER BY messages.ID";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $outgoing_id, $incoming_id,$incoming_id,$outgoing_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if ($row['Outgoing_ID'] === $outgoing_id) {
                $output .= '<div class="chat outgoing">
                            <div class="details">
                                <p>'. htmlspecialchars($row['Message']) .'</p>
                            </div>
                            </div>';
            } else {
                $output .= '<div class="chat incoming">
                            <img src="profile-image.webp" alt="">
                            <div class="details">
                                <p>'. htmlspecialchars($row['Message']) .'</p>
                            </div>
                            </div>';
            }
        }
    } else {
        $output .= '<div class="text">No messages are available. Once you send a message, they will appear here.</div>';
    }
    echo $output;
} else {
    header("location: index.php");
}
?>
