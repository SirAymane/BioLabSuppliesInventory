<?php

namespace SirAymane\ecommerce\model;

require_once __DIR__ . "/persist/UserDao.php";
require_once __DIR__ . "/persist/ProductDao.php";
require_once __DIR__ . "/persist/OrderDao.php";
require_once __DIR__ . "/persist/OrderItemDao.php";


require_once __DIR__ . '/User.php';
require_once __DIR__ . '/Product.php';
require_once __DIR__ . '/Order.php';


use SirAymane\ecommerce\model\persist\UserDao;
use SirAymane\ecommerce\model\persist\ProductDao;
use SirAymane\ecommerce\model\persist\OrderDao;
use SirAymane\ecommerce\model\persist\OrderItemDao;



class WebStoreModel
{
    private UserDao $userDao;
    private ProductDao $productDao;
    private OrderDao $orderDao;
    private OrderItemDao $orderItemDao;

    /**
     * Constructor for the DAO class. This is called by __construct () to initialize the data access object
     */
    public function __construct()
    {
        $this->userDao = new UserDao();
        $this->productDao = new ProductDao();
        $this->orderDao = new OrderDao();
        $this->orderItemDao = new OrderItemDao();
    }

    /***************** START USER METHODS *****************/


    /**
     * Returns all users in the system. This is a shortcut for findAll (). The array can be modified by passing a different array to $this - > userDao - > selectAll ()
     * 
     * 
     * @return an array of all
     */
    public function findAllUsers(): array
    {
        return $this->userDao->selectAll();
    }

    /**
     * Find users by role. This is a shortcut for $this - > selectWhere ( " role " $role ).
     * 
     * @param $role
     * 
     * @return An array of users that match the given role or an empty array if no users match the given role
     */
    public function findUsersByRole(string $role): array
    {
        return $this->userDao->selectWhere("role", $role);
    }

    /**
     * Update user in the database. This is a no - op if the user doesn't exist.
     * 
     * @param $user
     * 
     * @return The number of rows
     */
    public function modifyUser(User $user): ?int
    {
        return $this->userDao->update($user);
    }

    /**
     * Find a user by id. This is a shortcut for $this - > select ( $user ) - > select ( $user ).
     * 
     * @param $id - id The id of the user to find. If you want to search for a user by id use $this - > findUserById ( )
     * 
     * @return The user or null if not found ( for example if there is no user with the given id )
     */
    public function findUserById(int $id): ?User
    {
        $user = new User($id, '', '', '', '', null);
        return $this->userDao->select($user);
    }

    /**
     * Find a user by username and password. This is a wrapper around validateCredentials () to avoid having to do a query every time
     * 
     * @param $username
     * @param $password
     * 
     * @return The user or null if not found ( no exception is thrown in this case ) Note : The return value is cached
     */
    public function findUserByUsernameAndPassword(string $username, string $password)
    {
        return $this->userDao->validateCredentials($username, $password);
    }

    /***************** END USER METHODS *****************/


    /***************** START PRODUCTS METHODS *****************/


    /**
     * Returns all products in the database. This is a shortcut for findAllDaos (). The array is indexed by product id and will contain an array of Product objects.
     * 
     * 
     * @return array of Product objects or null if none are found ( empty array is returned for non - existant products
     */
    public function findAllProducts(): ?array
    {
        return $this->productDao->selectAll();
    }

    /**
     * Find a product by id. This is a shortcut to $this - > select ( $product ).
     * 
     * @param $id
     * 
     * @return The product or null if not found ( for example if there is no product with the given id )
     */
    public function findProductById(int $id): ?Product
    {
        $product = new Product($id, '', '', 0.0);
        return $this->productDao->select($product);
    }


    /**
     * Add a product to the database. This is a wrapper for DAO :: insert () with correct return type
     * 
     * @param $product
     * 
     * @return Id of the product that was added or null if an error occurred ( DB error code : E_WARNING
     */
    public function addProduct(Product $product): ?int
    {
        // Correct return type for insert method
        return $this->productDao->insert($product);
    }

    /**
     * Modifies a product in the database. This is a no - op if the product does not exist
     * 
     * @param $product
     * 
     * @return The number of rows affected or null if there was an error ( in which case it could not be determined
     */
    public function modifyProduct(Product $product): ?int
    {
        return $this->productDao->update($product);
    }

