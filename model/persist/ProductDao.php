<?php
namespace SirAymane\ecommerce\model\persist;

require_once __DIR__ . '/Database.php';
require_once __DIR__ . './../Product.php';

use SirAymane\ecommerce\model\persist\Database as DbConnect;
use SirAymane\ecommerce\model\Product as Product;

use PDO;

/**
 * Product database persistence class.
 */
class ProductDao {
    private DbConnect $dbConnect;
    private static string $TABLE_NAME = 'products';
    private array $queries;

    public function __construct() {
        $this->dbConnect = new DbConnect();
        $this->queries = array();
        $this->initQueries();
    }

    private function initQueries() {
        $this->queries['SELECT_ALL'] = sprintf(
            "SELECT * FROM %s",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_ID'] = sprintf(
            "SELECT * FROM %s WHERE id = :id",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_CODE'] = sprintf(
            "SELECT * FROM %s WHERE code = :code",
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = sprintf(
            "INSERT INTO %s (code, description, price) VALUES (:code, :description, :price)",
            self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = sprintf(
            "UPDATE %s SET code = :code, description = :description, price = :price WHERE id = :id",
            self::$TABLE_NAME
        );
        $this->queries['DELETE'] = sprintf(
            "DELETE FROM %s WHERE id = :id",
            self::$TABLE_NAME
        );
    }

    /**
     * Selects an entity given its id.
     * @param Product the entity to search.
     * @return Product object being searched or null if not found or in case of error.
     */
    public function select(Product $entity): ?Product {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                    // //set fetch mode.
                    // $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                    // // get one row at the time
                    // if ($u = $this->fetchToEntity($stmt)){
                    //     $data = $u;
                    // }
                    // $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Product::class);
                    // $data = $stmt->fetch();
                    $data = $this->fetchToProduct($stmt);
                } else {
                    $data = null;
                }
            } else {
                $data = null;
            }

        } catch (\PDOException $e) {
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $data = null;
        }   
        return $data;
    }

    /**
     * selects all entitites in database.
     * return array of entity objects.
     */
    public function selectAll(): array {
        $data = array();
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            //query execution.
            $success = $stmt->execute(); //bool
            //Statement data recovery.
            if ($success) {
                if ($stmt->rowCount()>0) {
                   //fetch in class mode and get array with all data.                   
                    // $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Product::class);
                    // $data = $stmt->fetchAll(); 
                    //or in one single sentence:
                    // $data = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Product::class);
                    $stmt->setFetchMode(\PDO::FETCH_ASSOC);
                    $data = $stmt->fetchAll();
                    $data = array_map(function ($row) {
                        return new Product($row['id'], $row['code'], $row['description'], $row['price']);
                    }, $data);
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
//            print "Error Code <br>".$e->getCode();
//            print "Error Message <br>".$e->getMessage();
//            print "Stack Trace <br>".nl2br($e->getTraceAsString());
            $data = array();
        }   
        return $data;   
    }

    /**
     * Selects entities in the database where a field value matches precisely.
     * Useful for searching with total matches.
     *
     * @param string $fieldName The name of the field to search in.
     * @param string $fieldValue The term to search for.
     * @return array An array of Product objects that match the criteria.
     */
    public function selectWhere(string $fieldName, string $fieldValue): array {
        $data = array();
        try {
            // PDO object creation.
            $connection = $this->dbConnect->getConnection();
            // Query preparation.
            $query = sprintf("SELECT * FROM %s WHERE %s = :fieldValue", self::$TABLE_NAME, $fieldName);
            $stmt = $connection->prepare($query);
            $stmt->bindParam(':fieldValue', $fieldValue, PDO::PARAM_STR);
            // Query execution.
            $success = $stmt->execute(); // bool
            // Statement data recovery.
            if ($success) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $product = new Product(
                        $row['id'],
                        $row['code'],
                        $row['description'],
                        $row['price']
                    );
                    $data[] = $product;
                }
            }
        } catch (\PDOException $e) {
            // Handle the exception or log it.
            // You can add error handling logic here if needed.
            $data = array();
        }
        return $data;
    }



    /**
     * Selects products from the database where a specific field matches a value using a LIKE query.
     * Useful for searching with partial matches.
     * 
     * @param string $fieldname The name of the field to search in.
     * @param string $searchTerm The term to search for.
     * @return array An array of Product objects that match the criteria.
     */
    public function selectWhereLike(string $fieldname, string $searchTerm): array {
        $data = [];
        try {
            $connection = $this->dbConnect->getConnection();
            $query = sprintf("SELECT * FROM %s WHERE %s LIKE :searchTerm", self::$TABLE_NAME, $fieldname);
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', \PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                $data[] = new Product($row['id'], $row['code'], $row['description'], $row['price']);
            }
        } catch (\PDOException $e) {
            // Handle any PDO exceptions here
            $data = [];
        }
        return $data;
    }


    /**
     * inserts a new entity in database.
     * @param entity the entity object to insert.
     * @return int of rows affected.
     */
    public function insert(Product $entity): int {
        $numAffected = 0;
        try {
            // PDO object creation.
            $connection = $this->dbConnect->getConnection();
            // Query preparation.
            $stmt = $connection->prepare($this->queries['INSERT']);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(':description', $entity->getDescription(), \PDO::PARAM_STR);
            $stmt->bindValue(':price', $entity->getPrice(), \PDO::PARAM_STR);
            // Query execution.
            $stmt->execute(); //bool
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $numAffected = 0;
        }
        return $numAffected;
    }

    public function update(Product $entity): int {
        try {
            // PDO Object creation
            $connection = $this->dbConnect->getConnection();
            // Query preparation
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(':description', $entity->getDescription(), \PDO::PARAM_STR);
            $stmt->bindValue(':price', $entity->getPrice(), \PDO::PARAM_STR);
            // Query execution
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            // print "Error Code ".$e->getCode();
            // print "<br>Error Message ".$e->getMessage();
            // print "<br>Strack Trace ".nl2br($e->getTraceAsString());
			// $numAffected = 0;
			throw $e;
        }
        return $numAffected;  
    }
    

    /**
     * deletes entity from database.
     * @param entity the entity object to delete.
     * @return int of rows affected.
     */
    public function delete(Product $entity): int {
        $numAffected = 0;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.            
            $stmt = $connection->prepare($this->queries['DELETE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute(); //bool
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            // print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $numAffected = 0;
        }
        return $numAffected;        
    } 

    /**
     * Fetches data from the result set and builds a product object.
     * @param $statement The statement with query data.
     * @return Product|null The product object or null in case of error.
     */
    private function fetchToProduct($statement): ?Product {
        $row = $statement->fetch();
        if ($row) {
            return new Product(
                intval($row['id']),
                $row['code'],
                $row['description'],
                floatval($row['price'])
            );
        } else {
            return null;
        }
    }
}
