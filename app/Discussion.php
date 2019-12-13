<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ 
    protected $fillable = [
        'topic', 'description'
    ];

    /**
     * Set up the relationship between discussions and users.
     * 1 discussion BELONGS TO 1 user.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Set up the relationship between discussions and posts.
     * 1 discussion HAS MANY posts.
     */
    public function posts() {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the creator of the discussion.
     */
    public function getCreator() {
        return $this->user->username;
    }

    /**
     * Get the original post of the discussion.
     */
    public function getOriginalPost() {
        return $this->posts()->where('is_reply', false)->get()->first();
    }

    /**
     * Get the total number of replies in the discussion.
     */
    public function getNumOfReplies() {
        return $this->posts()->where('is_reply', true)->count();
    }

    /**
     * Get the author of the last created reply of the discussion.
     */
    public function getLastReplyAuthor() {
        return $this->posts()->get()->last()->user->username;
    }

    /**
     * Get the time of the last created reply of the discussion.
     */
    public function getLastReplyTime() {
        return $this->posts()->get()->last()->created_at;
    }
}
