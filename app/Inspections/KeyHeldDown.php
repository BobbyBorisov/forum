<?php

namespace App\Inspections;

use App\Exceptions\KeyHeldDownException;

class KeyHeldDown {

    public function detect($body)
    {
        if(preg_match('/(.)\\1{4,}/', $body))
        {
            throw new KeyHeldDownException('You pressed same key multiple times');
        }
    }
}