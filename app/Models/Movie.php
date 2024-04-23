<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'release_date',
        'rating'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_movies', 'movie_id', 'category_id');
    }

}
