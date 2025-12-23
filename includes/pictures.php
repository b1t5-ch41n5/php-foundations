<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        // 🖼️ Pictures page setup
        include 'session.php';
        include 'header_nav.php'; 
        
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Pictures</title>

    <style>
        /* 🖼️ Styles for the pictures page */
        body {
            background: black;
            color: white;
            overflow-x: hidden;
            min-height: 100vh;
        }

        body::before {
            /* 🎥 Subtle animated background */
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://media.giphy.com/media/oEI9uBYSzLpBK/giphy.gif') center center / cover no-repeat;
            opacity: 0.1;
            z-index: -1;
        }

        .main-wrapper {
            border-radius: 15px;
            padding: 30px;
            margin-top: 20px;
            backdrop-filter: blur(10px);
            box-shadow: 0 0 30px rgba(0, 191, 255, 0.3);
        }

        .main-wrapper h2 {
            color: #00bfff !important;
            text-shadow: 0 0 10px #00bfff;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .main-wrapper .image-button {
            border: 2px solid #00bfff;
            border-radius: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
            margin: 10px;
            background: rgba(20, 20, 20, 0.9);
            box-shadow: 0 0 15px rgba(0, 191, 255, 0.2);
        }
        
        .main-wrapper .image-button:hover {
            border-color: #00bfff;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(0, 191, 255, 0.5);
            background: rgba(0, 191, 255, 0.1);
        }
        
        .fullscreen-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.95);
            z-index: 9999;
            cursor: pointer;
            backdrop-filter: blur(5px);
        }
        
        .fullscreen-image {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
            border-radius: 10px;
            box-shadow: 0 0 30px rgba(0, 191, 255, 0.5);
        }
        
        .close-btn {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #00bfff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            text-shadow: 0 0 10px #00bfff;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            color: #ffffff;
            text-shadow: 0 0 15px #00bfff;
            transform: scale(1.1);
        }

        .main-wrapper .alert {
            background: rgba(20, 20, 20, 0.9) !important;
            border: 2px solid #ff6600 !important;
            color: #ff8800 !important;
            border-radius: 10px !important;
            backdrop-filter: blur(10px);
        }

        .main-wrapper .alert h4 {
            color: #ff4444 !important;
            text-shadow: 0 0 10px #ff4444;
        }

        .main-wrapper .btn-primary {
            background: linear-gradient(45deg, #00bfff, #0099cc) !important;
            border: none !important;
            color: #000 !important;
            font-weight: bold !important;
            transition: all 0.3s ease;
            box-shadow: 0 0 15px rgba(0, 191, 255, 0.3);
        }

        .main-wrapper .btn-primary:hover {
            background: linear-gradient(45deg, #0099cc, #00bfff) !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 5px 20px rgba(0, 191, 255, 0.5) !important;
            color: #000 !important;
        }

        .main-wrapper .btn-secondary {
            background: rgba(20, 20, 20, 0.9) !important;
            border: 2px solid #00bfff !important;
            color: #00bfff !important;
            font-weight: bold !important;
            transition: all 0.3s ease;
        }

        .main-wrapper .btn-secondary:hover {
            background: rgba(0, 191, 255, 0.2) !important;
            border-color: #00bfff !important;
            color: #ffffff !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 191, 255, 0.3);
        }

        .main-wrapper .text-muted {
            color: #00bfff !important;
            font-size: 16px !important;
            text-shadow: 0 0 5px #00bfff;
        }

        .main-wrapper .img-fluid {
            border: 2px solid #00bfff;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.3);
        }   

        /* Hover effect for image rows */
        .main-wrapper .col-md-4:hover .image-button {
            background: rgba(0, 191, 255, 0.2) !important;
            box-shadow: 0 0 25px rgba(0, 191, 255, 0.4);
            transition: 0.2s ease;
        }
    </style>
</head>

<body style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('../css/img/pic.jpg') no-repeat center center fixed; background-size: cover; min-height: 100vh;">
    <div class="container mt-4  main-wrapper">
        <h2 class="text-center text-warning mb-4">User Pictures</h2>
        
        <?php
        
        include '../db/conect_database.php';
        include '../db/users.php';

        $isLoggedIn = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);

        if (isset($_GET['username']) && $isLoggedIn) {
            $UserId = $_GET['username'];
            $SqlOperationUser = new Users($Connection);

            // Delete image if requested
            if (isset($_GET['username']) && isset($_GET['delete'])) {
                $DeleteImag = $SqlOperationUser->DeleteUserImages($_GET['delete']);
                if ($DeleteImag) {
                    // Delete the image from the server
                    if (file_exists($DeleteImag['image_path'])) {
                        unlink($DeleteImag['image_path']);
                        echo '<div class="alert alert-success">Image deleted successfully.</div>';
                    }
                }
                    
            }
            $UserPictures = $SqlOperationUser->UserImages($UserId);
            echo '<div class="row">';
            $imageCount = 0;
            while ($ImageDb = $UserPictures->fetch(PDO::FETCH_ASSOC)) { 
                if (!empty($ImageDb['image_path'])) {
                    $imageCount++;
                ?>
                <div class="col-md-4 col-sm-6 mb-3">
                    <div class="text-center">
                        <img class="img-fluid rounded image-button mb-2" 
                            style="height: 200px; width: 100%; object-fit: cover;"
                            src="<?php echo htmlspecialchars($ImageDb['image_path']); ?>" 
                            alt="User Picture <?php echo $imageCount; ?>"
                            onclick="openFullscreen('<?php echo htmlspecialchars($ImageDb['image_path']); ?>')"
                        >
                        <!-- Buttons to delete and set as profile -->
                        <div class="d-flex justify-content-center gap-2">
                            <a href="pictures.php?username=<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>&delete=<?php echo $ImageDb['id_image']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Are you sure you want to delete this image?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                }
            }
            
            if ($imageCount == 0) {
                echo '<div class="col-12 text-center">';
                echo '<p class="text-muted">No images found for this user.</p>';
                echo '</div>';
            }
            
            echo '</div>';
        } else {
            echo '<div class="alert alert-warning text-center">';
            echo '<h4>Access Denied</h4>';
            echo '<p>Please log in to view pictures.</p>';
            echo '<a href="../index.php" class="btn btn-primary">Go to Login</a>';
            echo '</div>';
        }
        ?>
        
        
    </div>

    <!-- Overlay for fullscreen -->
    <div id="fullscreenOverlay" class="fullscreen-overlay" onclick="closeFullscreen()">
        <span class="close-btn" onclick="closeFullscreen()">&times;</span>
        <img id="fullscreenImage" class="fullscreen-image" src="" alt="Fullscreen Image">
    </div>

    <script>
        function openFullscreen(imageSrc) {
            document.getElementById('fullscreenImage').src = imageSrc;
            document.getElementById('fullscreenOverlay').style.display = 'block';
            document.body.style.overflow = 'hidden'; // Prevent scroll
        }

        function closeFullscreen() {
            document.getElementById('fullscreenOverlay').style.display = 'none';
            document.body.style.overflow = 'auto'; // Restore scroll
        }

        // Close with ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeFullscreen();
            }
        });
    </script>
</body>
</html>