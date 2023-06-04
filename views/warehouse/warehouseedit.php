<?php
require_once 'lib/Renderer.php';
require_once 'model/Warehouse.php';
use proven\store\model\Warehouse;

echo "<p>Warehouse detail page</p>";

$message = $params['message'] ?? "";
printf("<p>%s</p>", $message);

$warehouse = $params['warehouse'] ?? new Warehouse();
$mode = $params['mode'] ?? 'edit';


if ($mode === 'add') {
    echo "<form method='post' action='index.php'>";
    echo proven\lib\views\Renderer::renderWarehouseFields($warehouse);
    echo "<button type='submit' name='action' value='warehouse/add'>Add</button>";
} else {
    echo "<form method='post' action='index.php'>";
    echo proven\lib\views\Renderer::renderWarehouseFields($warehouse);
    echo "<button type='submit' name='action' value='warehouse/modify'>Modify</button>";
    
    // Add the 'Manage Stocks' button
    if ($mode === 'edit') {
        echo "<button type='button' onclick=\"location.href='index.php?action=warehouse/stocks&id={$warehouse->getId()}'\">Manage Stocks</button>";

        // Add a 'Remove' button
        echo "<button type='submit' name='action' value='warehouse/remove'>Remove</button>";
    }
}

echo "</form>";
