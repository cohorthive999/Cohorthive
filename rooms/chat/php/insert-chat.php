<?php 
session_start();
if(isset($_SESSION['id'])){
    include_once "../../../components/config.php";
    $outgoing_id = $_SESSION['id'];
    $room_id=$_SESSION['room_id'];
    $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    if(!empty($message)){
        // Use a prepared statement for the insert query
        $sql = "INSERT INTO `messages` (Room_ID,Incomming_ID, Outgoing_ID, Message) VALUES (?,?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $stmt->bind_param("ssss", $room_id,$incoming_id, $outgoing_id, $message);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Error preparing statement: " . $conn->error);
        }
    }
} else {
    header("location: index.php");
}
?>
