<?php
namespace App\Controllers;

class IndexController extends \Lib\Controller {
    public function index() : void {
        $this->setViewVariable('title', 'Home');
        $this->render('index');
    }

    public function error_500() {
        throw new \Exception('500 Internal Server Error');
    }
}
