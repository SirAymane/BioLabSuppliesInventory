<?php
namespace proven\store\model\persist;

require_once 'model/WarehouseProduct.php';
require_once 'model/persist/StoreDb.php';
require_once 'model/Product.php';

use proven\store\model\Product as Product;
use proven\store\model\WarehouseProduct as WarehouseProduct;
use proven\store\model\persist\StoreDb as StoreDb;
use proven\store\model\Warehouse;

class WarehouseProductDao {
	private StoreDb $dbConnect;
	private static string $TABLE_NAME = 'warehousesproducts';
	private array $queries;


	public function __construct() {
		$this->dbConnect = new StoreDb;
		$this->queries = array();
		$this->initQueries();
	}

	private function initQueries() {
		$this->queries['SELECT_ALL'] = \sprintf(
			"select * from %s",
			self::$TABLE_NAME
		);
        $this->queries['SELECT_WHERE_ID'] = \sprintf(
                "select * from %s where id = :id", 
                self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_WID'] = \sprintf(
            "select * from %s where warehouse_id = :wid", 
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_PID'] = \sprintf(
            "select * from %s where product_id = :pid", 
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = \sprintf(
                "insert into %s (warehouse_id, product_id, stock) values (:wid, :pid, :stock)", 
                self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = \sprintf(
                "update %s set stock = :stock where product_id = :pid and warehouse_id = :wid", 
                self::$TABLE_NAME
        );
        $this->queries['DELETE_PID'] = \sprintf(
                "delete from %s where product_id = :id", 
                self::$TABLE_NAME
		);
        $this->queries['DELETE_WID'] = \sprintf(
                "delete from %s where warehouse_id = :id", 
                self::$TABLE_NAME
		);
	}

	public function selectAll(): array {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection(); 
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            $success = $stmt->execute(); 
            if ($success) {
                if ($stmt->rowCount()>0) {
                   //fetch in class mode and get array with all data.                   
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, WarehouseProduct::class);
                    $data = $stmt->fetchAll(); 
                    //or in one single sentence:
                    // $data = $stmt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Product::class);
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
        /*  Delete for debugging
            print "Error Code <br>".$e->getCode();
            print "Error Message <br>".$e->getMessage();
            print "Stack Trace <br>".nl2br($e->getTraceAsString()); */
            $data = array();
        }   
        return $data;   
	}

    public function selectWhere(string $fieldname, string $fieldvalue): array {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection(); 
            $query = sprintf("select * from %s where %s = '%s'", 
                self::$TABLE_NAME, $fieldname, $fieldvalue);
            $stmt = $connection->prepare($query);
            $success = $stmt->execute(); //bool
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, WarehouseProduct::class);
                    $data = $stmt->fetchAll(); 
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
//            print "Error Code <br>".$e->getCode();
//            print "Error Message <br>".$e->getMessage();
//            print "Strack Trace <br>".nl2br($e->getTraceAsString());
            $data = array();
        }   
        return $data;   
	}

    public function selectByProductId(Product $product): array {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection(); 
            if ($connection === null) {
                throw new \Exception("Failed to establish database connection.");
            }
    
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_PID']);
            if ($stmt === false) {
                throw new \Exception("Failed to prepare statement.");
            }
    
            $stmt->bindValue(':pid', $product->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute();
            if ($success === false) {
                throw new \Exception("Failed to execute statement.");
            }
    
            if ($stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, WarehouseProduct::class);
                $data = $stmt->fetchAll(); 
            }
    
            echo "SQL Query: " . $this->queries['SELECT_WHERE_PID'] . "<br/>";
            echo "Product ID: " . $product->getId() . "<br/>";
            echo "Rows returned: " . $stmt->rowCount() . "<br/>";
            var_dump($data);
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage() . "<br/>";
        }   
        return $data;   
    }
    
    

    public function selectByWarehouseId(Warehouse $warehouse): array {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection(); 
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_WID']);
            $stmt->bindValue(':wid', $warehouse->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute(); 
            if ($success) {
                if ($stmt->rowCount()>0) {
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, WarehouseProduct::class);
                    $data = $stmt->fetchAll(); 
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
            
            // // Debugging code
            // // echo "SQL Query: " . $this->queries['SELECT_WHERE_WID'] . "<br/>";
            // // echo "Warehouse ID: " . $warehouse->getId() . "<br/>";
            // // echo "Rows returned: " . $stmt->rowCount() . "<br/>";
            // var_dump($data);
        } catch (\PDOException $e) {
            throw $e;
        }   
        return $data;   
    }
    


    public function removeByPid(Product $entity): int {
		$result = 0;
        try {
            $connection = $this->dbConnect->getConnection(); 
			$stmt = $connection->prepare($this->queries['DELETE_PID']);
			$stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute(); //bool
            if ($success) {
				$result = $stmt->rowCount();
            } else {
                $result = 0;
            }
        } catch (\PDOException $e) {
			// print "Error Code <br>".$e->getCode();
            // print "Error Message <br>".$e->getMessage();
            // print "Strack Trace <br>".nl2br($e->getTraceAsString());
			throw $e;
        }   
		return $result;
	}


    public function updateStock(WarehouseProduct $warehouseProduct): int
    {
        $result = 0;
        try {
            $connection = $this->dbConnect->getConnection(); 
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':wid', $warehouseProduct->getWarehouseId(), \PDO::PARAM_INT);
            $stmt->bindValue(':pid', $warehouseProduct->getProductId(), \PDO::PARAM_INT);
            $stmt->bindValue(':stock', $warehouseProduct->getStock(), \PDO::PARAM_INT);
            $success = $stmt->execute(); 
            if ($success) {
                $result = $stmt->rowCount();
            } else {
                $result = 0;
            }
        } catch (\PDOException $e) {
            throw $e;
        }   
        return $result;
    }


    
}



