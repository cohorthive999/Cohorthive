<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}
require_once "../../components/config.php";
require_once "../components/UserAuthorization.php";

if (isset($_POST['submit'])) {
    $repo_link = $_POST['link'];
    $room_id = $_SESSION['room_id'];
    $owner_id = $_SESSION['id'];
    $id = create_unique_id();

    // Function to validate GitHub repository link
    function validateGitHubLink($url) {
        return preg_match('/^(https?:\/\/)?(www\.)?github\.com\/([a-zA-Z0-9-]+)\/([a-zA-Z0-9_.-]+)\/?$/', $url);
    }

    if (validateGitHubLink($repo_link)) {
        // Extract username and reponame from GitHub link
        preg_match('/^(https?:\/\/)?(www\.)?github\.com\/([a-zA-Z0-9-]+)\/([a-zA-Z0-9_.-]+)\/?$/', $repo_link, $matches);
        $username = $matches[3];
        $reponame = $matches[4];

        // Prepare and bind parameters for inserting into github table
        $stmt = $conn->prepare("INSERT INTO `githubrepos` (ID, Room_ID, Owner_ID,UserName, Reponame) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id, $room_id, $owner_id, $username,$reponame);

        if ($stmt->execute()) {
            echo '<script>
                    window.alert("Repository Added!");
                    setTimeout(function(){
                    window.location.href = "viewrepo.php?owner='.$username.'&repo='.$reponame.'";
                }, 500);  
                  </script>';
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo '<script>
                window.alert("Invalid GitHub Repository Link!");
                setTimeout(function(){
                    window.location.href = "index.php";
                }, 500);
              </script>';
    }
}

?>
