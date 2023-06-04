<?php
namespace proven\lib\views;
require_once 'model/User.php';
use proven\store\model\User;
use proven\store\model\Warehouse;
use proven\store\model\Product;
use proven\store\model\Category;





class Validator {

    public static function cleanAndValidate(int $method, string $variable, int $filter=\FILTER_SANITIZE_FULL_SPECIAL_CHARS) {
        $clean = null;
        if (\filter_has_var($method, $variable)) {
            $clean = \filter_input($method, $variable, $filter); 
        }
        return $clean;
    }

    public static function validateUser(int $method): ?User {
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $username = static::cleanAndValidate($method, 'username', FILTER_DEFAULT); 
        $password = static::cleanAndValidate($method, 'password', FILTER_DEFAULT); 
        $firstname = static::cleanAndValidate($method, 'firstname', FILTER_DEFAULT); 
        $lastname = static::cleanAndValidate($method, 'lastname', FILTER_DEFAULT); 
        $role = static::cleanAndValidate($method, 'role', FILTER_DEFAULT); 
    
        // Check if all data is valid
        $validData = ($id !== false) && ($username !== false) 
            && ($password !== false) && ($firstname !== false) 
            && ($lastname !== false) && ($role !== false);
    
        if ($validData) {
            $obj = new User($id, $username, $password, $firstname, $lastname, $role);
        } else {
            $obj = null;
        }
    
        return $obj;
    }
    
    
    public static function validateWarehouse(int $method): ?Warehouse {
        $id   = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $code = static::cleanAndValidate($method, 'code', FILTER_DEFAULT); 
        $address = static::cleanAndValidate($method, 'address', FILTER_DEFAULT); 
    
        // Ensuring $id is not false
        $id = $id ? $id : 0;

        $validData = ($id !== false) && ($code !== false) && ($address !== false);

        if ($validData) {
            $warehouse = new Warehouse($id, $code, $address);
        } else {
            $warehouse = null;
        }
    
        return $warehouse;        
    }
    

    public static function validateProduct(int $method): ?Product {
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT);
        $code = static::cleanAndValidate($method, 'code', FILTER_DEFAULT);
        $description = static::cleanAndValidate($method, 'description', FILTER_DEFAULT);
        $price = static::cleanAndValidate($method, 'price', FILTER_VALIDATE_FLOAT);
        $categoryId = static::cleanAndValidate($method, 'category_id', FILTER_VALIDATE_INT);
    
        // Check if all data is valid
        $validData = ($id !== false) && ($code !== false) && ($description !== false) 
            && ($price !== false) && ($categoryId !== false);
    
        if ($validData) {
            $obj = new Product($id, $code, $description, $price, $categoryId);
        } else {
            $obj = null;
        }
        
        return $obj;
    }
    

    public static function validateCategory(int $method): ?Category {
        $id   = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT); 
        $code = static::cleanAndValidate($method, 'code', FILTER_DEFAULT); 
        $description = static::cleanAndValidate($method, 'description', FILTER_DEFAULT); 

        $validData = ($id !== false) && ($code !== false) && ($description !== false);

        if ($validData) {
            $obj = new Category($id, $code, $description);
        } else {
            $obj = null;
        }
        
        return $obj;        
    }
    
    

}