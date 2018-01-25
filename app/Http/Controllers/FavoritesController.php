<?php

namespace laravel\Http\Controllers;

use laravel\Favorite;
use laravel\Reply;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * FavoritesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * @param Reply $reply
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function store(Reply $reply)
    {

        $reply->favorite();

        if(request()->wantsJson()){

            return response([],204);
        }

        return back()
            ->with('flash' , 'A reply was favorited!');
    }


    public function destroy(Reply $reply)
    {

        $reply->unFavorite();

        if(request()->wantsJson()){

            return response([],204);
        }

        return back()
            ->with('flash' , 'A reply was un favorited!');
    }


}
