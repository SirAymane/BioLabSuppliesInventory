<?php

namespace proven\store\controller;

require_once 'lib/ViewLoader.php';
require_once 'lib/Validator.php';
require_once 'lib/UserLoginForm.php';
require_once 'model/StoreModel.php';
require_once 'model/User.php';
require_once 'auth.php';
require_once 'model/Category.php';


use proven\store\model\User as User;
use proven\store\model\StoreModel as Model;
use proven\store\model\ProductDao;
use proven\store\model\Category;
use proven\store\model\Warehouse;
use proven\store\model\Product;





use proven\lib\ViewLoader as View;
use proven\lib\views\Validator as Validator;
use UserLoginForm;

/**
 * Main controller
 * @author ProvenSoft
 */
class MainController
{
    /**
     * @var ViewLoader
     */
    private $view;
    /**
     * @var Model 
     */
    private $model;
    /**
     * @var string  
     */
    private $action;
    /**
     * @var string  
     */
    private $requestMethod;

    public function __construct()
    {
        // create new model
        $this->view = new View();
        //instantiate the model.
        $this->model = new Model();
    }

    // START HTTP REQUEST FUNCTIONS

    // Receives the request from the client and provides a method
    public function processRequest()
    {
        $this->action = ""; //Initializing action
        if (\filter_has_var(\INPUT_POST, 'action')) {
            $this->action = \filter_input(\INPUT_POST, 'action');
            // If we have an input, replace the action value
        } else {
            if (\filter_has_var(\INPUT_GET, 'action')) {
                $this->action = \filter_input(\INPUT_GET, 'action');
            } else {
                $this->action = "home";
            }
        }
        if (\filter_has_var(\INPUT_SERVER, 'REQUEST_METHOD')) {
            $this->requestMethod = \strtolower(\filter_input(\INPUT_SERVER, 'REQUEST_METHOD'));
        }
        //process action according to request method.
        switch ($this->requestMethod) {
            case 'get':
                $this->doGet();
                break;
            case 'post':
                $this->doPost();
                break;
            default:
                $this->handleError();
                break;
        }
    }

    private function doGet() // GET requests handling
    {
        //process action.
        switch ($this->action) {
            case 'home':
                $this->doHomePage();
                break;
            case 'user':
                $this->doUserMng();
                break;
            case 'user/edit':
                $this->doUserEditForm("edit");
                break;
            case 'category':
                $this->doCategoryMng();
                break;
            case 'category/edit':
                $this->doCategoryEditForm("edit");
                break;
            case 'category/delete':
                $this->doConfirmCatRemove();
                break;
            case 'product':
                $this->doProductMng();
                break;
            case 'product/edit':
                $this->doProductEditForm("edit");
                break;
            case 'product/formremove':
                $this->doProductRemoveForm("remove");
                break;
            case 'product/formadd':
                $this->doProductAddForm();
                break;
            case 'product/delete':
                $this->doConfirmProdRemove();
                break;
            case 'product/showStocks':
                $this->doShowStocks('id');
                break;
            case 'category/formremove':
                $this->doCategoryRemoveForm("remove");
                break;
            case 'warehouse':
                $this->doWareHouseMng();
                break;
            case 'warehouse/add':
                $this->doWarehouseEditForm("add");
                break;
            case 'warehouse/edit':
                $this->doWarehouseEditForm("edit");
                break;
            case 'warehouse/delete':
                $this->doWarehouseEditForm("delete");
                break;
            case 'warehouse/stocks':
                $this->doShowStocks('wid');
                break;
            case 'loginform':
                $this->doLoginForm();
                break;
            case 'stocks/warehouse':
                $this->doShowStocks('wid');
                break;
            case 'stocks/product':
                $this->doShowStocks('id');
                break;
            case 'logout':
                $this->logout();
                break;
            default:  // If no action is defined, handle error
                $this->handleError();
                break;
        }
    }

