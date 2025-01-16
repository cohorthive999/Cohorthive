<?php
session_start();
require_once "../../components/config.php";
require_once "../components/UserAuthorization.php";

$fileLink="";
if(isset($_GET['file'])){
    $file_id = $_GET['file'];

    // Prepare statement to fetch link based on file_id
    $stmt = $conn->prepare("SELECT Link FROM `documents` WHERE ID = ?");
    $stmt->bind_param("s", $file_id);
    $stmt->execute();
    $stmt->bind_result($file_link);
    
    // Fetch the result
    $stmt->fetch();
    $stmt->close();

    // Redirect to the fetched link
    if (!empty($file_link)) {
        $fileLink=$file_link;
    } else {
        echo '<script>
        window.alert("File Not Found!");
        setTimeout(function(){
        window.location.href = "index.php";
        }, 000);
      </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="css/work.css">
    <link rel="icon" href="../../images/favicon.png" type="image/x-icon"> 
</head>
<body>
    <?php echo '<script>
        window.alert('.$fileLink.')
        </script>';
        ?>
    <div class="startPart">
        <div class="backBtn"><a href="index.php" class="backButton"><i class="fa-solid fa-chevron-left"></i></a></div>
        <div class="title">Work Place</div>
    </div>
    
    <br>
    <div class="googleAppln">
        <iframe id="docsFrame" src="<?php echo $fileLink; ?>" title="Google Doc"></iframe>
    </div>

</body>
</html>