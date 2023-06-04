<?php
// Check if the user is authenticated
function isAuthenticated() {
    return isset($_SESSION['username']);
}

// Check if the user has sufficient permission (optional)
function hasPermission($requiredRole) {
    if(isset($_SESSION['userrole'])) {
        $userRole = $_SESSION['userrole'];
        return $userRole === $requiredRole;
    } else {
        return false;
    }
}
?>
