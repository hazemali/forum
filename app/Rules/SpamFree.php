<?php

namespace laravel\Rules;

use laravel\Inspections\Spam;
use Mockery\Exception;

class SpamFree
{


    public function passes($attribute, $value)
    {

        try {
            return !resolve(Spam::class)->detect($value);
        } catch (\Exception $e) {

            return false;
        }

    }

}