<?php
/*
    // make sure the page uses a secure connection
    if (!isset($_SERVER['HTTPS'])) {
        $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        header("Location: " . $url);
        exit();
    }
 */
?>
<?php
    // Load required base components that drive application 
    require_once (ROOT . DS . 'config' . DS . 'config.php');
    require_once (ROOT . DS . 'lib' . DS . 'shared.php');