    /**
     * processes post requests from client.
     */
    private function doPost()
    {
        
        //process action.
        switch ($this->action) {
            case 'user/role':
                $this->doListUsersByRole();
                break;
            case 'user/form':
                $this->doUserEditForm("add");
                break;
            case 'user/add':
                $this->doUserAdd();
                break;
            case 'user/modify':
                $this->doUserModify();
                break;
            case 'product/add':
                $this->doProductAdd();
                break;
            case 'product/form':
                $this->doProductEditForm("add");
                break;
            case 'product/edit':
                $this->doProductEditForm("edit");
                break;
                case 'product/search':
                    $this->doListProductsByCategory();
                    break;
                case 'product/modify':
                    $this->doProductModify();
                    break;
            case 'category/modify':
                $this->doCategoryModify();
                break;
            case 'warehouse/add':
                $this->doWarehouseAdd();
                break;
            case 'warehouse/modify':
                $this->doWarehouseModify();
                break;
            case 'warehouse/remove':
                $this->doWarehouseRemove();
                break;
            case 'warehouse/stocks':
                $this->doShowStocks('wid');
                break;
            case 'user/remove':
                $this->doUserRemove();
                break;
            case 'product/remove':
                $this->doProductRemove();
                break;
            case 'category/remove':
                $this->doCategoryRemove();
                break;
            case 'login/submit':
                $this->doLoginUser();
                break;
            case 'product/stock':
                $this->doProductStock();
                break;

            default:  //processing default action.
                $this->doHomePage();
                break;
        }
    }

    // END HTTP REQUEST FUNCTIONS

    // START NAVIGATION CONTROL METHODS 

    /**
     * handles errors.
     */
    public function handleError()
    {
        $this->view->show("message.php", ['message' => 'Something went wrong!']);
    }

    /**
     * displays home page content.
     */
    public function doHomePage()
    {
        $this->view->show("home.php", []);
    }

    // END NAVIGATION CONTROL METHODS 

    // START SESSION CONTROL METHODS 

    /**
     * Checks either the user is logged in or not. 
     * Set as protected for security
     * This function can be used for any tab log required.
     * @return void
     */
    protected function checkLoggedIn()
    {
        if (!isset($_SESSION['username'])) {
            // User is not logged in, so redirect to login page
            header("Location: index.php?action=loginform");
            exit;
        }
    }

    /**
     * Checks either the user logged is admin or not
     * This function is called only on the admin required tabs.
     * @return void
     */
    public function checkAdminRole()
    {
        if (!isset($_SESSION['userrole']) || $_SESSION['userrole'] !== 'admin') {
            $this->handleError();
            exit();
        }
    }





    /**
     * displays login form page.
     */
    public function doLoginForm()
    {
        $this->view->show("login/loginform.php", []);  //initial prototype version;
    }

    /**
     * Do the login service - To be implemented 
     * @return void
     */
    public function doLoginUser()
    {
        $result = "";
        $userCredentials = UserLoginForm::getFormCredentials();
        list($user, $pass) = $userCredentials;
        $userFound = $this->model->findUserByUsernameAndPassword($user, $pass);
        if (!is_null($userFound)) {
            $role = $this->model->findUserByUsernameAndPassword($user, $pass);

            $_SESSION['userrole'] = $userFound->getRole();
            $_SESSION['username'] = $userFound->getUsername();

            // Assuming your User model has `getFirstName()` and `getLastName()` methods
            $_SESSION['fullname'] = $userFound->getFirstName() . ' ' . $userFound->getLastName();

            $result = 'Logged succesfuly!!';
            header("Location: index.php");
        } elseif ($userFound == -1) {
            $result = 'User not found';
        } elseif ($userFound == 0) {
            $result = 'Wrong Password';
        }
        $data['message'] = $result;
        $this->view->show("loginform.php", $data);
    }


    // Redirect the user when logged out
    private function logout()
    {
        // Start the session
        session_start();

        // Unset the session variables
        unset($_SESSION['userrole']);
        unset($_SESSION['username']);

        // Destroy the session
        session_destroy();
        // TODO delete cookie

        // Redirect to the login page
        header("Location: index.php?action=loginform");
        exit;
    }



