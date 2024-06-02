<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="./../../css/styles.css"> <!-- Add your CSS file path here -->
</head>

<body>

    <?php if (empty($cartItems)) : ?>
        <!-- Message when there are no products in the cart -->
        <div class="empty-cart-message"> <!-- Add the class for the empty cart message -->
            <p>Your shopping cart is currently empty. Please add items to your cart before proceeding.</p>
        </div>
    <?php else : ?>

        <!-- Display user information (username, email) and delivery method dropdown -->
        <div class="user-info">
            <!-- Check if username and email are set, if not display a placeholder or message -->
            <p>Username: <?php echo isset($username) ? $username : "Not available"; ?></p>
            <p>Email: <?php echo isset($email) ? $email : "Not available"; ?></p>
        </div>

        <!-- Delivery method -->
        <div class="delivery-method">
            <label for="delivery-method">Delivery Method:</label>
            <select id="delivery-method" name="delivery-method">
                <?php foreach ($deliveryMethods as $method) : ?>
                    <option value="<?php echo $method; ?>"><?php echo $method; ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Cart items table with improved styling -->
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $cartItem) : ?>
                        <tr>
                            <td><?php echo $cartItem['description']; ?></td>
                            <td><?php echo $cartItem['price']; ?></td>
                            <td><?php echo $cartItem['quantity']; ?></td>
                            <td><?php echo $cartItem['total']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    <!-- Checkout buttons (Buy, Cancel, Continue Shopping) -->
    <form method="post" action="index.php?action=order/buy">
        <button class="btn btn-primary" type="submit">Buy</button>
    </form>
    <form method="post" action="index.php?action=order/cancel">
        <button class="btn btn-secondary" type="submit">Cancel</button>
    </form>
    <a href="index.php?action=order/manageOrderItems" class="btn btn-info">Continue Shopping</a>

    <?php endif; ?>

</body>

</html>
