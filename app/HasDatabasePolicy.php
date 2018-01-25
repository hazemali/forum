<?php

namespace laravel;


trait HasDatabasePolicy
{


    public static function bootHasDatabasePolicy()
    {


        static::created(function ($model) {
            $model->AuthorizeUser($model->user_id);
        });

        static::deleting(function ($model) {
            $model->unAuthorizeAllUsers();
        });

    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function policies()
    {
        return $this->morphMany(Policy::class, 'policy');
    }

    public function AuthorizeUser($userId = null)
    {

        $userId = $userId ?: auth()->id();

        $attributes = ['user_id' => $userId, 'policy_id' => $this->id, 'policy_type' => $this->getModelType()];


        if (!$this->policies()->where($attributes)->exists()) {

            $this->policies()->create($attributes);
        }
    }


    public function unAuthorizeAllUsers(){
            $this->policies()->delete();
    }


    protected function getModelType()
    {
        return strtolower((new \ReflectionClass($this))->getShortName());

    }


    public function isAuthorized($userId = null)
    {

        $userId = $userId ?: auth()->id();
        $attributes = ['user_id' => $userId];
        return $this->policies()->where($attributes)->exists();

    }
}