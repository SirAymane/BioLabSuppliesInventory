<?php


// TODO: Tester needs to be adapted to the current database
// TODO: Querys need to not assign a specific ID, to be adapted.


/**
 * Description of TEsterConnect
 * Util class to connect and test webstoredb
 *
 * @author ProvenSoft
 */
class Tester { 
    
    private static $dsn;
    private $opt;
    private $connection;
    
    public function __construct() {
        //connection data.
        $host = 'localhost';
        $db = 'ecommerce';
        $user = 'testerusr'; // Using a different user, with higher privileges
        $pass = 'testerpsw';
        $charset = 'utf8';
        self::$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $this->opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];
        //PDO object creation.
        $this->connection = new \PDO(self::$dsn, $user, $pass, $this->opt);
    }    
    
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Truncate all project tables: orderitems, orders, products and users
     */
    public function truncateAllTables(){
        $this->connection->exec("SET FOREIGN_KEY_CHECKS = 0;");
        // $this->connection->exec('TRUNCATE table orderitems;');
        // $this->connection->exec('TRUNCATE table orders;');
        $this->connection->exec('TRUNCATE table products;');
        $this->connection->exec('TRUNCATE table users;');
        $this->connection->exec("SET FOREIGN_KEY_CHECKS = 1;");
        
    }

    /**
     * Init test data for all project tables: orderitems, orders, products, and users
     */
    public function initTestData()
    { // Instead of inserting these generic users, it should insert the ones from the sql script
        $this->connection->exec("INSERT INTO users (username, password, role, email, dob) VALUES 
        ('user1', 'pass1', 'admin', 'user1@proven.cat', '2000-01-01');");
        $this->connection->exec("INSERT INTO users (username, password, role, email, dob) VALUES 
        ('user2', 'pass2', 'registered', 'user2@proven.cat', '2000-02-02');");
        $this->connection->exec("INSERT INTO users (username, password, role, email, dob) VALUES 
        ('user3', 'pass3', 'admin', 'user3@proven.cat', '2000-03-03');");
        $this->connection->exec("INSERT INTO users (username, password, role, email, dob) VALUES 
        ('user4', 'pass4', 'registered', 'user4@proven.cat', '2000-04-04');");
        $this->connection->exec("INSERT INTO products (code, description, price) VALUES 
        ('P1', 'product 1', 11.1),
        ('P2', 'product 2', 22.2),
        ('P3', 'product 3', 33.3),
        ('P4', 'product 4', 44.4);");
        
        // Modify the customer ID to match an existing user ID in the users table
        $this->connection->exec("INSERT INTO orders(delMethod, customer) VALUES
        ('Click & Collect', 1)");
        
        $this->connection->exec("INSERT INTO orderitems(orderId, productId, quantity, unitPrice) VALUES
        (1, 1, 1, 11.1),
        (1, 2, 2, 44.4),
        (1, 3, 3, 99.9);");
    }




}