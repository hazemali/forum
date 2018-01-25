<?php

namespace laravel\Http\Controllers;

use laravel\Thread;
use laravel\channel;
use laravel\Filters\ThreadFilters;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use laravel\Trending;

class ThreadsController extends Controller
{

    public function __construct()
    {


        $this->middleware('auth')->except(['index', 'show']);

    }

    /**
     * Display a listing of the resource.
     *
     * @param channel $channel
     * @param ThreadFilters $filters
     * @param Trending $trending
     * @return Response
     */
    public function index(Channel $channel, ThreadFilters $filters, Trending $trending)
    {

        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }
        return view('threads.index', [
            'threads' => $threads,
            'trending_threads' => $trending->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        return view('threads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|spamFree',
            'body' => 'required|spamFree',
            'channel_id' => 'required|exists:channels,id'
        ]);


        $thread = Thread::create([
            'title' => request('title'),
            'body' => request('body'),
            'user_id' => auth()->id(),
            'channel_id' => request('channel_id')
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published!');

    }

    /**
     * Display the specified resource.
     *
     * @param $channel
     * @param Thread $thread
     * @param Trending $trending
     * @return Response
     */

    public function show($channel, Thread $thread, Trending $trending)
    {

        if (auth()->check())
            auth()->user()->read($thread);

        $thread->increment('visits');

        $trending->push($thread);

        return view('threads.show', compact('thread'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Thread|\laravel\Threads $threads
     * @return Response
     */
    public function edit(Thread $threads)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Threads|\laravel\Threads $threads
     * @return Response
     */
    public function update(Request $request, Threads $threads)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Thread|\laravel\Threads $thread
     * @return Response
     */
    public function destroy($channel, Thread $thread)
    {

        $this->authorize('update', $thread);

        $thread->delete();

        if (request()->wantsJson())
            return response([], 204);

        return redirect(route('threads.index'));

    }

    /**
     * @param channel $channel
     * @param ThreadFilters $filters
     * @return mixed
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }


        return $threads->paginate(25);
    }


}
