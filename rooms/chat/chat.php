<?php 
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
require_once "../../components/config.php";
require_once "../components/UserAuthorization.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['id'])) {
  header("location: ../../index.php");
  exit;
}

if (!isset($_GET['user_id'])) {
  // Handle the case where user_id is not set in the GET request
  header("location: index.php");
  exit;
}

$user_id = $_GET['user_id']; // the person we want to chat with, this is their user ID

// Include header
include_once "header.php"; 
?>
<body>
  
  <?php require_once "../../components/preloader.php"; ?>

  <div class="wrapper">
    <section class="chat-area">
      <header>
        <?php 
          // Use a prepared statement to fetch user details
          $stmt = $conn->prepare("SELECT * FROM `chat_users` WHERE User_ID = ?");
          $stmt->bind_param("s", $user_id);
          $stmt->execute();
          $result = $stmt->get_result();

          if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
          } else {
            header("location: index.php");
            exit;
          }
          $stmt->close();
        ?>
        <a href="index.php" class="back-icon"><i class="fas fa-arrow-left"></i></a>
        <img src="profile-image.webp" alt="">
        <div class="details">
          <span><?php echo htmlspecialchars($row['Name']); ?></span>
          <p><?php echo htmlspecialchars($row['Status']); ?></p>
        </div>
      </header>
      <div class="chat-box" id="chat-box">
        <!-- Messages will be loaded here -->
      </div>
      <form action="#" class="typing-area" id="message-form">
        <input type="text" class="incoming_id" name="incoming_id" value="<?php echo htmlspecialchars($user_id); ?>" hidden>
        <input type="text" name="message" class="input-field" placeholder="Type a message here..." autocomplete="off">
        <button type="submit"><i class="fab fa-telegram-plane"></i></button>
      </form>
    </section>
  </div>

  <script src="javascript/chat.js"></script>
</body>
</html>