    /**
     * Remove a product from the database. This is a destructive operation. If you want to remove a product that is no longer in the database you should use deleteProduct () instead.
     * 
     * @param $product
     * 
     * @return The number of rows
     */
    public function removeProduct(Product $product): ?int
    {
        return $this->productDao->delete($product);
    }

    /**
     * Finds products by their description using a LIKE query.
     * 
     * @param string $description The description to search for.
     * @return array|null An array of Product objects or null if none found.
     */
    public function findProductByDescription(string $description): ?array
    {
        return $this->productDao->selectWhereLike('description', $description);
    }

    /**
     * Finds a product by its code.
     * 
     * @param string $code The code of the product to find.
     * @return array The found Product objects (may contain one element) or an empty array if not found.
     */
    public function findProductByCode(string $code): array
    {
        $product = $this->productDao->selectWhere("code", $code);
        return is_array($product) ? $product : [];
    }

    /***************** END PRODUCTS METHODS *****************/


    /***************** START ORDER METHODS *****************/

    /**
     * Adds an order to the database.
     * 
     * @param $order
     * 
     * @return The id of the newly created
     */
    public function addOrder(Order $order)
    {
        // Adds a new order to the database
        return $this->orderDao->insert($order);
    }

    /**
     * Updates an existing order in the database. 
     * 
     * @param $order
     * 
     * @return The number of rows updated or null if nothing was updated.
     */
    public function updateOrder(Order $order)
    {
        // Updates an existing order in the database
        return $this->orderDao->update($order);
    }

    /**
     * Finds an order by its ID. 
     * 
     * @param $id
     * 
     * @return The order or null if not found ( for example if there is no order with the given ID )
     */
    public function findOrderById(int $id)
    {
        // Finds an order by its ID
        return $this->orderDao->getOrderById($id);
    }

    /**
     * Find orders by customer ID.
     * 
     * @param $customerId
     * 
     * @return An array of orders keyed by customer ID. Orders are returned in chronological order order_id
     */
    public function findOrdersByCustomerId(int $customerId)
    {
        // Finds orders by customer ID
        return $this->orderDao->findOrdersByCustomerId($customerId);
    }

    /**
     * Find orders by customer ID. 
     * 
     * @param $userId
     * 
     * @return array of orders with the customer ID as the key and the order ID as the value.
     */
    public function findOrdersByUserId(int $userId)
    {
        // Call the existing method to find orders by customer ID
        return $this->findOrdersByCustomerId($userId);
    }


    /**
     * Retrieves all orders from the database. 
     * 
     * @return An array of Order
     */
    public function findAllOrders(): array
    {
        // Retrieves all orders from the database
        return $this->orderDao->selectAll();
    }

    /**
     * Removes an order from the database. This is a destructive operation. 
     * 
     * @param $order
     * 
     * @return The number of rows deleted or null if the operation failed for some reason ( such as no rows in the database
     */
    public function removeOrder(Order $order)
    {
        // Removes an order from the database
        return $this->orderDao->delete($order);
    }

    /***************** END ORDER METHODS *****************/



    /***************** START ORDERITEMS METHODS *****************/


    /**
     * Find order items by order id. 
     * 
     * @param $orderId
     * 
     * @return An array of order items or null if none are found. Note that null is returned if there are no items
     */
    public function findOrderItemsByOrderId(int $orderId)
    {
        return $this->orderItemDao->selectByOrderId($orderId);
    }

    /**
     * Search for products by description.
     * 
     * @param $description
     * 
     * @return An array of Product objects or false if no product is found with the description. If a product is found it will be returned
     */
    public function searchProducts($description)
    {
        // Utilize the existing method findProductByDescription
        return $this->findProductByDescription($description);
    }

    /**
     * Add a product to the cart ( which is now linked to an order ) This is a wrapper for OrderItemDao
     * 
     * @param $userId
     * @param $productId
     * @param $quantity
     * 
     * @return True if the operation was successful false if there is an error ( something went wrong with the database connection
     */
    public function addToCart($userId, $productId, $quantity)
    {
        // Check if there is an existing order ID for the user
        $orderId = $this->getOrderIdForUser($userId);

        // Create a new order if it doesn t exist
        if ($orderId === null) {
            // Create a new order if it doesn't exist
            $orderId = $this->createOrder($userId, 'Click and collect');
            // Checks if the order id is not null.
            if ($orderId === null) {
                return false; // Handle the case where order creation fails
            }
        }

        // Add the product to the cart (which is now linked to an order)
        return $this->orderItemDao->addToOrder($orderId, $productId, $quantity);
    }



