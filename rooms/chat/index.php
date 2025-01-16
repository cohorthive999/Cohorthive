<?php 
require_once "../../components/config.php";
require_once "../components/UserAuthorization.php";
require_once "php/index.php";
 ?>

<?php require_once "header.php";?>
<body>
   <?php require_once "../../components/preloader.php"; ?>


  <div class="wrapper">
    <div class="upper-head">
        <a href="../index.php?room=<?php echo $_SESSION['room_code'];?>" class="back-to-room">
           <i class="fas fa-arrow-left"></i> Back To Room
        </a>
   </div>
    <section class="users">
      <header>
        <div class="content">
          <img src="profile-image.webp" alt="">
          <div class="details">
            <span><?php echo $user['Name']; ?></span>
            <p><?php echo $user['Status']; ?></p>
          </div>
        </div>
      </header>
      <div class="search">
        <span class="text">Select a user to start chat</span>
        <input type="text" placeholder="Enter name to search...">
        <button><i class="fas fa-search"></i></button>
      </div>
      <div class="users-list">
  
      </div>
    </section>
  </div>

  <script src="javascript/users.js"></script>

</body>
</html>
