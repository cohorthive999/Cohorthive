<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_COOKIE['remember_me_cookie_cohorthive']) && !isset($_SESSION['loggedin'])) {
    // Validate token against the database
    $token = $_COOKIE['remember_me_cookie_cohorthive'];
    $user_id=$token;

    $_SESSION['id']=$user_id;


    // $stmt = $conn->prepare("SELECT User_ID FROM `userbytoken` WHERE Token = ?");
    // $stmt->bind_param("s", $token);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // if ($result && $result->num_rows > 0) {
        // $row = $result->fetch_assoc();
        // $user_id = $row['User_ID'];
        // $stmt->close();

        $stmt = $conn->prepare("SELECT Name, Username, Email FROM `users` WHERE ID = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['Username'];
            $_SESSION['name'] = $row['Name'];
            $_SESSION['email'] = $row['Email'];
        }

        $stmt->close();
}

?>
