<?php

namespace App;

class Debugger
{
    public static function debug(...$args)
    {
        error_log(var_export($args, true));
    }
}