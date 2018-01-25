<?php

namespace laravel\Http\Controllers\Api;

use Illuminate\Http\Request;
use laravel\Http\Controllers\Controller;

class AvatarController extends Controller
{


    public function store()
    {

        $this->validate(\request(), [
            'avatar' => ['required', 'image']
        ]);

        Auth()->user()->update(
            [
                'avatar_path' => \request()->file('avatar')->store('avatars', 'public')
            ]
        );

        return response([],204);
    }
}
