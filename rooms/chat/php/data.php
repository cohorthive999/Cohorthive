<?php
while ($row = mysqli_fetch_assoc($query)) {
    $sql2 = "SELECT * FROM `messages` WHERE ((Incomming_ID = ? OR Outgoing_ID = ?) AND (Outgoing_ID = ? OR Incomming_ID = ?)) AND Room_ID=? ORDER BY ID DESC LIMIT 1";
    $stmt2 = $conn->prepare($sql2);
    if ($stmt2 === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt2->bind_param("sssss", $row['User_ID'],  $row['User_ID'],$user_id,$user_id, $_SESSION['room_id']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row2 = mysqli_fetch_assoc($result2);
    $result = (mysqli_num_rows($result2) > 0) ? $row2['Message'] : "No message yet!";
    $msg = (strlen($result) > 28) ? substr($result, 0, 28) . '...' : $result;
    $you = isset($row2['Outgoing_ID']) ? (($user_id == $row2['Outgoing_ID']) ? "You: " : "") : "";
    $offline = ($row['Status'] == "Offline") ? "offline" : "";
    $hid_me = ($user_id == $row['User_ID']) ? "hide" : "";

    $output .= '<a href="chat.php?user_id='. $row['User_ID'] .'">
                <div class="content">
                <img src="profile-image.webp" alt="">
                <div class="details">
                    <span>'. $row['Name'] .'</span>
                    <p>'. $you . $msg .'</p>
                </div>
                </div>
                <div class="status-dot '. $offline .'"><i class="fas fa-circle"></i></div>
            </a>';
    $stmt2->close();
}
?>
