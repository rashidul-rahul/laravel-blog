<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function categories(){
        return $this->belongsToMany('App\Category')->withTimestamps();
    }

    public function tags(){
        return $this->belongsToMany('App\Tag')->withTimestamps();
    }

    public function favorite_post_user(){
        return $this->belongsToMany('App\User');
    }

    public function comments(){
        return $this->hasMany('App\Comment');
    }

    public function scopeApprove($query){
        return $query->where('is_approve', 1);
    }

    public function scopePublished($query){
        return $query->where('status', 1);
    }
}
