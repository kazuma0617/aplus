<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }
}
