<?php 
  if(session_status()===PHP_SESSION_NONE){
    session_start();
  }
  require_once "../components/config.php";
  if(!$_SESSION['loggedin']){
    header("location : ../index.php");
  }
  $room_code="";

  if (isset($_GET['room'])) {
    $room_code = $_GET['room'];
    $_SESSION['room_code']=$room_code;
    // Prepare the statement to fetch Room_ID based on Room_code
    $stmt = $conn->prepare("SELECT Room_ID FROM `codetoroomid` WHERE Room_code = ?");
    $stmt->bind_param("s", $room_code);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($room_id);
    $stmt->fetch();
    $stmt->close();
    if (!empty($room_id)) {
        $_SESSION['room_id'] = $room_id;
    } else {
        echo "Room not found or invalid room code.";
    }
}
else{
    header("Location:../index.php");
}
if(isset($_SESSION['room_id'])){
    $stmt=$conn->prepare("SELECT Name,Password,Owner_ID FROM `rooms` WHERE ID=?");
    $stmt->bind_param("s",$_SESSION['room_id']);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($room_name,$room_password,$ownerid);
    $stmt->fetch();
    $stmt->close();
    $_SESSION['room_name']=$room_name;
    $_SESSION['room_password']=$room_password;
    $_SESSION['ownerid']=$ownerid;
}


// Fetch owner_id from rooms table using room_id
$query = $conn->prepare("SELECT Owner_ID FROM `rooms` WHERE ID = ?");
$query->bind_param("s", $_SESSION['room_id']);
$query->execute();
$result = $query->get_result();
$owner_id = $result->fetch_assoc()['Owner_ID'];

// Fetch owner's name from users table
$query = $conn->prepare("SELECT Name FROM `users` WHERE ID = ?");
$query->bind_param("s", $owner_id);
$query->execute();
$result = $query->get_result();
$owner_name = $result->fetch_assoc()['Name'];

// Fetch user IDs from rooms_users table excluding the owner
$query = $conn->prepare("SELECT User_ID FROM `usertoroom` WHERE Room_ID = ? AND User_ID != ?");
$query->bind_param("ss", $_SESSION['room_id'], $owner_id);
$query->execute();
$result = $query->get_result();

// To delete Announcements Older than 1 day
$sql = "DELETE FROM `announcements`
        WHERE Created_On < NOW() - INTERVAL 1 DAY 
        AND Room_ID = ?";

// Prepare and bind
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['room_id'] );
$stmt->execute();
$stmt->close();


$users = [];
while ($row = $result->fetch_assoc()) {
    $user_id = $row['User_ID'];

    // Fetch user details from users table
    $user_query = $conn->prepare("SELECT ID, Name FROM `users` WHERE ID = ?");
    $user_query->bind_param("s", $user_id);
    $user_query->execute();
    $user_result = $user_query->get_result();
    $user_data = $user_result->fetch_assoc();

    $users[] = $user_data;
}

if (isset($_GET['remove_user_id'])) {
    $user_id_to_remove = $_GET['remove_user_id'];
    $room_id = $_SESSION['room_id'];

    // Remove user from rooms_users table
    $query = $conn->prepare("DELETE FROM `usertoroom` WHERE Room_ID = ? AND User_ID = ?");
    $query->bind_param("ss", $room_id, $user_id_to_remove);
    $query->execute();

    // Remove user from chat_user table
    $query = $conn->prepare("DELETE FROM `chat_user` WHERE Room_ID = ? AND User_ID = ?");
    $query->bind_param("ss", $room_id, $user_id_to_remove);
    $query->execute();

    // Redirect to avoid resubmission on refresh
    header("Location:rooms/index.php?room=".$_SESSION['room_code']);

    exit;
}
if(isset($_GET['delete_timeline_id'])){
    $id=$_GET['delete_timeline_id'];

    $stmt=$conn->prepare("DELETE FROM `timeline` WHERE ID=?");
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $stmt->close();

    // $roomcode=$_SESSION['room_code'];
    header("Location:index.php?room=".$_SESSION['room_code']);
    exit;
}
if(isset($_POST['announce'])){
  // Escape user inputs for security (prevent SQL injection)
  $title = $conn->real_escape_string($_POST['title']);
  $description = $conn->real_escape_string($_POST['description']);
  $room_id = $_SESSION['room_id']; // Assuming you have room_id stored in session
  $user_id = $_SESSION['id']; // Assuming you have user_id stored in session

  // SQL query to insert data into announcements table
  $sql = "INSERT INTO `announcements` (Room_ID,User_ID,Title, Description) 
          VALUES ('$room_id', '$user_id','$title', '$description')";

  if ($conn->query($sql) === TRUE) {
      echo '<script>
            window.alert("Announcement Added Successfully!"); </script>';
      // Optionally, you can redirect or show a success message here
  } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
  }
}

// Fetching Announcements
$sql = "SELECT a.ID, a.Title, a.Description, a.User_ID, u.Name
        FROM announcements a
        LEFT JOIN users u ON a.User_ID = u.ID
        WHERE Room_ID = ?
        ORDER BY a.ID DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['room_id']);
$stmt->execute();
$result = $stmt->get_result();
$rowNumber = 1;

$announce_result=[];
if (isset($_GET['view_announcement'])) {
  $announcement_id = $_GET['view_announcement'];

  $query = $conn->prepare("SELECT a.ID, a.Title, a.Description, a.User_ID, u.Name
        FROM announcements a
        LEFT JOIN users u ON a.User_ID = u.ID
        WHERE a.ID = ?
        ORDER BY a.ID DESC");
  $query->bind_param("i", $announcement_id);
  $query->execute();
  $announce_result=$query->get_result();
}


if (isset($_POST['submitTimeline'])) {
  // Retrieve and sanitize form data
  $title = isset($_POST['Title']) ? trim($_POST['Title']) : '';
  $deadline = isset($_POST['Deadline']) ? trim($_POST['Deadline']) : '';
  $details = isset($_POST['Details']) ? trim($_POST['Details']) : '';

  $title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
  $deadline = htmlspecialchars($deadline, ENT_QUOTES, 'UTF-8');
  $details = htmlspecialchars($details, ENT_QUOTES, 'UTF-8');

  // Validate form data
  if (empty($title) || empty($deadline) || empty($details)) {
      echo "<script>alert('All fields are required.'); window.history.back();</script>";
      exit();
  }

  // Convert the deadline to a date format suitable for the database
  $deadlineDate = DateTime::createFromFormat('Y-m-d', $deadline);
  if (!$deadlineDate) {
      echo "<script>alert('Invalid deadline format. Please use DD-MM-YYYY.'); window.history.back();</script>";
      exit();
  }
  $formattedDeadline = $deadlineDate->format('Y-m-d');

  // Retrieve room ID and owner ID from session
  $roomId = $_SESSION['room_id'];
  $ownerId = $_SESSION['id'];
  // Insert timeline entry into the database
  $sql = "INSERT INTO timeline (Room_ID, Owner_ID, Title, Deadline, Details) VALUES (?, ?, ?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sssss", $roomId, $ownerId, $title, $formattedDeadline, $details);

  if ($stmt->execute()) {
      // Insert successful
      $roomcode=$_SESSION['room_code'];
      echo "<script>alert('Timeline entry added successfully.'); window.location.href ='index.php?room=$roomcode';</script>";
      exit();
  } else {
      // Insert failed
      echo "<script>alert('Error adding timeline entry: " . $stmt->error . "'); window.history.back();</script>";
  }
  $stmt->close();
}


?>