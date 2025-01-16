<?php
session_start();
require_once "components/config.php";
if (!isset($_SESSION['github_token'])) {
    $authUrl = "https://github.com/login/oauth/authorize?client_id=" . GITHUB_CLIENT_ID . "&redirect_uri=" . GITHUB_REDIRECT_URI . "&scope=repo";
}
else{
    require_once "php/index.php";
    require_once "php/dataFetching.php";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms | <?php echo  $_SESSION['room_name']; ?></title>
    <link rel="stylesheet" href="../Github/css/index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../images/favicon.png" type="image/x-icon">      

</head>
<body>
    <?php require_once "../../components/preloader.php"?>
    <?php
    if (!isset($_SESSION['github_token'])) { ?>
    <div class="container-login">
        <h1>Login with GitHub</h1>
        <p>CohortHive need to be authorized in order to access your repositories.</p>
        <a class="btn btn-primary btn-lg" href="<?= $authUrl ?>">Login with GitHub</a>
    </div>
    <?php }else{ ?>
    <?php require_once "php/header.php"; ?>
    <div class="logoutBtn">
        <a href="components/logout.php"><button type="submit" class="btn btn-outline-tertiary btn-sm btn-danger addDocLinkBtn" style="--bs-btn-font-size: 0.9rem; --bs-btn-color: white" name="logout">Logout From Github</button></a>
    </div>

    <div class="blur"></div>
    <div class="container gitBeg">
        <div class="startSec">
            <div class="createSec">
                <div class="guidelinesTitle">Add A New Github Link</div>
                <div class="guidelinesText">
                    <ul>
                        <li>Go to Github</li>
                        <li>Copy the link of repository you want to add</li>
                        <li>Also authorize us to access your public and private repositories</li>
                        <!-- <li class="helpLink"><a href="#" class="helpCreateLink"><span>Get Help!</span></a></li> -->
                    </ul>
                    
                </div>
                <form method="POST" class="row g-2">
                    
                    <div class="col-10">      
                      <input type="githubLink" class="form-control" id="docLink" placeholder="Enter Github Link" name="link">
                    </div>
                    <div class="addDocLink">
                        <button type="submit" class="btn btn-outline-secondary btn-lg btn-dark addDocLinkBtn" style="--bs-btn-font-size: 0.9rem; --bs-btn-color: white" name="submit">Add Repository</button>
                    </div>
                </form>
               
            </div>

            <div class="existingDocs">
                <div class="existingDocsContent">
                    <div class="createdDocsTitle">Stored GitHub Links</div>
                    <div class="annTable">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Repo Name</th>
                            <th scope="col">Added By</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php 
                            if(!empty($github_repos)){
                            $i=1;
                            foreach($github_repos as $github_repo){  ?>
                               <tr>
                                 <th scope="row"><?php echo $i++; ?></th>
                                 <td><?php echo $github_repo['RepoName']; ?></td>
                                 <td><?php echo $github_repo['NameofUser']; ?></td>
                                 <td><a href="viewrepo.php?owner=<?php echo $github_repo['UserName']; ?>&repo=<?php echo $github_repo['RepoName']; ?>" class="actionLink"><button class="btn btn-outline-secondary btn-lg btn-dark seeAnnouncements" style="--bs-btn-font-size: 0.8rem; --bs-btn-color: white">Open</button></a></td>
                               </tr>
                            <?php }}else{ ?>
                                <tr>
                                    <td colspan="4" class="text-center">No Repository Added Yet!</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
    <script src="roomPage.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>