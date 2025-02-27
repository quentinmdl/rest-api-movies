<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CategoryMovie extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'category_id',
        'movie_id'
    ];
}
