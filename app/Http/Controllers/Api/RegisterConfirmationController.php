<?php

namespace laravel\Http\Controllers\Api;

use Illuminate\Http\Request;
use laravel\Http\Controllers\Controller;
use laravel\User;

class RegisterConfirmationController extends Controller
{


    public function index(Request $request)
    {

        try {
            User::where('confirmation_token', $request->token)
                ->firstOrFail()
                ->confirm();
        } catch (\Exception $e) {
            return redirect(route('threads.index'))
                ->with('flashError', 'unknown token');

        }

        return redirect(route('threads.index'))
            ->with('flash', 'Yee! your email has been confirmed');

    }
}
