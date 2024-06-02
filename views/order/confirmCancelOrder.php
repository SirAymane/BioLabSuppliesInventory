<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Cancellation</title>
    <link rel="stylesheet" href="./../../css/styles.css">
</head>
<body>
    <h2>Confirm Cart Cancellation</h2>
    <p>Are you sure you want to clear the cart?</p>
    
    <!-- Confirmation Form -->
    <form method="post" action="index.php?action=order/cancelConfirmation">
        <input type="hidden" name="confirm" value="yes">
        <button type="submit" class="btn btn-danger">Yes, clear my cart</button>
    </form>
</body>
</html>
