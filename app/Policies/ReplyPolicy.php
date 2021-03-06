<?php

namespace App\Policies;

use App\User;
use App\Reply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the =AppReply.
     *
     * @param  \App\User $user
     * @param \App\Reply $reply
     * @return mixed
     */
    public function view(User $user, Reply $reply)
    {
        //
    }

    /**
     * Determine whether the user can create =AppReplies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        $reply = Reply::where('user_id', $user->id)->latest()->first();

        if(optional($reply)->wasJustPublished()){
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the =AppReply.
     *
     * @param  \App\User $user
     * @param \App\Reply $reply
     * @return mixed
     */
    public function update(User $user, Reply $reply)
    {
        dd('ajajaja');
    }

    /**
     * Determine whether the user can delete the =AppReply.
     *
     * @param  \App\User $user
     * @param \App\Reply $reply
     * @return mixed
     */
    public function delete(User $user, Reply $reply)
    {
        dd('jaajaj');
        return true;
        //return $reply->owner->id == $user->id;
    }
}
