<!DOCTYPE html>
<html lang="en">
<?php
    include "session.php";
    include "header_nav.php";
    include "../db/conect_database.php";
    include "../db/users.php";
    include "../db/crud.php";

    $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

    if (isset($_POST['submit']) && $isLoggedIn ) {

        if (isset($_FILES['fileToUpload']) && $_FILES['fileToUpload']['size'] > 0 && !empty($_FILES['fileToUpload']['name'])) {

            // Next, check if the image file is an actual image or a fake image

            // $target_dir = "../uploads/" - specifies the directory where the file is going to be placed
            $SqlOperationUser = new Users($Connection);
            $User = $_SESSION['username'];

            $UserQuery  = $SqlOperationUser->GetUser($User);
            $UserId     = $UserQuery['id_hacker'];
            $Username   = trim($UserQuery['hacker_name']);
            $target_dir = "../uploads/$Username/";

            // create a directory if it doesn't exist
            if (! is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            // $target_file specifies the path of the file to be uploaded
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

            // $uploadOk=1 is not used yet (will be used later)
            $uploadOk = 0;

            // $imageFileType holds the file extension of the file (in lower case)
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            $ImagesSaved = $SqlOperationUser->UserImages($UserId);
            if (file_exists($target_file)) {

                // "tmp_name" is the temporary file name along with the file size
                if (filesize($_FILES["fileToUpload"]["tmp_name"]) == filesize($target_file)) {
                    ?>
                    <div class="d-flex justify-content-center align-items-center" style="min-height:40vh;">
                        <div class="alert alert-danger text-center p-2 m-0" style="max-width:320px;">
                            The file is exactly the same as the first uploaded file.<br><small>forwaring...</small>
                        </div>
                    </div>
                    <script>setTimeout(function(){ window.location.href = "load_data.php"; }, 1800);</script>
                    <?php
                    $uploadOk = 0;
                } else {
                    $target_file = $target_dir . time() . "." . $imageFileType;
                    $uploadOk    = 1;
                }
            } else {
                $uploadOk = 1;
                
            //        
            }

            // Correction to ensure the image is stored correctly in the database
            if ($uploadOk == 1) {
                // Validate if the file is an image
                $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
                if ($check !== false) {
                    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                        // Save that the image was uploaded
                        $imageUploaded = true;

                        // Verify if there is already an image for the logged in user
                        $existingImages = $SqlOperationUser->UserImages($UserId);
                        $hasEmptyImage = false;

                        while ($ImageDb = $existingImages->fetch(PDO::FETCH_ASSOC)) {
                            if (empty($ImageDb['image_path'])) {
                                // Update the image if there is an empty record
                                $SqlOperationUser->UpdateUserImages($ImageDb['id_image'], $target_file);
                                $hasEmptyImage = true;
                                break;
                            }
                        }

                        if (!$hasEmptyImage) {
                            // Insert a new image if there are no empty records
                            $SqlOperationUser->InsertUserImages($UserId, $target_file);
                        }
                    } else {
                        $imageUploaded = false;
                        echo "<div class='alert alert-danger'>Error moving the uploaded file.</div>";
                    }
                } else {
                    $imageUploaded = false;
                    echo "<div class='alert alert-danger'> The file is not a valid image.</div>";
                }
            } else {
                $imageUploaded = false;
                echo "<div class='alert alert-danger'>Could not upload the image.</div>";
            }
        }


        if (isset($_POST['hacker_name']) && !empty($_POST['hacker_name']) && !empty($_POST['programming_lenguage']) && !empty($_POST['profetion']) && 
            !empty($_POST['skill']) && !empty($_POST['level'])  && !empty($_POST['id_academy'])) {

            $UsersSvc = new Users($Connection);
            $SqlOperation = new Crud($Connection);
            $HackerName = trim($_POST['hacker_name']);
            $ProgrammingLenguage = (int)$_POST['programming_lenguage'];
            $Profetion = trim($_POST['profetion']);
            $Skill = trim($_POST['skill']);
            $level = isset($_POST['level']) ? trim($_POST['level']) : '';
            $Academy = isset($_POST['id_academy']) ? (int)$_POST['id_academy'] : null;

            // 1) Create user credentials (users.php handles password hashing)
            $password_raw = isset($_POST['password']) ? $_POST['password'] : 'defaultpassword';
            $created = $UsersSvc->NewUser($HackerName, $password_raw);

            if ($created) {
                // 2) fetch created user id
                $userRow = $UsersSvc->GetUser($HackerName);
                if ($userRow && isset($userRow['id_hacker'])) {
                    $newUserId = (int)$userRow['id_hacker']; // ✅ Correct field

                    // 3) set programming language
                    if (!empty($ProgrammingLenguage)) {
                        $SqlOperation->set_programming_language_single($newUserId, $ProgrammingLenguage);
                    }

                    // 4) set profession (can be name or id from the form)
                    if (is_numeric($Profetion)) {
                        $SqlOperation->set_profetion_by_id($newUserId, (int)$Profetion);
                    } else {
                        $SqlOperation->set_profetion_single($newUserId, $Profetion);
                    }

                    // 5) set skill + level
                    $level_id = is_numeric($level) ? (int)$level : null;
                    if ($level_id !== null) {
                        $SqlOperation->set_skill_and_level_single($newUserId, $Skill, $level_id);
                    }

                    // 6) set academy (find or create + map)
                    if (!empty($Academy)) {
                        $SqlOperation->set_academy_by_name($newUserId, $Academy);
                    }

                    $DataInsertion = true;
                } else {
                    $DataInsertion = false;
                }
            } else {
                $DataInsertion = false;
            }
        } else {
            $DataInsertion = false;
        }

        
    }else{
        $errorMessage = "All fields are required for registration";
        echo $errorMessage;
        header('Location:  ../index.php');
        exit();
    }


    // Display message based on the result
    if (isset($DataInsertion) || isset($imageUploaded)) {
        if ($DataInsertion && isset($imageUploaded) && $imageUploaded) {
            ?>
            <div class="d-flex justify-content-center align-items-center" style="min-height:40vh;">
                <div class="alert alert-success text-center p-2 m-0" style="max-width:320px;">
                    User registered and image uploaded successfully.<br><small>forwarding...</small>
                </div>
            </div>
            <script>setTimeout(function(){ window.location.href = "load_data.php"; }, 1800);</script>
            <?php
        } elseif ($DataInsertion && !isset($imageUploaded) ) {
            ?>
            <div class="d-flex justify-content-center align-items-center" style="min-height:40vh;">
                <div class="alert alert-warning text-center p-2 m-0" style="max-width:320px;">
                    User registered but image not uploaded.<br><small>forwarding...</small>
                </div>
            </div>
            <script>setTimeout(function(){ window.location.href = "load_data.php"; }, 1800);</script>
            <?php
        } elseif (!$DataInsertion && isset($imageUploaded) && $imageUploaded) {
            ?>
            <div class="d-flex justify-content-center align-items-center" style="min-height:40vh;">
                <div class="alert alert-warning text-center p-2 m-0" style="max-width:320px;">
                    User not registered but image uploaded.<br><small>forwarding...</small>
                </div>
            </div>
            <script>setTimeout(function(){ window.location.href = "load_data.php"; }, 1800);</script>
            <?php
        } else {
            ?>
            <div class="d-flex justify-content-center align-items-center" style="min-height:40vh;">
                <div class="alert alert-danger text-center p-2 m-0" style="max-width:320px;">
                    User not registered and image not uploaded.<br><small>forwarding...</small>
                </div>
            </div>
            <script>setTimeout(function(){ window.location.href = "load_data.php"; }, 1800);</script>
            <?php
        }
        exit();
    }
?>