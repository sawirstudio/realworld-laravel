<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    public function tags(){return $this->belongsToMany(Tag::class, 'article_tags');}
    public function comments(){return $this->hasMany(Comment::class);}
    public function user(){return $this->belongsTo(User::class);}
    public function favorites(){return $this->hasMany(Favorite::class);}

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
