<header class="bg-dark text-white py-0 mb-1">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="../../index.php">
                    <img src="../../images/logo.png" alt="Logo" width="70" height="70" class="d-inline-block align-text-center">
                    <h1 class="mb-0 ms-2">Cohort Hive</h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="../index.php?room=<?php echo $_SESSION['room_code']; ?>" class="nav-link text-white d-flex align-items-center mx-2">
                                <i class="bi bi-house me-2"></i><span class="d-lg-none">Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../chat/" class="nav-link text-white d-flex align-items-center mx-2">
                                <i class="bi bi-chat me-2"></i><span class="d-lg-none">Chat</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../Conference" class="nav-link text-white d-flex align-items-center mx-2">
                                <i class="bi bi-camera-video me-2"></i><span class="d-lg-none">Conference</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="../github" class="nav-link text-white d-flex align-items-center mx-2">
                                <i class="bi bi-github me-2"></i><span class="d-lg-none">GitHub</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link text-white d-flex align-items-center mx-2">
                                <i class="bi bi-briefcase me-2"></i><span class="d-lg-none">Documents</span>
                            </a>
                        </li>
                        <!-- <li class="nav-item">
                            <a href="#" class="nav-link text-white d-flex align-items-center mx-2">
                                <i class="bi bi-easel me-2"></i><span class="d-lg-none">Presentations</span>
                            </a>
                        </li> -->
                        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                            <li class="nav-item">
                                <span class="nav-link text-white d-flex align-items-center" style="font-size: 1.25rem; cursor:default">
                                    <i class="bi bi-person-circle mx-3 me-2"></i> <?php echo $_SESSION['username']; ?>
                                </span>
                            </li>
                            <li class="nav-item">
                                <a href="../components/logout.php" class="btn btn-outline-light ms-3 me-3">Logout</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>





    <style>
        .nav-link {
            font-size: 1.5rem;
        }
        @media (min-width: 992px) {
            .navbar-nav .nav-link {
                margin-right: 2rem; 
            }
        }
    </style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Event listener for settings icon click
        $('#settings-icon').click(function(e) {
            e.preventDefault(); // Prevent default link behavior
            
            // Smooth scroll to top of the page
            window.scroll({ top: 0, left: 0, behavior: 'smooth' });
            
            // Display the form and apply styles
            console.log("Inside settings form!");
            $('#settings-form').removeClass("hide");
            $('#settings-form').css({
                'position': 'fixed',
                'top': '50%',
                'left': '50%',
                'transform': 'translate(-50%, -50%)',
                'background-color': 'rgba(255, 255, 255, 0.9)',
                'z-index': '1000',
                'padding': '20px',
                'box-shadow': '0 0 10px rgba(0, 0, 0, 0.5)',
                'width': '90vw',
                'max-width': '500px'
            });
            $('.container').addClass("blur");
            $('body').addClass("no-vertical-scroll");
        });

        // Event listener for close button click
        $('#settings-form .close-btn').click(function() {
            $('#settings-form').addClass("hide");
            $('.container').removeClass("blur");
            $('body').removeClass("no-vertical-scroll");
        });
    });
</script>