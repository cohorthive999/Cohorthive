<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}
$room_id=$_SESSION['room_id'];
$user_id=$_SESSION['id'];

$stmt=$conn->prepare("SELECT * FROM `usertoroom` WHERE Room_ID=? AND User_ID=?");
$stmt->bind_param("ss",$room_id,$user_id);
$stmt->execute();
$result=$stmt->get_result();
if($result->num_rows<=0){
    echo '<script>
    window.alert("Invalid User For this Room!");
    setTimeout(function(){
    window.location.href = "../index.php";
    }, 000);
  </script>';
}

?>