<?php
    // 🚪 Logout script
    // Destroy the session and redirect to the homepage
    include "session.php";
    session_destroy();
    header("Location: ../index.php"); // 🔄 Redirect to the homepage
    exit();
?>