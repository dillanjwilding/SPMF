<?php
namespace Lib;

class Request {
    private /*string*/ $url;
    private /*array*/ $params = [];

    public function __construct() {
        $this->url = ltrim(trim($_SERVER['REQUEST_URI']), '/');
        $this->params = array_slice(explode('/', $this->url), 3);
    }

    public function __set(string $key, $value) : void {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
    }

    public function __get(string $key) {
        if (property_exists($this, $key)) {
            return $this->$key;
        }
    }

    public function __isset(string $key) : bool {
        return property_exists($this, $key);
    }
}
