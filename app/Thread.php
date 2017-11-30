<?php

namespace App;

use App\Notifications\NewReplyAdded;
use App\Policies\ReplyPolicy;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use SoftDeletes, CascadeSoftDeletes, RecordsActivity;

    protected $guarded = [];

    protected $cascadeDeletes = ['replies'];

    protected $with = ['creator','channel'];

    protected $withCount = ['subscriptions'];

    protected $dates = ['deleted_at'];

    protected $appends = ['isSubscribed'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
        return $this->hasMany(Reply::class)
                    ->withCount('favorites')
                    ->with('owner');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);

        $this->subscriptions
             ->where('user_id','!=', auth()->user()->id)
             ->each
             ->notify($reply);

        return $reply;
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($user)
    {
        $this->subscriptions()->create(['user_id' => $user->id]);
    }

    public function unsubscribe($user)
    {
        $this->subscriptions()->where('user_id', $user->id)->delete();
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function isSubscribed()
    {
        if (!auth()->user()) return false;
        return $this->subscriptions()->where('user_id', auth()->user()->id)->exists();
    }

    public function getIsSubscribedAttribute()
    {
        return $this->isSubscribed();
    }

    public function notify($reply)
    {
        $this->user->notify(new NewReplyAdded());
    }

    public function hasUpdatesFor($user)
    {
        $key = sprintf('users.%s.thread.%s', $user->id, $this->id);

        return $this->updated_at > cache($key);
    }
}
