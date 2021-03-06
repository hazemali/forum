<?php

namespace laravel\Http\Controllers;

use laravel\User;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{



    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param  $notificationId
     */
    public function destroy($notificationId)
    {

        auth()->user()->notifications()->findOrFail($notificationId)
            ->markAsRead();

    }


    public function index()
    {

        return auth()->user()->unreadnotifications;

    }
}
