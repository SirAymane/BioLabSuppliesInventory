<?php

namespace SirAymane\ecommerce\model\persist;

require_once __DIR__ . './../OrderItem.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . './../Order.php';
require_once __DIR__ . './../Product.php';

use SirAymane\ecommerce\model\OrderItem as OrderItem;
use SirAymane\ecommerce\model\persist\Database as DbConnect;
use SirAymane\ecommerce\model\Order as Order;
use SirAymane\ecommerce\model\Product as Product;

class OrderItemDao
{
    private DbConnect $dbConnect;
    private static string $TABLE_NAME = 'ordersitems';
    private array $queries;

    /**
     * Constructor for the class. Initializes the DbConnect class and the queries array
     */
    public function __construct()
    {
        $this->dbConnect = new DbConnect();
        $this->queries = array();
        $this->initQueries();
    }

    /**
     * Initialize queries for SQL query generation. This is called by __construct () to initialize the queries
     */
    private function initQueries()
    {
        $this->queries['SELECT_ALL'] = \sprintf(
            "select * from %s",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_ID'] = \sprintf(
            "select * from %s where id = :id",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_WID'] = \sprintf(
            "select * from %s where order_id = :oid",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_PID'] = \sprintf(
            "select * from %s where product_id = :pid",
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = \sprintf(
            "insert into %s (order_id, product_id, quantity) values (:oid, :pid, :quantity)",
            self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = \sprintf(
            "update %s set quantity = :quantity where product_id = :pid and order_id = :oid",
            self::$TABLE_NAME
        );
        $this->queries['DELETE_PID'] = \sprintf(
            "delete from %s where product_id = :id",
            self::$TABLE_NAME
        );
        $this->queries['DELETE_WID'] = \sprintf(
            "delete from %s where order_id = :id",
            self::$TABLE_NAME
        );
    }

    /**
     * Returns all rows from the ORDER BY statement. This is useful for SELECT queries that do not return data.
     * 
     * 
     * @return An array of OrderItem objects or an empty array if something went
     */
    public function selectAll(): array
    {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            $success = $stmt->execute();
            // Returns an array of all the rows in the result set.
            if ($success) {
                // Returns an array of all rows from the statement.
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, OrderItem::class);
                    $data = $stmt->fetchAll();
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
            $data = array();
        }
        return $data;
    }

    /**
     * Insert a new order item into the database. This is the same as the insert method except that it does not check if the item already exists
     * 
     * @param $orderItem
     * 
     * @return The number of rows inserted or 0 on error ( database error
     */
    public function insert(OrderItem $orderItem): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['INSERT']);
            $stmt->bindValue(':oid', $orderItem->getOrderId(), \PDO::PARAM_INT);
            $stmt->bindValue(':pid', $orderItem->getProductId(), \PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $orderItem->getQuantity(), \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }


