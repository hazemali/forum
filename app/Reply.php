<?php

namespace laravel;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use laravel\Notifications\ReplyWasFavoried;


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


    public function FavoritedNotify()
    {

        if ($this->owner->id != auth()->id())
            $this->owner->notify(new ReplyWasFavoried($this, auth()->user()));

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


    public function wasJustPublished()
    {
        return $this->created_at->gt(Carbon::now()->subMinute(1));
    }


    /**
     * @return User collection
     */
    public function mentionedUsers()
    {

        preg_match_all('/\@([^\s\.^\<]+)/', $this->body, $matches);

        return $matches[1];

    }


    /**
     * @param $body
     */
    public function setBodyAttribute($body)
    {
        $this->attributes['body'] = preg_replace('/\@([\w\-]+)/',
            '<a href="/profiles/$1">$0</a>', $body);

    }
}

