<?php

namespace SirAymane\ecommerce\lib;

/**
 * Authentication and Authorization utility class.
 */
class Auth
{
    /**
     * Check if the user is authenticated.
     * 
     * @return bool True if authenticated, false otherwise.
     */
    public static function isAuthenticated() {
        return isset($_SESSION['username']);
    }

    /**
     * Check if the current user has a specific role.
     * 
     * @param string $requiredRole The role to check against the current user's role.
     * @return bool True if the user has the required role, false otherwise.
     */
    public static function hasPermission($requiredRole) {
        return isset($_SESSION['role']) && $_SESSION['role'] === $requiredRole;
    }

    /**
     * Ensure the user has the admin role.
     * Redirects to an error page or performs an action if the user does not have admin privileges.
     */
    public static function ensureAdminRole() {
        if (!self::hasPermission('admin')) {
            // Redirect or handle the error as needed
            header("Location: error.php"); // Replace 'error.php' with your error handling page
            exit;
        }
    }

    /**
     * Start a session if it hasn't been started already.
     */
    public static function startSessionIfNotStarted() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

}
