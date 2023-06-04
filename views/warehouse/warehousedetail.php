<?php
require_once 'lib/Renderer.php';
require_once 'model/Warehouse.php';
use proven\store\model\Warehouse;
echo "<p>Warehouse detail page</p>";
$addDisable = "";
$editDisable = "disabled";
if ($params['mode']!='add') {
    $addDisable = "disabled";
    $editDisable = "";
}
$mode = "Warehouse/{$params['mode']}";
$message = $params['message'] ?? "";
printf("<p>%s</p>", $message);
if (isset($params['mode'])) {
    printf("<p>mode: %s</p>", $mode);
}
$warehouse = $params['warehouse'] ?? new Warehouse();

echo "<form method='post'>";
echo proven\lib\views\Renderer::renderWarehouseFields($warehouse);
echo "<button type='submit' formaction='index.php?action=Warehouse/add' $addDisable>Add</button>";
echo "<button type='submit' formaction='index.php?action=Warehouse/modify' $editDisable>Modify</button>";
echo "<button type='submit' formaction='index.php?action=Warehouse/remove' $editDisable>Remove</button>";

// Add the 'Manage Stocks' button
if ($params['mode']!='add') { // show this button only for 'edit' mode
    echo "<button type='button' onclick=\"location.href='index.php?action=Warehouse/stocks&id={$warehouse->getId()}'\">Manage Stocks</button>";
}

echo "</form>";
?>
