<?php

namespace SirAymane\ecommerce\model\persist;

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/../Order.php';

use SirAymane\ecommerce\model\persist\Database as DbConnect;
use SirAymane\ecommerce\model\Order as Order;

use DateTime;

/**
 * Order database persistence class.
 */
class OrderDao
{
    private DbConnect $dbConnect;
    private static string $TABLE_NAME = 'orders';
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
     * Initializes queries for this instance of Silverpeas. Stores queries in queries
     */
    private function initQueries()
    {
        $this->queries['SELECT_ALL'] = sprintf(
            "SELECT * FROM %s",
            self::$TABLE_NAME
        );
        $this->queries['SELECT_WHERE_ID'] = sprintf(
            "SELECT * FROM %s WHERE id = :id",
            self::$TABLE_NAME
        );
        $this->queries['INSERT'] = sprintf(
            "INSERT INTO %s (creationDate, totalAmount, deliveryMethod, user_id) VALUES (:creationDate, :totalAmount, :deliveryMethod, :userId)",
            self::$TABLE_NAME
        );
        $this->queries['UPDATE'] = sprintf(
            "UPDATE %s SET creationDate = :creationDate, totalAmount = :totalAmount, deliveryMethod = :deliveryMethod, user_id = :userId WHERE id = :id",
            self::$TABLE_NAME
        );
        $this->queries['DELETE'] = sprintf(
            "DELETE FROM %s WHERE id = :id",
            self::$TABLE_NAME
        );
    }

