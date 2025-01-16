<?php
    session_start();
    require_once "../../../components/config.php";
    $user_id = $_SESSION['id'];
    $room_id=$_SESSION['room_id'];
    $sql = "SELECT * FROM `chat_users` WHERE NOT User_ID = ? AND Room_ID=? ORDER BY ID DESC";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("ss",$user_id,$room_id);
    $stmt->execute();
    $query=$stmt->get_result();
    $output = "";
    if($query->num_rows == 0){
        $output .= "No users are available to chat";
    }elseif($query->num_rows > 0){
        require_once "data.php";
    }
    echo $output;
?>