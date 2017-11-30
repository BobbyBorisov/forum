<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function threads()
    {
        return $this->hasMany(\App\Thread::class)->latest();
    }

    public function activities()
    {
        return $this->hasMany(\App\Activity::class)->latest();
    }

    public function read($thread)
    {
        $key = sprintf('users.%s.thread.%s', $this->id, $thread->id);
        cache()->forever($key, Carbon::now('Europe/Sofia'));
    }

    public function lastReply()
    {
        return $this->hasOne(Reply::class)->latest();
    }
}
