<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'article_url',
        'github_url',
        'image_path',
    ];


    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }
}
