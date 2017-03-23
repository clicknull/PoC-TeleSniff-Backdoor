<?php
session_start();
/*
    HijackGram 1.0
    Webservice (PHP)
*/

class HijackGram
{
    public function __construct()
    {
        $class_file = scandir('class');
        foreach ($class_file as $key => $value)
           if(stristr($value,'.class'))
                require_once("class/".$value);
        $this->load_core();
    }

    private function load_core()
    {
        $core = new Core;
    }
}

new HijackGram();