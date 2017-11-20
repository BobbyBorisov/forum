<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $guarded = [];

    protected $with = ['subject'];

    public static function feed($take = 50)
    {
        return static::where('user_id', auth()->user()->id)
            ->latest()
            ->with('subject')
            ->take($take)
            ->get()
            ->groupBy(function ($activity) {
                return $activity->created_at->format('Y-m-d');
            });
    }

    public function subject()
    {
        return $this->morphTo();
    }
}
