<?php

namespace laravel\Inspections;

use Exception;

class InvalidKeywords
{

    protected $keywords = [
        'Yahoo Customer Support'
    ];

    public function detect($body)
    {

        foreach ($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('your reply has a spam');
            }

        }
    }

}