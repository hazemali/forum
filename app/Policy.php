<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    protected $guarded = [];

    protected $hidden = ['id','user_id','created_at','updated_at'];
}
