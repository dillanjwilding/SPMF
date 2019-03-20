<?php
namespace Lib\Debug;

class Logger {
    // @todo: Need to be more readable
    public static function saveData($data) : void {
        // Change variables
        // Edit output format
        $toLog = print_r($data, true);
        $caller = array_shift(debug_backtrace());
        $toLog .= "\n".$caller['file'].":".$caller['line'];
        $fHandler = fopen(ROOT . DS . 'temp' . DS . 'logs' . DS . 'debug.log', 'a+');
        fwrite($fHandler, date('Y/m/d-h:i:s').": ".$toLog."\n");
    }
}
