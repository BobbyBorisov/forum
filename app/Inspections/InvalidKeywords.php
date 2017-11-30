<?php

namespace App\Inspections;

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
                throw new \Exception('your reply contains spam');
            }
        }
    }
}