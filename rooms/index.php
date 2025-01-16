<?php
require_once "../components/config.php";
require_once "../components/autologinbycookie.php";
?>
<?php require_once "index-php.php"; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms | <?php echo  $_SESSION['room_name']; ?></title>

    <link rel="stylesheet" href="css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="icon" href="../images/favicon.png" type="image/x-icon">
    <style>
        /* Custom CSS to disable resizing */
        textarea {
            resize: none !important;
        }
    </style>
</head>
<body>

   <?php require_once "../components/preloader.php"; ?>
   <?php require_once "components/header.php";?>

  <div class="blur"></div>
    <div class="container">
      <div class="startSec">
        <div class="roomDet">
          <div class="roomName"><?php echo  $_SESSION['room_name']; ?></div>
          <div class="ownerName"><?php echo  $owner_name; ?></div>
          <div id="roomId" class="roomsubdet"><span class="idtitle">Code: </span><?php echo $room_code; ?><button class="btn btn-sm" onclick="copyID()"><i class="fa-regular fa-copy"></i></button></div>
          <div id="roomPass" class="roomsubdet"><span class="idtitle">Password: </span><?php echo  $_SESSION['room_password']; ?><button class="btn btn-sm" onclick="copyPass()"><i class="fa-regular fa-copy"></i></button></div>
            <div class="attendeeList">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Attendee Name</th>
                    <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody class="table-group-divider">
                  <?php
                    $i=1;
                    foreach ($users as $user): ?>
                    <tr>
                      <th scope="row"><?php echo $i++;?></th>
                      <td><?php echo htmlspecialchars($user['Name']); ?></td>
                      <td>
                        <?php if ($_SESSION['id'] != $owner_id): ?>
                          <a href="?remove_user_id=<?php echo htmlspecialchars($user['ID']); ?>" class="actionLink" disabled style="cursor: not-allowed;">Kick</a>
                        <?php else: ?>
                          <a href="?remove_user_id=<?php echo htmlspecialchars($user['ID']); ?>" class="actionLink">Kick</a>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
              </table>
            </div>
        </div>

        <div class="announcements">
          <div class="accouncementsContent">
            <div class="roomName">Announcements</div>
            <div class="annTable">
              <table class="table">
                <thead>
                  <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Title</th>
                    <th scope="col">Creator</th>
                    <th scope="col">Action</th>
                  </tr>
                </thead>
                <tbody class="table-group-divider">
                  <?php
                          
                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $title = htmlspecialchars($row['Title']);
                      $description = htmlspecialchars($row['Description']);
                      $creator = htmlspecialchars($row['Name']);
                      echo '<tr>';
                      echo '<th scope="row">' . $rowNumber . '</th>';
                      echo '<td>' . $title . '</td>';
                      echo '<td>' . $creator . '</td>';
                      echo '<td><button class="btn btn-outline-secondary btn-lg btn-dark seeAnnouncements" style="--bs-btn-font-size: 0.8rem; --bs-btn-color: white" data-title="' . $title . '" data-description="' . $description . '" onclick="showAnnouncementDetails(this)">Read</button></td>';
                      echo '</tr>';
                      $rowNumber++;
                    }
                  } else {
                      echo '<tr><td colspan="4">No announcements found.</td></tr>';
                    }  
                  ?>
                </tbody>
              </table>
            </div>
            <div class="addAnnouncement"><button class="btn btn-outline-secondary btn-lg btn-dark addAnnouncement" style="--bs-btn-font-size: 0.9rem; --bs-btn-color: white">Add Announcement</button></div>
          </div>
        </div>
      </div>
        
        

      <!-- Timeline Part -->
      <div class="timelinePart">
            <div class="row">
              <div class="col-md-12">
                  <div class="card" style="background: rgba(255, 255, 255, 0.45);">
                      <div class="card-body" >
                        <div class="timelineHead">
                          <h6 class="card-title">Timeline</h6>
                          <div class="addToTimeline"><button class="btn btn-outline-secondary btn-lg btn-dark addToTimelineBtn" style="--bs-btn-font-size: 0.9rem; --bs-btn-color: white">Add To Timeline</button></div>
                        </div>
                          <div id="content">
                              <ul class="timeline" >
                                <?php 
                                require_once "timeline.php";
                                if(!empty($timelineEntries)){
                                  foreach ($timelineEntries as $timeline_entry){ ?>
                                  <li class="event" data-date="<?php echo htmlspecialchars($timeline_entry['Deadline']); ?>">
                                      <h3><?php echo htmlspecialchars($timeline_entry['Title']) ?></h3>
                                      <h2><?php  echo $timeline_entry['Owner_name']; ?></h2>
                                      <div class="deadline">By- <span><?php  echo $timeline_entry['Deadline']; ?></span></div>
                                      <p><?php echo  $timeline_entry['Details']; ?></p>
                                      <a href="?delete_timeline_id=<?php echo $timeline_entry['ID'];?>"><div class="deleteicon"><i class="fa-regular fa-trash-can"></i></div></a>
                                  </li>
                                  <?php } ?>
                                <?php } else{ ?>
                                    <li> No Timeline Found</li>
                                  <?php } ?>
                              </ul>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          </div>
      </div>
    </div>

    
    <div id="createAnnouncementid" class="createAnnouncement form hide">
      <button class="close-btn">&times;</button>
      <form method="POST">
          <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" id="title" aria-describedby="emailHelp" name="title" required>
          </div>
          <div class="mb-4">
              <label for="description" class="form-label">Description</label>
              <textarea class="form-control" id="description" rows="3" name="description" required></textarea>
          </div>
          <div class="mb-3">
              <label for="formFile" class="form-label">Upload File</label>
              <input class="form-control" type="file" id="formFile" disabled style="cursor: not-allowed;">
          </div>
          <button type="submit" name="announce" class="btn btn-outline-secondary btn-lg btn-dark" style="--bs-btn-font-size: 1.1rem; --bs-btn-color: white">Submit</button>
      </form>
    </div>
    <div id="viewAnnouncementid" class="viewAnnouncement form hide">
        <button class="close-btn">&times;</button>
        <div class="roomName" id="announcementTitle"></div>
        <div class="description" id="announcementDescription"></div>
    </div>
    <div id="addToTimeline" class="addToTimelineFrom form hide">
        <button class="close-btn">&times;</button>
        <form method="POST" action="#">
            <div class="mb-3">
              <label for="title" class="form-label">Title</label>
              <input type="text" class="form-control" id="timelineTitle" aria-describedby="emailHelp" name="Title" placeholder="Title of Timeline">
            </div>
            
            <div class="mb-3">
              <label for="deadline" class="form-label">Deadline</label>
              <input type="date" class="form-control" id="deadline" name="Deadline" placeholder="DD-MM-YYYY">
          </div>
          <div class="mb-3">
            <label for="deadlineTextArea" class="form-label">Details</label>
            <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="Details" placeholder="Enter Details"></textarea>
          </div>
            <button type="submit" class="btn btn-outline-secondary btn-lg btn-dark" style="--bs-btn-font-size: 1.1rem; --bs-btn-color: white" name="submitTimeline">Submit</button>
          </form>
    </div>
    <!-- Settings Dialogue Box -->
    <div id="settings-form" class="settingsForm form hide">
        <button class="close-btn" onclick="closeSettingsForm()">&times;</button>
        <form method="POST" action="settings.php">
            <h1>Settings</h1>
            <div class="mb-3">
                <label for="changeroomName" class="form-label">Change Room Name</label>
                <input type="text" class="form-control" id="changeroomName" name="changeroomName" aria-describedby="emailHelp">
            </div>
            
            <div class="mb-3">
                <label for="changeroomPass" class="form-label">Change Room Password</label>
                <input type="password" class="form-control" id="changeroomPass" name="changeroomPass" aria-describedby="emailHelp">
            </div>
            
            <button type="submit" name="updateRoom" class="btn btn-outline-secondary btn-lg btn-dark" style="--bs-btn-font-size: 1.1rem; --bs-btn-color: white">Submit</button>
            <button type="submit" name="deleteRoom" class="btn btn-danger btn-outline btn-lg ms-5 mx-2" style="--bs-btn-font-size: 1.1rem; --bs-btn-color: white;">Delete Room</button>
        </form>
    </div>



  <?php require_once "../components/footer.php";?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>
</body>
</html>

<script>
  function showAnnouncementDetails(button) {
    var title = button.getAttribute('data-title');
    var description = button.getAttribute('data-description');
    document.getElementById('announcementTitle').textContent = title;
    document.getElementById('announcementDescription').textContent = description;
    document.getElementById('viewAnnouncementid').classList.remove('hide');
}
</script>

