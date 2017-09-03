<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Thread extends Model
{


    use RecordsActivity, hasDatabasePolicy;

    protected $guarded = [];


    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribed'];

    public static function boot()
    {
        parent::boot();


        static::deleting(function ($thread) {

            $thread->replies->each->delete();
        });


    }

    /**
     * the routing path of the single thread
     * @return string
     */
    public function path()
    {
        //   return route('threads.show', $this);

        return route('threads.show', [$this->channel->slug, $this->id]);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {

        return $this->hasMany(Reply::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo(Channel::class);

    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
        $reply = $this->replies()->create($reply);


        $this->subscriptions
            ->filter(function ($subscription) use ($reply) {

                return $reply->user_id != $subscription->user_id;

            })->each->notify($reply);


        return $reply;
    }

    public static function byChannel($channelId)
    {
        return self::where('channel_id', $channelId);
    }


    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {


        if (!$this->isSubscribed) {

            $this->subscriptions()->create([
                'user_id' => $userId ?: auth()->id()
            ]);
        }

        return $this;
    }

    public function unsubscribe($userId = null)
    {

        if ($this->isSubscribed) {
            $this->subscriptions()
                ->where('user_id', $userId ?: auth()->id())
                ->delete();
        }

        return $this;

    }

    public function subscriptions()
    {

        return $this->hasMany(ThreadSubscriptions::class);
    }

    public function getIsSubscribedAttribute()
    {
        return $this->subscriptions()
            ->where(['user_id' => auth()->id()])
            ->exists();

    }


}
