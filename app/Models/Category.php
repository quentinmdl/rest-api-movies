<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'name'
    ];

    // public static function boot() {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $model->id = Str::uuid();
    //     });
    // }

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'category_movies', 'movie_id', 'category_id');
    }
}
