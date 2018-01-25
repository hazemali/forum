<?php

namespace laravel\Http\Controllers;

use laravel\Activity;
use laravel\User;
use Illuminate\Http\Request;

class ProfilesController extends Controller
{


    public function show(User $user)
    {

        return view('profiles.show', [
            'profileUser' => $user,
            'activities' => Activity::feed($user, 50)
        ]);
    }

}
