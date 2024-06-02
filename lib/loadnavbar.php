<?php
/**
 * Navigation bar loader.
 *
 * This script determines which navigation menu to display
 * based on the user's role.
 */

// Start the session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Default navigation menu path
$menupath = "views/layout/mainmenu.php";

// Check if there is a user role set in the session
if (isset($_SESSION['role'])) {
    $userrole = $_SESSION['role']; // Use $_SESSION['role'] instead of $_SESSION['userrole']

    // Determine the menu path based on the user's role
    switch ($userrole) {
        case "admin":
            // Path to the admin specific menu
            $menupath = "views/layout/adminmenu.php";
            break;
        // Add cases for other roles if needed
    }
}

// Include the chosen menu
include $menupath;
?>
