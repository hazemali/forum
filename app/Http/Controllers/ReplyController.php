<?php

namespace laravel\Http\Controllers;

use function foo\func;
use Illuminate\Support\Facades\Gate;
use laravel\Http\Forms\CreatePostForm;
use laravel\Notifications\YouWereMentioned;
use laravel\Reply;
use Illuminate\Http\Request;
use laravel\Thread;
use laravel\User;


class ReplyController extends Controller
{


    public function __construct()
    {

        $this->middleware('auth', ['except' => 'index']);
    }


    /**
     * Fetch the latest replies
     *
     * @param $channelId
     * @param Thread $thread
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($channelId, Thread $thread)
    {
        return $thread->replies()->latest()->paginate(25);

    }

    /**
     * Create a new reply
     *
     * @param $channelId
     * @param Thread $thread
     * @param CreatePostForm $form
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($channelId, Thread $thread, CreatePostForm $form)
    {

        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        return response($reply->load('owner'), 201);
    }


    /**
     * Delete a given reply
     *
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function destroy(Reply $reply)
    {

        $this->authorize('update', $reply);

        $reply->delete();

        if (request()->wantsJson())
            return response([], 204);

        return redirect()
            ->back()
            ->with('flash', 'The reply was deleted!');

    }


    /**
     * update the body of a given reply
     *
     * @param Reply $reply
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Reply $reply, Request $request)
    {

        $this->authorize('update', $reply);

        $this->validate(request(), [
            'body' => 'required|spamFree'
        ]);

        $reply->update(['body' => $request->body]);

        return response([], 204);

    }

}
