<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Notifications\NewReplyAdded;
use App\Notifications\YouWereMentioned;
use App\Policies\ReplyPolicy;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redis;

class Thread extends Model
{
    use SoftDeletes, CascadeSoftDeletes, RecordsActivity;

    protected $guarded = [];

    protected $cascadeDeletes = ['replies'];

    protected $with = ['creator','channel'];

    protected $withCount = ['subscriptions'];

    protected $dates = ['deleted_at'];

    protected $appends = ['isSubscribed','isLocked'];

    protected $casts = [
        'locked' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->slug}";
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

        event(new ThreadReceivedNewReply($reply, $this));

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

    public function hasUpdatesFor($user)
    {
        $key = sprintf('users.%s.thread.%s', $user->id, $this->id);

        return $this->updated_at > cache($key);
    }

    /**
     * @return \App\Visits
     */
    public function visits()
    {
        return new Visits($this);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists())
        {
            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }
    
    public function incrementSlug($slug)
    {
        $max = static::whereTitle($this->title)->latest('id')->value('slug');

        if (is_numeric($max[-1]))
        {
            return preg_replace_callback('/(\d+)$/', function($matches){
                return $matches[1] + 1;
            },$max);
        }

        return "{$slug}-2";
    }

    public function markBestReply(Reply $reply)
    {
        $this->update(['best_reply_id' => $reply->id]);
    }

    public function lock()
    {
        $this->update(['locked' => true]);
    }

    public function getIsLockedAttribute()
    {
        return $this->locked;
    }
}
