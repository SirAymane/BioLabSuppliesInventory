<?php

namespace SirAymane\ecommerce\lib;

require_once 'model/User.php';
require_once 'model/Product.php';
require_once 'model/Order.php';

use SirAymane\ecommerce\model\User;
use SirAymane\ecommerce\model\Product;
use SirAymane\ecommerce\model\Order;

/**
 * Validator class for sanitizing and validating input data.
 */
class Validator
{

    /**
     * Sanitizes and validates a specific input variable.
     *
     * @param int $method The method of input, e.g., INPUT_POST.
     * @param string $variable The name of the variable to validate.
     * @param int $filter The filter to apply for sanitization.
     * @return mixed The sanitized data, or null if not set or invalid.
     */
    public static function cleanAndValidate(int $method, string $variable, int $filter = \FILTER_SANITIZE_FULL_SPECIAL_CHARS)
    {
        if (\filter_has_var($method, $variable)) {
            return \filter_input($method, $variable, $filter);
        }
        return null;
    }

    /**
     * Validates and creates a User object from input data.
     *
     * @param int $method The method of input, e.g., INPUT_POST.
     * @return User|null A User object if valid, otherwise null.
     */
    public static function validateUser(int $method): ?User
    {
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT);
        $username = static::cleanAndValidate($method, 'username', FILTER_SANITIZE_STRING);
        $password = static::cleanAndValidate($method, 'password', FILTER_DEFAULT); // Consider encrypting or hashing
        $role = static::cleanAndValidate($method, 'role', FILTER_SANITIZE_STRING);
        $email = static::cleanAndValidate($method, 'email', FILTER_SANITIZE_EMAIL);
        $dob = static::cleanAndValidate($method, 'dob', FILTER_SANITIZE_STRING);

        // Check if all data is valid
        $validData = ($id !== false) && ($username !== false) && ($password !== false)
            && ($role !== false) && ($email !== false) && ($dob !== false);

        if ($validData) {
            $dobDateTime = new \DateTime($dob);
            $obj = new User($id, $username, $password, $role, $email, $dobDateTime);
        } else {
            $obj = null;
        }

        return $obj;
    }


    /**
     * Validates and creates a Product object from input data.
     *
     * @param int $method The method of input, e.g., INPUT_POST.
     * @return Product|null A Product object if valid, otherwise null.
     */
    public static function validateProduct(int $method): ?Product
    {
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT);
        $code = static::cleanAndValidate($method, 'code', FILTER_SANITIZE_STRING);
        $description = static::cleanAndValidate($method, 'description', FILTER_SANITIZE_STRING);
        $price = static::cleanAndValidate($method, 'price', FILTER_VALIDATE_FLOAT);

        $validData = ($id !== false) && ($code !== false) && ($description !== false) && ($price !== false);

        if ($validData) {
            $product = new Product($id, $code, $description, $price);
        } else {
            $product = null;
        }

        return $product;
    }

    /**
     * Validates and creates an Order object from input data.
     *
     * @param int $method The method of input, e.g., INPUT_POST.
     * @return Order|null An Order object if valid, otherwise null.
     */
    public static function validateOrder(int $method): ?Order
    {
        $id = static::cleanAndValidate($method, 'id', FILTER_VALIDATE_INT);
        $creationDate = static::cleanAndValidate($method, 'creationDate', FILTER_SANITIZE_STRING);
        $totalAmount = static::cleanAndValidate($method, 'totalAmount', FILTER_VALIDATE_FLOAT);
        $deliveryMethod = static::cleanAndValidate($method, 'deliveryMethod', FILTER_SANITIZE_STRING);
        $userId = static::cleanAndValidate($method, 'userId', FILTER_VALIDATE_INT);

        $validData = ($id !== false) && ($creationDate !== false) && ($totalAmount !== false)
            && ($deliveryMethod !== false) && ($userId !== false);

        if ($validData) {
            $creationDateTime = new \DateTime($creationDate);
            $order = new Order($id, $creationDateTime, $totalAmount, $deliveryMethod, $userId);
        } else {
            $order = null;
        }

        return $order;
    }


    /**
     * Validates and gets data from login form.
     * @return array with the user and password of the login form
     */
    public static function getFormCredentials()
    {

        $username = "";
        //retrieve id sent by client.
        if (filter_has_var(INPUT_POST, 'username')) {
            $username = filter_input(INPUT_POST, 'username');
        }
        $password = "";
        //retrieve User sent by client.
        if (filter_has_var(INPUT_POST, 'password')) {
            $password = filter_input(INPUT_POST, 'password');
        }
        $CredentialsArray = [$username, $password];
        //}
        return $CredentialsArray;
    }
}
