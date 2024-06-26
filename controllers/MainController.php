<?php

namespace SirAymane\ecommerce\controller;





// Models
require_once 'model/User.php';

use SirAymane\ecommerce\model\User;

require_once 'model/Product.php';

use SirAymane\ecommerce\model\Product;

require_once 'model/Order.php';

use SirAymane\ecommerce\model\Order;

require_once 'model/OrderItem.php';

use SirAymane\ecommerce\model\OrderItem;

require_once 'model/WebStoreModel.php';

use SirAymane\ecommerce\model\WebStoreModel;


// Utilities
require_once 'lib/ViewLoader.php';

use SirAymane\ecommerce\lib\ViewLoader as View;

require_once 'lib/Validator.php';

use SirAymane\ecommerce\lib\Validator;

require_once 'lib/Auth.php';

use SirAymane\ecommerce\lib\Auth;




/**
 * Main controller for the eCommerce application.
 */
class MainController
{
    /**
     * @var ViewLoader
     */
    private $view;
    /**
     * @var WebStoreModel 
     */
    private $WebStoreModel;
    /**
     * @var string  
     */
    private $action;
    /**
     * @var string  
     */
    private $requestMethod;

    /**
     * Constructor for the class. Called by __construct () to initialize the class and create the view and user
     */
    public function __construct()
    {
        $this->view = new View();
        $this->WebStoreModel = new WebStoreModel();
    }

    /***************** START HTTP REQUEST METHODS *****************/

    /**
     * Process request and return response to client. This method is called by web server when request is received from client
     */
    public function processRequest()
    {
        Auth::startSessionIfNotStarted();
        $this->action = $this->getActionFromRequest();
        $this->requestMethod = $this->getRequestMethod();

        // Handle the request method.
        switch ($this->requestMethod) {
            case 'get':
                $this->doGet();
                break;
            case 'post':
                $this->doPost();
                break;
            default:
                $this->handleError();
                break;
        }
    }

    /**
     * This is the entry point for GET requests. It dispatches the request based on the $this - > action
     */
    private function doGet()
    {
        switch ($this->action) {
            case 'loginform':
                $this->doLoginForm();
                break;
            case 'logout':
                $this->logout();
                break;
            case 'home':
                $this->doHomePage();
                break;
            case 'product/manage':
                $this->doProductManagement();
                break;
            case 'product/editForm':
                $this->doProductEditForm();
                break;
            case 'product/delete':
                $this->doProductConfirmRemoval();
                break;
            case 'order/manageOrderItems':
                $this->doOrderItemsManagement();
                break;
            case 'order/itemSearch': // Added to handle order item search
                $this->doOrderItemSearch();
                break;
            case 'order/shoppingCart':
                $this->doShoppingCart();
                break;
            case 'order/addItemToCart':
                $this->addItemToCart();
                break;
            default:
                $this->handleError();
                break;
        }
    }


    /**
     * Handle post requests from the web service This is the method that will be called by the server when a POST request
     */
    private function doPost()
    {
        switch ($this->action) {
            case 'login/submit':
                $this->doLoginUser();
                break;
            case 'product/addForm':
                $this->doProductAddForm();
                break;
            case 'product/add':
                $this->doProductAdd();
                break;
            case 'product/edit':
                $this->doProductEdit();
                break;
            case 'product/delete':
                $this->doProductDelete();
                break;
            case 'product/search':
                $this->doProductSearch();
                break;
            case 'order/itemSearch':
                $this->doOrderItemSearch();
                break;
            case 'order/addItemToCart':
                $this->addItemToCart();
                break;
            case 'order/buy':
                $this->buyOrder();
                break;
            case 'order/cancelConfirmation':
                $this->confirmCancelOrder();
                break;
            case 'order/cancel':
                $this->cancelOrder();
                break;
            default:
                $this->handleError();
                break;
        }
    }

    /**
     * Gets the action from the request. If there is no action the user is redirected to the home page.
     * 
     * @return The action to be executed by the user or the home page if none is found in the request (POST or GET)
     */
    private function getActionFromRequest()
    {
        // Returns the action to be used for the filter
        if (\filter_has_var(\INPUT_POST, 'action')) {
            return \filter_input(\INPUT_POST, 'action');
        } elseif (\filter_has_var(\INPUT_GET, 'action')) {
            return \filter_input(\INPUT_GET, 'action');
        } else {
            return "home";
        }
    }


