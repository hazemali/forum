<?php

namespace laravel\Filters;

use laravel\User;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{

    protected $filters = ['by','popular','unanswered'];

    /**
     * Filter the query by a given username.
     *
     * @param $username
     * @return mixed
     * @internal param $builder
     */
    protected function by($username)
    {
        $user = User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }


    /**
     * Filter the query according the popularity
     */
    public function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count','desc');

    }

    public function unanswered()
    {

        return $this->builder->where('replies_count', 0);
    }

}