    /**
     * Select an order from the database. This is a shortcut for select () to avoid duplicate data
     * 
     * @param $entity
     * 
     * @return The order or null if there was an error fetching
     */
    public function select(Order $entity): ?Order
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $stmt->execute();
            return $this->fetchToOrder($stmt);
        } catch (\PDOException $e) {
            // Error handling
            return null;
        }
    }

    /**
     * Returns all orders in the database. This is a shortcut for select ('*').
     * 
     * 
     * @return Array of Order objects or empty array if there are
     */
    public function selectAll(): array
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_ALL']);
            $stmt->execute();
            $orders = [];
            // Fetch the rows from the statement and add them to the orders array.
            while ($row = $stmt->fetch()) {
                $orders[] = $this->fetchToOrder($stmt);
            }
            return $orders;
        } catch (\PDOException $e) {
            // Error handling
            return [];
        }
    }

    /**
     * Insert a new Order into the database. Returns the number of rows affected.
     * 
     * @param $entity - entity The entity to insert. Must have - > getId () set
     * 
     * @return The number of rows
     */
    public function insert(Order $entity): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['INSERT']);
            $stmt->bindValue(':creationDate', $entity->getCreationDate()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':totalAmount', $entity->getTotalAmount(), \PDO::PARAM_STR);
            $stmt->bindValue(':deliveryMethod', $entity->getDeliveryMethod(), \PDO::PARAM_STR);
            $stmt->bindValue(':userId', $entity->getUserId(), \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }

    /**
     * Update an Order in the database. This is used to update the data of an Order in the database
     * 
     * @param $entity
     * 
     * @return The number of rows affected or 0 if there was an
     */
    public function update(Order $entity): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['UPDATE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $stmt->bindValue(':creationDate', $entity->getCreationDate()->format('Y-m-d H:i:s'), \PDO::PARAM_STR);
            $stmt->bindValue(':totalAmount', $entity->getTotalAmount(), \PDO::PARAM_STR);
            $stmt->bindValue(':deliveryMethod', $entity->getDeliveryMethod(), \PDO::PARAM_STR);
            $stmt->bindValue(':userId', $entity->getUserId(), \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }

    /**
     * Delete an entity from the database. This is a destructive operation. If you want to delete a non - persisted entity use $entity - > save ()
     * 
     * @param $entity
     * 
     * @return The number of rows deleted or 0 on error ( database error
     */
    public function delete(Order $entity): int
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['DELETE']);
            $stmt->bindValue(':id', $entity->getId(), \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }

    /**
     * Fetches data from the result set and builds an order object.
     * @param $statement The statement with query data.
     * @return mixed The order object or false in case of error.
     */
    /**
     * Fetches the next row from the statement and returns it as an Order. This method is used by fetchToOrder () to fetch the next row from the statement.
     * 
     * @param $statement
     * 
     * @return The next Order or null if there are no more
     */
    private function fetchToOrder($statement): ?Order
    {
        $row = $statement->fetch();
        // Returns an Order object based on the row data.
        if ($row) {
            return new Order(
                intval($row['id']),
                new \DateTime($row['creationDate']),
                floatval($row['totalAmount']),
                $row['deliveryMethod'],
                intval($row['user_id'])
            );
        } else {
            return null;
        }
    }

    /**
     * Get an order by id. This is the most common method to get orders that are associated with a user
     * 
     * @param $id
     * 
     * @return The order or null if not found or error in
     */
    public function getOrderById(int $id): ?Order
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare($this->queries['SELECT_WHERE_ID']);
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch();
            // Creates a new Order object based on the given row.
            if ($row) {
                return new Order(
                    $row['id'],
                    new DateTime($row['creationDate']),
                    $row['totalAmount'],
                    $row['deliveryMethod'],
                    $row['userId']
                );
            }
        } catch (\PDOException $e) {
            // Error handling
        }

        return null;
    }

    /**
     * Find Orders by Customer Id This method is used to find all orders associated with a customer
     * 
     * @param $customerId
     * 
     * @return An array of orders or an empty array if none
     */
    public function findOrdersByCustomerId(int $customerId): array
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("SELECT * FROM orders WHERE customer = :customerId");
            $stmt->bindParam(':customerId', $customerId, \PDO::PARAM_INT);
            $stmt->execute();
            // Returns an array of all rows in the statement.
            if ($stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Order::class);
                return $stmt->fetchAll();
            } else {
                return [];
            }
        } catch (\PDOException $e) {
            // Handle the exception here
            return [];
        }
    }



    /**
     * Creates a new order for $userId and $deliveryMethod. Returns null if there is an error.
     * 
     * @param $userId
     * @param $deliveryMethod
     * 
     * @return Order object or null if something went wrong during the
     */
    public function createOrder($userId, $deliveryMethod)
    {
        try {
            $stmt = $this->dbConnect->getConnection()->prepare(
                "INSERT INTO orders (creationDate, delMethod, customer) VALUES (CURRENT_TIMESTAMP, :delMethod, :customer)"
            );
            $stmt->bindValue(':delMethod', $deliveryMethod, \PDO::PARAM_STR);
            $stmt->bindValue(':customer', $userId, \PDO::PARAM_INT);
            $stmt->execute();

            $orderId = $this->dbConnect->getConnection()->lastInsertId();
            // Creates a new order object.
            if ($orderId) {
                return new Order($orderId, new DateTime(), 0.0, $deliveryMethod, $userId);
            } else {
                throw new \Exception("Failed to create order: Insertion returned no ID");
            }
        } catch (\PDOException $e) {
            print "Error in createOrder: " . $e->getMessage() . "<br>";
            return null;
        }
    }


    /**
     * Create a complete order and add items to it. This is used when you want to click and collect a cart item
     * 
     * @param $userId
     * @param $cartItems
     * @param $orderItemDao
     * 
     * @return The ID of the order that was created and added to
     */
    public function createCompleteOrder($userId, $cartItems, OrderItemDao $orderItemDao)
    {
        try {
            $connection = $this->dbConnect->getConnection();

            // Begin a transaction if not already in a transaction.
            if (!$connection->inTransaction()) {
                $connection->beginTransaction();
            }

            // Creating order
            $order = $this->createOrder($userId, 'Click and collect');
            // Creates a new order.
            if (!$order) {
                throw new \Exception("Order creation failed");
            }

            $orderId = $order->getId();

            // Adding items to the order
            foreach ($cartItems as $item) {
                // Add item to order. Throws exception if item already exists.
                if (!$orderItemDao->addToOrder($orderId, $item['productId'], $item['quantity'])) {
                    throw new \Exception("Failed to add item to order");
                }
            }

            // Commit the transaction
            $connection->commit();

            // Return the order ID
            return $orderId;
        } catch (\Exception $e) {
            // Handle exceptions
            error_log("Error in createCompleteOrder: " . $e->getMessage());
            // Rollback the transaction if the connection is in a transaction.
            if ($connection->inTransaction()) {
                $connection->rollback();
            }
            return null;
        }
    }




    /**
     * Confirm an order by checking if it has been filled. This is used to determine if we have a chance to do something with the order
     * 
     * @param $orderId
     * 
     * @return True if the order has been filled false if it
     */
    public function confirmOrder($orderId)
    {
        try {
            $stmt = $this->dbConnect->getConnection()->prepare("SELECT COUNT(*) FROM orders WHERE id = :orderId");
            $stmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->execute();
            $count = $stmt->fetchColumn();

            return $count > 0;
        } catch (\PDOException $e) {
            // Handle exceptions appropriately
            return false;
        }
    }


    /**
     * Get the order id for a user. This is used to determine which order is the most recent to which the user has access
     * 
     * @param $userId
     * 
     * @return The id of the order or null if there is
     */
    public function getCurrentOrderIdForUser($userId)
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("SELECT id FROM orders WHERE user_id = :userId ORDER BY id DESC LIMIT 1");
            $stmt->bindValue(':userId', $userId, \PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row ? $row['id'] : null;
        } catch (\PDOException $e) {
            // Error handling
            return null;
        }
    }


    /**
     * Confirms cancellation of an order. This is a no - op if the order doesn't exist
     *
     * @param int $orderId
     * @return void
     */
    public function cancelOrder($orderId)
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("DELETE FROM orders WHERE id = :orderId");
            $stmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->rowCount(); // Returns the number of affected rows
        } catch (\PDOException $e) {
            // Error handling
            return 0;
        }
    }


    /**
     * Confirms the cancellation of an order.
     * 
     * @param int $orderId The ID of the order to be canceled.
     * @return bool True if the cancellation is successful, false otherwise.
     */
    public function confirmOrderCancellation($orderId)
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("UPDATE orders SET status = 'cancelled' WHERE id = :orderId");
            $stmt->bindValue(':orderId', $orderId, \PDO::PARAM_INT);
            $stmt->execute();

            // Check if any row is affected
            if ($stmt->rowCount() > 0) {
                return true; // Cancellation successful
            } else {
                return false; // Cancellation failed
            }
        } catch (\PDOException $e) {
            // Error handling
            return false;
        }
    }

    /**
     * Used to find all orders
     *
     * @return array
     */
    public function findAllOrders(): array
    {
        try {
            $connection = $this->dbConnect->getConnection();
            $stmt = $connection->prepare("SELECT * FROM orders");
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $stmt->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, Order::class);
                return $stmt->fetchAll();
            } else {
                return [];
            }
        } catch (\PDOException $e) {
            // Handle the exception here
            return [];
        }
    }
}