    /**
     * Returns the request method.
     * 
     * @return The request method or false if none is found in the request header or there is no request method
     */
    private function getRequestMethod()
    {
        return \strtolower(\filter_input(\INPUT_SERVER, 'REQUEST_METHOD'));
    }

/***************** END HTTP REQUEST METHODS *****************/

/***************** START NAVIGATION CONTROL METHODS *****************/

    /**
     * Displays home page content.
     */
    public function doHomePage()
    {
        $this->view->show("home.php", []);
    }

    /**
     * Handles errors and displays them to the user. 
     * This is called by error handler when something goes wrong 
     */
    private function handleError()
    {
        $this->view->show("message.php", ['message' => 'Something went wrong!']);
        // Added for debugging 
        echo "Error: Action - " . $this->action . ", Method - " . $this->requestMethod;
    }

/***************** END NAVIGATION CONTROL METHODS *****************/

/***************** START USER CONTROL METHODS *****************/


    /**
     * Show the login form and allow the user to login with their username and password. 
     */
    private function doLoginForm()
    {
        $this->view->show("user/loginform.php", []);
    }

    /**
     * Validate and log in the user based on the username and password. 
     * If the user is valid redirect to the home page
     */
    private function doLoginUser()
    {
        $userCredentials = Validator::getFormCredentials();
        list($username, $password) = $userCredentials;

        $userFound = $this->WebStoreModel->findUserByUsernameAndPassword($username, $password);
        if ($userFound) {
            // Set up user session, redirect to the home page or dashboard
            $_SESSION['username'] = $userFound->getUsername();
            $_SESSION['role'] = $userFound->getRole();
            $_SESSION['userId'] = $userFound->getId(); // Store the user's ID in the session

            header("Location: index.php");
        } else {
            // Show error message on the login form
            $this->view->show("user/loginform.php", ['error' => 'Invalid credentials']);
        }
    }


    /**
     * Logout the user and destroy the session and redirect to the home page.
     * This is called when the user clicks on the logout button
     */
    private function logout()
    {
        // Unset user session and redirect to login form
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        session_destroy(); // Destroy the cookie
        header("Location: index.php?action=home"); // Redirect to the home page
    }

/***************** END USER CONTROL METHODS *****************/

/***************** START PRODUCT MANAGEMENT CONTROL METHODS *****************/


    /**
     * Handles the product management page display.
     * This method is accessible only by admin users.
     *
     * @param string|null $message Optional message to display.
     */
    public function doProductManagement($message = null)
    {
        Auth::ensureAdminRole(); // Ensure that the user has admin privileges

        $products = $this->WebStoreModel->findAllProducts(); // Fetch all products
        $this->view->show("product/productManagement.php", ['products' => $products, 'message' => $message]);
    }


    /**
     * Handles the search for products by description.
     * Accessible by both admin and registered users.
     */
    public function doProductSearch()
    {
        $description = filter_input(INPUT_POST, 'description'); // Ensure this matches your form method

        if (!empty($description)) {
            $products = $this->WebStoreModel->findProductByDescription($description);
        } else {
            $products = [];
        }

        $this->view->show("product/productList.php", ['products' => $products]);
    }


    /**
     * Display the form for adding a new product.
     * Only accessible by admin users.
     */
    public function doProductAddForm()
    {
        Auth::ensureAdminRole(); // Ensure admin role

        // Show the product add form
        $this->view->show("product/productAddForm.php", []);
    }


    /**
     * Handles the addition of a new product.
     * Only accessible by admin users.
     */
    public function doProductAdd()
    {
        Auth::ensureAdminRole(); // Check admin role

        $code = filter_input(INPUT_POST, 'code');
        $description = filter_input(INPUT_POST, 'description');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

        // Check for empty fields or invalid price
        if (empty($code) || empty($description) || $price === false) {
            $error = "Please fill all fields correctly.";
            $this->view->show("product/productAddForm.php", ['error' => $error]);
            return;
        }

        // Check if the code is unique before adding the product
        $existingProduct = $this->WebStoreModel->findProductByCode($code);

        if (!$existingProduct) {
            $product = new Product(0, $code, $description, $price);
            $result = $this->WebStoreModel->addProduct($product);

            if ($result) {
                // Product added successfully, display a success message
                $this->doProductManagement("Product added successfully.");
            } else {
                // Failed to add the product, display an error message
                $this->doProductManagement("Failed to add the product.");
            }
        } else {
            // Product code is not unique, display an error message
            $error = "Product code must be unique.";
            $this->doProductManagement($error);
        }
    }




