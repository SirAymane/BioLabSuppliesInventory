<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Store manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="./styles.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">  <body>
    <?php
      include "loadnavbar.php";  //navigation bar loaded depending on the role
    ?>
    <?php
      //dynamic html content generated here by controller.
      require_once 'controllers/MainController.php';
      use proven\store\controller\MainController;
      (new MainController())->processRequest(); // Executes the query result, for the navigation bar.
    ?>
  </body>
</html>
