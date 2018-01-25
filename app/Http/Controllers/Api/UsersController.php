<?php

namespace laravel\Http\Controllers\api;

use Illuminate\Http\Request;
use laravel\Http\Controllers\Controller;
use laravel\User;

class UsersController extends Controller
{
    public function index()
    {

        $name = \request('name');

        return User::where('name', 'like', $name . '%')
            ->take(5)
            ->pluck('name');

    }
}
