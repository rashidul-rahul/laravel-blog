<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user(){
        return $this->belongsTo('App\user');
    }

    public function categories(){
        return $this->belongsToMany('App\Category')->withTimestamps();
    }

    public function tags(){
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }
}