    /**
     * Update an order item in the database. This is used to update the information of an order item that has been added to a shopping cart
     * 
     * @param $orderItem
     * 
     * @return The number of rows affected or 0 if something went
     */
    public function update(OrderItem $orderItem): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':oid', $orderItem->getOrderId(), \PDO::PARAM_INT);
            $stmt->bindValue(':pid', $orderItem->getProductId(), \PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $orderItem->getQuantity(), \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }

    /**
     * Delete an order item from the database. This is a destructive operation and should be avoided if you want to keep the data in memory for a long time
     * 
     * @param $orderItem
     * 
     * @return The number of rows deleted or 0 on error ( database error
     */
    public function delete(OrderItem $orderItem): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("DELETE FROM {$this::$TABLE_NAME} WHERE order_id = :oid AND product_id = :pid");
            $stmt->bindValue(':oid', $orderItem->getOrderId(), \PDO::PARAM_INT);
            $stmt->bindValue(':pid', $orderItem->getProductId(), \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }




    /**
     * Select order items by WHERE clause. This is useful for selecting orders that have been added to the order list or where a particular order is not in the list
     * 
     * @param $fieldname
     * @param $fieldvalue
     * 
     * @return An array of OrderItem objects or an empty array if something
     */
    public function selectWhere(string $fieldname, string $fieldvalue): array
    {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection();
            $query = sprintf(
                "select * from %s where %s = '%s'",
                self::$TABLE_NAME,
                $fieldname,
                $fieldvalue
            );
            $stmt = $connection->prepare($query);
            $success = $stmt->execute();
            // Returns an array of all the rows in the result set.
            if ($success) {
                // Returns an array of all rows from the statement.
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, OrderItem::class);
                    $data = $stmt->fetchAll();
                } else {
                    $data = array();
                }
            } else {
                $data = array();
            }
        } catch (\PDOException $e) {
            $data = array();
        }
        return $data;
    }

    /**
     * Select order items by product id. This method is used to select order items based on product id
     * 
     * @param $product
     * 
     * @return List of order items or empty list if nothing was
     */
    public function selectByProductId(Product $product): array
    {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_PID']);
            $stmt->bindValue(':pid', $product->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute();
            // Fetch all rows from the database.
            if ($success) {
                // Fetch all rows from the statement.
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, OrderItem::class);
                    $data = $stmt->fetchAll();
                }
            }
        } catch (\Exception $e) {
            // Handle the exception here
        }
        return $data;
    }

    /**
     * Select order items by order id. This is used to retrieve all orders that belong to a specific order
     * 
     * @param $orderId
     * 
     * @return An array of OrderItem
     */
    public function selectByOrderId(int $orderId): array
    {
        $data = array();
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_WID']);
            $stmt->bindValue(':oid', $orderId, \PDO::PARAM_INT);
            $success = $stmt->execute();
            // Fetch all rows from the database.
            if ($success) {
                // Fetch all rows from the statement.
                if ($stmt->rowCount() > 0) {
                    $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, OrderItem::class);
                    $data = $stmt->fetchAll();
                }
            }
        } catch (\PDOException $e) {
            // Handle the exception here
        }
        return $data;
    }


    /**
     * Remove a product from the database by PID. This is used to remove an existing product and all its products in the shopping cart
     * 
     * @param $entity
     * 
     * @return The number of rows affected by the removal ( 0 if nothing was affected
     */
    public function removeByPid(Product $entity): int
    {
        $result = 0;
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['DELETE_PID']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $success = $stmt->execute();
            // Returns the number of rows in the result set.
            if ($success) {
                $result = $stmt->rowCount();
            }
        } catch (\PDOException $e) {
            // Handle the exception here
        }
        return $result;
    }

    /**
     * Update stock for order item. This is used to update stock for product and / or order
     * 
     * @param $orderItem
     * 
     * @return number of rows updated or 0 if something went wrong
     */
    public function updateStock(OrderItem $orderItem): int
    {
        $result = 0;
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':oid', $orderItem->getOrderId(), \PDO::PARAM_INT);
            $stmt->bindValue(':pid', $orderItem->getProductId(), \PDO::PARAM_INT);
            $success = $stmt->execute();
            // Returns the number of rows in the result set.
            if ($success) {
                $result = $stmt->rowCount();
            }
        } catch (\PDOException $e) {
            // Handle the exception here
        }
        return $result;
    }

    /**
     * Find order item by order and product id. This method is used to find order item by order id and product id
     * 
     * @param $orderId
     * @param $productId
     * 
     * @return OrderItem or null if not found or exception is thrown
     */
    public function findOrderItemByOrderAndProduct(int $orderId, int $productId): ?OrderItem
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $query = "SELECT * FROM " . self::$TABLE_NAME . " WHERE order_id = :orderId AND product_id = :productId";
            $stmt = $connection->prepare($query);
            $stmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->bindValue(':productId', $productId, \PDO::PARAM_INT);
            $stmt->execute();

            // Fetch the current row count.
            if ($stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, OrderItem::class);
                return $stmt->fetch();
            }
        } catch (\PDOException $e) {
            // Handle exception
        }
        return null;
    }
    // Adds or updates an OrderItem in the database. Returns true if the item was added

    public function processOrderItem(OrderItem $orderItem): bool
    {
        // Check if the OrderItem already exists
        $existingOrderItem = $this->findOrderItemByOrderAndProduct($orderItem->getOrderId(), $orderItem->getProductId());

        if ($existingOrderItem) {
            // Update the existing order item
            return $this->update($orderItem) > 0;
        } else {
            // Insert a new order item
            return $this->insert($orderItem) > 0;
        }
    }

    /**
     * Adds a product to an order.
     *
     * @param int $orderId
     * @param int $productId
     * @param int $quantity
     * @return void
     */
    public function addToOrder($orderId, $productId, $quantity)
    {
        try {
            // First, retrieve the unit price for the product
            $priceStmt = $this->dbConnect->getConnection()->prepare("SELECT price FROM products WHERE id = :productId");
            $priceStmt->bindValue(':productId', $productId, \PDO::PARAM_INT);
            $priceStmt->execute();
            $unitPrice = $priceStmt->fetchColumn();

            if ($unitPrice === false) {
                throw new \Exception("Product not found or price unavailable");
            }

            // Now, insert into orderitems
            $stmt = $this->dbConnect->getConnection()->prepare(
                "INSERT INTO orderitems (orderId, productId, quantity, unitPrice) VALUES (:orderId, :productId, :quantity, :unitPrice)"
            );
            $stmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->bindValue(':productId', $productId, \PDO::PARAM_INT);
            $stmt->bindValue(':quantity', $quantity, \PDO::PARAM_INT);
            $stmt->bindValue(':unitPrice', $unitPrice, \PDO::PARAM_STR); // Assuming price is a float, using STR for safe binding
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (\PDOException $e) {
            print "Error in addToOrder: " . $e->getMessage() . "<br>";
            return false;
        }
    }


    /**
     * Gets the items for each order
     *
     * @param integer $orderId
     * @return array
     */
    public function getOrderItems(int $orderId): array
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("SELECT * FROM " . self::$TABLE_NAME . " WHERE order_id = :orderId");
            $stmt->bindParam(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->execute();

            $orderItems = [];
            while ($row = $stmt->fetch()) {
                $orderItems[] = new OrderItem(
                    $row['id'],
                    $row['order_id'],
                    $row['product_id'],
                    $row['quantity']
                );
            }

            return $orderItems;
        } catch (\PDOException $e) {
            // Handle exception
            return [];
        }
    }

        /**
         * Clearing function function
         *
         * @param integer $orderId
         * @return boolean
         */
    public function clearOrder(int $orderId): bool
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("DELETE FROM " . self::$TABLE_NAME . " WHERE order_id = :orderId");
            $stmt->bindParam(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (\PDOException $e) {
            // Handle exception
            return false;
        }
    }

    /**
     * Handles the logic to add a product to the cart
     *
     * @param integer $orderId
     * @param integer $productId
     * @param integer $quantity
     * @return boolean
     */
    public function addToCart(int $orderId, int $productId, int $quantity): bool
    {
        try {
            $connection = $this->dbConnect->getConnection();

            // Check if the product is already in the order
            $stmt = $connection->prepare("SELECT * FROM " . self::$TABLE_NAME . " WHERE order_id = :orderId AND product_id = :productId");
            $stmt->bindParam(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->bindParam(':productId', $productId, \PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Product exists in the order, update the quantity
                $updateStmt = $connection->prepare("UPDATE " . self::$TABLE_NAME . " SET quantity = quantity + :quantity WHERE order_id = :orderId AND product_id = :productId");
                $updateStmt->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
                $updateStmt->bindParam(':orderId', $orderId, \PDO::PARAM_INT);
                $updateStmt->bindParam(':productId', $productId, \PDO::PARAM_INT);
                $updateStmt->execute();
            } else {
                // Product does not exist in the order, insert new record
                $insertStmt = $connection->prepare("INSERT INTO " . self::$TABLE_NAME . " (order_id, product_id, quantity) VALUES (:orderId, :productId, :quantity)");
                $insertStmt->bindParam(':orderId', $orderId, \PDO::PARAM_INT);
                $insertStmt->bindParam(':productId', $productId, \PDO::PARAM_INT);
                $insertStmt->bindParam(':quantity', $quantity, \PDO::PARAM_INT);
                $insertStmt->execute();
            }

            return true;
        } catch (\PDOException $e) {
            // Handle exception
            return false;
        }
    }

    /**
     * Returns the OrderId for a specific user, binding its userId
     *
     * @return void
     */
    public function getCurrentOrderIdForUser()
    {
        $userId = $_SESSION['userId'];

        try {
            $connection = $this->dbConnect->getConnection();

            // Check for existing order for the user
            $stmt = $connection->prepare("SELECT id FROM orders WHERE customer = :userId AND delMethod = 'pending'");
            $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                return $row['id']; // Return existing order ID
            } else {
                // Create a new order if none exists
                $stmt = $connection->prepare("INSERT INTO orders (customer, delMethod) VALUES (:userId, 'Click and collect')");
                $stmt->bindParam(':userId', $userId, \PDO::PARAM_INT);
                $stmt->execute();

                return $connection->lastInsertId(); // Return new order ID
            }
        } catch (\PDOException $e) {
            // Handle exception
            return null;
        }
    }
}
