<?php
    // Change 
    // Need to be more readable
    function logThis($var)
    {
        // Change variables
        // Edit output format
        $toLog = print_r($var, true);
        $bt = debug_backtrace();
        $caller = array_shift($bt);
        $file = $caller['file'];
        $line = $caller['line'];
        $toLog = $toLog."\n".$file.":".$line;
        $fHandle = fopen(ROOT . DS . 'temp' . DS . 'logs' . DS . 'debug.log', 'a+');
        fwrite($fHandle, date('Y/m/d-h:i:s').": ".$toLog."\n");
    }