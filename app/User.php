<?php

namespace laravel;

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
        'name', 'email', 'password','avatar_path','confirmation_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];


    protected $casts = ['confirmed' => 'boolean'];

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function threads()
    {

        return $this->hasMany(Thread::class)->latest();
    }

    public function path()
    {
        return Route('profiles.show', $this);
    }


    public function confirm()
    {
        $this->confirmed = true;
        $this->save();
    }

    public function activity()
    {
        return $this->hasMany(Activity::class);
    }

    public function policies()
    {
        return $this->hasMany(Policy::class);
    }


    public function getCacheVisitsKey($thread)
    {
        return sprintf('users.%s.visits.%s', $this->id, $thread->id);
    }


    public function read($thread)
    {
        cache()->forever($this->getCacheVisitsKey($thread), carbon::now());

    }

    /**
     * @return \Illuminate\Database\Query\Builder|static
     */
    public function lastReply(){

        return $this->hasOne(Reply::class)->latest();

    }

    public function getAvatarPathAttribute($avatar)
    {
        return \asset($avatar?: 'images/default-avatar.png');
    }

}

