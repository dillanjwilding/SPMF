<?php
namespace Lib;

abstract class Model /*extends InteractDB*/ {
    protected /*string*/ $table;

    public function __construct() {
        //parent::__construct();
        $this->table = strtolower(get_class($this));
    }
    
    public function __destruct() { }
}
