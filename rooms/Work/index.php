<?php
session_start();
require_once "php/index.php";
require_once "php/dataFetching.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rooms | <?php echo  $_SESSION['room_name']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
    <link rel="icon" href="../../images/favicon.png" type="image/x-icon">    
    <link rel="stylesheet" href="../Work/css/work.css">

</head>
<body>
    <?php require_once "../../components/preloader.php"?>
    <?php require_once "php/header.php"; ?>
    <div class="blur"></div>
    <div class="container">
        <div class="startSec">
            <div class="createSec">
                <div class="guidelinesTitle">Add A New Document</div>
                <div class="guidelinesText">
                    <ul>
                        <li>Change Access to "Anyone With the Link"</li>
                        <li>Change the Role to Editor</li>
                        <li>Copy the sharable link of the Documnent </li>
                        <li class="helpLink"><a href="#" class="helpCreateLink"><span>Get Help!</span></a></li>
                    </ul>
                    
                </div>
                <form method="POST" class="row g-2">
                    
                    <div class="col-auto">      
                      <input type="docLink" class="form-control" id="docLink" placeholder="Enter Document Link" name="link">
                    </div>
                    <div class="col-auto">
                        <input type="docName" class="form-control" id="docName" placeholder="Enter Document Name" name="docname">  
                    </div>
                    <div class="addDocLink">
                        <button type="submit" class="btn btn-outline-secondary btn-lg btn-dark addDocLinkBtn" style="--bs-btn-font-size: 0.9rem; --bs-btn-color: white" name="submitdoc">Add Document</button>
                    </div>
                </form>
               
            </div>

            <div class="existingDocs">
                <div class="existingDocsContent">
                    <div class="createdDocsTitle">Created Documents</div>
                    <div class="annTable">
                      <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">No.</th>
                            <th scope="col">FileName</th>
                            <th scope="col">FileType</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody class="table-group-divider">
                            <?php 
                            if(!empty($documents)){
                            $i=1;
                            foreach($documents as $document){  ?>
                               <tr>
                                 <th scope="row"><?php echo $i++; ?></th>
                                 <td><?php echo $document['File_Name']; ?></td>
                                 <td><?php echo $document['Type']; ?></td>
                                 <td><a href="File.php?file=<?php echo $document['ID']; ?>" class="actionLink"><button class="btn btn-outline-secondary btn-lg btn-dark seeAnnouncements" style="--bs-btn-font-size: 0.8rem; --bs-btn-color: white">Open</button></a></td>
                               </tr>
                            <?php }}else{ ?>
                                <!-- <td>No File Added Yet!</td> -->
                                <tr>
                                    <td colspan="4" class="text-center">No File Added Yet!</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                      </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="viewMediaAnnouncementid" class="viewHelpMedia form hide">
      <button class="close-btn">&times;</button>
      <div class="helpTitle">Steps To Get Link</div>
      <div class="mediaForm">
        <form>
          <div class="media"></div>
      </form>
      </div>
    </div>
    <script src="../Work/js/work.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="js/index.js"></script>
</body>
</html>