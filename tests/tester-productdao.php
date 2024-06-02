<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management Tester</title>
    <link rel="stylesheet" href="./../css/tester-styles.css">
</head>

<body>
    <header>
        <h1>Product Management Tester</h1>
    </header>
    <main>
        <section class="scenario">
            <h2>Scenario 1: Fetch and Display All Products</h2>
            <?php
            // Include necessary PHP files and namespaces
            require_once "./../lib/Debug.php";


            use SirAymane\ecommerce\controller\MainController;
            use SirAymane\ecommerce\lib\Debug;

            Debug::iniset();

            require_once "./../model/WebStoreModel.php";
            require_once __DIR__ . './../model/Product.php';

            use SirAymane\ecommerce\model\WebStoreModel;
            use SirAymane\ecommerce\model\Product;

            // Create an instance of the WebStoreModel
            $webStoreModel = new WebStoreModel();

            // Scenario 1: Fetch and display all products
            $products = $webStoreModel->findAllProducts();
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product) : ?>
                        <tr>
                            <td><?php echo $product->getId(); ?></td>
                            <td><?php echo $product->getCode(); ?></td>
                            <td><?php echo $product->getDescription(); ?></td>
                            <td><?php echo $product->getPrice(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="scenario">
            <h2>Scenario 2: Fetch and Display Products by Code (P2)</h2>
            <?php
            // Scenario 2: Fetch and Display Products by Code
            $code = "P2";
            $productsByCode = $webStoreModel->findProductByCode($code);
            ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productsByCode as $product) : ?>
                        <tr>
                            <td><?php echo $product->getId(); ?></td>
                            <td><?php echo $product->getCode(); ?></td>
                            <td><?php echo $product->getDescription(); ?></td>
                            <td><?php echo $product->getPrice(); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <section class="scenario">
            <h2>Scenario 3: Insert a New Product</h2>
            <?php
            // Scenario 3: Insert a New Product
            $newProduct = new Product(0, 'newcode', 'newdescription', 12.34);
            $addResult = $webStoreModel->addProduct($newProduct);
            if ($addResult) {
                $message = "Product added successfully.";
            } else {
                $message = "Failed to add the product.";
            }
            echo $message;
            ?>
        </section>

        <section class="scenario">
            <h2>Scenario 4: Attempt to add a product with an existing code</h2>
            <?php
            // Scenario 4: Attempt to add a product with an existing code
            $existingProduct = new Product(0, 'existingcode', 'existingdescription', 9.99);
            $addResult = $webStoreModel->addProduct($existingProduct);
            if ($addResult) {
                $message = "Product added successfully (unexpected).";
            } else {
                $message = "Product code must be unique (expected).";
            }
            echo $message;
            ?>
        </section>

        <section class="scenario">
            <h2>Scenario 5: Delete a product</h2>
            <?php
            // Scenario 5: Delete a product
            $productIdToDelete = 2; // Change this to the ID of an existing product for testing

            $productToDelete = $webStoreModel->findProductById($productIdToDelete);

            if ($productToDelete) {
                $deleteResult = $webStoreModel->removeProduct($productToDelete);

                if ($deleteResult) {
                    $message = "Product deleted successfully.";
                } else {
                    $message = "Failed to delete the product.";
                }
            } else {
                $message = "Product not found.";
            }

            echo $message;







            ?>
        </section>

        <section class="scenario">
            <h2>Scenario 6: Update an existing product</h2>
            <?php
            // Scenario 6: Update an existing product
            $productIdToUpdate = 2; // Change this ID to an existing product in your database
            $productToUpdate = $webStoreModel->findProductById($productIdToUpdate);

            if ($productToUpdate) {
                // Modify the product details as needed
                $productToUpdate->setDescription('Updated Description');
                $productToUpdate->setPrice(15.99);
                $updateResult = $webStoreModel->modifyProduct($productToUpdate);

                $message = $updateResult ? "Product updated successfully." : "Failed to update the product.";
            } else {
                $message = "Product not found.";
            }

            echo $message;


            ?>
        </section>

        <section class="scenario">
            <h2>Scenario 7: Attempt to update a product with a code that already exists</h2>
            <?php
            // Scenario 7: Attempt to update a product with a code that already exists
            $productIdToUpdate = 3; // Change this to the ID of an existing product for testing
            $productToUpdate = $this->WebStoreModel->findProductById($productIdToUpdate);
            if ($productToUpdate) {
                // Modify the product code to match an existing product's code
                $productToUpdate->setCode('existingcode');
                $updateResult = $this->WebStoreModel->modifyProduct($productToUpdate);
                if ($updateResult) {
                    $message = "Product updated successfully (unexpected).";
                } else {
                    $message = "Product code must be unique (expected).";
                }
            } else {
                $message = "Product not found.";
            }
            $this->doProductManagement($message);
            ?>
        </section>
    </main>
    <footer>
        <p>&copy; <?php echo date('Y'); ?> Aymane</p>
    </footer>
</body>

</html>