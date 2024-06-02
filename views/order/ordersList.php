<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link rel="stylesheet" href="./../../css/styles.css">
</head>

<body>
    <h1>Order List</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date of Order Creation</th>
                <th>Delivery Method</th>
                <th>Customer Name</th>
                <th>Total Order Amount</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order) : ?>
                <tr>
                    <td><?php echo $order->getId(); ?></td>
                    <td><?php echo $order->getCreationDate(); ?></td>
                    <td><?php echo $order->getDeliveryMethod(); ?></td>
                    <td><?php echo $order->getUserName(); ?></td>
                    <td><?php echo $order->getTotalAmount(); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>
