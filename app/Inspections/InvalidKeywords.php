<?php

namespace App\Inspections;

use App\Exceptions\InvalidKeyWordException;

class InvalidKeywords {

    private $keywords = [
        'yahoo customer support'
    ];

    public function detect($body)
    {
        foreach($this->keywords as $keyword)
        {
            if(str_contains($body, $keyword))
            {
                throw new InvalidKeyWordException('You have entered spam words.');
            }
        }

        return false;
    }
}