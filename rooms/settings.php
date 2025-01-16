<?php
session_start();
require_once "../components/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['updateRoom'])) {
        $newRoomName = ( isset($_POST['changeroomName']) && !empty($_POST['changeroomName']) ) ? trim($_POST['changeroomName']) : $_SESSION['room_name'];
        $newRoomPassword = (isset($_POST['changeroomPass']) && !empty($_POST['changeroomPass']) ) ? trim($_POST['changeroomPass']) : $_SESSION['room_password'];

        // Sanitize input
        $newRoomName = htmlspecialchars($newRoomName, ENT_QUOTES, 'UTF-8');
        $newRoomPassword = htmlspecialchars($newRoomPassword, ENT_QUOTES, 'UTF-8');

  
        $roomId = $_SESSION['room_id'];

        // Update room details in the database
        $sql = "UPDATE `rooms` SET Name = ?, Password = ? WHERE ID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $newRoomName, $newRoomPassword, $roomId);

        if ($stmt->execute()) {
            // Update successful
            $_SESSION['room_name'] = $newRoomName;
            $_SESSION['room_password'] = $newRoomPassword;
            $roomcode=$_SESSION['room_code'];
            header("Location: index.php?room=$roomcode");

        } else {
            // Update failed
            echo "Error updating room details: " . $stmt->error;
        }
        $stmt->close();
    } 
    if (isset($_POST['deleteRoom'])) {
        // Delete the room and all related entries
        $roomId = $_SESSION['room_id'];

        // Start transaction
        $conn->begin_transaction();

        try {
            // Delete from rooms table
            $sql = "DELETE FROM rooms WHERE ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roomId);
            $stmt->execute();
            $stmt->close();

            // Delete from messages table
            $sql = "DELETE FROM messages WHERE Room_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roomId);
            $stmt->execute();
            $stmt->close();

            // Delete from usertoroom table
            $sql = "DELETE FROM usertoroom WHERE Room_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roomId);
            $stmt->execute();
            $stmt->close();

            // Delete from codetoroomid table
            $sql = "DELETE FROM codetoroomid WHERE Room_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roomId);
            $stmt->execute();
            $stmt->close();

            // Delete from chat_users table
            $sql = "DELETE FROM chat_users WHERE Room_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roomId);
            $stmt->execute();
            $stmt->close();

            // Delete from announcements table
            $sql = "DELETE FROM announcements WHERE Room_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roomId);
            $stmt->execute();
            $stmt->close();

            // Delete from timeline table
            $sql = "DELETE FROM timeline WHERE Room_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $roomId);
            $stmt->execute();
            $stmt->close();

            // Commit transaction
            $conn->commit();

            // Clear session variables related to the room
            unset($_SESSION['room_id']);
            unset($_SESSION['room_name']);
            unset($_SESSION['room_password']);

            // echo "Room and all related entries deleted successfully.";
            echo '<script>
            window.alert("Deleted Succesful!");
            setTimeout(function(){
            window.location.href = "index.php";
            }, 500);
          </script>';
        } catch (Exception $e) {
            // Rollback transaction if any query fails
            $conn->rollback();
            echo "Error deleting room and related entries: " . $e->getMessage();
        }
    }
}

$conn->close();
?>
