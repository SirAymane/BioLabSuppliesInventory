<?php  
namespace SirAymane\ecommerce\lib;

/**
 * Template view loader.
 */
class ViewLoader {
    
    public function __construct() {
        // Constructor logic if needed
    }

    /**
     * Shows the template view with the provided information.
     * @param string $template Template for the view.
     * @param array $params Associative array of parameters to be passed to the template.
     * @return bool Returns true if the template is successfully included, otherwise false.
     */
    public function show($template, $params = array()) {
        // Build the full path for the template
        $path = __DIR__ . '/../views/' . $template;

        // Check if the file exists
        if (!file_exists($path)) {
            // Handle the error, e.g., log it or display a user-friendly error
            trigger_error('Template `' . $path . '` does not exist.', E_USER_NOTICE);
            return false;
        }

        // Extract the parameters to make them available in the template
        extract($params);

        // Include the template file
        include($path);

        return true;
    }
}
