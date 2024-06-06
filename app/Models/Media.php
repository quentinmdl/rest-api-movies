<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Media;
use App\Models\Movie;
use App\Models\MediaType;
use App\Helpers\MediaUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory, HasUuids;


    protected $keyType = 'string';
    
    public $incrementing = false;

    protected $table = 'medias';
    
    protected $fillable = [
        'media_url',
        'media_path',
        'media_type'
    ];


    public function movie()
    {
        return $this->hasOne(Movie::class);
    }


    public static function storeOrUpdateMedia($path, $mediaType, $id = null)
    {
        $media = Media::updateOrCreate(['id' => $id], [
            'media_path' => $path,
            'media_url' => asset(Storage::url($path)),
            'media_type' => MediaType::where('name',$mediaType)->first()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return $media ?? false; 
    }
}
