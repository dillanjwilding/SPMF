<?php
namespace Lib;

class Controller
{
    protected $variables = [];
    protected $layout = 'default';
    protected $views;

    public function __construct()
    {
        $this->views = strtolower(str_replace('Controller', '', (new \ReflectionClass(get_called_class()))->getShortName()));
    }

    public function setViewVariable(string $name, $value)
    {
        $this->variables[$name] = $value;
    }

    public function render(string $view)
    {
        extract($this->variables);
        ob_start();
        try {
            if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->views . DS . $view . '.php')) {
                // readfile();
                require ROOT . DS . 'app' . DS . 'views' . DS . $this->views . DS . $view . '.php';
            } elseif (file_exists(ROOT . DS . 'lib' . DS . 'views' . DS . $this->views . DS . $view . '.php')) {
                // readfile();
                require ROOT . DS . 'lib' . DS . 'views' . DS . $this->views . DS . $view . '.php';
            } else {
                throw new \Exception('View not found');
            }
        } catch (\Exception $ex) {
            $this->error(500, $ex);
        }
        $content = ob_get_clean();
        if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php')) {
            // readfile();
            require ROOT . DS . 'app' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php';
        } elseif (file_exists(ROOT . DS . 'lib' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php')) {
            // readfile();
            require ROOT . DS . 'lib' . DS . 'views' . DS . 'layouts' . DS . $this->layout . '.php';
        } else {
            echo $content;
        }
        ob_end_flush();
    }

    public function redirect(string $url)
    {
        header("Location:$url");
    }

    public function error(int $status, \Exception $error=null)
    {
        $this->views = 'errors';
        $this->setViewVariable('title', $status . ' Error');
        if (!empty($error)) {
            $this->setViewVariable('error', $error);
        }
        if (file_exists(ROOT . DS . 'app' . DS . 'views' . DS . $this->views . DS . $status . '.php') ||
            file_exists(ROOT . DS . 'lib' . DS . 'views' . DS . $this->views . DS . $status . '.php')) {
                $this->render($status);
        } else {
            $this->render('default');
        }
    }
}
