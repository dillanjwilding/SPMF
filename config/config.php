<?php

    /** Configuration Variables **/
    
    // Development?
    define ('DEVELOPMENT_ENVIRONMENT', true);
    
    // Database definition
    define('DB_NAME', 'yourdatabasename');
    define('DB_USER', 'yourusername');
    define('DB_PASSWORD', 'yourpassword');
    define('DB_HOST', 'localhost');
    
    if(DEVELOPMENT_ENVIRONMENT)
    {
        // Helpful debugging tool
        // logThis();
        require_once(ROOT . DS . 'lib' . DS . 'log.php');
    }