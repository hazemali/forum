<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;
use App\Thread;


class ReplyController extends Controller
{


    public function __construct()
    {

        $this->middleware('auth' , ['except' => 'index']);
    }



    public function index($channelId, Thread $thread){


        return $thread->replies()->latest()->paginate(25);

    }

    public function store($channelId, Thread $thread)
    {


        $this->validate(request(), [
            'body' => 'required'
        ]);

       $reply =  $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);


        if (request()->wantsJson())
            return response($reply->load('owner'), 201);


        return redirect($thread->path())
            ->with('flash','Your reply has been left!');

    }


    /**
     * @param Reply $reply
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */

    public function destroy(Reply $reply)
    {

        $this->authorize('update',$reply);

        $reply->delete();

        if (request()->wantsJson())
            return response([], 204);

        return redirect()
            ->back()
            ->with('flash' , 'The reply was deleted!');

    }


    public function update(Reply $reply , Request $request)
    {

        $this->authorize('update',$reply);

        $this->validate($request, [
            'body' => 'required'
        ]);


        $reply->update(['body' => $request->body]);

        if($request->wantsJson())
            return response([],204);

        return redirect()
            ->back()
            ->with('flash' , 'the reply was updated!');

    }
}