    /**
     * Display the form for editing an existing product.
     * Accessible only by admin users.
     */
    public function doProductEditForm()
    {
        Auth::ensureAdminRole(); // Ensure admin role

        $productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $product = $this->WebStoreModel->findProductById($productId);

        if ($product) {
            $this->view->show("product/productEditForm.php", ['product' => $product]);
        } else {
            $this->handleError();
        }
    }

    /**
     * Handles the modification of a product.
     * Only accessible by admin users.
     */
    public function doProductEdit()
    {
        Auth::ensureAdminRole(); // Check admin role

        // Extract product details from POST request
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $code = filter_input(INPUT_POST, 'code');
        $description = filter_input(INPUT_POST, 'description');
        $price = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

        if ($id && $code && $description && $price) {
            $product = new Product($id, $code, $description, $price);

            // Check if the code is unique before attempting to modify the product
            $existingProduct = $this->WebStoreModel->findProductByCode($code);

            if (!empty($existingProduct) && $existingProduct[0]->getId() == $id) {
                $result = $this->WebStoreModel->modifyProduct($product);

                if ($result) {
                    $message = "Product updated successfully.";
                } else {
                    $message = "Failed to update the product.";
                }
            } else {
                $message = "Product code must be unique.";
            }

            // Check if an error message is set, and if so, display it
            if (isset($message)) {
                $this->doProductManagement($message);
            } else {
                // No error message, proceed with displaying the updated product list
                $this->doProductManagement();
            }
        } else {
            // Handle the error
            $this->handleError();
        }
    }


    /**
     * Handles the deletion of a product.
     * Only accessible by admin users.
     */
    public function doProductDelete()
    {
        Auth::ensureAdminRole(); // Check admin role

        $productId = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($productId) {
            $product = new Product($productId, '', '', 0.0);

            // Check if the product exists before attempting to delete
            $existingProduct = $this->WebStoreModel->findProductById($productId);

            if ($existingProduct) {
                $result = $this->WebStoreModel->removeProduct($product);

                if ($result) {
                    // Product deleted successfully, display a success message
                    $this->doProductManagement("Product deleted successfully.");
                } else {
                    // Failed to delete the product, display an error message
                    $this->doProductManagement("Failed to delete the product.");
                }
            } else {
                // Product with the provided ID doesn't exist, display an error message
                $this->doProductManagement("Product not found.");
            }
        } else {
            // Handle the error
            $this->handleError();
        }
    }


    /**
     *  Comment on the DoProductConfirmRemoval and the relational constraints in the database
     * 
     * --> Products are referenced in the 'orderitems' table (another table more than products).
     * 
     * --> The relationship between 'products' and 'orderitems' is defined as 'ON DELETE restrict',
     *   meaning that a product cannot be directly deleted if it's referenced in any 'orderitems'.
     * 
     * --> Therefore, before deleting a product, it's essential to check if the product is part of any order items.
     */

    /**
     *  Handles the confirmation process for product removal.
     *  
     *  This function fetches the product based on the provided ID and displays a confirmation page.
     *  The confirmation step ensures that the product is not part of any orders before deletion, maintaining data integrity.
     *  
     *  @return void Displays a confirmation page for product removal. If the product is part of an order, 
     *  appropriate measures are taken (e.g., informing the user that the product cannot be deleted).
     */
    public function doProductConfirmRemoval()
    {
        $data = array();
        // Fetch data for the selected product
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (($id !== false) && (!is_null($id))) {
            $product = $this->WebStoreModel->findProductById($id);
            if (!is_null($product)) {
                $data['product'] = $product;
            }
        }
        $this->view->show("product/productConfirmRemoval.php", $data);
    }



/***************** END PRODUCT MANAGEMENT CONTROL METHODS *****************/

/***************** START ORDER ITEMS MANAGEMENT METHODS *****************/


    private function doOrderItemsManagement($message = null)
    {

        $this->view->show('/order/orderItemsManagement.php', ['orders' => [], 'message' => $message]);
    }

