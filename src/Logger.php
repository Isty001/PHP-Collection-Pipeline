<?php

namespace Pipeline;

class Logger
{
    public static function add($text)
    {
        $file = fopen('../log.txt', 'a+');
        if(is_array($text) || is_object($text)){
            $text = var_export($text, true);
        }
        fwrite($file, $text);
        fclose($file);
    }
}
