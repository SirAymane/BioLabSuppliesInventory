<?php
namespace SirAymane\ecommerce\model\persist;

/**
 * PDO database connection for the eCommerce application.
 */
class Database {

    /**
     * @var string The Data Source Name, or DSN, contains the information required to connect to the database.
     */
    private $dsn;

    /**
     * @var string The host of the database.
     */
    private $host;

    /**
     * @var string The name of the database.
     */
    private $db;

    /**
     * @var string The username for the database connection.
     */
    private $user;

    /**
     * @var string The password for the database connection.
     */
    private $pass;

    /**
     * @var string The charset for the database connection.
     */
    private $charset;

    /**
     * @var array The options for the PDO connection.
     */
    private $opt;

    /**
     * Constructor for the Database class.
     */
    public function __construct() {
        $this->host = 'localhost';
        $this->db = 'ecommerce';
        $this->user = 'ecommerceusr';
        $this->pass = 'ecommercepsw';
        $this->charset = 'utf8';
        $this->dsn = sprintf("mysql:host=%s; dbname=%s;charset=%s", $this->host, $this->db, $this->charset);

        // Setting the options for PDO connection.
        $this->opt = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];
    }

    /**
     * Establishes and returns a PDO connection.
     *
     * @return \PDO The PDO connection object.
     */
    public function getConnection() {
        $connection = new \PDO($this->dsn, $this->user, $this->pass, $this->opt);
        return $connection;
    }
}
