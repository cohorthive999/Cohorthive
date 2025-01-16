<?php
if(session_status()===PHP_SESSION_NONE){
    session_start();
}
require_once "../../components/config.php";
require_once "../components/UserAuthorization.php";

if (isset($_POST['submitdoc'])) {
    $docname = $_POST['docname'];
    $doclink = $_POST['link'];
    $room_id = $_SESSION['room_id'];
    $id=create_unique_id();
    // Function to validate the Google Docs/Sheets/Slides link
    function validateLink($url) {
        return preg_match('/^https:\/\/(docs|sheets|slides)\.google\.com\/(document|spreadsheets|presentation)\/d\/[a-zA-Z0-9_-]+(\/[a-zA-Z]*)?(\?.*)?$/', $url);
    }
    
    // Determine document type based on URL
    function getDocType($url) {
        if (strpos($url, 'docs.google.com/document') !== false) {
            return 'document';
        } elseif (strpos($url, 'docs.google.com/spreadsheets') !== false) {
            return 'sheet';
        } elseif (strpos($url, 'docs.google.com/presentation') !== false) {
            return 'slide';
        } else {
            return false;
        }
    }

    if (validateLink($doclink)) {
        $doctype = getDocType($doclink);
        if ($doctype) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO `documents` (ID,Room_ID, Link, Type,File_Name) VALUES (?,?, ?, ?, ?)");
            $stmt->bind_param("sssss", $id,$room_id, $doclink, $doctype, $docname);

            if ($stmt->execute()) {
                echo '<script>
                window.alert("Document Added!");
                setTimeout(function(){
                window.location.href = "File.php?file='.$id.'";
                }, 50000);
              </script>';
            } else {
                echo "Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo '<script>
            window.alert("Invalid Document Type!");
            setTimeout(function(){
            window.location.href = "index.php";
            }, 500);
          </script>';
        }
    } else {
        echo '<script>
        window.alert("Only Add Google Sheets, Docs or Slides!");
        setTimeout(function(){
        window.location.href = "index.php";
        }, 500);
      </script>';
    }

}

?>