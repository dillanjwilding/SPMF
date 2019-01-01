<?php
namespace Lib;

class Dispatcher
{
    private $controller = 'IndexController';
    private $action = 'index';

    public function __construct()
    {
        $url = (new Request())->url;
        if (strpos($url, '/') !== false) {
            $parts = explode('/', ltrim($url, '/'));
            $this->controller = isset($parts[0]) ? ucfirst($parts[0]) . 'Controller' : $this->controller;
            $this->action = str_replace('-', '_', isset($parts[1]) ? $parts[1] : $this->action);
        } elseif (!empty($url)) {
            if (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . 'IndexController.php')) {
                $action = str_replace('-', '_', $url);
                if (method_exists(new \App\Controllers\IndexController(), $action)) {
                    $this->action = $action;
                } else {
                    $this->controller = ucfirst($url) . 'Controller';
                }
            } else {
                $this->controller = ucfirst($url) . 'Controller';
            }
        }
    }

    public function dispatch()
    {
        try {
            if (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . $this->controller . '.php')) {
                $controller = '\\App\\Controllers\\' . $this->controller;
                (new $controller())->{$this->action}();
            } else {
                // 404
                (new \Lib\Controller())->error(404, new \Exception('404 Page Not Found'));
            }
        } catch (\Exception $ex) {
            // 500
            if (file_exists(ROOT . DS . 'app' . DS . 'controllers' . DS . 'ErrorController.php')) {
                (new \App\Controllers\ErrorController())->index();
            } else {
                (new \Lib\Controller())->error(500, $ex);
            }
        }
    }
}
