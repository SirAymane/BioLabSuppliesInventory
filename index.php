<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>eCommerce Application</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="./css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


    // Start the session if not already started
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Include the navigation bar loader
    require_once 'lib/loadnavbar.php';

    // Include and use MainController to handle requests
    require_once 'controllers/MainController.php';
    use SirAymane\ecommerce\controller\MainController;
    (new MainController())->processRequest();
    ?>
</body>
</html>
