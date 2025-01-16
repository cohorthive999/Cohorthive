<header class="bg-dark text-white py-3 mb-5">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <h1 class="mb-0" style="font-size: 1.5rem;">CohortHive | <?php echo $_SESSION['room_name']; ?></h1>
            <nav class="d-flex align-items-center ms-5">
                <a href="" class="nav-link text-white mx-3" style="font-size: 1.5rem;"><i class="bi bi-house"></i></a>
                <a href="chat/" class="nav-link text-white mx-3" style="font-size: 1.5rem;"><i class="bi bi-chat"></i></a>
                <a href="#" class="nav-link text-white mx-3" style="font-size: 1.5rem;"><i class="bi bi-camera-video"></i></a>
                <a href="#" class="nav-link text-white mx-3" style="font-size: 1.5rem;"><i class="bi bi-github"></i></a>
                <a href="#" class="nav-link text-white mx-3" style="font-size: 1.5rem;"><i class="bi bi-briefcase"></i></a>
                <a href="#" class="nav-link text-white mx-3" style="font-size: 1.5rem;"><i class="bi bi-easel"></i></a>
                <a href="#" class="nav-link text-white mx-3" style="font-size: 1.5rem;"><i class="bi bi-gear"></i></a>
            </nav>
        </div>
        <nav class="d-flex align-items-center">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                <span class="nav-link text-white me-3" style="font-size: 1.25rem;">
                    <i class="bi bi-person-circle me-2"></i> <?php echo $_SESSION['username']; ?>
                </span>
                <a href="../components/logout.php" class="btn btn-outline-light ms-3 me-2">Logout</a>
            <?php endif; ?>
        </nav>
    </div>
</header>