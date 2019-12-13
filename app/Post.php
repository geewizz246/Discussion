<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */ 
    protected $fillable = [
        'discussion_id', 'user_id', 'is_reply',
        'body',
    ];

    /**
     * Set up the relationship between posts and users.
     * 1 post BELONGS TO 1 user.
     */
    public function user() {
        return $this->belongsTo(User::class);
    }

    /**
     * Set up the relationship between posts and discussion.
     * 1 post BELONGS TO 1 discussion.
     */
    public function discussion() {
        return $this->belongsTo(Discussion::class);
    }

    /**
     * Set up the relationship between posts and attachments.
     * 1 post HAS MANY attachments.
     */
    public function attachments() {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Get the author of the post.
     */
    public function getAuthor() {
        return $this->user->username;
    }
}
