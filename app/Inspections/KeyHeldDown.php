<?php

namespace laravel\Inspections;

use Exception;
class KeyHeldDown
{

    public function detect($body)
    {

        if (preg_match('/(.)\\1{4,}/', $body))
            throw new Exception('your reply has a spam');

    }

}