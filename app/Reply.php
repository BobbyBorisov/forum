<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reply extends Model
{
    use Favoritable, SoftDeletes, RecordsActivity;

    protected $guarded = [];

    protected $with = ['owner','favorites','thread'];

    protected $touches = ['thread'];

    protected $withCount = ['favorites'];

    protected $appends = ['isFavorited'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all("/@([^\s]+)/", $this->body,$match);

        return $match[1];
    }
}
