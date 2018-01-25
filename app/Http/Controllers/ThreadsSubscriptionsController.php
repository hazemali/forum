<?php

namespace laravel\Http\Controllers;

use laravel\Thread;
use Illuminate\Http\Request;

class ThreadsSubscriptionsController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function store($channelId, Thread $thread)
    {

        $thread->subscribe();

        return response([],201);

    }


    /**
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function destroy($channelId, Thread $thread)
    {

        $thread->unsubscribe();

        return response([] ,202);

    }

}
