<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__DIR__));

// require_once ROOT . DS . 'vendor' . DS . 'autoload.php'; // composer
require_once ROOT . DS . 'lib' . DS . 'loader.php';

new \Lib\Loader();
(new \Lib\App())->run(); // App::run();
