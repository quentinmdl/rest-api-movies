<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Movie extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'name',
        'description',
        'release_date',
        'rating',
        'media_id'
    ];

    
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_movies', 'movie_id', 'category_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

}
