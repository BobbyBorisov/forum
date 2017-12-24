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

    protected $appends = ['isFavorited','isBest'];

    public static function boot(){
        static::deleting(function ($model){
            if($model->isBest())
            {
                $model->thread->update(['best_reply_id' => null]);
            }
        });
    }
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
        if (app()->environment('local')) return false;

        return $this->created_at->gt(Carbon::now()->subMinute());
    }

    public function mentionedUsers()
    {
        preg_match_all("/@(\w+)/", $this->body,$match);

        return $match[1];
    }

    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/@(\w+)/','<a href="/user/$1/profile">$0</a>',$body);
    }

    public function isBest()
    {
        return $this->thread->best_reply_id == $this->id;
    }

    public function getIsBestAttribute()
    {
        return $this->isBest();
    }
}
