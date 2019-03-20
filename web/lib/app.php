<?php
namespace Lib;

class App {
    private /*Config*/ $config;
    // private /*Logger*/ $logger;

    public function __construct() {
        session_start();
        $this->config = new Config();
    }

    public function run() : void {
        /* if ($this->config->ENV === 'development') {
            $this->logger = new Logger();
        } */
        $this->setReporting();
        (new Dispatcher())->dispatch();
    }

    private function setReporting() : void {
        // @todo: Fix this:
        if ($this->config->ENV === 'development') {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', 'Off');
            ini_set('log_errors', 'On');
            // @todo: Change error log location
            ini_set('error_log', ROOT . DS . 'temp' . DS . 'logs' . DS . 'error.log');
        }
    }
}
