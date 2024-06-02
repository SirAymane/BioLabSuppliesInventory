<?php

require_once __DIR__ . '/../model/persist/UserDao.php';
require_once __DIR__ . '/../model/persist/UserDao.php';

require_once __DIR__ . '/vendor/autoload.php';


use SirAymane\ecommerce\model\persist\UserDao;
use SirAymane\ecommerce\model\User;


$userDao = new UserDao();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class UserDaoTest extends PHPUnit\Framework\TestCase {
    private $userDao;

    protected function setUp(): void {
        // Create an instance of your user DAO class
        $this->userDao = new UserDao();
    }

    protected function tearDown(): void {
        // Clean up after each test if needed
    }

    public function testGetUserById() {
        // Arrange: Prepare test data or objects
        $userId = 1; // Replace with the actual user ID

        // Act: Perform the action you want to test
        $user = $this->userDao->getUserById($userId);

        // Assert: Check the result
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userId, $user->getId());
    }

    public function testGetAllUsers() {
        // Act: Perform the action you want to test
        $users = $this->userDao->getAllUsers();

        // Assert: Check the result
        $this->assertIsArray($users);
        // Add more specific assertions based on your implementation
    }

    // Add more test methods for other DAO operations

    // Example test method for inserting a user
    public function testInsertUser() {
        // Arrange: Prepare test data or objects
        $user = new User(); // Create a User object with test data

        // Act: Perform the action you want to test
        $result = $this->userDao->insertUser($user);

        // Assert: Check the result
        $this->assertTrue($result);
    }
}

// Run the tests
// PHPUnit_TextUI_Command::main();

?>
