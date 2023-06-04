<h2>Warehouse Management page</h2>

<?php if (isset($params['message'])): ?>
    <div class='alert alert-warning'>
        <strong><?php echo $params['message']; ?></strong>
    </div>
<?php endif ?>

<form method="post">
    <div class="row g-3 align-items-center">
        <span class="col-auto">
            <button class="btn btn-primary" type="submit" name="action" value="warehouse/add">Add</button>
        </span>
    </div>
</form>

<?php
$list = $params['list'] ?? null;

if (isset($list)) {
    echo <<<EOT
        <table class="table table-sm table-bordered table-striped table-hover caption-top table-responsive-sm">
        <caption>List of Warehouses</caption>
        <thead class='table-dark'>
        <tr>
            <th>Code</th>
            <th>Address</th>
            <th>Manage Stocks</th>
        </tr>
        </thead>
        <tbody>
EOT;
    foreach ($list as $elem) {
        $id = htmlspecialchars($elem->getId());
        $code = htmlspecialchars($elem->getCode());
        $address = htmlspecialchars($elem->getAddress());

        echo <<<EOT
            <tr>
                <td><a href="index.php?action=warehouse/edit&id={$id}">{$code}</a></td>
                <td>{$address}</td>
                <td><a href="index.php?action=stocks/warehouse&wid={$id}" class="btn btn-primary">Manage Stocks</a></td>
                </tr>               
EOT;
    }
    echo "</tbody>";
    echo "</table>";
    echo "<div class='alert alert-info' role='alert'>";
    echo count($list), " elements found.";
    echo "</div>";   
} else {
    echo "No data found";
}

$message = $_GET['message'] ?? '';
if (!empty($message)) {
    echo "<p>$message</p>";
}
?>
