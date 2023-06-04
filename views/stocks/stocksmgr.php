<h1>Stock Page</h1>
<?php if (isset($params['message'])): ?>
<div class='alert alert-warning'>
<strong><?php echo $params['message']; ?></strong>
</div>
<?php endif ?>
<?php
require_once 'model/WarehouseProduct.php';
require_once 'model/Warehouse.php';
use \proven\store\model\WarehouseProduct;
use \proven\store\model\Warehouse;

$mode = $params['mode'];
$stocks = $params['stocks'] ?? array();

switch ($mode) {
	case 'warehouse':
		$warehouse = $params['warehouse'];
		echo <<<EOT
		<table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
		<caption>Stocks on Warehouse "{$warehouse->getCode()}"</caption>
		<thead class='table-dark'>
		<tr>
			<th>Product ID</th>
			<th>Stock</th>
		</tr>
		</thead>
		<tbody>
EOT;
		foreach ($stocks as $stock) {
			echo <<<EOT
				<tr>
					<td>{$stock->getProductId()}</td>
					<td>{$stock->getStock()}</td>
				</tr>               
EOT;
		}
		echo "</tbody>";
		echo "</table>";
		echo "<div class='alert alert-info' role='alert'>";
		echo count($stocks), " elements found.";
		echo "</div>";   
		break;
	
	case 'product':
		$product = $params['product'];
		echo <<<EOT
		<table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
		<caption>Product "{$product->getDescription()} stocks"</caption>
		<thead class='table-dark'>
		<tr>
			<th>Warehouse ID</th>
			<th>Stock</th>
		</tr>
		</thead>
		<tbody>
EOT;
		foreach ($stocks as $stock) {
			echo <<<EOT
				<tr>
					<td>{$stock->getWarehouseId()}</td>
					<td>{$stock->getStock()}</td>
				</tr>               
EOT;
		}
		echo "</tbody>";
		echo "</table>";
		echo "<div class='alert alert-info' role='alert'>";
		echo count($stocks), " elements found.";
		echo "</div>";   
		break;
	
	default:
		// todo
		break;
}
