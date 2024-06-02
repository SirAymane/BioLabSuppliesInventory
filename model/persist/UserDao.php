<?php

namespace SirAymane\ecommerce\model\persist;

require_once __DIR__ . '/Database.php';
require_once __DIR__ . './../User.php';



use SirAymane\ecommerce\model\persist\Database as DbConnect;
use SirAymane\ecommerce\model\User as User;

/**
 * User database persistence class.
 */
class UserDao
{
    private DbConnect $dbConnect;
    private static string $TABLE_NAME = 'users';
    private array $queries;

    public function __construct()
    {
        $this->dbConnect = new DbConnect();
        $this->queries = array();
        $this->initQueries();
    }

    private function initQueries()
    {

        $this->queries['SELECT_ALL'] = sprintf("SELECT * FROM %s", self::$TABLE_NAME);

        $this->queries['SELECT_WHERE_ID'] = sprintf("SELECT * FROM %s WHERE id = :id", self::$TABLE_NAME);

        $this->queries['SELECT_WHERE_USERNAME_AND_PASSWORD'] = sprintf(
            "SELECT * FROM %s WHERE username = :username AND password = :password",
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = sprintf(
            "INSERT INTO %s (username, password, role, email, dob) VALUES (:username, :password, :role, :email, :dob)",
            self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = sprintf(
            "UPDATE %s SET username = :username, password = :password, role = :role, email = :email, dob = :dob WHERE id = :id",
            self::$TABLE_NAME
        );
        $this->queries['DELETE'] = sprintf(
            "DELETE FROM %s WHERE id = :id",
            self::$TABLE_NAME
        );
    }

    /**
     * Convert database row to User entity
     *
     * @param $statement
     * @return User|null
     */
    private function fetchToEntity($statement): ?User
    {
        $row = $statement->fetch();
        if ($row) {
            $dob = $row['dob'] ? new \DateTime($row['dob']) : null; // Handle null dob values
            $user = new User(
                $row['id'],
                $row['username'],
                null, // Set password to null here, as it's already hashed in the database
                $row['role'],
                $row['email'],
                $dob
            );
            return $user;
        } else {
            return null;
        }
    }

    public function select(User $entity): ?User
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $stmt->execute();
            return $this->fetchToEntity($stmt);
        } catch (\PDOException $e) {
            // Error handling
            return null;
        }
    }

    /**
     * Selects entities in the database where a specific field matches a value.
     *
     * @param string $fieldname The name of the field to search.
     * @param string $fieldvalue The value to match in the specified field.
     * @return array|null An array of entity objects that match the criteria or null if none are found.
     */
    public function selectWhere(string $fieldname, string $fieldvalue): ?array
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $query = sprintf("SELECT * FROM %s WHERE %s = :value", self::$TABLE_NAME, $fieldname);
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':value', $fieldvalue, \PDO::PARAM_STR);
            $stmt->execute();

            return $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, User::class) ?: null;
        } catch (\PDOException $e) {
            // Error handling
            return null;
        }
    }

    public function selectAll(): array
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            $stmt->execute();

            // Fetch all rows as associative arrays with string dates
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Initialize an empty array to store User objects
            $users = array();

            foreach ($rows as $row) {
                // Create a new User object and populate its properties
                $dob = $row['dob'] ? new \DateTime($row['dob']) : null; // Handle null dob values
                $user = new User($row['id'], $row['username'], null, $row['role'], $row['email'], $dob);
                $user->setRawPassword($row['password']); // Set the password without hashing

                // Add the User object to the array
                $users[] = $user;
            }

            return $users;
        } catch (\PDOException $e) {
            // Error handling
            return array();
        }
    }

    /**
     * Finds a user by their username.
     * @param string $username The username to search for.
     * @return User|null The found user, or null if not found.
     */
    public function findByUsername(string $username): ?User
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("SELECT * FROM " . self::$TABLE_NAME . " WHERE username = :username");
            $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
            $stmt->execute();
            return $this->fetchToEntity($stmt);
        } catch (\PDOException $e) {
            // Error handling
            return null;
        }
    }

    /**
     * Validates a user's login credentials.
     * This method now uses the 'SELECT_WHERE_USERNAME_AND_PASSWORD' query for authentication.
     */
    public function validateCredentials(string $username, string $password): ?User
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_USERNAME_AND_PASSWORD']);
            $stmt->bindValue(':username', $username, \PDO::PARAM_STR);
            $stmt->bindValue(':password', $password, \PDO::PARAM_STR);
            $stmt->execute();
            return $this->fetchToEntity($stmt);
        } catch (\PDOException $e) {
            // Error handling
            return null;
        }
    }

    /**
     * Inserts a new user into the database.
     * @param User $entity The user entity to insert.
     * @return int The number of rows affected.
     */
    public function insert(User $entity): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['INSERT']);

            // Binding the parameters
            $stmt->bindValue(':username', $entity->getUsername(), \PDO::PARAM_STR);
            $stmt->bindValue(':password', $entity->getPassword(), \PDO::PARAM_STR);
            $stmt->bindValue(':role', $entity->getRole(), \PDO::PARAM_STR);
            $stmt->bindValue(':email', $entity->getEmail(), \PDO::PARAM_STR);
            $stmt->bindValue(':dob', $entity->getDob()->format('Y-m-d'), \PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }

    /**
     * Updates an existing user in the database.
     * @param User $entity The user entity to update.
     * @return int The number of rows affected.
     */
    public function update(User $entity): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['UPDATE']);

            // Binding the parameters
            $stmt->bindValue(':username', $entity->getUsername(), \PDO::PARAM_STR);
            $stmt->bindValue(':password', $entity->getPassword(), \PDO::PARAM_STR);
            $stmt->bindValue(':role', $entity->getRole(), \PDO::PARAM_STR);
            $stmt->bindValue(':email', $entity->getEmail(), \PDO::PARAM_STR);
            $stmt->bindValue(':dob', $entity->getDob()->format('Y-m-d'), \PDO::PARAM_STR);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }

    /**
     * Deletes a user from the database.
     * @param User $entity The user entity to delete.
     * @return int The number of rows affected.
     */
    public function delete(User $entity): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['DELETE']);

            // Binding the parameter
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);

            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }

    /**
     * Get an instance of UserDao (Singleton pattern).
     *
     * @return UserDao
     */
    public static function getInstance(): UserDao
    {
        static $instance = null;
        if ($instance === null) {
            $instance = new UserDao();
        }
        return $instance;
    }
}
