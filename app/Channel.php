<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Channel extends Model
{


    public static function boot()
    {
        parent::boot();

        static::created(function ($model) {

            Cache::forget('channels');


        });
    }

    public function getRouteKeyName()
    {
        return 'slug';

    }


    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

}
