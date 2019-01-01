<?php 
/**
 * Declare functions
 */

// Display errors only when development environment else write errors to file
function setReporting()
{
    if (DEVELOPMENT_ENVIRONMENT) {
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
    } else {
        error_reporting(E_ALL);
        ini_set('display_errors', 'Off');
        ini_set('log_errors', 'On');
        ini_set('error_log', ROOT . DS . 'temp' . DS . 'logs' . DS . 'error.log');
    }
}

function stripSlashesDeep($value)
{
    return is_array($value) ? array_map('stripSlashesDeep', $value) : stripSlashes($value);
}

function removeMagicQuotes()
{
    if (get_magic_quotes_gpc()) {
        $_GET    = stripSlashesDeep($_GET);
        $_POST   = stripSlashesDeep($_POST);
        $_COOKIE = stripSlashesDeep($_COOKIE);
    }
}

function unregisterGlobals()
{
    if (ini_get('register_globals')) {
        $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

// ROUTER
// I want the router to be it's own file
// prepares the url and passes it on to where it needs to go to be functional

// Main processing
function callHook()
{
    global $url;
    // website/model controller pair/action/query
    $urlArray = explode("/", $url);
    
    // Find base to compare
    $baseArray = explode(DS, ROOT);
    $base = $baseArray[count($baseArray) - 1];

    // Shift to base
    while ($urlArray[0] != $base) {
        array_shift($urlArray);
    }
    array_shift($urlArray);

    // Get the name of model/controller pair and action to perform
    if (array_key_exists(0, $urlArray)) {
        $controller = $urlArray[0];
        array_shift($urlArray);
    }
    if (array_key_exists(0, $urlArray)) {
        $action = $urlArray[0];
        array_shift($urlArray);
    }
    if (isset($urlArray)) {
        $queryString = $urlArray;
    }
    $controllerName = $controller;
    $controller = ucwords($controller);
    $model = rtrim($controller);
    $controller .= 'Controller';

    // If the controller (file) exists create/instantiate it
    if (file_exists(ROOT. DS . 'app' . DS . 'controllers' . DS . $controller . '.php')) {
        $dispatch = new $controller($model, $controllerName, $action);
        //$dispatch = new $controller($model, $controller, $action);
        if (method_exists($controller, $action)) {
            call_user_func_array(array($dispatch, $action), $queryString);
        } else {
            /* Error Generation Code Here */
        }
    } else {
        header('Location: ' . $_SERVER['REQUEST_URI'] . 'index/view/');
        // Create/Instantiate default controller
        /*$dispatch = new IndexController('Index', 'index', 'view');
        if((int)method_exists('IndexController', 'view'))
        {
            call_user_func_array(array($dispatch, 'view'), $queryString);
        }
        else
        {
            /* Error Generation Code Here */
        //}
    }
}

// Load needed classes if and only if they exist
function __autoload($className)
{
    if (file_exists(ROOT . DS . 'lib' . DS . 'classes' . DS . strtolower($className) . '.php')) {
        include_once ROOT . DS . 'lib' . DS . 'classes' . DS . strtolower($className) . '.php';
    } elseif (file_exists(ROOT . DS . 'lib' . DS . 'classes' . DS . 'abstract' . DS . strtolower($className) . '.php')) {
        include_once ROOT . DS . 'lib' . DS . 'classes' . DS . 'abstract' . DS . strtolower($className) . '.php';
    } elseif (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . strtolower($className) . '.php')) {
        include_once ROOT . DS . 'app' . DS . 'controllers' . DS . strtolower($className) . '.php';
    } elseif (file_exists(ROOT . DS . 'app' . DS . 'models' . DS . strtolower($className) . '.php')) {
        include_once ROOT . DS . 'app' . DS . 'models' . DS . strtolower($className) . '.php';
    } else {
        /* Error Generation Code Here */
    }
}

// Execute functions
setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook();
