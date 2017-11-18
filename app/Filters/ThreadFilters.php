<?php

namespace App\Filters;

class ThreadFilters
{
    public $filters = ['by', 'popularity'];

    public function apply($builder)
    {
        foreach (request()->only($this->filters) as $filter => $value)
        {
            if(method_exists($this, $filter))
            {
                $this->$filter($value, $builder);
            }
        }
    }

    public function by($value, $builder)
    {
        $builder->whereHas('creator', function($query) use ($value){
            $query->where('name',$value);
        });
    }

    public function popularity($value, $builder)
    {
        $builder->getQuery()->orders =[];
        $builder->orderBy('replies_count','desc');
    }
}