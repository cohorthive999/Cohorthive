<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once "components/config.php";

$result="";
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $user_id = $_SESSION['id'];

    $stmt = $conn->prepare("SELECT r.ID, r.Name, c.Room_code 
                            FROM `rooms` r 
                            INNER JOIN `usertoroom` ur ON r.ID = ur.Room_ID 
                            INNER JOIN `codetoroomid` c ON r.ID = c.Room_ID 
                            WHERE ur.User_ID = ?");
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
} else {
    $result = null; // If user is not logged in or no rooms found
}


$errors = [];
if(isset($_POST['signup'])){
    $id = create_unique_id();
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    

    if(empty($name)){
        $errors[] = "Name is required";
    }
    if(empty($username)){
        $errors[] = "Username is required";
    } else {
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0){
            $errors[] = "Username already taken";
        }
        $stmt->close();
    }
    
    if(empty($email)){
        $errors[] = "Email is required";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors[] = "Invalid email format";
    } else {
        $stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows > 0){
            $errors[] = "Email already taken";
        }
        $stmt->close();
    }
    if(empty($password)){
        $errors[] = "Password is required";
    }
    if($password !== $confirm_password){
        $errors[] = "Passwords do not match";
    }

    if(empty($errors)){
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user into database
        $stmt = $conn->prepare("INSERT INTO `users` (ID, Name, Username, Email, Password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("sssss", $id, $name, $username, $email, $hashed_password);

        if($stmt->execute()){
            echo '<script>window.alert("Registration successful!")</script>';
            if (session_status() == PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['loggedin']=true;
            $_SESSION['id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;

            // Automatically set a "remember me" cookie
            $token =$id; // Generate a random token
            setcookie('remember_me_cookie_cohorthive', $token, time() + 30 * 24 * 60 * 60, '/'); // Cookie expires in 30 days
            // Store the token in database or session storage for validation
            // $stmt = $conn->prepare("INSERT INTO `userbytoken` (User_ID, Token) VALUE(?,?)");
            // $stmt->bind_param("ss",$id,$token);
            // $stmt->execute();

        } else {
            // echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Display errors
        foreach($errors as $error){
            echo '<script>window.alert("'.$error.'")</script>';
        }
    }
    $conn->close();
}
if (isset($_POST['signin'])) {
    $usernameOrEmail = $_POST['username_or_email'];
    $password = $_POST['password'];

    if (empty($usernameOrEmail)) {
        $errors[] = "Username or Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        // Check if the username/email exists
        $stmt = $conn->prepare("SELECT ID, Name, Username, Email, Password FROM `users` WHERE username = ? OR email = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id,$name,$username,$email, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['loggedin']=true;
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $username;
                $_SESSION['name'] = $name;
                $_SESSION['email'] = $email;

                 //cookie
                 $token = $id;
                 setcookie('remember_me_cookie_cohorthive', $token, time() + 30 * 24 * 60 * 60, '/');
                //  $stmt = $conn->prepare("INSERT INTO `userbytoken` (User_ID, Token) VALUE(?,?)");
                //  $stmt->bind_param("ss",$id,$token);
                //  $stmt->execute();
                
                 echo '<script>
                   window.alert("Login Succesful!");
                   setTimeout(function(){
                   window.location.href = "index.php";
                   }, 500);
                 </script>';
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "Username or Email not found";
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        // Display errors
        foreach ($errors as $error) {
            echo '<script>window.alert("' . $error . '")</script>';
        }
    }
    $conn->close();
}

$errors = [];
if (isset($_POST['create_room'])) {
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        $owner_id = $_SESSION['id'];  
    } else {
        echo '<script>
            window.alert("Please login first!");
            setTimeout(function(){
                window.location.href = "index.php";
            }, 500);
        </script>';
        exit;
    }
    $id = create_unique_id();
    $room_name = $_POST['room_name'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $code = create_unique_code();

    // Validate inputs
    if (empty($room_name)) {
        $errors[] = "Room name is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        

        // Insert room into the database
        $stmt = $conn->prepare("INSERT INTO `rooms` (ID, Name, Password, Owner_ID) VALUES (?, ?, ?, ?)");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("ssss", $id, $room_name, $password, $owner_id);

        if ($stmt->execute()) {

            // Insert owner into usertoroom table
            $stmt2 = $conn->prepare("INSERT INTO `usertoroom` (User_ID, Room_ID) VALUES (?, ?)");
            $stmt2->bind_param("ss", $owner_id, $id);
            $stmt2->execute();
            $stmt2->close();

            // Insert room code into codetoroomid table
            $stmt3 = $conn->prepare("INSERT INTO `codetoroomid` (Room_code, Room_ID) VALUES (?, ?)");
            $stmt3->bind_param("ss", $code, $id);
            $stmt3->execute();
            $stmt3->close();

            // insert user details in chat_users table
            $stmt4=$conn->prepare("INSERT INTO `chat_users` (User_ID,Name,Room_ID,Status) VALUES(?,?,?,?) ");
            $status="Active now";
            $stmt4->bind_param("ssss",$owner_id,$_SESSION['name'],$id,$status);
            $stmt4->execute();
            $stmt4->close();

            // Redirect to room/index.php
            echo '<script>
                window.location.href = "rooms/index.php?room=' . $code . '";
               </script>';

            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // Display errors
        foreach ($errors as $error) {
            echo '<script>window.alert("' . $error . '")</script>';
        }
    }
    $conn->close();
}

$errors = [];
if (isset($_POST['join_room'])) {
    
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
        $user_id = $_SESSION['id'];  
    } else {
        echo '<script>
            window.alert("Please login first!");
            setTimeout(function(){
                window.location.href = "index.php";
            }, 500);
        </script>';
        exit;
    }
 
    $room_code = $_POST['room_code'];
    $password = $_POST['password'];
    // Validate inputs
    if (empty($room_code)) {
        $errors[] = "Room code is required";
    }
    else{
        $stmt = $conn->prepare("SELECT * FROM `codetoroomid` WHERE Room_code = ?");
        $stmt->bind_param("s", $room_code);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows<=0){
            $errors[]="Invalid Room Code";
        }
        $stmt->close();
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        // Check if room code exists and fetch room ID and hashed password
        $stmt = $conn->prepare("SELECT r.ID, r.Password FROM `rooms` r INNER JOIN `codetoroomid` c ON r.ID = c.Room_ID WHERE c.Room_code = ?");
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }
        $stmt->bind_param("s", $room_code);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($room_id, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if ($password===$hashed_password) {
                // Insert user into usertoroom table
                $stmt3=$conn->prepare("SELECT * FROM `usertoroom` WHERE User_ID =? and Room_ID=?");
                $stmt3->bind_param("ss",$user_id,$room_id);
                $stmt3->execute();
                $stmt3->store_result();
                if($stmt3->num_rows<=0){
                    $stmt2 = $conn->prepare("INSERT INTO `usertoroom` (User_ID, Room_ID) VALUES (?, ?)");
                    $stmt2->bind_param("ss", $user_id, $room_id);
                    $stmt2->execute();
                    $stmt2->close();
                }
                $stmt3->close();

                $stmt5=$conn->prepare("SELECT * FROM `chat_users` WHERE User_ID=? AND Room_ID=? ");
                $stmt5->bind_param("ss",$user_id,$room_id);
                $stmt5->execute();
                $stmt5->store_result();
                if($stmt5->num_rows<=0){
                    // insert user details in chat_users table
                    $stmt4=$conn->prepare("INSERT INTO `chat_users` (User_ID,Name,Room_ID,Status) VALUES(?,?,?,?) ");
                    $status="Active now";
                    $stmt4->bind_param("ssss",$user_id,$_SESSION['name'],$room_id,$status);
                    $stmt4->execute();
                    $stmt4->close();
                }
                $stmt5->close();


               // Redirect to room/index.php
               echo '<script>
                   window.location.href = "rooms/index.php?room=' . $room_code . '";
                    </script>';
                exit();
            } else {
                $errors[] = "Incorrect password for the room";
            }
        } else {
            $errors[] = "Room code not found";
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        // Display errors
        foreach ($errors as $error) {
            echo '<script>window.alert("' . $error . '")</script>';
        }
    }
    $conn->close();
}

?>
