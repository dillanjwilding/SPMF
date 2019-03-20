<?php
namespace Lib;

class Loader {
    public function __construct() {
        // Load library classes
        spl_autoload_register(function ($class) {
            $file = ROOT . DS . str_replace('\\', DS, strtolower($class)) . '.php';
            if (!class_exists($class, false) && file_exists($file)) {
                require_once $file;
            }
        });

        // Load Controllers and Models
        spl_autoload_register(function ($class) {
            // There has to be a better way to lowercase everything but controller/model name
            $parts = explode('\\', $class);
            $temp = array_values(array_slice($parts, -1))[0];
            array_walk($parts, function (&$item, $key) {
                $item = strtolower($item);
            });
            $parts[count($parts) - 1] = $temp;
            $file = ROOT . DS . implode(DS, $parts) . '.php';
            if (!class_exists($class, false) && file_exists($file)) {
                require_once $file;
            }
        });
    }
}
