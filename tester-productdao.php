<?php
require_once "lib/Debug.php";
use proven\lib\debug;
use proven\store\model\Product;
debug\Debug::iniset();

require_once "model/persist/ProductDao.php";
require_once "model/Product.php";

use proven\store\model\persist\ProductDao;
//use proven\store\model\Product;

$dao = new ProductDao();
debug\Debug::display($dao->selectAll());
debug\Debug::display($dao->selectWhere("code", "prodcode03"));
echo($dao->insert(new Product(123, 1,'descripion', 1, 1)));
// debug\Debug::display($dao->insert("code", "prodcode03"));
// debug\Debug::display($dao->delete(new Product(3)));
// debug\Debug::display($dao->update());



        
        // // Debugging code
        // // echo "SQL Query: " . $this->queries['SELECT_WHERE_WID'] . "<br/>";
        // // echo "Warehouse ID: " . $warehouse->getId() . "<br/>";
        // // echo "Rows returned: " . $stmt->rowCount() . "<br/>";
        // var_dump($data);
