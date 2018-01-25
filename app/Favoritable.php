<?php

namespace laravel;


use laravel\Notifications\ReplyWasFavoried;

trait Favoritable
{


    public static function bootFavoritable()
    {

        if (auth()->guest()) return;


        static::deleting(function ($model) {

            $model->favorites->each->delete();

        });


    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */

    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favorited');
    }

    /**
     * Favorite a current reply.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (!$this->favorites()->where($attributes)->exists()) {

            if (method_exists($this, 'FavoritedNotify')) $this->FavoritedNotify();

            return $this->favorites()->create($attributes);
        }
    }


    public function unFavorite()
    {

        $favorites = $this->favorites()->where(['user_id' => auth()->id()]);
        if ($favorites->exists()) {
            return $favorites->first()->delete();
        }
    }

    /**
     * check if the current reply is favorited
     *
     * @return mixed
     */
    public function isFavorited()
    {

        return !!$this->favorites->where('user_id', Auth()->id())->count();

    }


    /**
     * @return mixed
     */

    public function getIsFavoritedAttribute()
    {

        return $this->isFavorited();

    }

    /**
     * @return mixed
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();

    }

}