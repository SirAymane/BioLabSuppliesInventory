<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make an Item Order</title>
    <link rel="stylesheet" href="./../../css/styles.css"> <!-- Add your CSS file path here -->
</head>

<body>

    <!-- Main Content Section -->
    <div class="container">

        <section class="item-search">
            <!-- Display Messages -->
            <?php if (isset($params['message'])) : ?>
                <div class="alert alert-warning">
                    <strong><?php echo $params['message']; ?></strong>
                </div>
            <?php endif ?>

            <!-- No Items Message -->
            <?php if (empty($params['items'])) : ?>
                <div class="no-items">
                    <h3 id="order-title">Make a search to add items to your cart</h3>
                    <p>Search for items to add them to your order.</p>
                </div>
            <?php endif; ?>

            <!-- Item Search Form -->
            <form method="post" action="index.php?action=order/itemSearch">
                <div class="row g-3 align-items-center">
                    <span class="col-auto">
                        <label for="description" class="col-form-label">Item Description</label>
                    </span>
                    <span class="col-auto">
                        <input type="text" id="description" name="description" class="form-control" value="<?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?>">
                    </span>
                    <span class="col-auto">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </span>
                </div>
            </form>

            <!-- Item List Table -->
            <?php if (!empty($params['items'])) : ?>
                <div class="table-responsive">
                    <h3>List of Items Found</h3>
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($params['items'] as $item) : ?>
                                <tr>
                                    <form method="post" action="index.php?action=order/addItemToCart">
                                        <td><?php echo $item->getCode(); ?></td>
                                        <td><?php echo $item->getDescription(); ?></td>
                                        <td><?php echo $item->getPrice(); ?></td>
                                        <td><input type="number" name="quantity" min="1" class="form-control" required></td>
                                        <td>
                                            <input type="hidden" name="item_id" value="<?php echo $item->getId(); ?>">
                                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                                        </td>
                                    </form>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </div>

</body>

</html>
