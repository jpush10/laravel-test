<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    /**
     * Get the comments for the blog post.
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
