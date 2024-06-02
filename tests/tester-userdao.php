<?php

require_once __DIR__ . '/../model/persist/UserDao.php';

use SirAymane\ecommerce\model\persist\UserDao;
use SirAymane\ecommerce\model\User;

$userDao = new UserDao();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    echo "<html><head><title>UserDao Tester</title></head><body>";

    // Test selectAll function
    $users = $userDao->selectAll();
    echo "<h2>Select all Users found:</h2>";
    if (empty($users)) {
        echo "<p>No users found.</p>";
    } else {
        foreach ($users as $user) {
            echo "<p>Username: {$user->getUsername()}, Email: {$user->getEmail()}, DOB: ";
            echo $user->getDob() ? $user->getDob()->format('Y-m-d') : 'N/A';
            echo "</p>";
        }
    }

    // Test insert function
    $newUserId = 4; // Change to the desired user ID
    $newUser = new User($newUserId, 'newuser', 'password', 'user', 'newuser@example.com', new DateTime('1990-01-01'));
    $insertedRows = $userDao->insert($newUser);
    echo "<h2>Test insert function:</h2>";

    if ($insertedRows > 0) {
        echo "<p>User '{$newUser->getUsername()}' inserted successfully.</p>";
    } else {
        echo "<p>Failed to insert user '{$newUser->getUsername()}'.</p>";
    }

    // Test update function
    $userToUpdate = $userDao->findByUsername('newuser'); // Change to the username you just inserted
    echo "<h2>Test update function:</h2>";

    if ($userToUpdate) {
        // Update email and handle null date
        $newEmail = 'updatedemail@example.com';
        $userToUpdate->setEmail($newEmail);
        $newDob = new DateTime('1990-01-02'); // Change to the desired date
        $userToUpdate->setDob($newDob);

        $updatedRows = $userDao->update($userToUpdate);

        if ($updatedRows > 0) {
            echo "<p>User '{$userToUpdate->getUsername()}' updated successfully.</p>";
        } else {
            echo "<p>Failed to update user '{$userToUpdate->getUsername()}'.</p>";
        }
    } else {
        echo "<p>User not found for update.</p>";
    }

    // Test delete function
    $userToDelete = $userDao->findByUsername('newuser'); // Change to the username you just inserted
    echo "<h2>Test delete function:</h2>";

    if ($userToDelete) {
        $deletedRows = $userDao->delete($userToDelete);

        if ($deletedRows > 0) {
            echo "<p>User '{$userToDelete->getUsername()}' deleted successfully.</p>";
        } else {
            echo "<p>Failed to delete user '{$userToDelete->getUsername()}'.</p>";
        }
    } else {
        echo "<p>User not found for delete.</p>";
    }


    echo "<h2>More testing to be added...</h2>";
    echo "<p>Once there are more functionalities implemented.</p>";



    echo "</body></html>";
} catch (Exception $e) {
    echo "<p>An error occurred: " . $e->getMessage() . "</p>";
}
?>
