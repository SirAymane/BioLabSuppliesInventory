<?php
require_once 'lib/Renderer.php';
require_once 'model/Product.php';
use proven\store\model\Product;

echo "<p>Product detail page</p>";

$addDisable = "";
$editDisable = "disabled";

if ($params['mode']!='add') {
    $addDisable = "disabled";
    $editDisable = "";
}

$mode = "product/{$params['mode']}";
$message = $params['message'] ?? "";

printf("<p>%s</p>", $message);

if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}

$product = $params['product'] ?? new Product();
$product_id = $product->getId();

echo "<form method='post' action=\"index.php\">";

echo proven\lib\views\Renderer::renderProductFields($product);

echo "<input type='hidden' name='id' value='{$product_id}'>";
echo "<button type='submit' name='action' value='product/add' $addDisable>Add</button>";
echo "<button type='submit' name='action' value='product/modify' $editDisable>Modify</button>";
echo "<button type='submit' name='action' value='product/remove' $editDisable onclick='return confirm(\"Are you sure you want to delete this product?\");'>Remove</button>";
echo "</form>";
?>
