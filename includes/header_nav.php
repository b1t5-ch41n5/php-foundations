<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Icons -->
     
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        body {
            /* background as a image */
            background-image: url('/bg.jpg'); /* Ruta absoluta para que funcione desde cualquier carpeta */
            background-size: cover; /* to cover whole screen */
            background-position: center; /* to align on screen */
            background-repeat: no-repeat; /* not to repeat image */
            background-attachment: fixed; /* to keep steady image when scrolling */
            font-family: Arial;
            color: white;
            padding-top: 50px; /* add space to nav bar */
        }
        form {
            background-color: rgba(15, 14, 14, 0.9); /* Add transparency for better readability */
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            width: 400px;
            color: black;
            margin: 20px auto; /* Center horizontally */
        }
        form input {
            width: 100%;
            margin: 10px 0;
            padding: 8px;
        }
        .result {
            margin-top: 100px;
            padding: 10px;
            background-color: rgba(255,255,255,0.2);
            color: white;
        }
        /* Custom navbar styles */
        .navbar-brand {
            font-weight: bold;
        }
        .user-greeting {
            color: #ffc107 !important;
            font-weight: 500;
        }
    </style>
    <title><?php echo isset($Title) ? $Title : 'Hacker Users System'; ?></title>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark  fixed-top">
            <div class="container">
                <!-- Brand/Logo -->
                <a href="../index.php" class="navbar-brand d-flex align-items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-pc-display me-2" viewBox="0 0 16 16">
                        <path d="M8 1a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v14a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1zm1 13.5a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0m2 0a.5.5 0 1 0 1 0 .5.5 0 0 0-1 0M9.5 1a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM9 3.5a.5.5 0 0 0 .5.5h5a.5.5 0 0 0 0-1h-5a.5.5 0 0 0-.5.5M1.5 2A1.5 1.5 0 0 0 0 3.5v7A1.5 1.5 0 0 0 1.5 12H6v2h-.5a.5.5 0 0 0 0 1H7v-4H1.5a.5.5 0 0 1-.5-.5v-7a.5.5 0 0 1 .5-.5H7V2z"/>
                    </svg>
                    b1t5 ch41n5 PHP hacker Users
                </a>
                
                <!-- Mobile menu button -->
                <!-- navbar-toggler is used so that we can create a button for mobile devices -->
                <!-- data-bs-toggle='collapse' is used to make the button work opening and closing -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <!-- Navigation menu -->
                <!-- collapse navbar-collapse helps to prepare a list to be used by the button -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <!-- All navigation items aligned to the right -->
                    <!-- ms-auto pushes items to the right -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Home link -->
                        <li class="nav-item">
                            <a class="nav-link" href="load_data.php">
                                <i class="bi bi-file-earmark-text-fill"></i> Upload Data
                            </a>
                        </li>
                        
                        <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['username'])): ?>
                            <!-- User List link - only visible when logged in -->
                            <li class="nav-item">
                                <a class="nav-link" href="home.php">
                                    <i class="bi bi-list"></i> User List
                                </a>
                            </li>
                            
                            <!-- User greeting - only visible when logged in -->
                            <li class="nav-item">
                                <div class="nav-link p-1">
                                    <a href="<?php echo 'pictures.php?username=' . $_SESSION['user_id']; ?>" class="text-decoration-none">
                                        <button class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                            <span class="me-1">Hello, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
                                            <img src="<?php echo ('../uploads/' . $_SESSION['username'] . '/' . 'facebook' . '.jpg') ? ('../uploads/default.jpg') : ('../uploads/' . $_SESSION['username'] . '/' . 'facebook' . '.jpg') ; ?>" alt="User Picture" class="rounded-circle" width="20" height="20">
                                        </button>
                                    </a>
                                </div>
                            </li>
                            
                            <!-- Logout button - only visible when logged in -->
                            <li class="nav-item">
                                <a class="nav-link bg-danger rounded px-3 ms-2" href="./logout.php">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </li>
                            
                        <?php else: ?>
                            <!-- Login button - only visible when not logged in -->
                            <li class="nav-item">
                                <a class="nav-link bg-primary rounded px-3" href="../index.php">
                                    <i class="bi bi-box-arrow-in-right"></i> Login
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>