    // END SESSION CONTROL METHODS 


    // START USER MANAGEMENT CONTROL METHODS

    /**
     * Display the user management page
     *
     * @return void
     */
    public function doUserMng()

    {
        $this->checkLoggedIn();
        $this->checkAdminRole();
        $result = $this->model->findAllUsers();
        $this->view->show("user/usermanage.php", ['list' => $result]);
    }

    public function doListUsersByRole()
    {
        $this->checkLoggedIn();
        $this->checkAdminRole();
        $roletoSearch = \filter_input(INPUT_POST, "search");
        if ($roletoSearch !== false) {
            $result = $this->model->findUsersByRole($roletoSearch);
            $this->view->show("user/usermanage.php", ['list' => $result]);
        } else {
            $this->view->show("user/usermanage.php", ['message' => "No data found"]);
        }
    }


    public function doUserEditForm(string $mode)
    {
        $this->checkLoggedIn();
        $this->checkAdminRole();
        $data = array();
        if ($mode != 'user/add') {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (($id !== false) && (!is_null($id))) {
                $user = $this->model->findUserById($id);
                if (!is_null($user)) {
                    $data['user'] = $user;
                }
            }
            $data['mode'] = $mode;
        }
        $this->view->show("user/userdetail.php", $data);  //initial prototype version.
    }