    /**
     * View the shopping cart. This is a wrapper for $this - > orderItemDao - > getOrderItems ( $orderId ).
     * 
     * @param $orderId
     * 
     * @return Returns the number of items viewed in the shopping cart or false if something went wrong. Note that it is possible to return an empty array
     */
    public function viewCart($orderId)
    {
        // Views the contents of the shopping cart
        return $this->orderItemDao->getOrderItems($orderId);
    }

    /**
     * Clears the shopping cart. Clears the contents of the shopping cart and all items in the order
     * 
     * @param $orderId
     * 
     * @return True if success false
     */
    public function clearCart($orderId)
    {
        // Clears the contents of the shopping cart
        return $this->orderItemDao->clearOrder($orderId);
    }

    /**
     * Create order for a user. This is a wrapper for orderDao - > createOrder ( $userId $deliveryMethod ).
     * 
     * @param $userId
     * @param $deliveryMethod
     * 
     * @return the newly created order or false if something went wrong during the creation process ( for example if there was an error creating the order
     */
    public function createOrder($userId, $deliveryMethod)
    {
        return $this->orderDao->createOrder($userId, $deliveryMethod);
    }

    /**
     * Create a complete order. This is a wrapper for OrderItemDao :: createCompleteOrder ( $userId $cartItems ).
     * 
     * @param $userId
     * @param $cartItems
     */
    public function createCompleteOrder($userId, $cartItems)
    {
        $orderItemDao = new OrderItemDao(); // Create an instance of OrderItemDao
        return $this->orderDao->createCompleteOrder($userId, $cartItems, $orderItemDao);
    }

    /**
     * Process order item and return true if success. This is a wrapper for OrderItemDao :: processOrderItem ()
     * 
     * @param $orderItem
     * 
     * @return True if the item was processed false if it was not processed ( in which case no changes are made
     */
    public function processOrderItem(OrderItem $orderItem): bool
    {
        return $this->orderItemDao->processOrderItem($orderItem);
    }

    /**
     * Gets the order ID for a user. This is used to determine which order is the most recent in the system
     * 
     * @param $userId
     * 
     * @return The order ID or null if none is available for the user ( in which case the user has no order
     */
    public function getOrderIdForUser($userId)
    {
        // Gets the current order ID for a user
        return $this->orderDao->getCurrentOrderIdForUser($userId);
    }

    /**
     * Cancels an order by it's ID. This is a wrapper for CiviCRM's cancelOrder method
     * 
     * @param $orderId
     * 
     * @return True if success false if not ( error can be cast to Exception ) Note : The order is deleted
     */
    public function cancelOrder($orderId)
    {
        // Cancels an existing order
        return $this->orderDao->cancelOrder($orderId);
    }

    /**
     * Confirms the cancellation of an order.
     * 
     * @param int $orderId The ID of the order to be canceled.
     * @return bool True if the cancellation is successful, false otherwise.
     */
    public function confirmOrderCancellation($orderId)
    {
        // Call the cancelOrder method to cancel the order
        return $this->orderDao->confirmOrderCancellation($orderId);
    }

    /**
     * Confirm an order and update the order status. 
     * 
     * @param $orderId
     * 
     * @return true if success false if not ( in which case errors will be available via the DAO ). 
     */
    public function confirmOrder($orderId)
    {
        return $this->orderDao->confirmOrder($orderId);
    }


    /**
     * Get cart details with user information for a given user. 
     * 
     * @param $username
     * 
     * @return An array with the user information or null if the user is not found in the database 
     */
    public function getCartDetailsWithUser($username)
    {
        // Fetch user by username
        $user = $this->userDao->findByUsername($username);

        // Check if user exists
        // Returns the user if the user is not found.
        if (!$user) {
            // Handle the case where the user is not found
            return null;
        }

        // Get user ID and retrieve order ID for the user
        $userId = $user->getId();
        $orderId = $this->getOrderIdForUser($userId);

        // Fetch cart items for the order
        $cartItems = $this->viewCart($orderId);

        // Return cart details along with user info
        return [
            'cartItems' => $cartItems,
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'deliveryMethod' => 'Click and collect' // Since it's a constant
        ];
    }


    /***************** END ORDERITEMS METHODS *****************/
}
