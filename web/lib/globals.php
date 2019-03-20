<?php
namespace Lib;

class Globals {
    private /*array*/ $globals = ['_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES'];

    public function __construct() {
        $this->sanitize();
        $this->unregister();
    }

    private function sanitize() : void {
        if (get_magic_quotes_gpc()) {
            $_GET    = $this->stripSlashes($_GET);
            $_POST   = $this->stripSlashes($_POST);
            $_COOKIE = $this->stripSlashes($_COOKIE);
        }
    }
    
    private function stripSlashes($value) {
        return is_array($value) ? array_map('stripSlashes', $value) : stripSlashes($value);
    }

    private function unregister() : void {
        if (ini_get('register_globals')) {
            foreach ($globals as $global) {
                foreach ($GLOBALS[$global] as $key => $value) {
                    if ($value === $GLOBALS[$key]) {
                        unset($GLOBALS[$key]);
                    }
                }
            }
        }
    }
}