    public function doUserAdd()
    {
        $this->checkLoggedIn();
        $this->checkAdminRole();
        $user = Validator::validateUser(INPUT_POST);
        if (!is_null($user)) {
            $result = $this->model->addUser($user);
            $message = ($result > 0) ? "Successfully added" : "Error adding";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }

    public function doUserModify()
    {
        $this->checkLoggedIn();
        $this->checkAdminRole();
        $user = Validator::validateUser(INPUT_POST);
        if (!is_null($user)) {
            $result = $this->model->modifyUser($user);
            $message = ($result > 0) ? "Successfully modified" : "Error modifying";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }

    public function doUserRemove()
    {
        $this->checkLoggedIn();
        $this->checkAdminRole();
        $user = Validator::validateUser(INPUT_POST);
        if (!is_null($user)) {
            $result = $this->model->removeUser($user);
            $message = ($result > 0) ? "Successfully removed" : "Error removing";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("user/userdetail.php", ['mode' => 'add', 'message' => $message]);
        }
    }

    // END USER MANAGEMENT CONTROL METHODS

    // START CATEGORY MANAGEMENT CONTROL METHODS 

    public function doCategoryMng()
    {
        $result = $this->model->findAllCategories();
        $this->view->show("category/categorymanage.php", ['list' => $result]);
    }



    public function doCategoryEditForm(string $mode)
    {
        $data = array();
        if ($mode != 'category/add') {
            //fetch data for selected category
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (($id !== false) && (!is_null($id))) {
                $category = $this->model->findCategoryById($id);
                if (!is_null($category)) {
                    $data['category'] = $category;
                }
            }
            $data['mode'] = $mode;
        }
        $this->view->show("category/categorydetail.php", $data);
    }

    public function doCategoryModify()
    {
        //find the category to be modified and make the changes
        $category = Validator::validateCategory(INPUT_POST);
        if (!is_null($category)) { // Validate the category
            $result = $this->model->modifyCategory($category);
            $message = ($result > 0) ? "Successfully modified" : "Error modifying";
            $this->view->show("category/categorydetail.php", ['mode' => 'modify', 'message' => $message, 'category' => $category]);
        } else {
            $message = "Invalid data";
            $this->view->show("category/categorydetail.php", ['mode' => 'modify', 'message' => $message]);
        }
    }


    public function doCategoryRemove()
    {
        $category = Validator::validateCategory(INPUT_POST);
        if (!is_null($category)) {
            $result = $this->model->removeCategory($category);
            $message = ($result > 0) ? "Successfully removed" : "Error removing";
            $this->view->show("category/categoryremoveform.php", ['mode' => 'add', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("category/categoryremoveform.php", ['mode' => 'add', 'message' => $message]);
        }
    }

    public function doProductRemove()
    {
        //get all the product data from form and validate it
        $product = Validator::validateProduct(INPUT_POST);
        if (!is_null($product)) {  //add product to database after it's validated
            $result = $this->model->removeProduct($product);
            $result += $this->model->removeStockByProduct($product);
            $message = ($result > 0) ? "Successfully removed" : "Error removing";
            $prodList = $this->model->findAllProducts();
            $this->view->show("product/productmanage.php", ['message' => $message, 'list' => $prodList]);
        } else {
            $message = "Invalid data";
            $prodList = $this->model->findAllProducts();
            $this->view->show("product/productmanage.php", ['message' => $message, 'list' => $prodList]);
        }
    }

    public function doConfirmCatRemove()
    {
        $data = array(); // We iterate the users data array
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (($id !== false) && (!is_null($id))) {
            $category = $this->model->findCategoryById($id);
            if (!is_null($category)) {
                $data['category'] = $category;
            }
        }
        $this->view->show("category/catdelconfirm.php", $data);
    }

    public function doCategoryRemoveForm(string $mode)
    {
        $data = array();
        if ($mode != 'category/add') {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (($id !== false) && (!is_null($id))) {
                $category = $this->model->findCategoryById($id);
                if (!is_null($category)) {
                    $data['category'] = $category;
                }
            }
            $data['mode'] = $mode;
        }
        $this->view->show("category/categoryremoveform.php", $data);  //initial prototype version.
    }

    // END CATEGORY MANAGEMENT CONTROL METHODS 

    // START PRODUCT MANAGEMENT CONTROL METHODS


    public function doProductMng()
    {
        $result = $this->model->findAllProducts();
        //show list of products
        $this->view->show("product/productmanage.php", ['list' => $result]);
    }

    public function doListProductsByCategory()
    {
        $categoryId = \filter_input(INPUT_POST, "category");
        if (!empty($categoryId)) {
            $category = new Category((int) $categoryId);
            $result = $this->model->findProductsByCategory($category);
            $this->view->show("product/productmanage.php", ['list' => $result]);
        } else {
            $this->view->show("product/productmanage.php", ['message' => "No data found"]);
        }
    }


    public function doProductAddForm()
    {
        // Display the product add form
        $this->view->show("product/productaddform.php", []);
    }


    public function doProductAdd()
{
    // Check if the form was submitted with data
    if (
        isset($_POST['code']) && !empty($_POST['code']) &&
        isset($_POST['description']) && !empty($_POST['description'])
        // Add other field checks if necessary
    ) {
        $product = Validator::validateProduct(INPUT_POST);

        // If validation is successful
        if (!is_null($product)) {
            // Check if category exists
            $categoryId = $product->getCategoryId();
            if (!$this->model->categoryExists($categoryId)) {
                $message = "Invalid category id";
                $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message, 'product' => $product]);
                return;
            }

            // Add the product if it does not exist yet
            $result = $this->model->addProduct($product);
            $message = ($result > 0) ? "Successfully added" : "Product with this code already exists";
        } else {
            // In case of validation failure
            $message = "Invalid data";
        }
        
        $this->view->show("product/productdetail.php", ['product' => $product, 'mode' => 'add', 'message' => $message]);
    } else {
        // If the form was accessed without input data, just show the empty form without any message
        $this->view->show("product/productdetail.php", ['product' => new Product(), 'mode' => 'add']);
    }
}





public function doProductEditForm($action, $params = []) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if(is_null($id) || $id === false) {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    }
    $params['message'] = $_GET['message'] ?? '';

    if (!$this->validateCategoryId($_POST['category_id'] ?? null, $params)) {
        return;
    }
    
    if($action === 'add') {
        $params['mode'] = 'add';
        $params['product'] = new Product();
    } elseif($action === 'edit' && !empty($id)) {
        $product = $this->model->findProductById($id);
        if($product) {
            $params['mode'] = 'edit';
            $params['product'] = $product;
        } else {
            $this->handleError();
            return;
        }
    } else {
        $this->handleError();
        return;
    }
    $this->view->show('product/productdetail.php', $params);
}

private function validateCategoryId($categoryId, &$params) {
    if ($categoryId !== null && !$this->model->categoryExists($categoryId)) {
        $params['message'] = "Invalid category id";
        $this->view->show('product/productdetail.php', $params);
        return false;
    }
    return true;
}


    


    






    public function doProductRemoveForm(string $mode)
    {
        $data = array();
        if ($mode != 'product/add') {
            $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
            if (($id !== false) && (!is_null($id))) {
                $product = $this->model->findProductById($id);
                if (!is_null($product)) {
                    $data['product'] = $product;
                }
            }
            $data['mode'] = $mode;
        }
        $this->view->show("product/productremoveform.php", $data);
    }

    /*  Note that a confirmation is needed, since it has M:N relationship. 

    On the sql query, we have an “ON UPDATE” clause, which requires confirmation.
    Model → DAO warehouse products → Delete DAO products */


    public function doConfirmProdRemove()
    {
        $data = array();
        //fetch data for selected user
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (($id !== false) && (!is_null($id))) {
            $product = $this->model->findProductById($id);
            if (!is_null($product)) {
                $data['product'] = $product;
            }
        }
        $this->view->show("product/proddelconfirm.php", $data);
    }

    public function doProductModify()
{
    // Get product data from form and validate it
    $product = Validator::validateProduct(INPUT_POST);

    // Modify product in the database if valid
    if (!is_null($product)) {
        // Fetch the existing product from the database
        $existingProduct = $this->model->findProductById($product->getId());

        if (!is_null($existingProduct)) {
            // Check if category exists
            $categoryId = $product->getCategoryId();
            if (!$this->model->categoryExists($categoryId)) {
                $message = "Invalid category id";
                $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message, 'product' => $existingProduct]);
                return;
            }

            // Update the product details
            // $existingProduct->setCode($product->getCode());
            // $existingProduct->setDescription($product->getDescription());
            // $existingProduct->setPrice($product->getPrice());

            $result = $this->model->modifyProduct($product);
            $message = ($result > 0) ? "Successfully modified" : "Error modifying";

            if ($result > 0) {
                // If modification is successful, fetch the modified product again from the database
                $modifiedProduct = $this->model->findProductById($product->getId());
                if ($modifiedProduct) {
                    $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message, 'product' => $modifiedProduct]);
                } else {
                    $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => "Failed to retrieve modified product"]);
                }
            } else {
                $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message, 'product' => $existingProduct]);
            }
        } else {
            $message = "Product not found";
            $this->view->show("product/productdetail.php", ['mode' => 'edit', 'message' => $message]);
        }
    } else {
        $message = "Invalid data";
        $this->view->show("product/productdetail.php", ['mode' => 'add', 'message' => $message]);
    }
}







    // END PRODUCT MANAGEMENT CONTROL METHODS

    // START WAREHOUSE MANAGEMENT CONTROL METHODS
    public function showWarehouseAddForm()
    {
        // Display the form without any message
        $this->view->show("warehouse/warehouseedit.php", ['mode' => 'add']);
    }



    public function doWarehouseAdd()
    {
        // Check if specific fields are set and not empty
        if (
            isset($_POST['code']) && !empty($_POST['code']) &&
            isset($_POST['address']) && !empty($_POST['address'])
        ) {

            $whs = Validator::validateWarehouse(INPUT_POST);
            $message = "";

            // Only try to add if the $whs is not null (validation successful)
            if ($whs) {
                $result = $this->model->addWarehouse($whs);
                $message = ($result > 0) ? "Successfully added" : "Error adding";
            } else {
                $message = "Invalid data"; // In case of validation failure
            }

            // After trying to add, redirect to the edit form, so the user can correct errors if there were any
            $this->view->show("warehouse/warehouseedit.php", ['mode' => 'add', 'message' => $message]);
        } else {
            // In case the form was accessed without input data, just show the empty form without any message
            $this->view->show("warehouse/warehouseedit.php", ['mode' => 'add']);
        }
    }












    public function doWarehouseRemove()
    {
        $warehouse = Validator::validateWarehouse(INPUT_POST);
        if ($warehouse) {
            $result = $this->model->removeWarehouse($warehouse);
            $message = ($result > 0) ? "Successfully removed" : "Error removing";
            $this->view->show("warehouse/warehousedetail.php", ['mode' => 'remove', 'message' => $message]);
        } else {
            $message = "Invalid data";
            $this->view->show("warehouse/warehousedetail.php", ['mode' => 'remove', 'message' => $message]);
        }
    }



    public function doWarehouseMng() //Find product and show it.
    {
        $result = $this->model->findAllWarehouses();
        $this->view->show("warehouse/warehousemanage.php", ['list' => $result]);
    }

    public function doWarehouseEditForm($action, $params = [])
    {
        $id = $_GET['id'] ?? '';
        $params['message'] = $_GET['message'] ?? '';

        if ($action === 'add') {
            $params['mode'] = 'add';
        } elseif ($action === 'edit' && !empty($id)) {
            $warehouse = $this->model->findWarehouseById($id);
            if ($warehouse) {
                $params['mode'] = 'edit';
                $params['warehouse'] = $warehouse;
            } else {
                $this->handleError();
                return;
            }
        } else {
            $this->handleError();
            return;
        }

        $this->view->show('warehouse/warehouseedit.php', $params);
    }







    public function doWarehouseModify()
    {
        $id = $_POST['id'] ?? '';
        $code = $_POST['code'] ?? '';
        $address = $_POST['address'] ?? '';

        // Create a Warehouse object
        $warehouse = new \proven\store\model\Warehouse();
        $warehouse->setId($id);
        $warehouse->setCode($code);
        $warehouse->setAddress($address);

        $result = $this->model->modifyWarehouse($warehouse);
        if ($result) {
            $params['message'] = "Warehouse $code modified successfully.";
        } else {
            $params['message'] = "Failed to modify Warehouse $code.";
        }

        // Render the warehouse edit form page again
        header("Location: index.php?action=warehouse/edit&id=$id&message=" . urlencode($params['message']));
        exit;
    }


    // END WAREHOUSE MANAGEMENT CONTROL METHODS


    // START STOCK CONTROL METHODS


    private function doProductStock()
    {
        //get the id of the product from the POST data
        $id = filter_input(INPUT_POST, 'id');

        //perform stock operation on the product
        //you need to implement the stock operation in your model

        //reload the product management page
        $this->doProductMng();
    }



    public function doShowStocks(string $var2fetch = "id")
    {
        // Adding var to fetch as param
        $id = filter_input(INPUT_GET, $var2fetch, FILTER_VALIDATE_INT);
        switch ($var2fetch) {
            case 'id':
                $product = $this->model->findProductById($id);
                if (!is_null($product)) {
                    $data['stocks'] = $this->model->findStocksByProduct($product);
                    $data['mode'] = 'product';
                    $data['product'] = $product;
                } else {
                    $data['message'] = 'Product not found';
                }
                break;
            case 'wid':
                $warehouse = $this->model->findWarehouseById($id);
                if (!is_null($warehouse)) {
                    $data['stocks'] = $this->model->findStocksByWarehouse($warehouse);
                    $data['mode'] = 'warehouse';
                    $data['warehouse'] = $warehouse;
                } else {
                    $data['message'] = 'Warehouse not found';
                }
                break;
            default:
                $data['message'] = 'Something went wrong!';
                break;
        }
        $this->view->show('stocks/stocksmgr.php', $data);
    }
}
    // END STOCK AND WAREHOUSE METHODS