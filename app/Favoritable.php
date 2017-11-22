<?php
/**
 * Created by PhpStorm.
 * User: bobbyborisov
 * Date: 11/15/17
 * Time: 10:55 PM
 */

namespace App;

trait Favoritable
{
    protected static function bootFavoritable()
    {
        static::deleting(function ($model){
            $model->favorites->each->delete();
        });
    }

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $this->favorites()->create(['user_id' => auth()->user()->id]);

        return $this;
    }

    public function unfavorite()
    {
        $this->favorites()->where('user_id', auth()->user()->id)->get()->each->delete();
    }

    public function isFavorited()
    {
        return ! ! $this->favorites->where('user_id', auth()->user()->id)->count();
    }
}