    /** 
     * Process the order item addition or update.
     */
    private function manageOrderItems()
    {
        // Retrieve product and quantity from POST data
        $productId = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

        // Validate inputs
        if (!$productId || !$quantity) {
            $message = 'Invalid product or order details.';
            $this->doOrderItemsManagement($message);
            return;
        }

        // Process the order item directly without creating an object
        $result = $this->WebStoreModel->processOrderItem($productId, $quantity);

        // Check the result and display appropriate message
        $message = $result ? 'Order item processed successfully.' : 'Failed to process order item.';
        $this->doOrderItemsManagement($message);
    }



    /**
     * Handles the search for order items by description.
     * Accessible by both admin and registered users.
     */
    public function doOrderItemSearch()
    {
        $description = filter_input(INPUT_POST, 'description');
        $_SESSION['lastSearch'] = $description; // Store the search term

        if (!empty($description)) {
            $orderItems = $this->WebStoreModel->findProductByDescription($description);
        } else {
            $orderItems = [];
        }

        $this->view->show("order/orderItemsList.php", ['orderItems' => $orderItems]);
    }



    /**
     * Adds a product to the cart. This is a POST request and must contain at least product_id and quantity
     * @return void
     */
    private function addItemToCart()
    {
        // Retrieve product_id and quantity from the POST parameters
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);

        // Check if product_id and quantity are valid
        if ($product_id !== false && $quantity !== false && $quantity > 0) {
            // Check if the cart session variable exists, if not, initialize it
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = array();
            }

            // Add the item to the cart in the session
            $_SESSION['cart'][$product_id] = $quantity;
            $message = 'Item successfully added to your cart.';
        } else {
            $message = 'Invalid product or quantity.';
        }

        // Redirect back to the order item search page with the message
        $this->doOrderItemsManagement($message);
    }


    /**
     *  Redirects back to the search page if there's a last search term stored
     *
     * @return void
     */
    private function redirectBackToSearch()
    {
        // Check if there's a last search term stored
        $lastSearch = $_SESSION['lastSearch'] ?? '';

        // Redirect to the search page, preserving the last search term if available
        header("Location: index.php?action=order/itemSearch" . (!empty($lastSearch) ? "&description=" . urlencode($lastSearch) : ""));
        exit;
    }

    /**
     * Display order details for a specific order.
     */
    public function doShowOrderDetails()
    {
        // Retrieve order ID from GET parameters
        $orderId = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

        // Fetch order details
        if ($orderId) {
            $order = $this->WebStoreModel->findOrderById($orderId);
            $orderItems = $this->WebStoreModel->findOrderItemsByOrderId($orderId);

            if ($order && $orderItems) {
                $data = ['order' => $order, 'orderItems' => $orderItems];
            } else {
                $data = ['message' => 'Order details not found.'];
            }
        } else {
            $data = ['message' => 'Invalid order ID.'];
        }

        $this->view->show('orders/orderDetails.php', $data);
    }




/***************** END ORDER ITEMS MANAGEMENT METHODS *****************/

