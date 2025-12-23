<?php
    // 🛡️ Check if the user session is active
    // If the session variable 'user_id' is not set, redirect to the login page
    if (!isset($_SESSION['user_id'])):
        header("Location: ../index.php"); // 🔄 Redirect to the homepage
    endif;
?>