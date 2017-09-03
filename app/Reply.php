<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Reply extends Model
{


    use Favoritable, RecordsActivity, HasDatabasePolicy;

    protected $guarded = [];


    protected $with = [
        'owner',
        'favorites'
    ];

    protected $appends = ['FavoritesCount', 'IsFavorited'];

    public static function boot()
    {
        parent::boot();


        static::created(function ($reply) {

            $reply->thread->increment('replies_count');

        });

        static::deleting(function ($reply) {
            $reply->favorites->each->delete();
            $reply->thread->decrement('replies_count');
        });


    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    public function path()
    {
        $count = floor(Reply::where(['thread_id' => $this->thread_id])->count() / 25);

        $page = $count - ceil(Reply::where(['thread_id' => $this->thread_id, ['id', '<=', $this->id]])->count() / 25);

        return "{$this->thread->path()}?page={$page}#reply-{$this->id}";
    }


}

