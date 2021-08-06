<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /**
     * Get the comments for the blog post.
     */
    public function workshop()
    {
        return $this->hasMany(Workshop::class);
    }
}
