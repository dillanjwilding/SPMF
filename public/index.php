<?php 
    // Defines global variables
    // Loads current User
    // Analytics
    // require_once(ROOT . DS . 'lib' . DS . 'loader.php');

    // Define User 
    // For analytics?

    session_start();

    // If User doesn't exist then create User
    // If User does exist, then keep track of them and add their info to existing (directories) folders/files

    // Relatively single point of access: .htaccess file redirects to this page
    define('DS', DIRECTORY_SEPARATOR);
    define('ROOT', dirname(dirname(__FILE__))); // Base Directory

    $url = ltrim($_SERVER['REQUEST_URI'], "/"); // Not what is needed
    // what is needed is the requested url - base directory

    require_once(ROOT. DS . 'lib' . DS . 'bootstrap.php');
