<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ 
    protected $fillable = [
        'path'
    ];

    /**
     * Set up the relationship between attachments and posts.
     * 1 BELONGS TO 1 post.
     */
    public function post() {
        return $this->belongsTo(Post::class);
    }
}
