<!-- components/header.php -->

<header class="bg-dark text-white py-3 mb-5">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/logo.png" alt="Logo" width="70vw" height="70vw" class="d-inline-block align-text-center">
            <h1 class="mb-0 ms-2" style="font-size: 3.5vw">Cohort Hive</h1>
        </a>
        <nav class="d-flex align-items-center">
            <!-- <a href="#" class="nav-link text-white me-3">Home</a>
            <a href="#" class="nav-link text-white me-3">Features</a>
            <a href="#" class="nav-link text-white me-3">Contact</a> -->
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <span class="nav-link text-white me-3"> <?php echo $_SESSION['username']; ?></span>
                <a href="components/logout.php" class="btn btn-outline-light ms-3 me-2">Logout</a>
            <?php else: ?>
                <a href="#" class="btn btn-outline-light ms-3 me-2 sign-in-btn">Sign In</a>
                <a href="#" class="btn btn-primary ms-2 sign-up-btn">Sign Up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<!-- Sign In Pop-up -->
<div id="signInPopup" class="popup hide">
    <button class="close-btn">&times;</button>
    <form method="POST">
        <div class="mb-3">
            <label for="signInEmail" class="form-label">Email address</label>
            <input type="text" class="form-control" id="signInEmail" name="username_or_email" placeholder="Enter Username or Email">
        </div>
        <div class="mb-3">
            <label for="signInPassword" class="form-label">Password</label>
            <input type="password" class="form-control" aria-describedby="passwordHelpInline" minlength="8" id="signInPassword" placeholder="Enter Password" name="password">
            <small id="passwordHelpInline" class="text-muted">
            Must be 8-20 characters long.
            </small>
        </div>
        <button type="submit" name="signin" class="btn btn-outline-secondary btn-lg btn-dark" style="--bs-btn-font-size: 1.1rem; --bs-btn-color: white">Sign In</button>
    </form>
</div>

<!-- Sign Up Pop-up -->
<div id="signUpPopup" class="popup hide">
    <button class="close-btn">&times;</button>
    <form method="POST">
        <div class="mb-3">
            <label for="signUpName" class="form-label">Full Name</label>
            <input type="text" class="form-control" id="signUpName" name="name" required>
        </div>
        <div class="mb-3">
            <label for="signUpUsername" class="form-label">Username</label>
            <input type="text" class="form-control" id="signUpUsername" name="username" required>
        </div>
        <div class="mb-3">
            <label for="signUpEmail" class="form-label">Email address</label>
            <input type="email" class="form-control" id="signUpEmail" name="email" required>
        </div>
        <div class="mb-3">
            <label for="signUpPassword" class="form-label">Password</label>
            <input type="password" class="form-control" aria-describedby="passwordHelpInline" id="signUpPassword" minlength="8" name="password" required>
            <small id="passwordHelpInline" class="text-muted">
            Must be 8-20 characters long.
            </small>
        </div>
        <div class="mb-3">
            <label for="signUpConfirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="signUpConfirmPassword" name="confirm_password" required>
        </div>
        <button type="submit" name="signup" class="btn btn-outline-secondary btn-lg btn-dark" style="--bs-btn-font-size: 1.1rem; --bs-btn-color: white">Sign Up</button>
    </form>
</div>

<!-- Background Blur -->
<div id="blurBackground" class="blurr hide"></div>

