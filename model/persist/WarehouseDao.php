<?php
namespace proven\store\model\persist;

require_once 'model/persist/StoreDb.php';
require_once 'model/Warehouse.php';

use proven\store\model\persist\StoreDb as StoreDb;
use proven\store\model\Warehouse as Warehouse;
use PDO;
use PDOException;

/**
 * Warehouse db persistence class.
 */
class WarehouseDao {
	private StoreDb $dbConnect;

	private static string $TABLE_NAME = 'warehouses';

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
        $this->queries['SELECT_WHERE_CODE'] = \sprintf(
            "select * from %s where code = :code", 
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = \sprintf(
                "insert into %s (code, address) values (:code, :address)", 
                self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = \sprintf(
                "update %s set code = :code, address = :address where id = :id", 
                self::$TABLE_NAME
        );
        $this->queries['DELETE'] = \sprintf(
                "delete from %s where id = :id", 
                self::$TABLE_NAME
		);
	}

    private function executeQuery(string $queryKey, array $params = []): ?PDOStatement {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries[$queryKey]);

            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val);
            }

            $stmt->execute();
            return $stmt;

        } catch (PDOException $e) {
            // Consider logging the error instead of just printing
            error_log("PDOException in WarehouseDao::executeQuery: " . $e->getMessage());
            return null;
        }
    }


    /**
     * selects an entity given its id.
     * @param entity the entity to search.
     * @return entity object being searched or null if not found or in case of error.
     */
    public function select(Warehouse $entity): ?Warehouse {
        $data = null;
        try {
            //PDO object creation.
            $connection = $this->dbConnect->getConnection(); 
            //query preparation.
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            //query execution.
            $success = $stmt->execute();
            //Statement data recovery.
            if ($success && $stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Warehouse::class);
                $data = $stmt->fetch();
            }
        } catch (\PDOException $e) {
            $data = null;
        }   
        return $data;
    }

    /**
     * selects all entitites in database.
     * return array of entity objects.
     */
    public function selectAll(): array {
        $data = [];
        try {
            $connection = $this->dbConnect->getConnection(); 
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            $success = $stmt->execute();
            if ($success && $stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Warehouse::class);
                $data = $stmt->fetchAll();
            }
        } catch (\PDOException $e) {
            $data = [];
        }   
        return $data;   
    }

    /**
     * selects entitites in database where field value.
     * return array of entity objects.
     */
    public function selectWhere(string $fieldname, string $fieldvalue): array {
        $data = [];
        try {
            $connection = $this->dbConnect->getConnection(); 
            $query = sprintf("SELECT * FROM %s WHERE %s = '%s'", 
                $this->tableName, $fieldname, $fieldvalue);
            $stmt = $connection->prepare($query);
            $success = $stmt->execute();
            if ($success && $stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Warehouse::class);
                $data = $stmt->fetchAll();
            }
        } catch (\PDOException $e) {
            $data = [];
        }   
        return $data;   
    }

    /**
     * inserts a new entity in database.
     * @param entity the entity object to insert.
     * @return number of rows affected.
     */
    public function insert(Warehouse $entity): int {
        $numAffected = 0;
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['INSERT']);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(':address', $entity->getAddress(), \PDO::PARAM_STR);
            //query execution.
            $success = $stmt->execute();
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
        }
        return $numAffected;
    }

    /**
     * updates entity in database.
     * @param entity the entity object to update.
     * @return number of rows affected.
     */
    public function update(Warehouse $entity): int {
        $numAffected = 0;
        try {
            $connection = $this->dbConnect->getConnection(); 
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':code', $entity->getCode(), \PDO::PARAM_STR);
            $stmt->bindValue(':address', $entity->getAddress(), \PDO::PARAM_STR);
            $success = $stmt->execute();
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
        }
        return $numAffected;  
    }

    /**
     * deletes entity from database.
     * @param entity the entity object to delete.
     * @return number of rows affected.
     */
    public function delete(Warehouse $entity): int {
        $numAffected = 0;
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['DELETE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute();
            $numAffected = $success ? $stmt->rowCount() : 0;
        } catch (\PDOException $e) {
            $numAffected = 0;
        }
        return $numAffected;        
    }
}
?>

