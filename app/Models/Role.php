<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $fillable = [
        'name'
    ];

}
