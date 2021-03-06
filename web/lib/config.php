<?php
namespace Lib;

class Config {
    private /*array*/ $values;

    public function __construct() {
        if (file_exists(ROOT . DS . 'config' . DS . '.env')) {
            $this->readFile(ROOT . DS . 'config' . DS . '.env');
        }
    }

    private function readFile(string $file) : void {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            $line = ltrim($line);
            if (substr($line, 0, 1) !== '#') {
                if (strpos($line, '=') !== false) {
                    $parts = explode('=', $line);
                    if (count($parts) === 2) {
                        $this->values[$parts[0]] = $parts[1];
                    }
                }
            }
        }
    }

    /* public function __set(string $key, $value) {
        $this->values[$key] = $value;
    } */

    public function __get(string $key) {
        if (isset($this->values[$key])) {
            return $this->values[$key];
        }
    }
    
    /* public function __isset(string $key) {
        return isset($this->values[$key]);
    } */
}