/***************** START ORDER ITEMS CHECKOUT METHODS *****************/

    /**
     * Function to display the shopping cart. This function is called by the view
     *
     * @return void
     */
    private function doShoppingCart()
    {
        // Check if there are items in the shopping cart session
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            // No items in the cart, display a message in the orderItemCheckout.php view
            $this->view->show('/order/orderItemsCheckout.php', [
                'emptyCartMessage' => 'Your shopping cart is currently empty. Please add items to your cart before proceeding.',
            ]);
            return;
        }

        // Retrieve the user's ID from the session (assuming you store it upon login)
        $userId = $_SESSION['userId']; // Using the user_id defined before at the order

        // Retrieve the user's information (username and email) from your model
        $user = $this->WebStoreModel->findUserById($userId);

        if (!$user) {
            // Handle the case where the user is not found (e.g., session expired)
            // You can redirect them to the login page or take appropriate action.
            return;
        }

        // Get the username and email from the user object
        $username = $user->getUsername();
        $email = $user->getEmail();

        // You can set the available delivery methods here
        $deliveryMethods = ['Click and collect'];

        // Fetch the product details based on the product IDs stored in the cart
        $cartItems = []; // Array to store cart items with description, price, quantity, and total per line

        foreach ($_SESSION['cart'] as $productId => $quantity) {
            // Retrieve product details (description and price) from your product model
            $product = $this->WebStoreModel->findProductById($productId);

            if ($product) {
                $productDescription = $product->getDescription();
                $productPrice = $product->getPrice();

                // Calculate total per line (price * quantity)
                $total = $productPrice * $quantity;

                // Create an array for each cart item
                $cartItems[] = [
                    'description' => $productDescription,
                    'price' => $productPrice,
                    'quantity' => $quantity,
                    'total' => $total,
                ];
            }
        }

        // Pass the data to the checkout view
        $this->view->show('order/orderItemsCheckout.php', [
            'username' => $username,
            'email' => $email,
            'deliveryMethods' => $deliveryMethods,
            'cartItems' => $cartItems,
        ]);
    }

    /**
     * Buy an order from the user's cart. Reads cart from session data and makes an order at the database
     *
     * @return void
     */
    private function buyOrder()
    {
        $userId = $_SESSION['userId'];
        $cartItems = $_SESSION['cart'] ?? [];

        if (empty($cartItems)) {
            $this->view->show('message.php', ['message' => 'Your cart is empty. Please add items to your cart before proceeding.']);
            return;
        }

        $formattedCartItems = [];
        foreach ($cartItems as $productId => $quantity) {
            $formattedCartItems[] = ['productId' => $productId, 'quantity' => $quantity];
        }
        $orderNumber = rand(100000, 999999); // Generate a random order number
        $completedOrderId = $this->WebStoreModel->createCompleteOrder($userId, $formattedCartItems);


        if (!$completedOrderId) {
            unset($_SESSION['cart']); // Clear the cart on successful order creation
            $this->view->show('message.php', ['message' => "Your order has been successfully placed. Order ID: EU{$orderNumber}"]);
        } else {
            $this->view->show('message.php', ['message' => "Failed to place the order. Please try again later."]);
        }
    }

    /**
     * Display orders for a specific customer.
     * 
     * @param int $customerId The ID of the customer.
     */
    public function doShowCustomerOrders(int $customerId)
    {
        // Fetch orders by customer ID
        $orders = $this->WebStoreModel->findOrdersByUserId($customerId);

        // Display orders
        $this->view->show("order/customerOrders.php", ['orders' => $orders]);
    }

    /**
     * Method used to cancel the order, redirects to confirmation
     *
     * @return void
     */
    public function cancelOrder()
    {
        // Check if there are items in the cart
        if (!empty($_SESSION['cart'])) {
            // Show the confirmation view
            $this->view->show("order/confirmCancelOrder.php");
        } else {
            $message = "No items in the cart to cancel.";
            $this->view->show("message.php", ['message' => $message]);
        }
    }

    /**
     * Handles the confirmation process for order cancellation. 
     * Confirms that the cart data is to be deleted, and if so, clears it from session
     */
    public function confirmCancelOrder()
    {
        // Check if the confirmation form has been submitted
        if (isset($_POST['confirm']) && $_POST['confirm'] === 'yes') {
            // Clear the cart
            unset($_SESSION['cart']);
            $message = "Cart cleared successfully.";
        } else {
            $message = "Cart clearance cancelled.";
        }

        // Redirect back to the main page with a message
        $this->doOrderItemsManagement($message);
    }

/***************** END ORDER ITEMS CHECKOUT METHODS *****************/


/***************** START ORDER LIST METHODS *****************/

    /**
     * Display the list of orders.
     */
    public function doListOrders()
    {
        Auth::startSessionIfNotStarted(); // Start session if not started
        $userRole = $_SESSION['role'] ?? ''; // Get user role from session

        // Check if the user is logged in and has the necessary role
        if (Auth::isAuthenticated() && ($userRole === 'admin' || $userRole === 'user')) {
            // Fetch orders based on user role
            if ($userRole === 'admin') {
                $orders = $this->WebStoreModel->findAllOrders(); // Get all orders for admin
            } else {
                $userId = $_SESSION['userId'] ?? null; // Get user ID from session
                $orders = $this->WebStoreModel->findOrdersByUserId($userId); // Get orders for the logged-in user
            }

            // Display the order list view
            $this->view->show('order/orderList.php', ['orders' => $orders]);
        } else {
            // Redirect to login page if user is not logged in or does not have the necessary role
            header("Location: index.php?action=loginform");
        }
    }

/***************** END ORDER LIST METHODS *****************/
}
