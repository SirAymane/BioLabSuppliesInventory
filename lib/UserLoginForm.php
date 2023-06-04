<?php
require_once 'model/User.php';
// require_once 'model/persist/UserPersistFileDao.php';

/**
 * Description of UserFormValidation
 * Provides validation to get data from User form.
 * @author ProvenSoft
 */
class UserLoginForm {

    /**
     * validates and gets data from login form.
     * @return array with the user and password of the login form
     */
    public static function getFormCredentials() {

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