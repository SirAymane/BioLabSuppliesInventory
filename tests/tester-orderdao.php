<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Management Tester</title>
    <link rel="stylesheet" href="./../css/tester-styles.css">
</head>

<body>
    <header>
        <h1>Order Management Tester</h1>
    </header>
    <main>
        <section class="scenario">
            <h2>Scenario 1: Fetch and Display Order Items</h2>
            <?php
            // Establish database connection
            $servername = "localhost";
            $username = "testerusr";
            $password = "testerpsw";
            $dbname = "ecommerce";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch and display order items
            $sql = "SELECT * FROM orderitems";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><thead><tr><th>Order ID</th><th>Product ID</th><th>Quantity</th><th>Unit Price</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["orderId"] . "</td><td>" . $row["productId"] . "</td><td>" . $row["quantity"] . "</td><td>" . $row["unitPrice"] . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>
        </section>


        <section class="scenario">
            <h2>Scenario 2: Fetch and Display Orders</h2>
            <?php
            

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }


            // Fetch and display orders
            $sql = "SELECT * FROM orders";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table><thead><tr><th>Order ID</th><th>Creation Date</th><th>Delivery Method</th><th>Customer ID</th></tr></thead><tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id"] . "</td><td>" . $row["creationDate"] . "</td><td>" . $row["delMethod"] . "</td><td>" . $row["customer"] . "</td></tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "0 results";
            }

            $conn->close();
            ?>
        </section>



        <section class="scenario">
            <h2>Scenario 3: Add Item to Shopping Cart</h2>
            <?php
            // Re-establish database connection for each scenario for better robustness
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Simulate adding an item to the shopping cart
            $product_id = 123; // Replace 123 with the ID of the product to be added
            $quantity = 2; // Specify the quantity of the product to be added

            // Execute the addItemToCart method (assuming it's a POST request)
            // This would typically involve sending a POST request to the server with product_id and quantity parameters
            // For demonstration purposes, we'll just call the method directly
            addItemToCart($product_id, $quantity);

            // Display a success message
            echo "Item added to shopping cart successfully.";

            $conn->close();

            // Define the addItemToCart method to simulate adding the item to the cart
            function addItemToCart($product_id, $quantity)
            {
                // In a real scenario, this method would handle the addition of the item to the cart
                // For demonstration purposes, we'll just print the product ID and quantity
                echo "Adding product ID: $product_id, Quantity: $quantity to the shopping cart. ";
            }
            ?>
        </section>

        <section class="scenario">
            <h2>Scenario 4: Buy Order</h2>
            <?php
            // Re-establish database connection for each scenario for better robustness
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Set up session data for the cart
            $_SESSION['userId'] = 1; // Assuming the user is logged in with ID 1
            $_SESSION['cart'] = [
                123 => 2, // Product ID => Quantity
                456 => 1,
            ];

            // Attempt to buy an order
            buyOrder();

            $conn->close();

            // Define the buyOrder method to simulate buying an order
            function buyOrder()
            {
                // Retrieve user ID and cart items from session data
                $userId = $_SESSION['userId'];
                $cartItems = $_SESSION['cart'] ?? [];

                // Check if the cart is empty
                if (empty($cartItems)) {
                    echo 'Your cart is empty. Please add items to your cart before proceeding.';
                    return;
                }

                // Simulate creating a complete order in the database
                $formattedCartItems = [];
                foreach ($cartItems as $productId => $quantity) {
                    $formattedCartItems[] = ['productId' => $productId, 'quantity' => $quantity];
                }
                $completedOrderId = rand(1000, 9999); // Simulate generating a random order ID

                if ($completedOrderId) {
                    // Clear the cart on successful order creation
                    unset($_SESSION['cart']);
                    echo "Your order has been successfully placed. Order ID: EU{$completedOrderId}";
                } else {
                    echo "Failed to place the order. Please try again later.";
                }
            }
            ?>
        </section>


        <section class="scenario">
            <h2>Scenario 5: Display Order Details</h2>
            <?php
            // Re-establish database connection for each scenario for better robustness
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Display order details
            $sql = "SELECT * FROM orderitems WHERE orderId = 1";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<ul>";
                while ($row = $result->fetch_assoc()) {
                    echo "<li>Product ID: " . $row["productId"] . ", Quantity: " . $row["quantity"] . ", Unit Price: " . $row["unitPrice"] . "</li>";
                }
                echo "</ul>";
            } else {
                echo "No order details found.";
            }

            $conn->close();
            ?>
        </section>

        <section class="scenario">
            <h2>Scenario 6: Cancel Order</h2>
            <?php
            // Re-establish database connection for each scenario for better robustness
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Cancel order
            $sql = "DELETE FROM orders WHERE id = 1";

            if ($conn->query($sql) === TRUE) {
                echo "Order canceled successfully.";
            } else {
                echo "Error canceling order: " . $conn->error;
            }

            $conn->close();
            ?>
        </section>

        <section class="scenario">
            <h2>Scenario 7: Confirm Cancel Order</h2>
            <?php
            // Re-establish database connection for each scenario for better robustness
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Confirm cancel order (This is just an example scenario)
            echo "Order cancellation confirmed.";

            $conn->close();
            ?>
        </section>

    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Aymane</p>
    </footer>
</body>

</html>