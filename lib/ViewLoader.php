<?php  
namespace proven\lib;
/**
 * template view loader.
 * @author ProvenSoft
 */
class ViewLoader {
    
    function __construct() {

    }
 
    /**
     * shows the template view with the provided information.
     * @param type $template template for the view.
     * @param type $params associative array of parameters that have to be passed to the template.
     * @return boolean. In case of error, it returns false.
     */
    public function show($template, $params = array()) {
        //build template path.
        $path = 'views/' . $template;
        //if the file is not found, return a 404 error.
        if (!file_exists($path)) {
            trigger_error ('Template `' . $path . '` does not exist.', E_USER_NOTICE);
            return false;
        }         
        //include the template.
        include($path);
        return true;
    }
}

